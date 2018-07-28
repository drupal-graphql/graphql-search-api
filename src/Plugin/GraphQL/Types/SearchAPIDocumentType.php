<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Types;

use Drupal\graphql\GraphQL\Execution\ResolveContext;
use Drupal\graphql\Plugin\GraphQL\Types\TypePluginBase;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * A Search API document type.
 *
 * @GraphQLType(
 *   id = "search_api_document_type",
 *   interfaces = {"SearchAPIDocument"},
 *   deriver = "Drupal\graphql_search_api\Plugin\GraphQL\Derivers\SearchAPIDocumentTypeDeriver"
 * )
 */
class SearchAPIDocumentType extends TypePluginBase {

  /**
   * {@inheritdoc}
   */
  public function applies($object, ResolveContext $context, ResolveInfo $info) {

    // Retrieve the document type derivative.
    $derivative_id = $this->getDerivativeId();

    return $object['type'] == $derivative_id;
  }

}
