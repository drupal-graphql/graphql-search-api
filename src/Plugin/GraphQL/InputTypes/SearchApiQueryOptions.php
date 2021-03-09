<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * Search API Query Options input type.
 *
 * @GraphQLInputType(
 *   id = "SearchApiQueryOptions",
 *   name = "SearchApiQueryOptions",
 *   description =  @Translation("Allow passing Search API query options.
 * For simple string use ""value"" field, for array - use ""json"" field as JSON string"),
 *   fields = {
 *     "key" = "String!",
 *     "value" = "String",
 *     "json" = "String",
 *   }
 * )
 */
class SearchApiQueryOptions extends InputTypePluginBase {

}
