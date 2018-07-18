<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Fields;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Fields\FieldPluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * The id of a doc.
 *
 * @GraphQLField(
 *   secure = true,
 *   parents = {"SolrDoc"},
 *   id = "doc_id",
 *   deriver = "Drupal\graphql_search_api\Plugin\GraphQL\Derivative\SolrField"
 * )
 */
class DocId extends FieldPluginBase {

  /**
   * {@inheritdoc}
   */
  public function resolveValues($value, array $args, ResolveContext $context, ResolveInfo $info) {
    if (in_array($this->getDerivativeId(), [
      'job_ocupational_fields_name',
      'field_job_ocupational_fields',
      'job_employment_type_name',
    ])) {
      foreach ($value[$this->getDerivativeId()] as $value_item) {
        yield $value_item;
      }
    }
    else {
      yield $value[$this->getDerivativeId()];
    }
  }
}
