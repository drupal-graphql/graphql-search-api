<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Search API result count.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIResult"},
 *   id = "search_api_result_count",
 *   name = "result_count",
 *   type = "Int"
 * )
 */
class SearchAPIResultCount extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['result_count'];
  }

}
