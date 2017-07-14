<?php

namespace Ordermind\LogicalAuthorizationBundle\Annotation\Routing;

/**
 * @Annotation
 */
class LogAuth {
  protected $permissions;

  public function __construct(array $data) {
    $this->permissions = $data['value'];
  }

  public function getPermissions() {
    return $this->permissions;
  }
}