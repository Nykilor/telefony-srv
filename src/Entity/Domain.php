<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * ApiResource, config in config/api_platform/Domain.yaml
 *
 * @ORM\Entity(repositoryClass="App\Repository\DomainRepository")
 */
class Domain
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $prefix;

    /**
     * @ORM\Column(type="json")
     */
    private $hosts = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $base_dn;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\Choice({"ActiveDirectory", "FreeIPA", "OpenLDAP"})
     */
    private $connection_schema = "ActiveDirectory";

    /**
     * @ORM\Column(type="integer")
     */
    private $port = 389;

    /**
     * @ORM\Column(type="boolean")
     */
    private $use_ssl = 0;

    /**
     * @ORM\Column(type="boolean")
     */
    private $use_tls = 0;

    /**
     * @ORM\Column(type="integer")
     * @Assert\Range(
     *  min = 2,
     *  max = 3
     * )
     */
    private $version = 3;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeout = 5;

    /**
     * @ORM\Column(type="json")
     */
    private $custom = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

    public function getHosts(): ?array
    {
        return $this->hosts;
    }

    public function setHosts(array $hosts): self
    {
        $this->hosts = $hosts;

        return $this;
    }

    public function getBaseDn(): ?string
    {
        return $this->base_dn;
    }

    public function setBaseDn(string $base_dn): self
    {
        $this->base_dn = $base_dn;

        return $this;
    }

    public function getConnectionSchema(): ?string
    {
        return $this->connection_schema;
    }

    public function setConnectionSchema(string $connection_schema): self
    {
        $this->connection_schema = $connection_schema;

        return $this;
    }

    public function getPort(): ?int
    {
        return $this->port;
    }

    public function setPort(int $port): self
    {
        $this->port = $port;

        return $this;
    }

    public function getUseSsl(): ?bool
    {
        return $this->use_ssl;
    }

    public function setUseSsl(bool $use_ssl): self
    {
        $this->use_ssl = $use_ssl;

        return $this;
    }

    public function getUseTls(): ?bool
    {
        return $this->use_tls;
    }

    public function setUseTls(bool $use_tls): self
    {
        $this->use_tls = $use_tls;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getTimeout(): ?int
    {
        return $this->timeout;
    }

    public function setTimeout(int $timeout): self
    {
        $this->timeout = $timeout;

        return $this;
    }

    public function getCustom(): ?array
    {
        return $this->custom;
    }

    public function setCustom(?array $custom): self
    {
        $this->custom = $custom;

        return $this;
    }
}
