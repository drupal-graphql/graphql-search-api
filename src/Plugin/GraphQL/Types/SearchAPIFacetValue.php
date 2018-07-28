<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Types;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A solr facet value.
 *
 * @GraphQLType(
 *   id = "facet_value",
 *   name = "SearchAPIFacetValue",
 * )
 */
class SearchAPIFacetValue extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info) {
    return $object['type'] == 'SearchAPIFacetValue';
  }
}
