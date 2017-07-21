<?php

namespace Ordermind\LogicalAuthorizationBundle\Security;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter as VoterBase;
use Symfony\Component\HttpFoundation\Request;

use Ordermind\LogicalAuthorizationBundle\Services\LogicalAuthorizationRouteInterface;

class RequestVoter extends VoterBase {
    protected $laRoute;

    public function __construct(LogicalAuthorizationRouteInterface $laRoute) {
        $this->laRoute = $laRoute;
    }

    protected function supports($attribute, $subject)
    {
        if(strtolower($attribute) === 'logauth' && $subject instanceof Request) {
            return true;
        }

        return false;
    }

    protected function voteOnAttribute($attribute, $request, TokenInterface $token)
    {
        $routeName = $request->get('_route');
        if($routeName) {
            return $this->laRoute->checkRouteAccess($routeName);
        }

        return true;
    }
}