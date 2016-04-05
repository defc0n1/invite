<?php

/**
 * @file
 * Contains \Drupal\invite\Entity\InviteType.
 */

namespace Drupal\invite\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;
use Drupal\invite\InviteTypeInterface;

/**
 * Defines the Invite type entity.
 *
 * @ConfigEntityType(
 *   id = "invite_type",
 *   label = @Translation("Invite type"),
 *   handlers = {
 *     "list_builder" = "Drupal\invite\InviteTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\invite\Form\InviteTypeForm",
 *       "edit" = "Drupal\invite\Form\InviteTypeForm",
 *       "delete" = "Drupal\invite\Form\InviteTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\invite\InviteTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "invite_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "invite",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/invite_type/{invite_type}",
 *     "add-form" = "/admin/structure/invite_type/add",
 *     "edit-form" = "/admin/structure/invite_type/{invite_type}/edit",
 *     "delete-form" = "/admin/structure/invite_type/{invite_type}/delete",
 *     "collection" = "/admin/structure/invite_type"
 *   }
 * )
 */
class InviteType extends ConfigEntityBundleBase implements InviteTypeInterface {
  /**
   * The Invite type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Invite type label.
   *
   * @var string
   */
  protected $label;

}
