<?php

namespace auth_infoeduka_sso\business;

use auth_infoeduka_sso\business\Logon;
use auth_infoeduka_sso\Constant;

require_once ("JWT.php");

class Token implements Business
{

    public function __construct()
    {
        return $this;
    }

    public function decode($token)
    {
        global $USER, $CFG, $SESSION;
        try
        {
            $data = null;
            $jwtkey = Constant::JWT_KEY;
            if (!empty($token) && !empty($jwtkey))
            {
                $decoded = JWT::decode($token, $jwtkey, array('HS256'));
                if (!empty($decoded->data->username) && !empty($decoded->iss) && !empty($decoded->exp))
                {
                    $data = $decoded;
                }
                else
                {
                    $SESSION->wantsurl = $CFG->wwwroot . Constant::URL_REDIRECT_LOGIN_PAGE;
                    throw new \Exception(Constant::MSG_TOKEN_PROPERTIES_INVALID);
                }
            }
            return $data;
        }
        catch (\Exception $ex)
        {
            $SESSION->wantsurl = $CFG->wwwroot . Constant::URL_REDIRECT_LOGIN_PAGE;
            throw new \Exception($ex);
        }
    }

    public function encode($token)
    {
        global $CFG;
        try
        {
            $decoded = null;
            $jwtkey = Constant::JWT_KEY;
            if (!empty($token) && !empty($jwtkey))
            {
                $decoded = JWT::encode($token, $jwtkey);
            }
            return $decoded;
        }
        catch (\Exception $ex)
        {
            redirect($CFG->wwwroot . Constant::URL_REDIRECT_LOGIN_PAGE);
        }
    }
}
