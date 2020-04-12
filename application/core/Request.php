<?php

    class Request{
        public function isPost(){
            // $_SERVER['REQUEST_METHOD']:ページにアクセスする際に使用されたリクエストのメソッド名
            if ($_SERVER['REQUEST_METHOD'] === 'POST'){
                return true;
            }

            return false;
        }

        public function getGet($name, $default = null){
            if (isset($_GET[$name])){
                return $_GET[$name];
            }

            return $default;
        }

        public function getPost($name, $default = null){
            if (isset($_POST[$name])){
                return $_POST[$name];
            }

            return $default;
        }

        // サーバーのホスト名を取得するメソッド(リダイレクトを行う場合に利用)
        public function getHost(){
            // $_REQUEST['HTTP_HOST']:現在のリクエストにHost：ヘッダがもしあればその内容
            if (!empty($_SERVER['HTTP_HOST'])){
                return $_SERVER['HTTP_HOST'];
            }

            // $_REQUEST['SERVER_NAME']：現在のスクリプトが実行されているサーバーのホスト名。 スクリプトがバーチャルホスト上で実行されている場合は そのバーチャルホスト名となる
            return $_SERVER['SERVER_NAME'];
        }

        // HTTPSでアクセスされたかどうかの判定
        public function isSsl(){
            if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on'){
                return true;
            }

            return false;
        }

        public function getRequestUri(){
            // $_SERVER['REQUEST_URI']：ページにアクセスするために指定された URI
            return $_SERVER['REQUEST_URI'];
        }

        public function getBaseUrl(){
            $script_name = $_SERVER['SCRIPT_NAME'];
            $request_uri = $this->getRequestUri();

            if (0 === strpos($request_uri, $script_name)){
                return $script_name;
            } elseif (0 === strpos($request_uri, $script_name)){
                // $script_nameの最後から'/'を削除
                return rtrim(dirname($script_name), '/');
            }

            return '';
        }

        public function getPathInfo(){
            $base_url = $this->getBaseUrl();
            $request_uri = $this->getRequestUri();

            if(false !== ($pos = strpos($request_uri, '?'))){
                $request_uri = substr($request_uri, 0, $pos);
            }

            $path_info = (string)substr($request_uri, strlen($base_url));

            return $path_info;
        }

    }

?>