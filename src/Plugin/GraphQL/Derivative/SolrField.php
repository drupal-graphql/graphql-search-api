<?php

/**
 * @file
 * Contains \Drupal\graphql_search_api\Plugin\GraphQL\Derivative\SolrField.
 */

namespace Drupal\graphql_search_api\Plugin\GraphQL\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
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
    $nodes = $this->nodeStorage->loadByProperties(['type' => 'job_per_template']);
    foreach ($nodes as $node) {
      $this->derivatives['field' . $node->id()] = $base_plugin_definition;
      $this->derivatives['field' . $node->id()]['id'] = 'field_' . $node->id();
      $this->derivatives['field' . $node->id()]['name'] = 'field' . $node->id();
      $this->derivatives['field' . $node->id()]['type'] = 'String';
    }
    return $this->derivatives;
  }
}
