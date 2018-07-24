<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Solr facet values field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SolrFacet"},
 *   id = "solr_facet_values",
 *   name = "solrFacetValues",
 *   type = "[FacetValue]"
 * )
 */
class SolrFacetValues extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    foreach ($value['solrFacetValues'] as $facet_value) {
      yield $facet_value;
    }
  }
}
