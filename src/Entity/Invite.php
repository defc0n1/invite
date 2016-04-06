<?php

/**
 * @file
 * Contains \Drupal\invite\Entity\Invite.
 */

namespace Drupal\invite\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\invite\InviteInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Invite entity.
 *
 * @ingroup invite
 *
 * @ContentEntityType(
 *   id = "invite",
 *   label = @Translation("Invite"),
 *   bundle_label = @Translation("Invite type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\invite\InviteListBuilder",
 *     "views_data" = "Drupal\invite\Entity\InviteViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\invite\Form\InviteForm",
 *       "add" = "Drupal\invite\Form\InviteForm",
 *       "edit" = "Drupal\invite\Form\InviteForm",
 *       "delete" = "Drupal\invite\Form\InviteDeleteForm",
 *     },
 *     "access" = "Drupal\invite\InviteAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\invite\InviteHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "invite",
 *   admin_permission = "administer invite entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "bundle" = "type",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/invite/{invite}",
 *     "add-form" = "/invite/add/{invite_type}",
 *     "edit-form" = "/invite/{invite}/edit",
 *     "delete-form" = "/invite/{invite}/delete",
 *     "collection" = "/invite",
 *   },
 *   bundle_entity_type = "invite_type",
 *   field_ui_base_route = "entity.invite_type.edit_form"
 * )
 */
class Invite extends ContentEntityBase implements InviteInterface {
  use EntityChangedTrait;
  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {

    $expire_config = \Drupal::config('invite.invitesettings')->get('invitation_expiry');

    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
      'reg_code' => user_password(10),
      'invitee_user_id' => 0,
      'expiry' => REQUEST_TIME + $expire_config * 24 * 60 * 60,
      'invite_status' => INVITE_VALID,
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->bundle();
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getRegistrationCode() {
    return $this->get('reg_code')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setRegistrationCode($code) {
    $this->set('reg_code', $code);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Invite entity.'))
      ->setReadOnly(TRUE);

    $fields['type'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Type'))
      ->setDescription(t('The Invite type/bundle.'))
      ->setSetting('target_type', 'invite_type')
      ->setRequired(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Invite entity.'))
      ->setReadOnly(TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Invite entity.'))
      ->setReadOnly(TRUE);

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Invite entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);


    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Invite is published.'))
      ->setDefaultValue(TRUE);

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code for the Invite entity.'))
      ->setDisplayOptions('form', array(
        'type' => 'language_select',
        'weight' => 10,
      ))
      ->setDisplayConfigurable('form', TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    $fields['invitee_user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Invitee'))
      ->setDescription(t('Drupal uid of the invitee upon registration.'));

    $fields['reg_code'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Registration code'))
      ->setDescription(t('Stores the issued registration code.'));

    $fields['expiry'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Expiry'))
      ->setDescription(t('The Unix timestamp when the invite will expire.'));

    $fields['joined'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Joined'))
      ->setDescription(t('Will be filled with the time the invite was accepted upon registration.'));

    $fields['canceled'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Expiry'))
      ->setDescription(t('The Unix timestamp when the invite has been withdrawn.'));

    $fields['invite_status'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Invite Status'))
      ->setDescription(t('This stores status of Invite entity, whether they are used, withdrawn, expired etc.'));

    return $fields;
  }

}
