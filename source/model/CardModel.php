<?php
    class CardModel extends BaseModel{
        
        function get_all()
        {
            $sql = 'select * from cards where 1';
            $data = $this->query($sql);
            return $data;
        }


        function get_by_id($id)
        {
            $sql = 'select * from cards where id=?';
            $param = array('i', &$id);
            $data = $this->query_prepared($sql, $param);
            return $data;
        }

        function get_by_genre($genre){
            $sql = 'select * from cards where genre=? order by numofDownload desc';
            $param = array('s', &$genre);
            $data = $this->query_prepared($sql, $param);
            return $data;
        }

        function update_by_id($id){
            $todaydate = date("Y-m-d");
            $sqlDate = date('Y-m-d', strtotime($todaydate));
            $sql = "UPDATE cards SET dateUpdate='$todaydate' WHERE id=?";
            $param = array('s', &$id);
            $data = $this->query_prepared($sql, $param);
            // $data = $this->query($sql);
            return $data;
        }
    }