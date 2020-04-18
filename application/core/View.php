<?php

    class View{

        protected $base_dir;
        protected $defaults;
        protected $layout_variables = array();

        public function __construct($base_dir, $defaults = array()) {

            $this->base_dir = $base_dir;
            $this->defaults = $defaults;

        }

        public function setLayoutVar($name, $value) {

            $this->layout_variables[$name] = $value;

        }

        public function render($_path, $_variables = array(), $_layout = false) {

            $_file = $this->base_dir . '/' . $_path . '.php';

            // extract:配列からシンボルテーブルに変数をインポートする
            // array_merge:ひとつまたは複数の配列をマージする
            extract(array_merge($this->defaults, $_variables));

            // 出力のバッファリングを有効にする
            ob_start();
            // 自動フラッシュをオンまたはオフにする(1:ON, 0:OFF)
            ob_implicit_flush(0);

            require $_file;

            // 現在のバッファの内容を取得し、出力バッファを削除する
            $content = ob_get_clean();

            if ($_layout) {
                $content = $this->render($_layout, array_merge($this->layout_variables, array(
                    '_content' => $content,
                    )
                ));
            }

            return $content;

        }

        public function escape($string) {

            return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
        
        }

    }

