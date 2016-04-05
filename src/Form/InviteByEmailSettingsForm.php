<?php

/**
 * @file
 * Contains Drupal\invite\Form\InviteByEmailSettingsForm.
 */

namespace Drupal\invite\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class InviteByEmailSettingsForm.
 *
 * @package Drupal\invite\Form
 */
class InviteByEmailSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'invite.invitebyemailsettings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'invite_by_email_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('invite.invitebyemailsettings');

    $form['invite_message_editable'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Editable subject'),
      '#description' => $this->t('Choose whether users should be able to customize the subject.'),
      '#default_value' => $config->get('invite_message_editable'),
    );

    $form['invite_default_mail_subject'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Subject'),
      '#default_value' => $config->get('invite_default_mail_subject'),
      '#description' => $this->t('Type the default subject of the invitation e-mail.') . ' ' . t('Use the syntax [token] if you want to insert a replacement pattern.'),
      '#required' => TRUE,
    );

    $form['invite_default_mail_body'] = array(
      '#type' => 'textarea',
      '#title' => $this->t('Mail template'),
      '#default_value' => $config->get('invite_default_mail_body'),
      '#required' => TRUE,
      '#description' => $this->t('Use the syntax [token] if you want to insert a replacement pattern.'),
    );

    $form['invite_default_replace_tokens'] = array(
      '#type' => 'checkbox',
      '#title' => $this->t('Apply token replacements'),
      '#default_value' => $config->get('invite_default_replace_tokens'),
      '#description' => $this->t('Whether token replacement patterns should be applied.'),
    );

    /*if (\Drupal::moduleHandler()->moduleExists('token')) {
      $form['token_help'] = array(
        '#title' => $this->t('Replacement patterns'),
        '#type' => 'fieldset',
        '#collapsible' => TRUE,
        '#collapsed' => TRUE,
      );

      $form['token_help']['help'] = array(
        '#markup' => theme('token_tree', array('token_types' => array('user', 'profile', 'invite'))),
      );
    }*/

    $form['invite_use_users_email'] = array(
      '#type' => 'radios',
      '#title' => $this->t('<em>From</em> e-mail address'),
      '#description' => t('Choose which e-mail address will be in the From: header for the invitation mails sent; <em>site</em> or <em>inviter</em>. <em>Site</em> will use the default e-mail address of the site, whereas <em>inviter</em> will use the e-mail address of the user who is sending the invitation. Alternatively, you can set this value manually by clicking on <em>advanced settings</em> below.'),
      '#options' => array($this->t('Site'), $this->t('Inviter')),
      '#default_value' => $config->get('invite_use_users_email'),
    );

    $form['invite_use_users_email_replyto'] = array(
      '#type' => 'radios',
      '#title' => $this->t('<em>Reply-To</em> e-mail address'),
      '#description' => $this->t('Choose which e-mail address will be in the Reply-To: header for the invitation mails sent; <em>site</em> or <em>inviter</em>. <em>Site</em> will use the default e-mail address of the site, whereas <em>inviter</em> will use the e-mail address of the user who is sending the invitation. Alternatively, you can set this value manually by clicking on <em>advanced settings</em> below.'),
      '#options' => array($this->t('Site'), $this->t('Inviter')),
      '#default_value' => $config->get('invite_use_users_email_replyto'),
    );

    $form['advanced'] = array(
      '#type' => 'fieldset',
      '#title' => $this->t('Advanced settings'),
      '#collapsible' => TRUE,
      '#collapsed' => TRUE,
      '#description' => $this->t('<strong>Note:</strong> The addresses defined here will replace the site e-mail, if it is selected above.'),
    );

    $form['advanced']['invite_manual_from'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Manually override <em>From</em> e-mail address'),
      '#default_value' => $config->get('invite_manual_from'),
      '#description' => $this->t('The e-mail address the invitation e-mail is sent from.'),
    );

    $form['advanced']['invite_manual_reply_to'] = array(
      '#type' => 'textfield',
      '#title' => $this->t('Manually override <em>Reply-To</em> e-mail address'),
      '#default_value' => $config->get('invite_manual_reply_to'),
      '#description' => $this->t('The e-mail address you want recipients to reply to.'),
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

    $this->config('invite.invitebyemailsettings')
      ->set('invite_message_editable', $form_state->getValue('invite_message_editable'))
      ->set('invite_default_mail_subject', $form_state->getValue('invite_default_mail_subject'))
      ->set('invite_default_mail_body', $form_state->getValue('invite_default_mail_body'))
      ->set('invite_default_replace_tokens', $form_state->getValue('invite_default_replace_tokens'))
      ->set('invite_use_users_email', $form_state->getValue('invite_use_users_email'))
      ->set('invite_use_users_email_replyto', $form_state->getValue('invite_use_users_email_replyto'))
      ->set('invite_manual_from', $form_state->getValue('invite_manual_from'))
      ->set('invite_manual_reply_to', $form_state->getValue('invite_manual_reply_to'))
      ->save();
  }

}
