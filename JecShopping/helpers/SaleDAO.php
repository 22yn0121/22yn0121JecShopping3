<?php
    require_once 'DAO.php';
    require_once 'cartDAO.php';
    require_once 'SaleDetailDAO.php';

    class SaleDAO
    {   
        //Saleテーブルから最新のsaleNoを取得する
        private function get_saleno()
        {
            $dbh = DAO::get_db_connect();
            //Saleテーブルから最新の販売番号を取得するSQL
            $sql = "SELECT IDENT_CURRENT('Sale')AS saleno";
            $stmt = $dbh->query($sql);
            $row = $stmt->fetchObject();
            return $row->saleno; //最新のsaleを返す
        }

        //DBに購入データを追加する
        public function insert(int $memberid,Array $cart_list)
        {   
            //戻り値
            $ret = false;

            $dbh = DAO::get_db_connect();

            try{
                //トランザクションを開始する
                $dbh->beginTransaction();

                //トランザクション終了までsale表共有ロックする
                $sql = "SELECT * FROM Sale WITH(TABLOCK,HOLDLOCK)";
                $dbh->query($sql);

                $sql = "INSERT INTO sale VALUES(:saledate,:memberid)";
                $stmt=$dbh->prepare($sql);
                //現在の時刻を取得する
                $saledate = date("Y-m-d H:i:s");
                $stmt->bindValue(':saledate',$saledate,PDO::PARAM_STR);
                $stmt->bindValue(':memberid',$memberid,PDO::PARAM_INT);
                $stmt->execute();

                //最新のSalenoの値を取得する
                $saleno = $this->get_saleno();
                $saleDetailDAO = new SaleDetailDAO();

                //カートの商品をSaleDetailテーブルに追加する
                foreach($cart_list as $cart){
                    $saleDetail = new SaleDetail();

                    $saleDetail->saleno = $saleno;
                    $saleDetail->goodscode = $cart->goodscode;
                    $saleDetail->num = $cart->num;

                    $saleDetailDAO->insert($saleDetail,$dbh);
                }

                //コミットしてトランザクションを終了する
                $dbh->commit();
                $ret = true;
            }
            //DB更新中に例外が発生したとき
            catch(PDOException $e){
                $dbh->rollBack();
                $ret = false;
            }
            return $ret;
        }
    }
?>