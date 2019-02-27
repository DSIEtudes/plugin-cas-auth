<?php

namespace Kanboard\Plugin\CASAuth\Controller;

use Kanboard\Plugin\CASAuth\Auth\CasAuthProvider;

/**
 * CAS Auth Controller
 *
 * @package  controller
 */
class AuthController extends CasAuthProvider
{
    /**
     * Handle authentication
     *
     * @access public
     */
    public function handler()
    {
        $this->authenticate();
    }
}