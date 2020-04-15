<?php

    class Response {
        
        protected $content;
        protected $status_code = 200;
        protected $status_text = 'OK';
        protected $http_headers = array();

        public function send() 
        {
            header('HTTP/1.1 ' . $this->status_code . ' ' . $this->status_text);
            foreach ($this->http_headers as $name => $value){
                header($name . ':' . $value);
            }

            echo $this->content;

        }

        // HTMLなどの実際にクライアントへ返す内容を設定するメソッド
        public function setContent($content) {
            
            $this->content = $content;
        
        }

        // 本来は色々なステータスコードがあるが、省略。ステータスコードを設定。
        public function setStatusCode($status_code, $status_text = '') {
            
            $this->status_code = $status_code;
            $this->status_text = $status_text;
        
        }

        // HTTPヘッダを格納し設定するメソッド
        public function setHttpHeader($name, $value) {
        
            $this->http_headers[$name] = $value;
        
        }

    }

?>