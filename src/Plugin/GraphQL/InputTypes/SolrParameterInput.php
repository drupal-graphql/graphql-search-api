<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\InputTypes;

use Drupal\graphql\Plugin\GraphQL\InputTypes\InputTypePluginBase;

/**
 * An input type that allows for direct Solr Query parameters.
 *
 * @GraphQLInputType(
 *   id = "search_api_solr_params",
 *   name = "SolrParameterInput",
 *   fields = {
 *     "parameter" = "String!",
 *     "value" = "String!",
 *   }
 * )
 */
class SolrParameterInput extends InputTypePluginBase {

}
