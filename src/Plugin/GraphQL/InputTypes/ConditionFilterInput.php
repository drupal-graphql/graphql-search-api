<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * Condition input type.
 *
 * @GraphQLInputType(
 *   id = "condition_filter_input",
 *   name = "ConditionFilterInput",
 *   fields = {
 *     "conditions" = "[ConditionInput]",
 *     "groups" = "[ConditionFilterInput]",
 *     "operator" = "String",
 *     "conjunction" = {
 *       "type" = "QueryConjunction",
 *       "default" = "AND"
 *     }
 *   }
 * )
 */
class ConditionFilterInput extends InputTypePluginBase {

}
