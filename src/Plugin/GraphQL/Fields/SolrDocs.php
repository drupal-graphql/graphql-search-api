<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Solr Field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SolrResponse"},
 *   id = "solrDocs",
 *   name = "solrDocs",
 *   type = "[Doc]",
 * )
 */
class SolrDocs extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['solrDocs'];
  }
}
