<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * Condition input type.
 *
 * @GraphQLInputType(
 *   id = "facetInput",
 *   name = "FacetInput",
 *   fields = {
 *     "field" = "String!",
 *     "limit" = "Int!",
 *     "operator" = "String!",
 *     "min_count" = "Int!",
 *     "missing" = "Boolean!",
 *   }
 * )
 */
class FacetInput extends InputTypePluginBase {

}
