<?php 
    // session_start(); 
    class HomeController extends BaseController{
              
           public function error(){
               $data = array('title' => 'Error Page', 'message'=> 'Nội dung bạn tìm không khả dụng.');
               $this->render('error.html', $data);
           } 

           public function index(){
                
                if (isset($_SESSION['user'])) {
                    $hello = $_SESSION['name'];
                }else{
                    header('Location: account/login');
                    exit();
                }
            

                $model = new AppModel();
                $appsUpdate = $model->get_updated_games()['data'];
                $appsUpdate = array_slice($appsUpdate, 0, 6);

                $appsNew = $model->get_new_games()['data'];
                $appsNew = array_slice($appsNew, 0, 6);

                $appsPopular = $model->get_popular_games()['data'];
                $appsPopular = array_slice($appsPopular, 0, 6);
                
                $loadData = $this->load();
                $hello = $loadData['hello'];
                $genresOut = $loadData['genresOut'];

                if(isset($_GET['search'])){
                     $controller = new AppController();
                     $search = $_GET['search'];
                     $apps = $this->search($search);
                     $error='';
                     if(empty($apps)){
                            $error = 'Không tìm thấy ứng dụng nào. Vui lòng tìm theo từ khóa khác.';
                     }
                     $data = array('title' => 'Search Result', 
                                   'apps'=> $apps,
                                   'genres' => $genresOut,
                                   'filter'=>"theo từ khóa",
                                   'hello' =>$hello,
                                   'genre'=>"\"$search\"",
                                   'error'=>$error,
                                   'controller' => 'app',
                            );
                    $controller->render('searchResult.html', $data);
                    exit();        
                    }
                  
               $data = array('title' => 'Homepage', 
                                'appsUpdate'=> $appsUpdate,
                                'appsNew'=>$appsNew,
                                'appsPopular'=>$appsPopular,
                                'hello'=>$hello,
                                'genres'=>$genresOut,
                                'controller'=>'home',
                            );
               $this->render('index.html', $data);
           }
    }