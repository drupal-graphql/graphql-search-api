<?php

namespace Drupal\graphql_search_api\Utility;

use Drupal\field\FieldStorageConfigInterface;

/**
 * TODO.
 */
class SearchAPIHelper {

  /**
   * Checks storage if a field is multivalued.
   *
   * @field
   *  The field to be checked.
   */
  public static function checkMultivalue($field) {

    $multivalue = FALSE;

    $field_config = $field->getDependencies()['config'][0];

    if ($field_config) {

      $field_id = str_replace('field.storage.',"", $field_config);

      // Load the field from storage.
      $field_storage_config = \Drupal::entityTypeManager()
        ->getStorage('field_storage_config')
        ->load($field_id);

      // Set field as multivalue if it has unlimited cardinality.
      if (isset($field_storage_config) && $field_storage_config->getCardinality() == FieldStorageConfigInterface::CARDINALITY_UNLIMITED) {
        $multivalue = TRUE;
      }
    }

    return $multivalue;
  }

}
