<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Search API document Field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIResult"},
 *   id = "search_api_documents",
 *   name = "documents",
 *   type = "[SearchAPIDocument]",
 * )
 */
class SearchAPIDocuments extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (isset($value['SearchAPIDocument'])) {
      foreach ($value['SearchAPIDocument'] as $doc) {
        $doc['item'] = $doc['item']->getFields();
        yield $doc;
      }
    }
  }

}
