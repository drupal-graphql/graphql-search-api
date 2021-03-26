<?php

declare(strict_types=1);

namespace Drupal\graphql_search_api\Plugin\GraphQL\Types;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Solr spellcheck.
 *
 * @GraphQLType(
 *   id = "spellcheck",
 *   name = "SearchAPISpellcheck",
 * )
 */
class SearchAPISpellcheck extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info) {
    return $object['type'] == 'SearchAPISpellcheck';
  }

}
