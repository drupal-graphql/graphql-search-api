<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\Core\Cache\CacheableMetadata;
use Drupal\graphql\GraphQL\Cache\CacheableValue;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use GraphQL\Type\Definition\ResolveInfo;
use Drupal\search_api\Entity\Index;

/**
 * A query field that wraps a Search API query.
 *
 * @GraphQLField(
 *   secure = true,
 *   id = "search_api_search",
 *   type = "SearchAPIResult",
 *   name = "searchAPISearch",
 *   nullable = true,
 *   multi = false,
 *   arguments = {
 *     "index_id" = "String!",
 *     "fulltext" = "FulltextInput",
 *     "language" = "[String]",
 *     "condition_group" = "ConditionGroupInput",
 *     "conditions" = "[ConditionInput]",
 *     "range" = "RangeInput",
 *     "sort" = "[SortInput]",
 *     "facets" = "[FacetInput]",
 *     "more_like_this" = "MLTInput",
 *     "solr_params" = "[SolrParameterInput]",
 *   },
 * )
 */
class SearchAPISearch extends FieldPluginBase {

  private $query;
  private $index;

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {

    // Load up the index passed in argument.
    $this->index = Index::load($args['index_id']);

    // Prepare the query with our arguments.
    $this->prepareSearchQuery($args);

    // Execute search.
    try {
      $results = $this->query->execute();
    }
    // Handle error, check exception type -> SearchApiException ?
    catch (\Exception $exception) {
      \Drupal::logger('graphql_search_api')->error($exception);
    }

    // Get search response from results.
    $search_response = $this->getSearchResponse($results);

    // Add the result count to the response.
    $search_response['result_count'] = $results->getResultCount();

    // Set response type.
    $search_response['type'] = 'SearchAPIResult';

    // Adding Caching.
    $metadata = new CacheableMetadata();

    $metadata->addCacheTags(['search_api_list:' . $this->index->id()]);

    yield new CacheableValue($search_response, [$metadata]);

  }

  /**
   * Adds conditions to the Search API query.
   *
   * @conditions
   *  The conditions to be added.
   */
  private function addConditions($conditions) {

    // Loop through conditions to add them into the query.
    foreach ($conditions as $condition) {
      if (empty($condition['operator'])) {
        $condition['operator'] = '=';
      }
      if ($condition['value'] == 'NULL') {
        $condition['value'] = NULL;
      }
      // Set the condition in the query.
      $this->query->addCondition($condition['name'], $condition['value'], $condition['operator']);
    }
  }

  /**
   * Adds a condition group to the Search API query.
   *
   * @condition_group
   *  The conditions to be added.
   */
  private function addConditionGroup($condition_group_arg) {

    // Loop through the groups in the args.
    foreach ($condition_group_arg['groups'] as $group) {

      // Set default conjunction and tags.
      $group_conjunction = 'AND';
      $group_tags = array();

      // Set conjunction from args.
      if (isset($group['conjunction'])) {

        $group_conjunction = $group['conjunction'];
      }
      if (isset($group['tags'])) {
        $group_tags = $group['tags'];
      }

      // Create a single condition group.
      $condition_group = $this->query->createConditionGroup($group_conjunction, $group_tags);

      // Loop through all conditions and add them to the Group.
      foreach ($group['conditions'] as $condition) {

        $condition_group->addCondition($condition['name'], $condition['value'], $condition['operator']);
      }

      // Merge the single groups to the condition group.
      $this->query->addConditionGroup($condition_group);
    }
  }

  /**
   * Adds direct Solr parameters to the Search API query.
   *
   * @params
   *  The conditions to be added.
   */
  private function addSolrParams($params) {

    // Loop through conditions to add them into the query.
    foreach ($params as $param) {
      // Set the condition in the query.
      $this->query->setOption('solr_param_' . $param['parameter'], $param['value']);
    }
  }

  /**
   * Sets fulltext fields in the Search API query.
   *
   * @full_text_params
   *  Parameters containing fulltext keywords to be used as well as optional
   *  fields.
   */
  private function setFulltextFields($full_text_params) {

    // Check if keys is an array and if so set a conjunction.
    if (is_array($full_text_params['keys'])) {
      // If no conjunction was specified use OR as default.
      if ($full_text_params['conjunction']) {
        $full_text_params['keys']['#conjunction'] = $full_text_params['conjunction'];
      }
      else {
        $full_text_params['keys']['#conjunction'] = 'OR';
      }
    }

    // Set the keys in the query.
    $this->query->keys($full_text_params['keys']);

    // Set the optional fulltext fields if specified.
    if (!empty($full_text_params['fields'])) {
      $this->query->setFulltextFields($full_text_params['fields']);
    }
  }

  /**
   * Sets facets in the Search API query.
   *
   * @facets
   *  The facets to be added to the query.
   */
  private function setFacets($facets) {

    // Retrieve this index server details.
    $server = $this->index->getServerInstance();

    // Check if the index server supports facets (e.g solr).
    if ($server->supportsFeature('search_api_facets')) {

      $facets_array = [];

      // Loop through all the provided facets.
      foreach ($facets as $facet) {
        $facets_array[$facet['field']] = [
          'field' => $facet['field'],
          'limit' => $facet['limit'],
          'operator' => $facet['operator'],
          'min_count' => $facet['min_count'],
          'missing' => $facet['missing'],
        ];
      }

      // Set the facets in the query.
      $this->query->setOption('search_api_facets', $facets_array);
    }
  }

  /**
   * Sets MLT in the Search API query.
   *
   * @facets
   *  The MLT params to be added to the query.
   */
  private function setMLT($mlt_params) {

    // Retrieve this index server details.
    $server = $this->index->getServerInstance();

    // Check if the index server supports More Like This (e.g solr).
    if ($server->supportsFeature('search_api_mlt')) {

      // Set the more like this parameters in the query.
      $this->query->setOption('search_api_mlt', $mlt_params);
    }
  }

  /**
   * Prepares the Search API query by adding all possible options.
   *
   * Options include conditions, language, fulltext, range, sort and facets.
   *
   * @args
   *  The arguments containing all the parameters to be loaded to the query.
   */
  private function prepareSearchQuery($args) {

    // Prepare a query for the respective Search API index.
    $this->query = $this->index->query();

    // Adding query conditions if they exist.
    if ($args['conditions']) {
      $this->addConditions($args['conditions']);
    }
    // Adding query group conditions if they exist.
    if ($args['condition_group']) {
      $this->addConditionGroup($args['condition_group']);
    }
    // Adding Solr specific parameters if they exist.
    if ($args['solr_params']) {
      $this->addSolrParams($args['solr_params']);
    }
    // Restrict the search to specific languages.
    if ($args['language']) {
      $this->query->setLanguages($args['language']);
    }
    // Set fulltext search parameters in the query.
    if ($args['fulltext']) {
      $this->setFulltextFields($args['fulltext']);
    }
    // Adding range parameters to the query (e.g for pagination).
    if ($args['range']) {
      $this->query->range($args['range']['start'], $args['range']['end']);
    }
    // Adding sort parameters to the query.
    if ($args['sort']) {
      foreach ($args['sort'] as $sort) {
        $this->query->sort($sort['field'], $sort['value']);
      }
    }
    // Adding facets to the query.
    if ($args['facets']) {
      $this->setFacets($args['facets']);
    }
    // Adding more like this parameters to the query.
    if ($args['more_like_this']) {
      $this->setMLT($args['more_like_this']);
    }
  }

  /**
   * Obtain a Search Response from the results returned by Search API.
   *
   * @results
   *  The Search API results to be parsed.
   */
  private function getSearchResponse($results) {

    // Obtain result items.
    $result_items = $results->getResultItems();
    // Initialise response array.
    $search_response = [];

    // Loop through each item in the result set.
    foreach ($result_items as $id => &$item) {
      // Load the response document into the search response array.
      $search_response['SearchAPIDocument'][] = $this->loadResponseDocument($item);
    }

    // Extract facets from the result set.
    $facets = $results->getExtraData('search_api_facets');

    if ($facets) {
      // Loop through each facet in the result set.
      foreach ($facets as $facet_id => $facet_values) {
        // Load the response facet in the response array.
        $search_response['facets'][] = $this->loadResponseFacet($facet_id, $facet_values);
      }
    }
    return $search_response;
  }

  /**
   * Loads a Facet into a structured response array.
   *
   * @facet_id
   *  The id of the facet to be added.
   * @facet_values
   *  The values for the facet to be added.
   */
  private function loadResponseFacet($facet_id, $facet_values) {

    // Initialise variables.
    $response_facet = [];

    // Config the facet response.
    $response_facet['type'] = 'SearchAPIFacet';
    $response_facet['name'] = $facet_id;

    // Loop through the facet values and load them into the response.
    foreach ($facet_values as $facet_value) {
      $response_facet_value = [];
      $response_facet_value['type'] = 'SearchAPIFacetValue';
      $response_facet_value['filter'] = trim($facet_value['filter'], '"');
      $response_facet_value['count'] = $facet_value['count'];
      $response_facet['solrFacetValues'][] = $response_facet_value;
    }

    return $response_facet;
  }

  /**
   * Loads a Document into a structured response array.
   *
   * @result_item
   *  The result item that contains the document information.
   */
  private function loadResponseDocument($result_item) {

    // Initialise a response document.
    $response_document = [];

    // Loop through all fields in the result item.
    foreach ($result_item->getFields() as $field_id => $field) {

      // Initialise a values.
      $value = NULL;
      $field_values = $field->getValues();
      $field_type = $field->getType();
      $field_cardinality = count($field_values);

      // Fulltext multivalue fields have a different format.
      if ($field_type == 'text') {
        // Multivalue fields.
        if ($field_cardinality > 1) {
          // Create a new array with text values instead of objects.
          foreach ($field_values as $field_value) {
            $value[] = $field_value->getText();
          }
        }
        // Singlevalue fields.
        elseif (!empty($field_values)) {
          $value = $field_values[0]->getText();
        }
      }
      // For other types of fields we can just grab contents from the array.
      else {
        // Multivalue fields.
        if ($field_cardinality > 1) {
          $value = $field_values;
        }
        // Single value fields.
        else {
          if (!empty($field_values)) {
            $value = $field_values[0];
          }
        }
      }
      // Load the value in the response document.
      if (!empty($value)) {
        $response_document[$field_id] = $value;
      }
    }

    // Set the index id in the response document.
    $response_document['index_id'] = $this->index->id();

    // Append the response to the correct index document.
    $response_document['type'] = str_replace("_", "", ucwords($this->index->id() . "Doc", '_'));

    return $response_document;
  }

}
