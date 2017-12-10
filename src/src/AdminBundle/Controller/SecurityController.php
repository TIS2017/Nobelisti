<?php

namespace AdminBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;

class SecurityController extends Controller
{
    /**
     * @Route("/login", name="login")
     */
    public function login(Request $request) {
        $authUtils = $this->get('security.authentication_utils');
        $error = $authUtils->getLastAuthenticationError();
        $lastEmail = $authUtils->getLastUsername();
    
        return $this->render('AdminBundle:Security:login.html.twig', array(
            'last_email' => $lastEmail,
            'error'      => $error,
        ));
    }
}
