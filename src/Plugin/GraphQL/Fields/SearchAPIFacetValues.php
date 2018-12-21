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
 *   parents = {"SearchAPIFacet"},
 *   id = "search_api_facet_values",
 *   name = "values",
 *   type = "[SearchAPIFacetValue]"
 * )
 */
class SearchAPIFacetValues extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (isset($value['solrFacetValues'])) {
      foreach ($value['solrFacetValues'] as $facet_value) {
          yield $facet_value;
      }
    }
  }
}
