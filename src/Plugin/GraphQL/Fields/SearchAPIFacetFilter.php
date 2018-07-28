<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Search API facet filter field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIFacetValue"},
 *   id = "search_api_facet_filter",
 *   name = "filter",
 *   type = "String"
 * )
 */
class SearchAPIFacetFilter extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['filter'];
  }
}
