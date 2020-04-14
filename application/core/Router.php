<?php

    class Router {
        
        protected $routes;

        public function __construct($definitions) {

            $this->routes = $this->compileRoutes($definitions);
        
        }

        public function compileRoutes($definitions) {

            $routes = array();

            foreach ($definitions as $url => $params) {

                // explode:文字列を文字列により分割する
                // ltrim:文字列の最初から空白(もしくは指定の文字列)を取り除く
                $tokens = explode('/', ltrim($url, '/'));
                foreach ($tokens as $i => $token) {
                    if (0 === strpos($token, ':')) {
                        $name = substr($token, 1);
                        // 正規表現の形式に変換
                        $token = '(?P<' . $name . '>[^/]+)';
                    }

                    $tokens[$i] = $token;
                
                }

                // implode:配列要素を文字列により連結
                $pattern = '/' . implode('/', $tokens);
                $routes[$pattern] = $params;

            }

            return $routes;
        
        }

        public function resolve($path_info) {

            if ('/' !== substr($path_info, 0, 1)) {
                $path_info = '/' . $path_info;
            }

            foreach ($this->routes as $pattern => $params) {
                if (preg_match('#^' . $pattern . '$#', $path_info, $matches)) {
                    // array_merge:ひとつまたは複数の配列をマージする
                    $params = array_merge($params, $matches);

                    return $params;
                }
            }

            return false;
        }

    }



?>