<?php

require_once 'Model.php';

class User extends Model
{
    public function Register($login, $password, &$userId)
    {
        file_put_contents(APP . '/models/' . $login . '.txt', $password);

        $userId = 555;

        return true;
    }
}
