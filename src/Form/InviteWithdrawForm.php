<?php

/**
 * @file
 * Contains \Drupal\invite\Form\InviteWithdrawForm.
 */

namespace Drupal\invite\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use \Drupal\invite\InviteInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityManagerInterface;

/**
 * Class InviteWithdrawForm.
 *
 * @package Drupal\invite\Form
 */
class InviteWithdrawForm extends FormBase {
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
    return 'invite_withdraw_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, InviteInterface $invite = NULL) {
    /** @var \Drupal\invite\InviteInterface $invite */
    $form['#title'] = $this->t('Withdraw invite for ') . $invite->field_invitation_email_address->value;
    $form['#invite'] = $invite;

    $form['actions']['#type'] = 'actions';
    $form['withdraw_invite'] = array(
      '#type' => 'submit',
      '#title' => $this->t('Withdraw Invite'),
      '#description' => $this->t('Withdraw current invite.'),
      '#button_type' => 'primary',
      '#value' => $this->t('Withdraw Invite'),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

  }

}
