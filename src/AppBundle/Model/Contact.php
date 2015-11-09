<?php

namespace AppBundle\Model;

use Symfony\Component\Validator\Constraints as Assert;

class Contact
{
    /**
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=40)
     */
    private $senderName;

    /**
     * @Assert\NotBlank
     * @Assert\Email
     * @Assert\NotEqualTo("recipient@my-website.com")
     */
    private $senderEmailAddress;

    /**
     * @Assert\NotBlank
     * @Assert\Length(min=5, max=100)
     */
    private $subject;

    /**
     * @Assert\NotBlank
     */
    private $message;
    private $recipient;

    public function __construct($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return mixed
     */
    public function getRecipient()
    {
        return $this->recipient;
    }

    /**
     * @param mixed $recipient
     */
    public function setRecipient($recipient)
    {
        $this->recipient = $recipient;
    }

    /**
     * @return mixed
     */
    public function getSenderName()
    {
        return $this->senderName;
    }

    /**
     * @param mixed $senderName
     */
    public function setSenderName($senderName)
    {
        $this->senderName = $senderName;
    }

    /**
     * @return mixed
     */
    public function getSenderEmailAddress()
    {
        return $this->senderEmailAddress;
    }

    /**
     * @param mixed $senderEmailAddress
     */
    public function setSenderEmailAddress($senderEmailAddress)
    {
        $this->senderEmailAddress = $senderEmailAddress;
    }

    /**
     * @return mixed
     */
    public function getSubject()
    {
        return $this->subject;
    }

    /**
     * @param mixed $subject
     */
    public function setSubject($subject)
    {
        $this->subject = $subject;
    }

    /**
     * @return mixed
     */
    public function getMessage()
    {
        return $this->message;
    }

    /**
     * @param mixed $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }
}
