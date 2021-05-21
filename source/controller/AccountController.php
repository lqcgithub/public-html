<?php 
    class AccountController extends BaseController{
           public function login(){
            //    session_start();
               if (isset($_SESSION['user'])) {
                   header('Location: ..');
                   exit();
                }
                $error = '';
                $user = '';
                $pass = '';
                $hello = '';
                
                function login_check($user, $pass){
                    $model = new AccountModel();
                    $data = $model->get_by_user($user)['data'][0];
                    if(empty($data)){
                        return array('code' => 1, 'error'=> 'Username is not exist.');
                    }
                    $hashed_password = $data['password'];
                    if(!password_verify($pass, $hashed_password)){
                        return array('code' => 2, 'error'=> 'Password error');
                    }
                    else if($data['activated'] == 0){
                        return array('code' => 3, 'error'=> 'This account is not activated');
                    }
                    else return
                    array('code' => 0, 'error'=> '', 'data' => $data);
                }

                if (isset($_POST['user']) && isset($_POST['pass'])) {
                    
                    $user = $_POST['user'];
                    $pass = $_POST['pass'];
                    
                    if (empty($user)) {
                        $error = 'Please enter your username';
                    }
                    else if (empty($pass)) {
                        $error = 'Please enter your password';
                    }
                    else if (strlen($pass) < 6) {
                        $error = 'Password must have at least 6 characters';
                    }
                    else{
                    
                        $result = login_check($user, $pass);
                        if($result['code'] == 0){

                            $account = $result['data'];
                            
                            $_SESSION['user'] = $user;
                            $_SESSION['name'] = $account['firstname'].' '.$account['lastname'];

                            
                            header('Location: ..');
                            exit();
                        }else if ($result['code'] == 1){
                            $error = $result['error'];
                        }
                        else if ($result['code'] == 2){
                            $error = $result['error'];
                        }
                        else if ($result['code'] == 3){
                            $error = $result['error'];
                        }
                    }
                }
                $data = array('title' => 'Login',
                                'error' => $error,
                                'user' => $user,
                                'pass' => $pass,
                            );
               $this->render('login.html', $data);
           } 


           public function logout(){
                // session_start();
                session_destroy();

            $data = array('title' => 'Logout',
                        );
               $this->render('logout.html', $data);
           }
           

        public function register(){

            function register($user, $pass, $first, $last, $email){
                function open_database(){
                    define('HOST', '127.0.0.1');
                    define('USER', 'root');
                    define('PASS', '');
                    define('DB', 'finalprojectdb');
                        $conn = new mysqli(HOST, USER, PASS, DB);
                        if($conn->connect_error){
                            die('Connect error: '.$conn->connect_error);
                        }
                        return $conn;
                }
                // if(is_email_exists($email)){
                //     return array('code' => 1, 'error'=> 'Email exists.');
                // }
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $rand = random_int(0, 1000);
                $token = md5($user.'+'.$rand);
                
                $sql = 'insert into account(username, firstname, lastname, email, password, activate_token) values(?,?,?,?,?,?)';

                $conn = open_database();
                $stm = $conn->prepare($sql);

                $stm->bind_param('ssssss',$user, $first, $last, $email, $hash, $token);
                if(!$stm->execute()){
                    return array('code' => 2, 'error'=> 'Can not execute');
                }

                // sendActivationEmail($email, $token);
                // $model = new AccountModel();
                // $account = $model->insert_account($user, $pass, $first, $last, $email);    
                return array('code' => 0, 'error'=> 'Successful');
            }
            $error = '';
            $first_name = '';
            $last_name = '';
            $email = '';
            $user = '';
            $pass = '';
            $pass_confirm = '';

            if (isset($_POST['first']) && isset($_POST['last']) && isset($_POST['email'])
            && isset($_POST['user']) && isset($_POST['pass']) && isset($_POST['pass-confirm']))
            {
                $first_name = $_POST['first'];
                $last_name = $_POST['last'];
                $email = $_POST['email'];
                $user = $_POST['user'];
                $pass = $_POST['pass'];
                $pass_confirm = $_POST['pass-confirm'];

                if (empty($first_name)) {
                    $error = 'Please enter your first name';
                }
                else if (empty($last_name)) {
                    $error = 'Please enter your last name';
                }
                else if (empty($email)) {
                    $error = 'Please enter your email';
                }
                else if (filter_var($email, FILTER_VALIDATE_EMAIL) == false) {
                    $error = 'This is not a valid email address';
                }
                else if (empty($user)) {
                    $error = 'Please enter your username';
                }
                else if (empty($pass)) {
                    $error = 'Please enter your password';
                }
                else if (strlen($pass) < 6) {
                    $error = 'Password must have at least 6 characters';
                }
                else if ($pass != $pass_confirm) {
                    $error = 'Password does not match';
                }
                else {
                    // register a new account
                    $result = register($user, $pass, $first_name, $last_name, $email);
                    if($result['code'] == 0){
                        die('Registeration successful.');
                    }else if($result['code'] == 1){
                        $error = 'This email is already exists.';
                    }else{
                        $error = 'An error occured. Please try again later.';
                    }
                }

            }
            $data = array('title' => 'Register', 'error' => $error);
            $this->render('register.html', $data);
        }
    }