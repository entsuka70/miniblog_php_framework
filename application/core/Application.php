<?php

    abstract class Application{

        protected $debug = false;
        protected $request;
        protected $response;
        protected $session;
        protected $db_manager;

        public function __construct($debug = false){
            $this->setDebugMode($debug);
            $this->initialize();
            $this->configure();
        }

        protected function setDebugMode($debug){
            if ($debug){
                $this->debug = true;
                // エラー出力をONにする
                ini_set('display_errors', 1);
                // 全てのエラーを表示する
                error_reporting(-1);
            } else{
                $this->debug = false;
                ini_set('display_errors', 0);
            }
        }

        protected function initialize(){
            $this->request = new Request();
            $this->response = new Response();
            $this->session = new Session();
            $this->db_manager = new DbManager();
            $this->router = new Router($this->registerRoutes());
        }

        protected function Configure(){

        }

        abstract public function getRootDir();

        abstract protected function registerRoutes();

        public function isDebugMode(){
            return $this->debug;
        }

        public function getRequest(){
            return $this->request;
        }

        public function getResponse(){
            return $this->response;
        }

        public function getSession(){
            return $this->session;
        }

        public function getDbManager(){
            return $this->db_manager;
        }

        public function getControllerDir(){
            return $this->getRootDir() . '/controllers';
        }

        public function getViewDir(){
            return $this->getRootDir() . '/views';
        }

        public function getModeDir(){
            return $this->getRootDir() . 'models';
        }

        public function getWebDir(){
            return $this->getRootDir() . '/web';
        }

        // Routerからコントローラーを特定し、レスポンスの送信を行うまで管理するメソッド
        public function run(){
            // Routerクラスのresolveメソッドでルーティングパラメータを取得し、コントローラー名とアクション名を特定
            $params = $this->router->resolve($this->request->getPathInfo());
            if($params === false){
                throw new HttpNotFoundException('No route found for ' . $this->request->getPathInfo());
            }

            $controller = $params['controller'];
            $action = $params['action'];

            $this->runAction($controller, $action, $params);

            try{
                // ...
            } catch (HttpNotFoundException $e) {
                $this->render404page($e);
            }

            $this->response->send();
        }

        // 実際にアクションを実行するメソッド
        public function runAction($controller_name, $action, $params = array()){
            $controller_class = ucfirst($controller_name) . 'Controller';
            $controller = $this->findController($controller_class);
            if ($controller === false){
                throw new HttpNotFoundException($controller_class . 'controller is not found.');
            }

            $content = $controller->run($action, $params);

            $this->response->setContent($content);
        }

        // runActionメソッドの中でコントローラークラスを生成するメソッド
        protected function findController($controller_class){
            if (!class_exists($controller_class)){
                $controller_file = $this->getControllerDir() . '/' . $controller_class . '.php';
                if (!is_readable($controller_file)){
                    return false;
                } else {
                    require_once $controller_file;

                    if (!class_exists($controller_class)){
                        return false;
                    }
                }
            }

            return new $controller_class($this);
        
        }

        protected function render404Page($e){
            $this->response->setStatusCode(404, 'Not Found');
            $message = $this->isDebugMode() ? $e->getMessage() : 'Page not found.';
            $message = htmlspecialchars($message, ENT_QUOTES, 'UTF-8');

            $this->response->setContent(<<<EOF

                <!DOCTYPE>
                <html lang="ja">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>404</title>
                </head>
                <body>
                    {$message}
                </body>
                </html>

                EOF

            );

        }

    }

?>