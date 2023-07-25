<?php
    require_once 'DAO.php';

    class cart
    {
        public int $memberid;
        public string $goodscode;
        public string $goodsname;
        public int $price;
        public string $detail;
        public string $goodsimage;
        public int $num;
    }
    class CartDAO
    {
        //会員カートデータを取得する
        public function get_cart_by_memberid(int $memberid)
        {
            $dbh = DAO::get_db_connect();
            $sql = "SELECT memberid,cart.goodscode,goodsname,price,detail,goodsimage,num
                    FROM goods INNER JOIN cart ON goods.goodscode = cart.goodscode
                    WHERE memberid = :memberid";
            $stmt=$dbh->prepare($sql);
            $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
            $stmt->execute();
            $data = [];
            while($row = $stmt->fetchObject('Cart')){
                $data[] = $row;
            }
            return $data;
        }

        //指定した商品がカートテーブルが存在するか確認する
        public function cart_exists(int $memberid,string $goodscode)
        {
            $dbh = DAO::get_db_connect();
            $sql = "SELECT *
                    FROM cart
                    WHERE memberid = :memberid AND goodscode = :goodscode";
            $stmt=$dbh->prepare($sql);
            $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
            $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR);
            $stmt->execute();

            if($stmt->fetch() !== false){
                return true; //カートに商品が存在する
            }
            else{
                return false;
            }
        }

        //カートテーブルに商品を追加する
        public function insert(int $memberid,string $goodscode,int $num)
        {
            $dbh = DAO::get_db_connect();
            //カートテーブルに同じ商品がないとき
            if(!$this->cart_exists($memberid,$goodscode)){
                //カートテーブルに商品を追加する
                $sql = "INSERT INTO cart VALUES(:memberid,:goodscode,:num)";
                $stmt=$dbh->prepare($sql);
                $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
                $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR);
                $stmt->bindValue(':num',$num,PDO::PARAM_INT);
                $stmt->execute();
            }
            //カートテーブルに同じテーブルがある時
            else{
                //カートテーブルに商品個数を加算する
                $sql = "UPDATE cart
                        SET num = num + :num
                        WHERE memberid = :memberid AND goodscode = :goodscode";
                $stmt=$dbh->prepare($sql);
                $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
                $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR);
                $stmt->bindValue(':num',$num,PDO::PARAM_INT);
                $stmt->execute();
            }
            
        }

        //カートテーブルの商品個数を変更する
        public function update(int $memberid,string $goodscode,int $num)
        {
            $dbh = DAO::get_db_connect();
            $sql = "UPDATE cart
                    SET num = :num
                    WHERE memberid = :memberid AND goodscode = :goodscode";
            $stmt=$dbh->prepare($sql);
            $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
            $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR);
            $stmt->bindValue(':num',$num,PDO::PARAM_INT);
            $stmt->execute();
        }

        //カートテーブルから商品を削除する
        public function delete(int $memberid,string $goodscode)
        {
            $dbh = DAO::get_db_connect();
            $sql = "DELETE FROM cart
                    WHERE memberid = :memberid AND goodscode = :goodscode";
            $stmt=$dbh->prepare($sql);
            $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
            $stmt->bindValue(':goodscode',$goodscode,PDO::PARAM_STR);
            $stmt->execute();
        }

        //会員カートの情報をすべて削除する
        public function delete_by_memberid(int $memberid){
            $dbh = DAO::get_db_connect();
            $sql = "DELETE FROM cart WHERE memberid = :memberid";
            $stmt = $dbh->prepare($sql);
            $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
            $stmt->execute();
        }
        

    }
?>

