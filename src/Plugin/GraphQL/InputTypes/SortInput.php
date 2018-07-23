<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * Condition input type.
 *
 * @GraphQLInputType(
 *   id = "sortInput",
 *   name = "SortInput",
 *   fields = {
 *     "field" = "String!",
 *     "value" = "String!"
 *   }
 * )
 */
class SortInput extends InputTypePluginBase {

}
