<?php
// DB接続設定の読み込み
require_once 'config.php';

class DAO {

    private static $dbh;

    // DBに接続するメソッド
    public static function get_db_connect()
    {
        try {
            //DBに接続されていない場合
            ///selfはstaticに接続する場合
            if(self::$dbh === null){
                //DBに接続する
                self::$dbh = new PDO(DSN,DB_USER,DB_PASSWORD);
            }


        }
        // DB接続が失敗したとき
        catch (PDOException $e) { 
            // エラーメッセージを表示して終了
            echo $e->getMessage();
            die();
        }
        //DBオブジェクトを返す
        return self::$dbh;



    }
}
