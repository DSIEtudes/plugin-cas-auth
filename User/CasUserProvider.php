<?php

namespace Kanboard\Plugin\CASAuth\User;

use Kanboard\Core\User\UserProviderInterface;
use Kanboard\User\LdapUserProvider;
use Kanboard\Model\LanguageModel;

/**
 * CAS User Provider
 *
 * @package  user
 */
class CasUserProvider extends LdapUserProvider implements UserProviderInterface
{   
}
