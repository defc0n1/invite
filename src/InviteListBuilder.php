<?php

/**
 * @file
 * Contains \Drupal\invite\InviteListBuilder.
 */

namespace Drupal\invite;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Invite entities.
 *
 * @ingroup invite
 */
class InviteListBuilder extends EntityListBuilder {
  use LinkGeneratorTrait;
  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Invite ID');
    $header['subject'] = $this->t('Subject');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\invite\Entity\Invite */
    $row['id'] = $entity->id();
    $row['subject'] = $this->l(
      $entity->field_invitation_email_subject->value,
      new Url(
        'entity.invite.edit_form', array(
          'invite' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

  /**
   * {@inheritdoc}
   */
  public function getOperations(EntityInterface $entity) {
    $operations = parent::getOperations($entity);
    $operations['withdraw'] = array(
      'title' => $this->t('Withdraw Invite'),
      'weight' => 10,
      'url' => new Url(
        'invite.invite_withdraw_form', array(
          'invite' => $entity->id(),
        )
      ),
    );
    $operations['resend'] = array(
      'title' => $this->t('Resend Invite'),
      'weight' => 11,
      'url' => new Url(
        'invite.invite_resend_form', array(
          'invite' => $entity->id(),
        )
      ),
    );
    return $operations;
  }

}
