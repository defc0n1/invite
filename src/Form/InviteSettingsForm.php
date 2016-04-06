<?php

/**
 * @file
 * Contains Drupal\invite\Form\InviteSettingsForm.
 */

namespace Drupal\invite\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class InviteSettingsForm.
 *
 * @package Drupal\invite\Form
 */
class InviteSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'invite.invitesettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'invite_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('invite.invitesettings');

    $expiry_options = array(1 => 1, 3 => 3, 7 => 7, 14 => 14, 30 => 30, 60 => 60, 365 => 365);
    $form['invitation_expiry'] = array(
      '#type' => 'select',
      '#title' => $this->t('Invitation Expiry'),
      '#default_value' => $config->get('invitation_expiry'),
      '#options' => $expiry_options,
      '#description' => $this->t('Set the expiry period for user invitations, in days.'),
    );

    $form['path_to_registration_page'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Path to Registration Page'),
      '#default_value' => $config->get('path_to_registration_page'),
      '#description' => $this->t('Path to the registration page for invited users. '),
    );

    $form['admin_approval_for_invitee'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Require administrator approval for invitees'),
      '#default_value' => $config->get('restrict_role_login_by_ip_header'),
      '#description' => $this->t('Accounts that have been created with an invitation will require administrator approval.'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('invite.invitesettings')
      ->set('invitation_expiry', $form_state->getValue('invitation_expiry'))
      ->set('path_to_registration_page', $form_state->getValue('path_to_registration_page'))
      ->set('admin_approval_for_invitee', $form_state->getValue('admin_approval_for_invitee'))
      ->save();
  }

}
