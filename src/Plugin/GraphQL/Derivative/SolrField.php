<?php

/**
 * @file
 * Contains \Drupal\graphql_search_api\Plugin\GraphQL\Derivative\SolrField.
 */

namespace Drupal\graphql_search_api\Plugin\GraphQL\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Drupal\field\FieldStorageConfigInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides GraphQL Field plugin definitions for solr fields.
 */
class SolrField extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The node storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $nodeStorage;

  /**
   * Constructs new NodeBlock.
   *
   * @param \Drupal\Core\Entity\EntityStorageInterface $node_storage
   *   The node storage.
   */
  public function __construct(EntityStorageInterface $node_storage) {
    $this->nodeStorage = $node_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_id) {
    return new static(
      $container->get('entity.manager')->getStorage('node')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $index = \Drupal\search_api\Entity\Index::load('default_solr_index');
    foreach ($index->getFields() as $field_id => $field) {
      $this->derivatives[$field_id] = $base_plugin_definition;
      $this->derivatives[$field_id]['id'] = $field_id;
      $this->derivatives[$field_id]['name'] = $field_id;
      $type = $field->getType();
      $multivalue = false;

      if (!empty($field->getDatasourceId()[1])) {
        $datasource_id = explode(':', $field->getDatasourceId())[1];
        $property_path = $field->getPropertyPath();
        $field_name_original = explode(':', $property_path)[0];
        $field_storage_config = \Drupal::entityTypeManager()
          ->getStorage('field_storage_config')
          ->load($datasource_id . '.' . $field_name_original);
        if (isset($field_storage_config) && $field_storage_config->getCardinality() == FieldStorageConfigInterface::CARDINALITY_UNLIMITED) {
          $multivalue = TRUE;
        }
      }

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

      if($multivalue) {
        $this->derivatives[$field_id]['type'] = '[' . $this->derivatives[$field_id]['type'] . ']';
      }
    }
    return $this->derivatives;
  }
}
