<?php

namespace AppBundle\Controller;

use AppBundle\Form\ContactType;
use AppBundle\Model\Contact;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class MainController extends Controller
{
    /**
     * @Route("/contact", name="app_contact")
     * @Method("GET|POST")
     */
    public function contactAction(Request $request)
    {
        $contact = new Contact($this->getParameter('contact_recipient'));
        $form = $this->createForm(new ContactType(), $contact);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $this->get('app.contact')->handle($contact);

            return $this->redirectToRoute('app_contact');
        }

        return $this->render('main/contact.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
