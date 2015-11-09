<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\RegistrationType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    /**
     * This action handles the registration workflow.
     *
     * @param Request $request
     *
     * @Route("/signup", name="app_signup")
     * @Method("GET|POST")
     */
    public function signupAction(Request $request)
    {
        $user = new User();
        $form = $this->createForm(new RegistrationType(), $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->get('app.registration')->register($user);
    
            return $this->redirectToRoute('app_login');
        }

        return $this->render('user/signup.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Cache(smaxage=60)
     */
    public function sidebarAction()
    {
        $repository = $this->get('doctrine')->getRepository('AppBundle:User');

        return $this->render('user/sidebar.html.twig', [
            'users' => $repository->findMostRecentUsers(5),
        ]);
    }
}
