<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource(
 *  collectionOperations={"get"},
 *  itemOperations={"get"}
 * )
 * @ORM\Entity(repositoryClass="App\Repository\LdapUserRepository")
 */
class LdapUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $login;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Domain")
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $biuro;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $company;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $title;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PhoneNumbers", mappedBy="user_id")
     */
    private $phoneNumbers_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private $when_changed;

    public function __construct()
    {
        $this->phoneNumbers_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getDomainId(): ?Domain
    {
        return $this->domain;
    }

    public function setDomainId(?Domain $domain): self
    {
        $this->domain = $domain;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    public function getBiuro(): ?string
    {
        return $this->biuro;
    }

    public function setBiuro(?string $biuro): self
    {
        $this->biuro = $biuro;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getCompany(): ?string
    {
        return $this->company;
    }

    public function setCompany(?string $company): self
    {
        $this->company = $company;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection|PhoneNumbers[]
     */
    public function getPhoneNumbersId(): Collection
    {
        return $this->phoneNumbers_id;
    }

    public function addPhoneNumbersId(PhoneNumbers $phoneNumbersId): self
    {
        if (!$this->phoneNumbers_id->contains($phoneNumbersId)) {
            $this->phoneNumbers_id[] = $phoneNumbersId;
            $phoneNumbersId->setUserId($this);
        }

        return $this;
    }

    public function removePhoneNumbersId(PhoneNumbers $phoneNumbersId): self
    {
        if ($this->phoneNumbers_id->contains($phoneNumbersId)) {
            $this->phoneNumbers_id->removeElement($phoneNumbersId);
            // set the owning side to null (unless already changed)
            if ($phoneNumbersId->getUserId() === $this) {
                $phoneNumbersId->setUserId(null);
            }
        }

        return $this;
    }

    public function getWhenChanged(): ?\DateTimeInterface
    {
        return $this->when_changed;
    }

    public function setWhenChanged(\DateTimeInterface $when_changed): self
    {
        $this->when_changed = $when_changed;

        return $this;
    }
}
