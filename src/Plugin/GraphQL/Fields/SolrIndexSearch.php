<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\field\FieldStorageConfigInterface;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A simple field that wraps a search api solr query.
 *
 * For simplicity reasons, this example does not utilize dependency injection.
 *
 * @GraphQLField(
 *   id = "solr_index_search",
 *   type = "[Doc]",
 *   name = "solrIndexSearch",
 *   nullable = true,
 *   multi = false,
 *   arguments = {
 *     "keys" = "String",
 *     "language" = "[String]",
 *     "conditions" = "[ConditionInput]",
 *     "range" = "RangeInput",
 *     "sort" = "SortInput"
 *   },
 *   deriver =
 *   "Drupal\graphql_search_api\Plugin\GraphQL\Derivative\SolrIndexSearch"
 * )
 */
class SolrIndexSearch extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {

    $derivative_id = $this->getDerivativeId();
    $index = \Drupal\search_api\Entity\Index::load($derivative_id);

    $query = $index->query();

    // Set additional conditions.
    if ($args['conditions']) {
      foreach ($args['conditions'] as $condition) {
        if (empty($condition['operator'])) {
          $condition['operator'] = '=';
        }

        $query->addCondition($condition['name'], $condition['value'], $condition['operator']);
      }
    }

    if ($args['language']) {
      // Restrict the search to specific languages.
      $query->setLanguages($args['language']);
    }

    if ($args['keys']) {
      $query->keys($args['keys']);
    }

    if ($args['range']) {
      // Do paging.
      $query->range($args['range']['start'], $args['range']['end']);
    }

    if ($args['sort']) {
      // Do paging.
      $query->sort($args['sort']['field'], $args['sort']['value']);
    }

    try {
      // Execute the search.
      $results = $query->execute();
      $result_items = $results->getResultItems();
    }
    catch (\Exception $exception) {
      // Handle error, check exception type -> SearchApiException ?
      \Drupal::logger('graphql_search_api')->error($exception);
    }
    foreach ($result_items as $id => &$item) {
      $return = [];
      foreach ($item->getFields() as $field_id => $field) {
        $value = NULL;
        $field_values = $field->getValues();
        if (!empty($field_values[0]) && method_exists($field_values[0], 'getText')) {
          $value = $field_values[0]->getText();
        }
        else {
          if (!empty($field->getDatasourceId()[1])) {
            $datasource_id = explode(':', $field->getDatasourceId())[1];
            $property_path = $field->getPropertyPath();
            $field_name_original = explode(':', $property_path)[0];
            $field_storage_config = \Drupal::entityTypeManager()
              ->getStorage('field_storage_config')
              ->load($datasource_id . '.' . $field_name_original);
          }
          if (isset($field_storage_config) && $field_storage_config->getCardinality() == FieldStorageConfigInterface::CARDINALITY_UNLIMITED) {
            $value = $field_values;
          }
          else {
            if (!empty($field_values[0])) {
              $value = $field_values[0];
            }
          }
        }
        if (!empty($value)) {
          $return[$field_id] = $value;
        }
      }
      $return['type'] = 'Doc';
      yield $return;
    }
  }
}
