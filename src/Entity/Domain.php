<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ApiResource()
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
     * @ORM\Column(type="array")
     */
    private $hosts = [];

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $base_dn;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $connection_schema;

    /**
     * @ORM\Column(type="integer", options={"default": 389})
     */
    private $port;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $use_ssl;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     */
    private $use_tls;

    /**
     * @ORM\Column(type="integer", options={"default": 3})
     */
    private $version;

    /**
     * @ORM\Column(type="integer", options={"default": 5})
     */
    private $timeout;

    /**
     * @ORM\Column(type="array", nullable=true)
     */
    private $custom = [];

    public function __construct()
    {
        $this->relation_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(string $prefix): self
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
