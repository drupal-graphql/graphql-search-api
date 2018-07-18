<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * The id of a doc.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SolrDoc"},
 *   id = "doc_id",
 *   deriver = "Drupal\graphql_search_api\Plugin\GraphQL\Derivative\SolrField"
 * )
 */
class DocId extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    $derivative_id = $this->getDerivativeId();
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
