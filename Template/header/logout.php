<?php if (DISABLE_LOGOUT): ?>
    <li>
        <?= $this->url->icon('sign-out', t('Logout'), 'AuthController', 'logout', array('plugin' => 'CASAuth')) ?>
    </li>
<?php endif ?>