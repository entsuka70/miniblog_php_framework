<?php

    class MiniBlogApplication extends Application {

        protected $login_action = array('account', 'signin');

        public function getRootDir() {
            
            return dirname(__FILE__);

        }

        // ルーティング定義配列を返すメソッド
        protected function registerRoutes() {

            return array(
                '/'
                    =>array('controller' => 'status', 'action' => 'index'),
                '/status/post'
                    =>array('controller' => 'status', 'action' => 'post'),
                '/account'
                    => array('controller' => 'account', 'action' => 'index'),
                '/account/:action'
                    => array('controller' => 'account'),
            );

        }

        // アプリケーション設定を行うメソッド
        protected function configure() {

            $this->db_manager->connect('master', array(
                'dsn' => 'mysql:dbname=miniblog_phpframework;host=localhost;charset=utf8',
                'user' => 'root',
                'password' => 'root',
            ));

        }


    }

