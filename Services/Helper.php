<?php

namespace Ordermind\LogicalAuthorizationBundle\Services;

use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Ordermind\LogicalAuthorizationBundle\Interfaces\ModelDecoratorInterface;
use Ordermind\LogicalAuthorizationBundle\Exceptions\LogicalAuthorizationException;

class Helper implements HelperInterface {
  protected $environment;
  protected $tokenStorage;
  protected $logger;

  /**
   * @internal
   *
   * @param Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage Token storage service
   * @param Psr\Log\LoggerInterface $logger (optional) A service for logging errors
   */
  public function __construct($environment, TokenStorageInterface $tokenStorage, LoggerInterface $logger = null) {
    $this->environment = $environment;
    $this->tokenStorage = $tokenStorage;
    $this->logger = $logger;
  }

  /**
   * {@inheritdoc}
   */
  public function getCurrentUser() {
    $token = $this->tokenStorage->getToken();
    if(!is_null($token)) {
      return $token->getUser();
    }
    return null;
  }

  /**
   * {@inheritdoc}
   */
  public function handleError($message, $context) {
    if(!is_array($context)) {
      throw new \InvalidArgumentException('The context parameter must be an array. Current type is ' . gettype($context) . '.');
    }

    if('prod' === $this->environment && !is_null($this->logger)) {
      $this->logger->error($message, $context);
    }
    else {
      $message .= "\nContext:\n";
      foreach($context as $key => $value) {
        $message .= "$key => " . print_r($value, true) . "\n";
      }
      throw new LogicalAuthorizationException($message);
    }
  }
}
