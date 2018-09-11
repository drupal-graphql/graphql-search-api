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
 *   parents = {"SearchAPIResult"},
 *   id = "search_api_facets",
 *   name = "facets",
 *   type = "[SearchAPIFacet]",
 * )
 */
class SearchAPIFacets extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (isset($value['facets'])) {
      foreach ($value['facets'] as $facet) {
        yield $facet;
      }
    }
  }

}
