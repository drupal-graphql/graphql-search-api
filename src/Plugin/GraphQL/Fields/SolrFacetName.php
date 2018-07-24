<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Solr Field.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SolrFacet"},
 *   id = "solr_facet_name",
 *   name = "solrFacetName",
 *   type = "String"
 * )
 */
class SolrFacetName extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    yield $value['solrFacetName'];
  }
}
