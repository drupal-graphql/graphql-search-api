<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * More Like This input type.
 *
 * @GraphQLInputType(
 *   id = "MLTInput",
 *   name = "MLTInput",
 *   fields = {
 *     "id" = "String!",
 *     "fields" = "[String]!",
 *   }
 * )
 */
class MLTInput extends InputTypePluginBase {

}
