<?php 
    require_once 'DAO.php';
    //Goodsgrouprテーブルのデータを保持するクラス
    class GoodsGroup
    {
        public int $groupcode;
        public string $groupname;
    }
    class GoodsGroupDAO
    {
        //DBから全商品グループを取得するメソッド
        public function get_goodsgroup()
        {
            $dbh = DAO::get_db_connect();
            $sql = "SELECT * FROM GoodsGroup";
            $stmt = $dbh->prepare($sql);

            $stmt->execute();
            
            //取得したデータをGoodsGroupクラスの配列にする
            $data = [];
            while($row = $stmt->fetchObject('GoodsGroup')){
                $data[] = $row;
            }
            return $data;
        }
        
    }
?>