<?php

namespace Kanboard\Plugin\CASAuth\Auth;

use Kanboard\Core\Base;
use Kanboard\Core\Security\Role;
use Kanboard\Core\Security\PreAuthenticationProviderInterface;
use Kanboard\Plugin\CASAuth\User\CasUserProvider;

/**
 * CAS Authentication Provider
 *
 * @package  auth
 */
class CasAuthProvider extends Base implements PreAuthenticationProviderInterface
{
    /**
     * User properties
     *
     * @access private
     * @var \Kanboard\Plugin\CASAuth\User\CasUserProvider
     */
    private $userInfo = null;

    /**
     * phpCas instance
     *
     * @access protected
     * @var \phpCas
     */
    protected $service;

    /**
     * Get authentication provider name
     *
     * @access public
     * @return string
     */
    public function getName()
    {
        return 'CAS';
    }

    /**
     * Authenticate the user
     *
     * @access public
     * @return boolean
     */
    public function authenticate()
    {
        try {
            $this->getService();
            $this->service->forceAuthentication();
            if ($this->service->checkAuthentication()) {
                $this->userInfo = new CasUserProvider(
                    $this->service->getAttribute('distinguishedName'),
                    $this->service->getAttribute('userprincipalname'),
                    $this->service->getAttribute('cn'),
                    $this->service->getAttribute('mail'),
                    $this->service->getAttribute($this->getRole($this->service->getAttribute('memberof'))),
                    $this->service->getAttribute('memberof'),
                    $this->service->getAttribute('jpegPhoto'),
                    $this->service->getAttribute('')
                );
                return true;
            }
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }
        return false;
    }

    /**
     * Get role from LDAP groups
     *
     * Note: Do not touch the current role if groups are not configured
     *
     * @access public
     * @param  string[] $groupIds
     * @return string
     */
    public function getRole(array $groupIds)
    {
        if (!(LDAP_GROUP_MANAGER_DN || LDAP_GROUP_ADMIN_DN)) {
            return null;
        }

        // Init with smallest role
        $role = Role::APP_USER ;

        foreach ($groupIds as $groupId) {
            $groupId = strtolower($groupId);

            if ($groupId === strtolower(LDAP_GROUP_ADMIN_DN)) {
                // Highest role found : we can and we must exit the loop
                $role = Role::APP_ADMIN;
                break;
            }

            if ($groupId === strtolower(LDAP_GROUP_MANAGER_DN)) {
                // Intermediate role found : we must continue to loop, maybe admin role after ?
	            $role = Role::APP_MANAGER;
            }
        }
        return $role;
    }

    /**
     * Get user object
     *
     * @access public
     * @return CasUserProvider
     */
    public function getUser()
    {
        return $this->userInfo;
    }

    /**
     * Get CAS service
     *
     * @access public
     */
    public function getService()
    {
        //die();
        if (empty($this->service)) {
            $this->service = new \phpCAS();
            $this->service->setDebug("log/phpCAS.log");
            $this->service->client(SAML_VERSION_1_1, CAS_HOSTNAME, CAS_PORT, CAS_URI);
            $this->service->setNoCasServerValidation();
        }
    }

    /**
     * logout from CAS
     *
     * @access public
     */
    public function logout()
    {
        $this->getService();

        if ($this->service) {
            $this->service->logout();
        }
    }
}
