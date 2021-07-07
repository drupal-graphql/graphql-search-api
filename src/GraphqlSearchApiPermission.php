<?php

namespace Drupal\graphql_search_api;

use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\search_api\Entity\Index;
use Drupal\search_api\IndexInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides dynamic permissions for search API indexes.
 */
class GraphqlSearchApiPermission implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * GraphqlSearchApiPermission constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager) {
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static($container->get('entity_type.manager'));
  }

  /**
   * Returns an array of index permissions.
   *
   * @return array
   *   The search api index permissions.
   */
  public function getPermissions() {
    $permissions = [];

    foreach ($this->entityTypeManager->getStorage('search_api_index')->loadMultiple() as $index) {
      $permissions += [
        self::getPermissionName($index) => [
          'title' => $this->t('Execute GraphQL query against @index search index', [
            '@index' => $index->label(),
          ]),
          'description' => $this->t('Allows user to execute arbitrary GraphQL queries against the @index Search API index. Therefore contents of the @index Search API index will be available to users with this permission via GraphQL queries.', [
            '@index' => $index->label(),
          ]),
        ],
      ];
    }

    return $permissions;
  }

  /**
   * Assemble permission name that allows querying Search API index in GraphQL.
   *
   * @param \Drupal\search_api\IndexInterface $index
   *   Search API index whose permission name to assemble.
   *
   * @return string
   *   Permission name that allows executing GraphQL queries against the
   *   supplied Search API index.
   */
  public static function getPermissionName(IndexInterface $index) {
    return "execute graphql requests {$index->id()} index";
  }

}
