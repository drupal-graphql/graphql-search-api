<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * Condition input type.
 *
 * @GraphQLInputType(
 *   id = "condition_input",
 *   name = "ConditionInput",
 *   fields = {
 *     "name" = "String",
 *     "value" = "String",
 *     "operator" = "String"
 *   }
 * )
 */
class ConditionInput extends InputTypePluginBase {

}
