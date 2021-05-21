<?php
    class AppModel extends BaseModel{
        
        function get_all()
        {
            $sql = 'select * from apps where 1';
            $data = $this->query($sql);
            return $data;
        }

        function get_updated_games()
        {
            $sql = 'select * from apps order by dateUpdate desc';
            $data = $this->query($sql);
            return $data;
        }

        function get_by_dev($dev)
        {
            $sql = 'select * from apps where dev=? order by numofDownload desc';
            $param = array('s', &$dev);
            $data = $this->query_prepared($sql, $param);
            return $data;
        }

        function get_new_games(){
            $sql = 'select * from apps order by id desc';
            $data = $this->query($sql);
            return $data;
        }

        function get_popular_games(){
            $sql = 'select * from apps order by numofDownload desc';
            $data = $this->query($sql);
            return $data;
        }

        function get_by_id($id)
        {
            $sql = 'select * from apps where id=?';
            $param = array('i', &$id);
            $data = $this->query_prepared($sql, $param);
            return $data;
        }

        function get_genres(){
            $sql = 'select distinct genre from apps';
            
            $data = $this->query($sql);
            return $data;
        }

        function get_by_genre($genre){
            $sql = 'select * from apps where genre=? order by numofDownload desc';
            $param = array('s', &$genre);
            $data = $this->query_prepared($sql, $param);
            return $data;
        }

        function search_app($search){
            $sql = "select * from apps where title like '%$search%' or genre like '%$search%' or dev like '%$search%'";
            $data = $this->query($sql);
            return $data;
        }

        function update_by_id($id){
            $todaydate = date("Y-m-d");
            $sqlDate = date('Y-m-d', strtotime($todaydate));
            $sql = "UPDATE apps SET dateUpdate='$todaydate' WHERE id=?";
            $param = array('s', &$id);
            $data = $this->query_prepared($sql, $param);
            // $data = $this->query($sql);
            return $data;
        }
    }