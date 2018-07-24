<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Solr facet filter field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SolrFacetValue"},
 *   id = "solr_facet_filter",
 *   name = "solrFacetFilter",
 *   type = "String"
 * )
 */
class SolrFacetFilter extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['solrFacetFilter'];
  }
}
