<?php

declare(strict_types=1);

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Search API spellcheck source.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPISpellcheck"},
 *   id = "search_api_spellcheck_source",
 *   name = "source",
 *   type = "String"
 * )
 */
class SearchAPISpellcheckSource extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['source'];
  }

}
