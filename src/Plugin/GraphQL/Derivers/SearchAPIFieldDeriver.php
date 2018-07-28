<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Derivers;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\search_api\Entity\Index;
use Drupal\graphql_search_api\Utility\SearchAPIHelper;

/**
 * Provides GraphQL Field plugin definitions for Search API fields.
 */
class SearchAPIFieldDeriver extends DeriverBase {

  /**
   * Constructs a new Search API field.
   */
  public function __construct() {
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    // Loading all existing Search API Indexes.
    $indexes = Index::loadMultiple();

    // Initialise generic index_id field using the base plugin definition.
    $this->derivatives['index_id'] = $base_plugin_definition;
    $this->derivatives['index_id']['name'] = 'index_id';
    $this->derivatives['index_id']['type'] = 'String';

    foreach ($indexes as $index_id => $index) {

      foreach ($index->getFields() as $field_id => $field) {

        // Define to which Doc type variant the field belongs to.
        $base_plugin_definition['parents'][0] = str_replace("_", "", ucwords($index_id . "Doc", '_'));

        // Initialising derivative settings.
        $this->derivatives[$field_id] = $base_plugin_definition;
        $this->derivatives[$field_id]['id'] = $field_id;
        $this->derivatives[$field_id]['name'] = $field_id;

        // Set field type.
        $this->setFieldType($field, $field_id);

      }
    }
    return $this->derivatives;
  }

  /**
   * This method maps the field types in Search API to GraphQL types.
   *
   * @field
   *   The field to map.
   * @field_id
   *   The id of the field to map.
   */
  private function setFieldType($field, $field_id) {

    // Get field type.
    $type = $field->getType();

    // We can only check if a field is multivalue if it has a Datasource.
    // @Todo This seems inefficient, check when it's being cached
    $multivalue = SearchAPIHelper::checkMultivalue($field);

    // Map the Search API types to GraphQL.
    switch ($type) {

      case  'text':
        $this->derivatives[$field_id]['type'] = 'String';
        break;

      case  'string':
        $this->derivatives[$field_id]['type'] = 'String';
        break;

      case  'boolean':
        $this->derivatives[$field_id]['type'] = 'Boolean';
        break;

      case  'integer':
        $this->derivatives[$field_id]['type'] = 'Int';
        break;

      case  'date':
        $this->derivatives[$field_id]['type'] = 'Timestamp';
        break;

      default:
        $this->derivatives[$field_id]['type'] = 'String';
        break;

    }

    // If field is multivalue we set the type as an array.
    if ($multivalue) {
      $this->derivatives[$field_id]['type'] = '[' . $this->derivatives[$field_id]['type'] . ']';
    }
  }

}
