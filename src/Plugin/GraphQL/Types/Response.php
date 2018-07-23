<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Types;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A solr response type.
 *
 * @GraphQLType(
 *   id = "response",
 *   name = "Response",
 *   interfaces = {"SolrResponse"},
 * )
 */
class Response extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info) {
    return $object['type'] == 'Response';
  }
}
