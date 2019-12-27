<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Solr relevance score.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIDocument"},
 *   id = "search_api_relevance",
 *   name = "relevance",
 *   type = "Float",
 * )
 */
class SearchAPIRelevance extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['relevance'];
  }

}
