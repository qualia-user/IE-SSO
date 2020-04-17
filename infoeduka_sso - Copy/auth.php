<?php

defined('MOODLE_INTERNAL') || die();

use auth_infoeduka_sso\Constant;
//require_once($CFG->libdir . '/authlib.php');

class auth_plugin_infoeduka_sso extends auth_plugin_base
{

    public function __construct()
    {
        $this->authtype = Constant::PLUGIN_TAG_NAME;
    }

    public function can_be_manually_set()
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function user_login($username, $password)
    {
       return false;
    }


    /**
     * Will get called before the login page is shown
     *
     */
    function loginpage_hook()
    {
        global $CFG, $SESSION;

        try
        {
            if (!empty($_SERVER['REQUEST_URI']))
            {
                // First, let's remember where we were trying to get to before we got here
                if (empty($SESSION->wantsurl))
                {
                    $SESSION->wantsurl = null;
                    $referer = get_local_referer(false);
                    if ($referer &&
                        $referer != $CFG->wwwroot &&
                        $referer != $CFG->wwwroot . '/' &&
                        $referer != $CFG->wwwroot . '/login/' &&
                        $referer != $CFG->wwwroot . '/login/index.php')
                    {
                        $SESSION->wantsurl = $referer;
                    }
                }

                (new \auth_infoeduka_sso\controller\Request())->all($_SERVER['REQUEST_URI']);
            }
        }
        catch (Exception $e)
        {
            debugging("Exception occured while trying to authenticate: ".$e->getMessage());
            debugging($e->getTraceAsString(), DEBUG_DEVELOPER);
        }
    }

    function logoutpage_hook()
    {
        global $USER;
        \core\session\manager::kill_all_sessions();
        set_moodle_cookie('');
        $USER = null;
        return;
    }

    function pre_loginpage_hook()
    {
       $this->loginpage_hook();
    }

    function prelogout_hook()
    {
        $this->logoutpage_hook();
    }

}
