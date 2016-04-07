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
        if ($account->id() == $invite->getOwnerId()) {
          $message = $this->t('You could not use own invite.');
          $redirect = '<front>';
        }
        elseif (($account->id() == 0) && ($invite->getInviteeUserId() == 0) && $invite->getInviteStatus() == INVITE_VALID) {
          // Process new user invitation.
          $_SESSION[INVITE_SESSION_CODE] = $invite->getRegistrationCode();
          $redirect = \Drupal::config('invite.settings')->get('path_to_registration_page');
        }
        elseif (($account->id() != 0)  && ($invite->getInviteStatus() == INVITE_VALID)) {
          $invite->setInviteeUserId($account->id());
          $invite->setJoinedTime(REQUEST_TIME);
          entity_save('invite', $invite);

          unset($_SESSION[INVITE_SESSION_CODE]);
          /** @var \Drupal\user\EntityOwnerInterface $inviter */
          $inviter = $invite->getOwner();
          $message = $this->t('You have accepted the invitation from !user', array('!user' => theme('username', array('account' => $inviter))));
          $redirect = 'user.page';
        }
        elseif ($account->isAnonymous() && ($invite->getInviteeUserId() == 0) && ($invite->getInviteStatus() == INVITE_VALID)) {
          $_SESSION[INVITE_SESSION_CODE] = $invite->getRegistrationCode();
          $message = $this->t('You should login first to accept invite.');
          $redirect = 'user.login';
        }
        else {
          switch ($invite->getInviteStatus()) {
            case INVITE_WITHDRAWN:
              $message = $this->t('This invitation has been withdrawn.');
              break;

            case INVITE_USED:
              $message = $this->t('This invitation has already been used.');
              break;

            case INVITE_EXPIRED:
              $message = $this->t('This invitation has expired.');
              break;

            default:
              $redirect = 'user.page';
          }
        }
    }
    if (!empty($message)) {
      drupal_set_message($message);
    }
    return $this->redirect($redirect);
  }
}
