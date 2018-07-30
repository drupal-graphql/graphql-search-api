<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Derivers;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\search_api\Entity\Index;

/**
 * Provides derivers for different index documents in Search API.
 */
class SearchAPIDocumentTypeDeriver extends DeriverBase {

  /**
   * Constructs a new Search API document type.
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    // Loading all existing Search API Indexes.
    $indexes = Index::loadMultiple();

    // Loop through all indexes.
    foreach ($indexes as $index_id => $index) {

      // Create a document type based on the index.
      $document_type = str_replace("_", "", ucwords($index_id . "Doc", '_'));

      // Create a derivative.
      $this->derivatives[$document_type] = $base_plugin_definition;
      $this->derivatives[$document_type]['id'] = $document_type;
      $this->derivatives[$document_type]['name'] = $document_type;

    }
    return $this->derivatives;
  }

}
