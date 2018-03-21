<?php

require_once 'Model.php';

class User extends Model
{
    public function Register($login, $password)
    {
        file_put_contents(APP . '/models/' . $login . '.txt', $password);
        return true;
    }
}
