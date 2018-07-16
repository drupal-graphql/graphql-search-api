<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * The id of a doc.
 *
 * @GraphQLField(
 *   id = "doc_id",
 *   secure = true,
 *   name = "docId",
 *   type = "Int",
 *   parents = {"Doc"}
 * )
 */
class DocId extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['docId'];
  }
}
