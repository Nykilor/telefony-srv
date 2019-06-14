<?php
#MAKE THE PROPERTIES LIKE VIISBILITY ONLY VISIBLE TO ADMIN AND NOT TO USER< AND DON"T FETCH IT FOR USER EVEN
namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * ApiResource, config in config/api_platform/LdapUser.yaml
 * @ORM\Entity(repositoryClass="App\Repository\LdapUserRepository")
 * @ApiFilter(SearchFilter::class, properties={"id": "exact", "login": "partial"})
 */
class LdapUser
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer", nullable=false)
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=60, unique=true, nullable=false)
     */
    private $login;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Domain")
     * @ORM\JoinColumn(name="domain_id", referencedColumnName="id")
     */
    private $domain;

    /**
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    public $last_name;

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
     * @ORM\OneToMany(targetEntity="App\Entity\LdapPhoneNumbers", mappedBy="ldap_user")
     */
    private $ldapPhoneNumbers;

    /**
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $when_changed;

    /**
     * Property visible only to administrator.
     * @ORM\Column(type="boolean", options={"default": 1})
     */
    public $is_visible = true;

    public function __construct()
    {
        $this->ldapPhoneNumbers = new ArrayCollection();
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

    public function getDomain(): ?Domain
    {
        return $this->domain;
    }

    public function setDomain(?Domain $domain): self
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
     * @return Collection|LdapPhoneNumbers[]
     */
    public function getLdapPhoneNumbers(): Collection
    {
        return $this->ldapPhoneNumbers;
    }

    public function addLdapPhoneNumbers(LdapPhoneNumbers $phoneNumbersId): self
    {
        if (!$this->ldapPhoneNumbers->contains($phoneNumbersId)) {
            $this->ldapPhoneNumbers[] = $phoneNumbersId;
            $phoneNumbersId->setLdapUser($this);
        }

        return $this;
    }

    public function removeLdapPhoneNumbers(LdapPhoneNumbers $phoneNumbersId): self
    {
        if ($this->ldapPhoneNumbers->contains($phoneNumbersId)) {
            $this->ldapPhoneNumbers->removeElement($phoneNumbersId);
            // set the owning side to null (unless already changed)
            if ($phoneNumbersId->getLdapUser() === $this) {
                $phoneNumbersId->setLdapUser(null);
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

    public function getIsVisible(): ?bool
    {
        return $this->is_visible;
    }

    public function setIsVisible(bool $is_visible): self
    {
        $this->is_visible = $is_visible;

        return $this;
    }
}
