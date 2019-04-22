<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ApiResource(
 *  itemOperations={
 *      "get"={"method"="GET"},
 *      "put"={"method"="PUT"},
 *      "delete"={"method"="DELETE"},
 *      "LdapGetData"={
 *        "normalization_context"={"groups"={"ldap-r"}},
 *        "denormalization_context"={"groups"={"Ldap"}},
 *        "method"="POST",
 *        "route_name"="LdapGetData",
 *        "swagger_context" = {
 *          "parameters" = {
 *            {
 *              "name" = "id",
 *              "in" = "path",
 *              "required" = "true",
 *              "type" = "integer",
 *              "description" = "Domain ID with whom should the data be fetched"
 *            },
 *            {
 *              "name" = "credentials",
 *              "in" = "body",
 *              "required" = "true",
 *              "schema" = {
 *                 "type" = "object",
 *                 "properties" = {
 *                  "login" = {
 *                    "type" = "string"
 *                  },
 *                  "password" = {
 *                    "type" = "string"
 *                   }
 *                }
 *              }
 *            }
 *          },
 *          "responses" = {
 *            "200" = {
 *              "description" = "The count of changes will be in response."
 *            }
 *          },
 *          "summary" = "Fetches data from given LDAP schema."
 *        }
 *      }
 *   }
 * )
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
     * @ORM\Column(type="string", length=255, nullable=true)
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
     * @ORM\Column(type="integer")
     */
    private $port;

    /**
     * @ORM\Column(type="boolean")
     */
    private $use_ssl;

    /**
     * @ORM\Column(type="boolean")
     */
    private $use_tls;

    /**
     * @ORM\Column(type="integer")
     */
    private $version;

    /**
     * @ORM\Column(type="integer")
     */
    private $timeout;

    /**
     * @ORM\Column(type="array")
     */
    private $custom = [];

    /**
     * @Groups({"ldap","ldap-r"})
     */
    private $login;

    /**
     * @Groups({"ldap","ldap-r"})
     */
    private $password;

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

    public function setCustom(array $custom): self
    {
        $this->custom = $custom;

        return $this;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(?string $login): self
    {
        $this->login = $login;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }
}
