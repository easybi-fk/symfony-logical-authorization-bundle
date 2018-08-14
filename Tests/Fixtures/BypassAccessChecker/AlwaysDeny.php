<?php

namespace Ordermind\LogicalAuthorizationBundle\Tests\Fixtures\BypassAccessChecker;

use Ordermind\LogicalPermissions\BypassAccessCheckerInterface;

class AlwaysDeny implements BypassAccessCheckerInterface {
  public function checkBypassAccess($context) {
    return FALSE;
  }
}
