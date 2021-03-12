<?php

namespace Drupal\graphql_search_api\Plugin\GraphQL\Derivers;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides derivers for different index documents in Search API.
 */
class SearchAPIDocumentTypeDeriver extends DeriverBase implements ContainerDeriverInterface {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new Search API document type.
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
