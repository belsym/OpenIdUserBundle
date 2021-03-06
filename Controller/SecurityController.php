<?php

namespace WG\OpenIdUserBundle\Controller;

use Symfony\Component\Security\Core\SecurityContext;

use Fp\OpenIdBundle\Controller\SecurityController as BaseSecurityController;

class SecurityController extends BaseSecurityController
{
    public function loginAction()
    {
        //parent::loginAction();
        $request = $this->container->get('request');
        /* @var $request \Symfony\Component\HttpFoundation\Request */
        $session = $request->getSession();
        /* @var $session \Symfony\Component\HttpFoundation\Session */

        // get the error if any (works with forward and redirect -- see below)
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(SecurityContext::AUTHENTICATION_ERROR);
        } elseif (null !== $session && $session->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        } else {
            $error = '';
        }

        if ($error) {
            // TODO: this is a potential security risk (see http://trac.symfony-project.org/ticket/9523)
            $error = $error->getMessage();
        }

        return $this->container->get('templating')->renderResponse(
            'WGOpenIdUserBundle:Security:login.html.twig', array(
                'error' => $error
        ));
    }
}