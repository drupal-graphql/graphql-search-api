<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Solr facet count field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SolrFacetValue"},
 *   id = "solr_facet_count",
 *   name = "solrFacetCount",
 *   type = "Int"
 * )
 */
class SolrFacetCount extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['solrFacetCount'];
  }
}
