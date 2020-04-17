<?php
namespace auth_infoeduka_sso;

if (!defined('MOODLE_INTERNAL')) {
    die('Direct access to this script is forbidden.');    // It must be included from a Moodle page.
}

class Constant
{
    const PLUGIN_TAG_NAME                           = 'infoeduka_sso';
    const JWT_KEY                                   = '';

    const URL_REDIRECT_LOGIN_PAGE                   = '/login/index.php';
    const URL_REDIRECT_AUTHENTICATION_SUCCESSFUL    = '/index.php';

    const DEBUG                                     = 'debug';
    const VALIDATION_PARAMETER                      = 'username';

    const MSG_AUTHENTICATION_FAILED                 = 'Autentikacija neuspješna.';
    const MSG_TOKEN_PROPERTIES_INVALID              = 'Nesipravna svojstva tokena.';


    public function __construct()
    {
        return false;
    }

}
