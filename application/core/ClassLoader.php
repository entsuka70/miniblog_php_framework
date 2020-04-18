<?php
// クラスのオートロード設定
// オートローダクラスの実施に次の機能を実装
// ①PHPにオートローダクラスを登録する
// ②オートロードが実行された際にクラスファイルを読み込む

// オートロード対象とするクラスルール
// ①クラスは「クラス名.php」というファイル名で保存する
// ②クラスはcoreディレクトリおよびmodelsディレクトリに配置する

class ClassLoader {

    protected $dirs;
    
    public function register() {
    
        // 指定した関数を__autoload()の実装として登録
        spl_autoload_register(array($this, 'loadClass'));
    
    }

    public function registerDir($dir) {
    
        $this->dirs[] = $dir;
    
    }

    public function loadClass($class) {

        foreach ($this->dirs as $dir) {
            $file = $dir . '/' . $class . '.php';
            if (is_readable($file)) {
                require $file;
                return;
            }
        }

    }

}
