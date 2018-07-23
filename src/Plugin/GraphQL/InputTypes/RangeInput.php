<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * Condition input type.
 *
 * @GraphQLInputType(
 *   id = "rangeInput",
 *   name = "RangeInput",
 *   fields = {
 *     "start" = "Int!",
 *     "end" = "Int!"
 *   }
 * )
 */
class RangeInput extends InputTypePluginBase {

}
