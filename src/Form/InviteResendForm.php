<?php

/**
 * @file
 * Contains \Drupal\invite\Form\InviteResendForm.
 */

namespace Drupal\invite\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\invite\InviteInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityManagerInterface;
use \Drupal\Core\Url;

/**
 * Class InviteResendForm.
 *
 * @package Drupal\invite\Form
 */
class InviteResendForm extends FormBase {

  /**
   * The node storage.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $inviteStorage;

  /**
   * The entity manager.
   *
   * @var \Drupal\Core\Entity\EntityStorageInterface
   */
  protected $entityManager;

  /**
   * Constructs a InviteAcceptController object.
   * @param \Drupal\Core\Entity\EntityStorageInterface $invite_storage
   *   Invite storage
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct(EntityStorageInterface $invite_storage, EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
    $this->inviteStorage = $invite_storage;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    $entity_manager = $container->get('entity.manager');
    return new static(
      $entity_manager->getStorage('invite'),
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'invite_resend_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, InviteInterface $invite = NULL) {
    /** @var \Drupal\invite\InviteInterface $invite */
    $form['#title'] = $this->t('Resend invite to ') . $invite->field_invitation_email_address->value;
    $this->inviteStorage = $invite;

    $form['actions']['#type'] = 'actions';
    $form['resend_invite'] = array(
      '#type' => 'submit',
      '#title' => $this->t('Resend Invite'),
      '#description' => $this->t('Resend current invite.'),
      '#button_type' => 'primary',
      '#value' => $this->t('Resend Invite'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    /** @var \Drupal\invite\InviteInterface $invite */
    $invite = $this->inviteStorage;
    invite_by_email_send_invitation($invite);
    $url  = Url::fromRoute('user.page');
    $form_state->setRedirectUrl($url);
  }

}
