<?php
    class AccountModel extends BaseModel{
        
        function get_all()
        {
            $sql = 'select * from account where 1';
            $data = $this->query($sql);
            return $data;
        }

        function get_by_id($id)
        {
            $sql = 'select * from account where id=?';
            $param = array('i', &$id);
            $data = $this->query_prepared($sql, $param);
            return $data;
        }

        function get_by_user($user)
        {
            $sql = 'select * from account where username=?';
            $param = array('s', &$user);
            $data = $this->query_prepared($sql, $param);
            return $data;
        }


        function get_by_email($email)
        {
            $sql = 'select * from account where email=?';
            $param = array('s', &$email);
            $data = $this->query_prepared($sql, $param);
            return $data;
        }

        function insert_account($user, $pass, $first, $last, $email){
            $hash = password_hash($pass, PASSWORD_DEFAULT);
            $rand = random_int(0, 1000);
            $token = md5($user.'+'.$rand);
            $sql = "insert into account (username, firstname, lastname, email, password, activate_token) values(?, ?, ?, ?, ?, ?)";
            $param = array("ssssss", &$user, &$first, &$last, &$email, &$hash, &$token);
            $data = $this->query_prepared($sql, $param);
            return $data;
        } 
       

    }