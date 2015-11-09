<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\Role\Role;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Table(name="argus_user", uniqueConstraints={
 *   @ORM\UniqueConstraint("user_username_unique", columns="username"),
 *   @ORM\UniqueConstraint("user_email_address_unique", columns="email_address")
 * })
 * @ORM\Entity(repositoryClass="AppBundle\Entity\Repository\UserRepository")
 * @UniqueEntity("username", groups="Signup")
 * @UniqueEntity("emailAddress")
 */
class User implements UserInterface
{
    /**
     * @ORM\Column(type="integer", options={ "unsigned"=true })
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private $id;

    /**
     * @ORM\Column(length=50)
     * @Assert\NotBlank
     * @Assert\Length(min=2, max=50)
     */
    private $fullName;

    /**
     * @ORM\Column
     * @Assert\NotBlank
     * @Assert\Email
     */
    private $emailAddress;

    /**
     * @ORM\Column(length=25)
     * @Assert\NotBlank(groups="Signup")
     * @Assert\Length(min=5, max=25, groups="Signup")
     * @Assert\Regex(
     *   pattern="/^[a-z0-9_]+$/i",
     *   message="user.username.invalid_format",
     *   groups="Signup"
     * )
     * @Assert\NotEqualTo("_exit", groups="Signup")
     */
    private $username;

    /**
     * @ORM\Column
     */
    private $salt;

    /**
     * @ORM\Column
     * @Assert\NotBlank(groups="Signup")
     * @Assert\Length(min=8)
     */
    private $password;

    /**
     * @ORM\Column(type="date")
     * @Assert\NotBlank
     * @Assert\Range(max="-18 years")
     */
    private $birthdate;

    /**
     * @ORM\Column(length=20, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(type="simple_array", nullable=true)
     */
    private $permissions;

    /**
     * @ORM\Column(type="integer", options={ "unsigned"=true, "default"=0 })
     */
    private $creditsBalance;

    public function getCreditsBalance()
    {
        return $this->creditsBalance;
    }

    public function credit($numberOfCredits)
    {
        $this->creditsBalance += (int) $numberOfCredits;
    }

    public function debit($numberOfCredits)
    {
        $numberOfCredits = abs($numberOfCredits);

        if ($this->creditsBalance < $numberOfCredits) {
            throw new \RuntimeException('Not enough credit funds!');
        }

        $this->creditsBalance -= (int) $numberOfCredits;
    }

    /**
     * @Assert\Callback
     */
    public function checkPlainPassword(ExecutionContextInterface $context)
    {
        if (false !== stripos($this->password, $this->username)) {
            $context
                ->buildViolation('user.password.username_detected')
                ->atPath('password')
                ->addViolation()
            ;
        }
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFullName()
    {
        return $this->fullName;
    }

    public function setFullName($fullName)
    {
        $this->fullName = $fullName;
    }

    public function getEmailAddress()
    {
        return $this->emailAddress;
    }

    public function setEmailAddress($emailAddress)
    {
        $this->emailAddress = $emailAddress;
    }

    public function getUsername()
    {
        return $this->username;
    }

    public function setUsername($username)
    {
        $this->username = $username;
    }

    public function getSalt()
    {
        return $this->salt;
    }

    public function setSalt($salt)
    {
        $this->salt = $salt;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getBirthdate()
    {
        return $this->birthdate;
    }

    public function setBirthdate($birthdate)
    {
        if (!$birthdate instanceof \DateTime) {
            $birthdate = new \DateTime($birthdate);
        }

        $this->birthdate = $birthdate;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function upgrade($status)
    {
        $this->status = $status;
    }

    public function hasPermission($permission)
    {
        return in_array($permission, $this->getPermissions());
    }

    public function getPermissions()
    {
        return null === $this->permissions ? [] : $this->permissions;
    }

    public function addPermission($permission)
    {
        if (!$this->hasPermission($permission)) {
            $this->permissions[] = $permission;
        }
    }

    public function getRoles()
    {
        $roles = [];
        foreach ($this->getPermissions() as $permission) {
            $roles[] = new Role($permission);
        }

        return $roles;
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }
}
