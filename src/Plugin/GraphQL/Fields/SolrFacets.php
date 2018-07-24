<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Solr facet result.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SolrResponse"},
 *   id = "solrFacets",
 *   name = "solrFacets",
 *   type = "[Facet]",
 * )
 */
class SolrFacets extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    foreach ($value['solrFacets'] as $facet) {
      yield $facet;
    }
  }
}
