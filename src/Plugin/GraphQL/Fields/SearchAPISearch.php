<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\field\FieldStorageConfigInterface;
use GraphQL\Type\Definition\ResolveInfo;
use Drupal\search_api\Entity\Index;
use Drupal\graphql_search_api\Utility\SearchAPIHelper;

/**
 * A query field that wraps a Search API query.
 *
 * @GraphQLField(
 *   id = "search_api_search",
 *   type = "SearchAPIResult",
 *   name = "searchAPISearch",
 *   nullable = true,
 *   multi = false,
 *   arguments = {
 *     "index_id" = "String",
 *     "fulltext" = "FulltextInput",
 *     "language" = "[String]",
 *     "conditions" = "[ConditionInput]",
 *     "range" = "RangeInput",
 *     "sort" = "SortInput",
 *     "facets" = "[FacetInput]",
 *   },
 * )
 */
class SearchAPISearch extends FieldPluginBase {

  private $query;
  private $index;

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {

    // Load up the index passed in argument.
    $this->index = Index::load($args['index_id']);

    // Prepare the query with our arguments.
    $this->prepareSearchAPIQuery($args);

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

    // Set response type.
    $search_response['type'] = 'SearchAPIResult';

    yield $search_response;

  }

  protected function addConditions($conditions) {

    // Loop through conditions to add them into the query.
    foreach ($conditions as $condition) {
      if (empty($condition['operator'])) {
        $condition['operator'] = '=';
      }
      // Set the condition in the query.
      $this->query->addCondition($condition['name'], $condition['value'], $condition['operator']);
    }
  }

  protected function setFulltextFields($full_text_params) {

    // Set the mandatory keys in the query.
    $this->query->keys($full_text_params['keys']);

    // Set the optional fulltext fields if specified.
    if (!empty($full_text_params['fields'])) {
      $this->query->setFulltextFields($full_text_params['fields']);
    }
  }

  protected function setFacets($facets) {

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

  protected function prepareSearchAPIQuery($args) {

    // Prepare a query for the respective Search API index.
    $this->query = $this->index->query();

    // Adding query conditions if they exist.
    if ($args['conditions']) {
      $this->addConditions($args['conditions']);
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
      $this->query->sort($args['sort']['field'], $args['sort']['value']);
    }
    // Adding facets to the query.
    if ($args['facets']) {
      $this->setFacets($args['facets']);
    }
  }

  protected function getSearchResponse($results) {

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

    // Loop through each facet in the result set.
    foreach ($facets as $facet_id => $facet_values) {
      // Load the response facet in the response array.
      $search_response['facets'][] = $this->loadResponseFacet($facet_id, $facet_values);
    }
    return $search_response;
  }

  protected function loadResponseFacet($facet_id, $facet_values) {

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

  protected function loadResponseDocument($result_item) {

    // Initialise a response document.
    $response_document = [];

    // Loop through all fields in the result item.
    foreach ($result_item->getFields() as $field_id => $field) {

      // Initialise a values.
      $value = NULL;
      $field_values = $field->getValues();

      // Try to obtain the text value for this field.
      // @Todo this section isn't working properly.
      if (!empty($field_values[0]) && method_exists($field_values[0], 'getText')) {
        $value = $field_values[0]->getText();
      }
      // If we can't then we need to load it from the storage config.
      else {
        if (SearchAPIHelper::checkMultivalue($field)) {
          $value = $field_values;
        }
        else {
          if (!empty($field_values[0])) {
            $value = $field_values[0];
          }
        }
      }
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
