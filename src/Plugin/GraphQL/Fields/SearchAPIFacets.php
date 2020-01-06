<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Solr facet result.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIResult"},
 *   id = "search_api_facets",
 *   name = "facets",
 *   type = "[SearchAPIFacet]",
 * )
 */
class SearchAPIFacets extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (isset($value['facets'])) {
      foreach ($value['facets'] as $facet_id => $facet) {

        // Prepare a facet response.
        $response_facet = NULL;
        $response_facet['type'] = 'SearchAPIFacet';
        $response_facet['name'] = $facet_id;

        // Loop through the facet values and load them into the response.
        foreach ($facet as $facet_value) {
          $response_facet_value = [];
          $response_facet_value['type'] = 'SearchAPIFacetValue';
          $response_facet_value['filter'] = trim($facet_value['filter'], '"');
          $response_facet_value['count'] = $facet_value['count'];
          $response_facet['solrFacetValues'][] = $response_facet_value;
        }

        yield $response_facet;
      }
    }
  }

}
