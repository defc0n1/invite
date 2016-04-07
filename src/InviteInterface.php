<?php

/**
 * @file
 * Contains \Drupal\invite\InviteInterface.
 */

namespace Drupal\invite;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Invite entities.
 *
 * @ingroup invite
 */
interface InviteInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  // Add get/set methods for your configuration properties here.
  /**
   * Gets the Invite type.
   *
   * @return string
   *   The Invite type.
   */
  public function getType();

  /**
   * Gets the Invite name.
   *
   * @return string
   *   Name of the Invite.
   */
  public function getName();

  /**
   * Sets the Invite name.
   *
   * @param string $name
   *   The Invite name.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setName($name);

  /**
   * Gets the Invite creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Invite.
   */
  public function getCreatedTime();

  /**
   * Sets the Invite creation timestamp.
   *
   * @param int $timestamp
   *   The Invite creation timestamp.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Invite published status indicator.
   *
   * Unpublished Invite are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Invite is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Invite.
   *
   * @param bool $published
   *   TRUE to set this Invite to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setPublished($published);

  /**
   * Gets the Invite Registration Code.
   *
   * @return string
   *   Registration Code of the Invite.
   */
  public function getRegistrationCode();

  /**
   * Sets the Invite Registration Code.
   *
   * @param string $code
   *   The Invite registration code.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setRegistrationCode($code);

  /**
   * Gets the Invite Status.
   *
   * @return integer
   *   Status of Invite whether it is active or expired etc.
   */
  public function getInviteStatus();

  /**
   * Sets the Invite status.
   *
   * @param string $status
   *   The Invite status.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setInviteStatus($status);

  /**
   * Gets the Invitee User ID.
   *
   * @return integer
   *   User ID of Invitee.
   */
  public function getInviteeUserId();

  /**
   * Sets the Invitee User ID.
   *
   * @param string $uid
   *   The Invite user id.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setInviteeUserId($uid);

  /**
   * Gets the Invitee join timestamp.
   *
   * @return int
   *   Creation timestamp of the Invite.
   */
  public function getJoinedTime();

  /**
   * Sets the Invitee joined timestamp.
   *
   * @param int $timestamp
   *   The Invite creation timestamp.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setJoinedTime($timestamp);

  /**
   * Gets the invitation send attempt.
   *
   * @return int
   *   Number of attempt the Invite.
   */
  public function getResendAttempt();

  /**
   * Sets the number of attempt.
   *
   * @param string $number
   *   Number of attempt.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setResendAttempt($number);

  /**
   * Gets the Invitation expiration timestamp.
   *
   * @return int
   *   Creation timestamp of the Invite.
   */
  public function getExpiryTime();

  /**
   * Sets the Invitation expiration timestamp.
   *
   * @param int $timestamp
   *   The Invite expiration timestamp.
   *
   * @return \Drupal\invite\InviteInterface
   *   The called Invite entity.
   */
  public function setExpiryTime($timestamp);


}
