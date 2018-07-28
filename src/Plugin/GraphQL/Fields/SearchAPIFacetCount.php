<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Search API facet count field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIFacetValue"},
 *   id = "search_api_facet_count",
 *   name = "count",
 *   type = "Int"
 * )
 */
class SearchAPIFacetCount extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['count'];
  }
}
