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
    if (isset($value['item'][$derivative_id])) {

      $field = $value['item'][$derivative_id];

      $field_values = $field->getValues();
      $field_type = $field->getType();
      $value = NULL;

      // Fulltext multivalue fields have a different format.
      if ($field_type == 'text') {
        // Create a new array with text values instead of objects.
        foreach ($field_values as $field_value) {
          $value[] = $field_value->getText();
        }
      }
      // For other types of fields we can just grab contents from the array.
      else {
        $value = $field_values;
      }
      // Load the value in the response document.
      if (!is_null($value)) {
        // Checking if the value of this derivative is a list or single value so
        // we can parse accordingly.
        if (is_array($value)) {
          foreach ($value as $value_item) {
            yield $value_item;
          }
        }
        else {
          yield $value;
        }
      }
    }
  }

}
