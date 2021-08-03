<?php

class StatusRepository extends DbRepository {
    
    public function getModel() {
        return [
            'present'       => '',
            'destination'   => '',
            'reach_time'    => '',
            'memo'          => ''
        ];
    }

    public function getAll() {
        $sql = '
            select u.id,u.name,s.present,s.destination,s.reach_time,s.memo,s.modified_at
            from user as u
            left join status as s
            on u.id = s.user_id
        ';
        return $this->fetchAll($sql);
    }

    public function getStatus($user_id) {
        $sql = '
            select user_id,present,destination,reach_time,memo
            from status where user_id = :user_id
        ';
        return $this->fetch($sql, ['user_id' => $user_id]);
    }

    public function update($status) {
        $sql = '
            update status set
                present = :present,
                destination = :destination,
                reach_time = :reach_time,
                memo = :memo,
                modified_at = now()
            where user_id = :user_id
        ';
        $stmt = $this->execute($sql, $status);
    }

    public function insert($status) {
        $sql = '
            insert into status (user_id,present,destination,reach_time,memo,modified_at,created_at)
            values (:user_id,:present,:destination,:reach_time,:memo,now(),now())
        ';
        $stmt = $this->execute($sql, $status);
    }
}