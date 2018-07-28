<?php

namespace Drupal\graphql_search_api\Utility;

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

    if (!empty($field->getDatasourceId())) {

      // Obtain details needed to load the field from storage.
      $datasource_id = explode(':', $field->getDatasourceId())[1];
      $property_path = $field->getPropertyPath();
      $field_name_original = explode(':', $property_path)[0];

      // Load the field from storage.
      $field_storage_config = \Drupal::entityTypeManager()
        ->getStorage('field_storage_config')
        ->load($datasource_id . '.' . $field_name_original);

      // Set field as multivalue if it has unlimited cardinality.
      if (isset($field_storage_config) && $field_storage_config->getCardinality() == FieldStorageConfigInterface::CARDINALITY_UNLIMITED) {
        $multivalue = TRUE;
      }
    }

    return $multivalue;
  }

}
