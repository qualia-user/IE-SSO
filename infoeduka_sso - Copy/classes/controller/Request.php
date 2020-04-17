<?php

namespace auth_infoeduka_sso\controller;

use auth_infoeduka_sso\business\Logon;
use auth_infoeduka_sso\business\JWT;
use auth_infoeduka_sso\Constant;

class Request implements Controller
{

    public function __construct()
    {
        return $this;
    }

    public function all($uri)
    {
        global $SESSION, $PAGE, $OUTPUT, $CFG;

        switch ($_SERVER['REQUEST_METHOD'])
        {
            case 'GET':
                if (isset($_GET['token']))
                {
                    $token = clean_param($_GET['token'], PARAM_TEXT);
                    if ($response = (new Logon())->authentication($token))
                    {
                        $PAGE->set_context(\context_system::instance());
                        if (isset($GLOBALS["USER"]->id) && $GLOBALS["USER"]->id != 0)
                        {
                            $PAGE->set_context(\context_user::instance($GLOBALS["USER"]->id));
                        }
                        $OUTPUT->header();
                    }
                    else
                    {
                        $response = $CFG->wwwroot . Constant::URL_REDIRECT_LOGIN_PAGE;
                    }
                }
                else
                {
                    // sluèaj kad se korisnik odlogirao i želi se ulogirati preko forme
                    return;
                }
                redirect($response);
                \core\session\manager::kill_all_sessions();
                exit;

            case 'POST':
                // login forma
                return;
        }
    }
}
