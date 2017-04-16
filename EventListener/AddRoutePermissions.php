<?php

namespace Ordermind\LogicalAuthorizationBundle\EventListener;

use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Config\Loader\FileLoader;

use Ordermind\LogicalAuthorizationBundle\Event\AddPermissionsEvent;

class AddRoutePermissions {
  protected $router;

  public function __construct(RouterInterface $router) {
    $this->router = $router;
  }

  public function onAddPermissions(AddPermissionsEvent $event) {
    $permissions = ['routes' => []];
    foreach($this->router->getRouteCollection()->getIterator() as $name => $route) {
      $options = $route->getOptions();
      if(!empty($options['logical_authorization_permissions'])) {
        $permissions['routes'][$name] = $options['logical_authorization_permissions'];
      }
    }
    $event->setTree($event->mergePermissions([$event->getTree(), $permissions]));
  }
}

