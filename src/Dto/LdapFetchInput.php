<?php

namespace App\Dto;
use App\Entity\Domain;
use Symfony\Component\Validator\Constraints as Assert;

final class LdapFetchInput {

  /**
 * @var Domain $domain
 */
  public $domain;

  /**
   * @var string
   * @Assert\NotBlank
   */
  public $password;

  /**
   * @var string
   * @Assert\NotBlank
   */
  public $login;

  /**
   * @var string
   */
  public $query;
}
