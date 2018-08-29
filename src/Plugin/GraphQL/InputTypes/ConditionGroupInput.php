<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * Condition input type.
 *
 * @GraphQLInputType(
 *   id = "condition_group_input",
 *   name = "ConditionGroupInput",
 *   fields = {
 *     "conditions" = "[ConditionInput]",
 *     "groups" = "[ConditionGroupInput]",
 *     "tags" = "[String]",
 *     "conjunction" = {
 *       "type" = "QueryConjunction",
 *       "default" = "AND"
 *     }
 *   }
 * )
 */
class ConditionGroupInput extends InputTypePluginBase {

}
