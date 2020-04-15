<?php
    class DbManager {
        
        protected $connections = array();

        // 接続情報を入力するメソッド
        public function connect($name, $params) {
           
            $params = array_merge(array(
                'dsn'       => null,
                'user'      => '',
                'password'  => '',
                'options'   => array(),
            ), $params);

            $con = new PDO(
                $params['dsn'],
                $params['user'],
                $params['password'],
                $params['options']
            );

            $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $this->connections[$name] = $con;

        }

        // 接続情報がない場合に現在の接続条件を反映するメソッド
        public function getConnection($name = null) {

            if (is_null($name)){
                return current($this->connections);
            }

            return $this->connections[$name];

        }

        protected $repository_connection_map = array();

        // 最初に指定したコネクション以外の物を利用する場合に用いるメソッドs
        public function setRepositoryConnectionMap($repository_name, $name) {

            $this->repository_connection_map[$repository_name] = $name;

        }
        
        // 最初に指定したコネクション以外の物を利用する場合に用いるメソッドs
        public function getConnectionForRepository($repository_name) {

            if (isset($this->repository_connection_map[$repository_name])) {
                $name = $this->repository_connection_map[$repository_name];
                $con = $this->getConnection($name);
            } else {
                $con = $this->getConnection();
            }

            return $con;

        }

        // Repositoryクラスの管理
        protected $repositories = array();

        public function get($repository_name) {

            if (!isset($this->repositories[$repository_name])) {
                $repository_class = $repository_name . 'Repository';
                $con =  $this->getConnectionForRepository($repository_name);

                // 変数にクラス名を文字列で入れることで動的なクラス生成が可能
                $repository = new $repository_class($con);

                $this->repositories[$repository_name] = $repository;
            }

            return $this->repositories[$repository_name];

        }

        public function __destruct() {

            foreach ($this->repositories as $repository) {
                unset($repository);
            }

            foreach ($this->connections as $con) {
                unset($con);
            }
            
        }

    }

?>