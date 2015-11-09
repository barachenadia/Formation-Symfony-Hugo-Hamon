<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class SecurityController extends Controller
{
    /**
     * This action displays the login form.
     *
     * @Route("/login", name="app_login")
     * @Method("GET")
     */
    public function loginAction()
    {
        $utils = $this->get('security.authentication_utils');

        return $this->render('login.html.twig', [
            'last_username' => $utils->getLastUsername(),
            'error' => $utils->getLastAuthenticationError(),
        ]);
    }

    /**
     * This action handles the login form.
     *
     * @Route("/login", name="app_login_check")
     * @Method("POST")
     */
    public function loginCheckAction()
    {

    }

    /**
     * This action handles the logout.
     *
     * @Route("/logout", name="app_logout")
     * @Method("GET")
     */
    public function logoutAction()
    {

    }
}
