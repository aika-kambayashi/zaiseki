<?php

class UserRepository extends DbRepository {

    public function insert($user_name, $password) {
        $password = $this->hashPassword($password);
        $now = new DateTime();
        $sql = '
            insert into user(user_name,password,name,created_at) values(:user_name,:password,"名無し",:created_at)
        ';
        $stmt = $this->execute($sql, [
            ':user_name'    => $user_name,
            ':password'     => $password,
            ':created_at'   => $now->format('Y-m-d H:i:s')
        ]);
    }

    public function hashPassword($password) {
        return sha1($password . 'SecretKey');
    }
    
    public function fetchByUserName($user_name) {
        $sql = 'select * from user where user_name = :user_name';
        return $this->fetch($sql, [':user_name' => $user_name]);
    }

    public function isUniqueUserName($user_name) {
        $sql = 'select count(id) as count from user where user_name = :user_name';
        $row = $this->fetch($sql, [':user_name' => $user_name]);
        if ($row['count'] === '0') {
            return true;
        }
        return false;
    }

    public function update($user) {
        $sql = 'update user set name = :name where id = :id';
        $stmt = $this->execute($sql,$user);
    }

    public function fetchByUserId($id) {
        $sql = 'select * from user where id = :id';
        return $this->fetch($sql, [':id' => $id]);
    }
}