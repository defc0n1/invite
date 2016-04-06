<?php

/**
 * @file
 * Contains \Drupal\invite\Controller\InviteAcceptController.
 */

namespace Drupal\invite\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class InviteAcceptController.
 *
 * @package Drupal\invite\Controller
 */
class InviteAcceptController extends ControllerBase {

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityManager;

  /**
   * Constructs a InviteAcceptController object.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct(EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager')
    );
  }

  /**
   * Accept.
   *
   * @return string
   *   Return Hello string.
   */
  public function accept($reg_code) {
    $account = \Drupal::currentUser();
    /** @var \Drupal\invite\InviteInterface $invite */
    $invite = $this->entityManager->getStorage('invite')->loadByProperties(array('reg_code' => $reg_code));
    if ($invite = reset($invite)) {
        $entity_id = $invite->id();
        if ($account->id() == $invite->getOwnerId()) {
          $message = $this->t('You could not use own invite.');
          $redirect = '<front>';
        }
    }

    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: accept with parameter(s): !name', [
        '!name' => $entity_id,
      ]),
    ];
  }

}
