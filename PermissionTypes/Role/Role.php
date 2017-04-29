<?php

namespace Ordermind\LogicalAuthorizationBundle\PermissionTypes\Role;

use Symfony\Component\Security\Core\User\UserInterface as SecurityUserInterface;

use Ordermind\LogicalAuthorizationBundle\PermissionTypes\PermissionTypeInterface;

class Role implements PermissionTypeInterface {

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return 'role';
  }

  /**
   * Checks if access should be granted due to a role being present on a user
   *
   * @param string $role The name of the role to evaluate
   * @param array $context The context for evaluating the role. The context must contain a 'user' key which references either a user string (to signify an anonymous user) or an object implementing Symfony\Component\Security\Core\User\UserInterface. You can get the current user by calling getCurrentUser() from the service 'ordermind_logical_authorization.service.helper'.
   *
   * @return bool TRUE if access should be granted or FALSE if access should not be granted
   */
  public function checkPermission($role, $context) {
    if(!is_string($role)) {
      throw new \InvalidArgumentException('The role parameter must be a string.');
    }
    if(!$role) {
      throw new \InvalidArgumentException('The role parameter cannot be empty.');
    }
    if(!is_array($context)) {
      throw new \InvalidArgumentException('The context parameter must be an array. Current type is ' . gettype($context) . '.');
    }
    if(!isset($context['user'])) {
      throw new \InvalidArgumentException('The context parameter must contain a "user" key to be able to evaluate the ' . $this->getName() . ' flag.');
    }

    $user = $context['user'];
    if(is_string($user)) { //Anonymous user
      return false;
    }

    if(!($user instanceof SecurityUserInterface)) {
      throw new \InvalidArgumentException('The user class must implement Symfony\Component\Security\Core\User\UserInterface to be able to evaluate the user role.');
    }

    $roles = $user->getRoles();
    foreach($roles as $thisRole) {
      $strRole = '';
      if(is_string($thisRole)) {
        $strRole = $thisRole;
      }
      else {
        $strRole = (string) $thisRole->getRole();
      }
      if($role === $strRole) {
        return true;
      }
    }

    return false;
  }
}
