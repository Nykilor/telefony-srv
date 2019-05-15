<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * ApiResource, config in config/api_platform/PhoneNumbers.yaml
 * @ORM\Entity(repositoryClass="App\Repository\PhoneNumbersRepository")
 */
class PhoneNumbers
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\LdapUser", inversedBy="phoneNumbers")
     * @ORM\JoinColumn(name="ldap_user_id", nullable=false)
     */
    private $ldap_user;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $value;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLdapUser(): ?LdapUser
    {
        return $this->ldap_user;
    }

    public function setLdapUser(?LdapUser $ldap_user): self
    {
        $this->ldap_user = $ldap_user;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }
}
