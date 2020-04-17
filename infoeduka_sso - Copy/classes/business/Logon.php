<?php

namespace auth_infoeduka_sso\business;

//use auth_ie\business\RegisterUser;
//use auth_ie\business\JWT;
use auth_infoeduka_sso\Constant;

require_once ("Token.php");

class Logon implements Business
{

    public function __construct()
    {
        return $this;
    }

    public function authentication($token)
    {
        try
        {
            global $CFG, $SESSION, $USER;

            if ($data = (new Token())->decode($token))
            {
                if ($auth = $this->logon($data))
                {
                    $SESSION->wantsurl = $CFG->wwwroot . Constant::URL_REDIRECT_AUTHENTICATION_SUCCESSFUL;
                }
                else
                {
                    $SESSION->wantsurl = $CFG->wwwroot . Constant::URL_REDIRECT_LOGIN_PAGE;
                    return false;
                }
            }
        }
        catch (\Exception $ex)
        {
            redirect($SESSION->wantsurl, $ex->getMessage(), 0, \core\output\notification::NOTIFY_WARNING);
            \core\session\manager::kill_all_sessions();
        }
        return $SESSION->wantsurl;
    }

    private function logon($arg)
    {
        try {
            global $CFG, $frm, $USER, $SESSION;
            $auth = false;

            $validation_param = Constant::VALIDATION_PARAMETER;
            if ($user = get_complete_user_data($validation_param, $arg->data->$validation_param))
            {
                if (complete_user_login($user) && !$user->suspended)
                {
                    \core\session\manager::apply_concurrent_login_limit($user->id, session_id());
                    $auth = true;
                    $USER->loggedin = true;
                    $USER->site = $CFG->wwwroot;
                    set_moodle_cookie($USER->username);
                }
                if ($user->suspended)
                {
                    $USER = null;
                    set_moodle_cookie('');
                    \core\session\manager::kill_all_sessions();
                    redirect($CFG->wwwroot.Constant::URL_REDIRECT_LOGIN_PAGE, 'Account suspended', 0, \core\output\notification::NOTIFY_WARNING);
                }
            }
            return $auth;
        }
        catch (\Exception $ex)
        {
            $SESSION->wantsurl = $CFG->wwwroot . Constant::URL_REDIRECT_LOGIN_PAGE;
            throw $ex;
        }
    }
}
