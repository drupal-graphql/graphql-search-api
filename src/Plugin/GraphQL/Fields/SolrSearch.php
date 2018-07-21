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
 *   id = "solr_search",
 *   type = "[Doc]",
 *   name = "solrSearch",
 *   nullable = true,
 *   multi = false,
 *   arguments = {
 *     "query" = "String"
 *   }
 * )
 */
class SolrSearch extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  protected function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {

    // this should also come with the query
    $index = \Drupal\search_api\Entity\Index::load('default_solr_index');

    $query = $index->query();

    // Set additional conditions.
    $query->addCondition('status', 1);

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
