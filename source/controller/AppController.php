<?php 
    class AppController extends BaseController{
           public function getall(){
               
           }  

           public function observe(){
              $rating = 0;
              $comment = "";

              if(isset($_POST['rating']) && isset($_POST['comment'])){
                    $comment = $_POST['comment'];        
                    $rating = $_POST['rating'];        
              }

              echo "User comment:". $comment;
              echo "User rating:". $rating;
           } 

           public function download(){
              $error ='';

              if (!isset($_SESSION['user'])) {
                    header('Location: account/login');
                    exit();
              }
              if(empty($_GET['id'])){
                     $error ='Vui lòng cung cấp tên tập tin.';
                     die($error);
              }

              $name = $_GET['id'].'.zip';
              $fileDir = dirname(__DIR__, 1).'/files/';
              $filePath = $fileDir.$name;
              
              if(!file_exists($filePath)){
                     $error = "Tập tin không tồn tại.";
                     die($error);
              }

              header('Content-Description: File Transfer');
              header('Content-Type: application/octet-stream');
              header('Content-Disposition: attachment; filename="'.basename($filePath).'"');
              header('Expires: 0');
              header('Cache-Control: must-revalidate');
              header('Pragma: public');
              header('Content-Length: ' . filesize($filePath));
              flush(); // Flush system output buffer
              readfile($filePath);

              
                  
           }

           public function index(){
              if (!isset($_SESSION['user'])) {
                    header('Location: account/login');
                    exit();
              }

              $model = new AppModel();
              $loadData = $this->load();
              
              $hello = $loadData['hello'];
              $genresOut = $loadData['genresOut'];
              
              if(isset($_GET['genre']) && isset($_GET['id'])){
                     $data = array('title' => 'Error Page', 'message'=> 'Nội dung bạn tìm không khả dụng.');
                     $this->render('../home/error.html', $data); 
              }else
              
              if(isset($_GET['id'])){
                     $id = $_GET['id'];
                     $appDetail = $model->get_by_id($id)['data'][0];
                     $sameApp = $model->get_by_genre($appDetail['genre'])['data'];
                     $sameDev = $model->get_by_dev($appDetail['dev'])['data'];

                     $appDetail['imglist']= json_decode($appDetail['imglist'], true);
                     $data = array('title' => $appDetail['title'], 
                                   'appDetail' => $appDetail,
                                   'genres' =>$genresOut,
                                   'hello' =>$hello,
                                   'sameApp' =>$sameApp,
                                   'sameDev'=>$sameDev,
                                   // 'controller' => 'app',
                                   );
                     $this->render('appDetail.html', $data);       
              }else if(isset($_GET['genre'])){
                     $model = new AppModel();

                     $genre = $_GET['genre'];
                     $apps = $model->get_by_genre($genre)['data'];
                     
                     $data = array('title' => $genre." Games", 
                                          'apps' => $apps,
                                          'genre' =>$genre,
                                          'genres' =>$genresOut,
                                          'hello' =>$hello,
                                          'filter'=>"theo thể loại",
                                          // 'controller' => 'app',
                                   );
                     $this->render('appsbyGenre.html', $data);         
              }else if(isset($_GET['search'])){
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
                                   // 'controller' => 'app',
                            );
                     $this->render('searchResult.html', $data);
              }else if(isset($_GET['filter'])){
                     $filter = $_GET['filter'];
                     
                     if($filter == "top ranking"){
                            $apps = $model->get_popular_games()['data'];
                            $error='';
                            
                            $data = array('title' => 'Search Result', 
                                          'apps'=> $apps,
                                          'genres' => $genresOut,
                                          'filter'=>"có lượt tải cao nhất",
                                          'hello' =>$hello,
                                          'error'=>$error,
                                          // 'controller' => 'app',
                                   );
                            $this->render('searchResult.html', $data);    
                     }

                     if($filter == "new"){
                            $apps = $model->get_new_games()['data'];
                            $error='';
                            
                            $data = array('title' => 'Search Result', 
                                          'apps'=> $apps,
                                          'genres' => $genresOut,
                                          'filter'=>"mới nhất",
                                          'hello' =>$hello,
                                          'error'=>$error,
                                          // 'controller' => 'app',
                                   );
                            $this->render('searchResult.html', $data);    
                     }

                     if($filter == "updated"){
                            $apps = $model->get_updated_games()['data'];
                            $error='';
                            
                            $data = array('title' => 'Search Result', 
                                          'apps'=> $apps,
                                          'genres' => $genresOut,
                                          'filter'=>"mới được cập nhật",
                                          'hello' =>$hello,
                                          'error'=>$error,
                                          // 'controller' => 'app',
                                   );
                            $this->render('searchResult.html', $data);    
                     }
                     
              }
              else if(isset($_GET['dev'])){
                    $dev = $_GET['dev'];
                    $apps = $model->get_by_dev($dev)['data'];
                            $error='';
                            
                            $data = array('title' => 'Search Result', 
                                          'apps'=> $apps,
                                          'genres' => $genresOut,
                                          'filter'=>"của nhà phát triển ",
                                          'genre' => $dev,
                                          'hello' =>$hello,
                                          'error'=>$error,
                                          // 'controller' => 'app',
                                   );
                            $this->render('searchResult.html', $data); 
              }
              else{
                     $model = new AppModel();
                     $apps = $model->get_all()['data'];
                     $data = array('title' => 'All games', 
                                                 'apps'=> $apps, 
                                                 'genre'=>'của Yalp', 
                                                 'hello' =>$hello,
                                   'genres' => $genresOut,
                            );
                     $this->render('app-index.html', $data); 
              }
                     
           }
    }