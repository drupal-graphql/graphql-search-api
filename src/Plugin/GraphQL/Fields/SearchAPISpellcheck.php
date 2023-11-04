<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Solr spellcheck.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SearchAPIResult"},
 *   id = "search_api_spellcheck",
 *   name = "spellcheck",
 *   type = "[SearchAPISpellcheck]",
 * )
 */
class SearchAPISpellcheck extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (isset($value['spellcheck'])) {
      foreach ($value['spellcheck'] as $term => $suggestions) {

        // Prepare a facet response.
        $response_suggestion = NULL;
        $response_suggestion['type'] = 'SearchAPISpellcheck';
        $response_suggestion['source'] = $term;

        // Loop through the facet values and load them into the response.
        foreach ($suggestions as $suggestion) {
          $response_suggestion['suggestion'] = $suggestion;
          yield $response_suggestion;
        }
      }
    }
  }

}
