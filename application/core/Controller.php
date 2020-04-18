<?php

    abstract class Controller {

        protected $controller_name;
        protected $action_name;
        protected $application;
        protected $request;
        protected $response;
        protected $session;
        protected $db_manager;
        protected $auth_actions = array();

        public function __construct($application) {

            // Controller(10文字分)を取り除いて、オブジェクトのクラス名を取得
            // strlowerで小文字変換
            // 例:UserClassがUserとなりuserがコントローラー名
            $this->controller_name = strtolower(substr(get_class($this), 0, -10));

            $this->application = $application;
            $this->request = $application->getRequest();
            $this->response = $application->getResponse();
            $this->session = $application->getSession();
            $this->db_manager = $application->getDbManager();
        }

        public function run($action, $params = array()) {

            $this->action_name = $action;

            $action_method = $action . 'Action';
            if (!method_exists($this, $action_method)) {
                $this->forward404();
            }

            if ($this->needsAuthentication($action) && !$this->session->isAuthenticated()) {
                throw new UnauthorizedActionException();
            }

            $content = $this->$action_method($params);

            return $content;

        }

        protected function needsAuthentication($action) {
            
            if ($this->auth_actions === true || (is_array($this->auth_actions) && in_array($action, $this->auth_actions))) {
                return true;
            }

            return false;
        
        }

        // Viewファイルのレンダリング処理
        protected function render($variables = array(), $template = null, $layout = 'layout') {

            $defaults = array(
                'request'   => $this->request,
                'base_url'  => $this->request->getBaseUrl(),
                'session'   => $this->session,
            );

            $view = new View($this->application->getViewDir(), $defaults);

            if (is_null($template)) {
                $template = $this->action_name;
            }

            $path = $this->controller_name . '/' . $template;

            return $view->render($path, $variables, $layout);

        }

        // ページが存在しない時のエラー処理
        protected function forward404() {

            throw new HttpNotFoundException('Forwarded 404 page from ' . $this->controller_name . '/' . $this->action_name);
        
        }

        // URLリダイレクト処理
        protected function redirect($url) {
            
            if (!preg_match('#https?://#', $url)) {
                $protocol = $this->request->isSsl() ? 'https://' : 'http://';
                $host = $this->request->getHost();
                $base_url = $this->request->getBaseUrl();

                $url = $protocol . $host . $base_url . $url;
            }

            $this->response->setStatusCode(302, 'Found');
            $this->response->setHttpHeader('Location', $url);

        }

        // CSRF対策
        // トークンを生成し、サーバー上に保持するセッション格納
        protected function generateCsrfToken($form_name) {

            $key = 'csrf_tokens/' . $form_name;
            $tokens = $this->session->get($key, array());
            if (count($tokens) >= 10) {
                // array_shift:配列の先頭から要素をひとつ取り出す
                array_shift($tokens);
            }

            // sha1:文字列のsha1ハッシュを計算する
            // session_id:現在のセッションIDを取得または設定する
            // microtime:現在のUnixタイムスタンプをマイクロ秒まで返す
            $token = sha1($form_name . session_id() . microtime());
            $tokens[] = $token;

            $this->session->set($key, $tokens);

            return $token;

        }

        // CSRF対策
        // セッション上に格納されているトークンからPOSTされたトークンを探すメソッド
        protected function checkCsrfToken($form_name, $token) {

            $key = 'csrf_tokens/' . $form_name;
            $tokens = $this->session->get($key, array());

            // array_search:指定した値を配列で検索し、見つかった場合に対応する最初のキーを返す
            if (false !== ($pos = array_search($token, $tokens, true))) {
                unset($tokens[$pos]);
                $this->session->set($key, $tokens);
                
                return true;
            }

            return false;

        }


    }

