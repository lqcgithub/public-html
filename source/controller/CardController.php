<?php 
    // session_start(); 
    class CardController extends BaseController{
             
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
                
                $model = new CardModel();
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
               $this->render('../admin/card-management.html', $data);
           }
            public function open_database(){
                        $conn = new mysqli('127.0.0.1', 'root', '', 'finalprojectdb');
                        if($conn->connect_error){
                            die('Connect error: '.$conn->connect_error);
                        }
                        return $conn;
                }
            public function add(){
               function add($seri, $code, $nhaphanphoi){
                function open_database(){
                        $conn = new mysqli('127.0.0.1', 'root', '', 'finalprojectdb');
                        if($conn->connect_error){
                            die('Connect error: '.$conn->connect_error);
                        }
                        return $conn;
                }
                $sql = 'insert into cards(seri, code, nhaphanphoi) values(?,?,?)';

                $conn = open_database();
                $stm = $conn->prepare($sql);

                $stm->bind_param('iis',$seri, $code, $nhaphanphoi);
                if(!$stm->execute()){
                    return array('code' => 2, 'error'=> 'Can not execute');
                }
                return array('code' => 0, 'error'=> 'Successful');
            }
            $error = '';
            $seri = 1;
            $code = 1;
            $nhaphanphoi= '';
            
            

            if (isset($_POST['seri']) && isset($_POST['code']) && isset($_POST['nhaphanphoi']))
            
            {
                
                $seri = $_POST['seri'];
                $code = $_POST['code'];
                $nhaphanphoi = $_POST['nhaphanphoi'];

                if (empty($seri)) {
                    $error = 'Please enter your seri';
                }
                else if (empty($code)) {
                    $error = 'Please enter your code';
                }
                else if (empty($nhaphanphoi)) {
                    $error = 'Please enter your provider';
                }
                
                else {
                    $result = add($seri, $code, $nhaphanphoi);
                    if($result['code'] == 0){
                        $page=1;
                        if(isset($_GET['page'])){
                            $page=$_GET['page'];
                        }
                        header('Location: ../card?page='.$page);
                        exit();
                    }else if($result['code'] == 1){
                        $error = 'Error';
                    }else{
                        $error = 'An error occured. Please try again later.';
                    }
                }

            }
            $data = array('title' => 'Register', 'error' => $error);
            $this->render('../admin/add-card.html', $data);
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
                header('Location: ../card?page='.$page);
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

                
                $sql = "delete from cards where id=?";
                
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
                header('Location: ../card?page='.$page);
           }
           
}