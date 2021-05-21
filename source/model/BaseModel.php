<?php
abstract class BaseModel{
    private $db;
    public function __construct() {
        $this->db = Database::open();
    }


    abstract function get_all();
    abstract function get_by_id($id);

    public function query($sql){
        $result = $this->db->query($sql);
        if(!$result){
            return array('code' => 1, 'error' => $this->db->error);
        }

        $data = array();
        while ($item = $result->fetch_assoc()){
            array_push($data, $item);
        }
        return  array('code' => 0, 'data' => $data);
    }
    public function query_prepared($sql, $param){
        $stm = $this->db->prepare($sql);
        
        call_user_func_array(array($stm, 'bind_param'), $param);
        
        if(!$stm->execute()){
            return array('code' => 1, 'error' => $this->db->error);
        }
        
        $result = $stm->get_result();
    
        $data = array();
        while($row = $result->fetch_assoc()){
            
            array_push($data, $row);
        }
    
        return  array('code' => 0, 'data' => $data);
    }

}