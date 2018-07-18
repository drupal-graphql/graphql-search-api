<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql_search_api\Plugin\GraphQL\Types\Doc;

use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;
use Drupal\graphql\GraphQL\Execution\ResolveContext;

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
      $return['type'] = 'Doc';
      foreach ($item->getFields() as $field_id => $field) {
        if (!empty($field->getValues()[0]) && method_exists($field->getValues()[0], 'getText')) {
          $value = $field->getValues()[0]->getText();
        }
        else {
          if (!empty($field->getValues()[0])) {
            $value = $field->getValues()[0];
          }
        }
        $return[$field_id] = $value;
      }
      $return['type'] = 'Doc';
      yield $return;
    }
  }
}
