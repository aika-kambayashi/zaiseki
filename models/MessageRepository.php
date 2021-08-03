<?php

class MessageRepository extends DbRepository {

    public function getModel() {
        return [
            'to_user_id' => '',
            'pass_sec' => '',
            'pass_tel' => '',
            'pass_name' => '',
            'msec' => '',
            'message' => '',
            'from_user_id' => ''
        ];
    }

    public function getMessageByToUserId($to_user_id) {
        $sql = '
            select
                m.message_id,m.to_user_id,m.pass_sec,m.pass_tel,m.pass_name,m.mesc,m.message,m.from_user_id
            from message as m
            where m.to_user_id = :to_user_id
        ';
        return $this->fetchAll($sql,['to_user_id' => $to_user_id]);
    }

    public function delete($message_id) {
        $sql = 'delete from message wehere message_id = :message_id';
        return $this->execute($sql, ['message_id' => $message_id]);
    }

    public function insert($message) {
        $sql = '
            insert into message
                (to_user_id,pass_sec,pass_tel,pass_name,msec,message,from_user_id)
            values
                (:to_user_id,:pass_sec,:pass_tel,:pass_name,:msec,:message,:from_user_id)
        ';
        $stmt = $this->execute($sql, $message);
    }

    public function deleteByMessageIdAndToUserId($message_id, $to_user_id) {
        $sql = 'delete from message where message_id = :message_id and to_user_id = :to_user_id';
        $data = [
            'message_id' => $message_id,
            'to_user_id' => $to_user_id
        ];
        $this->execute($sql,$data);
    }
}