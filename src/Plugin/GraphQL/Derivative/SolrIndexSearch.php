<?php

/**
 * @file
 * Contains
 *   \Drupal\graphql_search_api\Plugin\GraphQL\Derivative\SolrIndexSearch.
 */

namespace Drupal\graphql_search_api\Plugin\GraphQL\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;

/**
 * Provides GraphQL Field plugin definitions for solr fields.
 */
class SolrIndexSearch extends DeriverBase {

  /**
   * Constructs new Solr index field.
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    // get indexes for each provide a custom name.
    $indexes = \Drupal\search_api\Entity\Index::loadMultiple();
    foreach ($indexes as $index_id => $index) {
      $this->derivatives[$index_id] = $base_plugin_definition;
      $this->derivatives[$index_id]['id'] = $index->id();
      $this->derivatives[$index_id]['name'] = $index->id() . 'SolrSearch';
    }
    return $this->derivatives;
  }
}
