<?php

namespace AppBundle\Service;

use AppBundle\Model\Contact;

class ContactService
{
    private $mailer;

    public function __construct(\Swift_Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    public function handle(Contact $contact)
    {
        $message = $this->buildMessage($contact);

        $this->mailer->send($message);
    }

    private function buildMessage(Contact $contact)
    {
        $message = \Swift_Message::newInstance()
            ->setTo($contact->getRecipient())
            ->setFrom(
                $contact->getSenderEmailAddress(),
                $contact->getSenderName()
            )
            ->setSubject($contact->getSubject())
            ->setBody($contact->getMessage())
        ;

        return $message;
    }
}
