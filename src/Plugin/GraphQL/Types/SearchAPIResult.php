<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Types;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Search API result type.
 *
 * @GraphQLType(
 *   id = "search_api_result",
 *   name = "SearchAPIResult",
 * )
 */
class SearchAPIResult extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info) {
    return $object['type'] == 'SearchAPIResult';
  }
}
