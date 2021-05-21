<?php 
    // session_start(); 
    class AdminController extends BaseController{
              
           public function error(){
               $data = array('title' => 'Error Page', 'message'=> 'Nội dung bạn tìm không khả dụng.');
               $this->render('../home/error.html', $data);
           } 

           public function index(){
                
                // if (isset($_SESSION['admin'])) {
                //     $hello = $_SESSION['name'];
                // }else{
                //     header('Location: /Final-project/account/login');
                //     exit();
                // }
                
                $model = new AppModel();
                $apps = $model->get_all()['data'];
                $page = 1;
                if(isset($_GET['page'])){
                    if(empty($page)){
                        $page = 1;
                    }else{
                        $page = $_GET['page'];
                    } 
                }
                $loadData = $this->load();
                $hello = $loadData['hello'];
                $genresOut = $loadData['genresOut'];
                switch ($page) {
                    case 2:
                        $appsSlice = array_slice($apps, 5, 5);
                        break;
                    case 3:
                        $appsSlice = array_slice($apps, 10, 5);
                        break;
                    case 4:
                        $appsSlice = array_slice($apps, 15, 5);
                        break;
                    case 5:
                        $appsSlice = array_slice($apps, 20, 5);
                        break;
                    default:
                        $appsSlice = array_slice($apps, -5, 5);
                }
        
               $data = array('title' => 'Admin Page', 
                                'apps' => $appsSlice,
                                'hello'=>$hello,
                                'genres'=>$genresOut,
                                'page' =>$page,
                            );
               
               $this->render('app-management.html', $data);
           }
            public function open_database(){
                        $conn = new mysqli('127.0.0.1', 'root', '', 'finalprojectdb');
                        if($conn->connect_error){
                            die('Connect error: '.$conn->connect_error);
                        }
                        return $conn;
                }
            public function add(){
               function add($title, $dev, $genre, $description){
                function open_database(){
                        $conn = new mysqli('127.0.0.1', 'root', '', 'finalprojectdb');
                        if($conn->connect_error){
                            die('Connect error: '.$conn->connect_error);
                        }
                        return $conn;
                }
                $sql = 'insert into apps(title, dev, genre, description) values(?,?,?,?)';

                $conn = open_database();
                $stm = $conn->prepare($sql);

                $stm->bind_param('ssss',$title, $dev, $genre, $description);
                if(!$stm->execute()){
                    return array('code' => 2, 'error'=> 'Can not execute');
                }
                return array('code' => 0, 'error'=> 'Successful');
            }
            $error = '';
            $title = '';
            $dev = '';
            $genre= '';
            $description = '';
            

            if (isset($_POST['title']) && isset($_POST['dev']) && isset($_POST['genre'])
            && isset($_POST['description']))
            {
                
                $title = $_POST['title'];
                $dev = $_POST['dev'];
                $genre = $_POST['genre'];
                $description = $_POST['description'];

                if (empty($title)) {
                    $error = 'Please enter your first name';
                }
                else if (empty($dev)) {
                    $error = 'Please enter your last name';
                }
                else if (empty($genre)) {
                    $error = 'Please enter your email';
                }
                else if (empty($description)) {
                    $error = 'Please enter your username';
                }
                else {
                    $result = add($title, $dev, $genre, $description);
                    if($result['code'] == 0){
                        $page=1;
                        if(isset($_GET['page'])){
                            $page=$_GET['page'];
                        }
                        header('Location: ../admin?page='.$page);
                        
                        exit();
                    }else if($result['code'] == 1){
                        $error = 'This email is already exists.';
                    }else{
                        $error = 'An error occured. Please try again later.';
                    }
                }

            }
            $data = array('title' => 'Register', 'error' => $error);
            $this->render('add-app.html', $data);
           }

           public function update(){
                // function open_database(){
                //         $conn = new mysqli('127.0.0.1', 'root', '', 'finalprojectdb');
                //         if($conn->connect_error){
                //             die('Connect error: '.$conn->connect_error);
                //         }
                //         return $conn;
                // }
                // if (isset($_SESSION['admin'])) {
                //     $hello = $_SESSION['name'];
                // }else{
                //     header('Location: /Final-project/account/login');
                //     exit();
                // }
                $error ='';
                
                if(!isset($_GET['id'])){
                    die('Không tìm thấy id.');
                }
                if(empty($_GET['id'])){
                    $error = 'Không tìm thấy app ID.';
                }

                $id = $_GET['id'];

                // $model = new AppModel();
                // $model->update_by_id($id);

                $todaydate = $todaydate = date("d-m-Y");;
                $sql = "UPDATE apps SET dateUpdate='$todaydate' WHERE id=?";
                
                $conn = $this->open_database();
                $stm = $conn->prepare($sql);

                $stm->bind_param('i',$id);
                if(!$stm->execute()){
                    die('Error');
                }
                $page=1;
                if(isset($_GET['page'])){
                    $page=$_GET['page'];
                }
                // $action='index';
                // if(isset($_GET['action'])){
                //     $action = $_GET['action'];
                // }
                  
                header('Location: ../admin?page='.$page);
                exit();  
           }

           public function delete(){
                
                // if (isset($_SESSION['admin'])) {
                //     $hello = $_SESSION['name'];
                // }else{
                //     header('Location: /Final-project/account/login');
                //     exit();
                // }
                $error ='';
                

                if(!isset($_GET['id'])){
                    die('Không tìm thấy id.');
                }
                if(empty($_GET['id'])){
                    $error = 'Không tìm thấy app ID.';
                }

                $id = $_GET['id'];

                // $model = new AppModel();
                // $model->update_by_id($id);

                $todaydate = $todaydate = date("d-m-Y");;
                $sql = "delete from apps where id=?";
                
                $conn = $this->open_database();
                $stm = $conn->prepare($sql);

                $stm->bind_param('i',$id);
                if(!$stm->execute()){
                    die('Error');
                }
                $page=1;
                if(isset($_GET['page'])){
                    $page=$_GET['page'];
                }
                header('Location: ../admin?page='.$page);
                exit(); 
           }
           
}