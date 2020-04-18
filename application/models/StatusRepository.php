<?php

    class StatusRepository extends DbRepository {

        public function insert($user_id, $body) {

            $now = new DateTime();

            $sql = "INSERT INTO status(user_id, body, created_at)
                    VALUES(:user_id, :body, :created_at)
            ";

            $stmt = $this->execute($sql, array(
                ':user_id' => $user_id,
                ':body' => $body,
                ':created_at' => $now->format('Y-m-d H:i:s'),
            ));

        }

        public function fetchAllPersonalArchivesByUserId($user_id) {

            $sql = "SELECT a.*, u.user_name
                    FROM status a LETT JOIN user u ON a.user_id = u.id
                    WHERE u.id = :user_id
                    ORDER BY a.created_at DESC
                    ";

            return $this->fetchAll($sql, array(':user_id' => $user_id));

        }

        // ユーザーIDに一致する投稿を投稿びの降順に全権取得
        public function  fetchAllByUserId($user_id) {

            $sqk = "SELECT a.*, u.user_name
                    FROM status a LEFT JOIN user u ON a.user_id = u.id
                    WHERE u.id = :user_id
                    ORDER BY a.created_at DESC
                    ";

            return $this->fetchAll($sql, array(':user_id' => $user_id));
        }

        // 投稿IDとユーザーのidに一致するレコードを一件取得
        public function fetchByIdAndUserName($id, $user_name) {

            $sql = "SELECT a.*, u.user_name
                    FROM status a LEFT JOIN user u ON u.id = a.user_id
                    WHERE a.id = :id
                    AND u.user_name = :user_name
                    ";

            return $this->fetch($sql, array(
                ';id' => $id,
                ':user_name' => $user_name,
            ));
        }

    }