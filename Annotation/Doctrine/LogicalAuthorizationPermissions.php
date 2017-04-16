<?php

namespace Ordermind\LogicalAuthorizationBundle\Annotation\Doctrine;

/**
 * @Annotation
 */
class LogicalAuthorizationPermissions {
  protected $permissions;

  public function __construct(array $data) {
    $this->permissions = $data['value'];
  }

  public function getPermissions() {
    return $this->permissions;
  }
}