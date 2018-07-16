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
    $array = [1, 2];
    foreach ($array as $doc) {
      yield [
        'type' => 'Doc',
        'docId' => $doc,
      ];
    }
  }
}
