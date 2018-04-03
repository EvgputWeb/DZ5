<?php

use Illuminate\Database\Eloquent\Model;

require_once 'User.php';


class Userslist extends Model
{
    protected $table = 'users';


    public function getUsersList()
    {
        $users = User::all()->sortByDesc('id')->toArray();
        return $users;
    }


    public function deleteUser($userId)
    {
        User::destroy($userId);

        // Удаляем фотку, если она есть
        $photoFilename = Config::getPhotosFolder() . '/photo_' . intval($userId) . '.jpg';
        if (file_exists($photoFilename)) {
            unlink($photoFilename);
        }
        return true;
    }


    public function getUserData($userId)
    {
        return User::query()->find($userId)->toArray();
    }


    public function updateUserData($userId, $newData)
    {
        User::query()->find($userId)->update([
            'name' => $newData['name'],
            'age' => $newData['age'],
            'description' => $newData['description'],
            'email' => $newData['email']
        ]);
    }

}
