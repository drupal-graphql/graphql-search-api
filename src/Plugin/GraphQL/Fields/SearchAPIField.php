<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Search API Field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIDocument"},
 *   id = "search_api_field",
 *   deriver = "Drupal\graphql_search_api\Plugin\GraphQL\Derivers\SearchAPIFieldDeriver"
 * )
 */
class SearchAPIField extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {

    $derivative_id = $this->getDerivativeId();

    // Not all documents have values for all fields so we need to check.
    if (isset($value[$derivative_id])) {

      // Checking if the value of this derivative is a list or single value so
      // we can parse accordingly.
      if (is_array($value[$derivative_id])) {
        foreach ($value[$derivative_id] as $value_item) {
          yield $value_item;
        }
      }
      else {
        yield $value[$derivative_id];
      }
    }
  }

}
