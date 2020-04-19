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
                '/user/:user_name'
                    =>array('controller' => 'status', 'action' => 'user'),
                '/user/:user_name/status/:id'
                    =>array('controller' => 'status', 'action' => 'show'),
                '/account'
                    => array('controller' => 'account', 'action' => 'index'),
                '/account/:action'
                    => array('controller' => 'account'),
                '/follow'
                    => array('controller' => 'account', 'action' => 'follow'),
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

