<?php


namespace App\Service;


class AdminService
{
    public function putSession($account)
    {
        $this->startSession();

        setSession($account);
    }

    public function forgetSession()
    {
        $this->startSession();

        forgetSession();
    }

    public function startSession()
    {
        session_start();
    }
}
