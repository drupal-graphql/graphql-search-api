<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Derivers;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\graphql_search_api\Utility\SearchAPIHelper;
use Drupal\search_api\Item\FieldInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides GraphQL Field plugin definitions for Search API fields.
 */
class SearchAPIFieldDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The entity type manager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new Search API field.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static($container->get('entity_type.manager'));
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {

    // Loading all existing Search API Indexes.
    $indexes = $this->entityTypeManager->getStorage('search_api_index')->loadMultiple();

    // Initialise generic index_id field using the base plugin definition.
    $this->derivatives['index_id'] = $base_plugin_definition;
    $this->derivatives['index_id']['name'] = 'index_id';
    $this->derivatives['index_id']['type'] = 'String';

    /** @var \Drupal\search_api\IndexInterface $index */
    foreach ($indexes as $index_id => $index) {
      $parent = str_replace("_", "", ucwords($index_id . "Doc", '_'));

      foreach ($index->getFields() as $field_id => $field) {
        $derivative_id = implode(':', [$parent, $field_id]);
        if (isset($this->derivatives[$derivative_id])) {
          $base_plugin_definition['parents'][] = $parent;
        }
        else {
          // Define to which Doc type variant the field belongs to.
          $base_plugin_definition['parents'] = [$parent];

          // Initialising derivative settings.
          $this->derivatives[$derivative_id] = $base_plugin_definition;
          $this->derivatives[$derivative_id]['id'] = $field_id;
          $this->derivatives[$derivative_id]['name'] = $field_id;

          // Set field type.
          $this->setFieldType($field, $derivative_id);
        }
      }
    }
    return $this->derivatives;
  }

  /**
   * This method maps the field types in Search API to GraphQL types.
   *
   * @param \Drupal\search_api\Item\FieldInterface $field
   *   The field to map.
   * @param string $derivative_id
   *   The id of the field derivative to map.
   */
  private function setFieldType(FieldInterface $field, $derivative_id) {

    // Get field type.
    $type = $field->getType();

    // We can only check if a field is multivalue if it has a Datasource.
    // @todo This seems inefficient, check when it's being cached
    $multivalue = SearchAPIHelper::checkMultivalue($field);

    // Map the Search API types to GraphQL.
    switch ($type) {

      case  'text':
        $this->derivatives[$derivative_id]['type'] = 'String';
        break;

      case  'string':
        $this->derivatives[$derivative_id]['type'] = 'String';
        break;

      case  'boolean':
        $this->derivatives[$derivative_id]['type'] = 'Boolean';
        break;

      case  'integer':
        $this->derivatives[$derivative_id]['type'] = 'Int';
        break;

      case  'decimal':
        $this->derivatives[$derivative_id]['type'] = 'Float';
        break;

      case  'date':
        $this->derivatives[$derivative_id]['type'] = 'Timestamp';
        break;

      default:
        $this->derivatives[$derivative_id]['type'] = 'String';
        break;

    }

    // If field is multivalue we set the type as an array.
    if ($multivalue) {
      $this->derivatives[$derivative_id]['type'] = '[' . $this->derivatives[$derivative_id]['type'] . ']';
    }
  }

}
