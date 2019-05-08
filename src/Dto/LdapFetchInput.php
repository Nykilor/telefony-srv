<?php

namespace App\Dto;

final class LdapFetchInput {
  /**
 * @var \App\Entity\Domain
 */
  public $domain;
  public $password;
  public $login;
}
