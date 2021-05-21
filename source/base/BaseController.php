<?php 
    session_start();
    class BaseController {

        private $twig;

        function __construct(){
            $loader = new \Twig\Loader\FilesystemLoader('source/view');
            $this->twig = new \Twig\Environment($loader);    
        }

        protected function render($view_name, $data=array()){
            $view_folder = get_called_class();
            $view_folder = str_replace('Controller', '', $view_folder);
            $view_folder = strtolower($view_folder);

            $view_path = "$view_folder/$view_name";
            echo $this->twig->render($view_path, $data);
        }

        public function search($string){
            $model = new AppModel();
            $apps = $model->search_app($string)['data'];
            
            return $apps;
        }

        public function load(){
            
            $hello ='';
            if (isset($_SESSION['user'])) {
                $hello = $_SESSION['name'];
            }

            $model = new AppModel();
            $genres= $model->get_genres()['data'];
            $genresOut = array();
            foreach($genres as $genre){
                array_push($genresOut, $genre['genre']);
            }

            $data=array('hello'=>$hello,
                        'genresOut'=>$genresOut,
            );
            return $data;
        }

        public function index(){
            echo "Index working on ".get_called_class();
        }
        
    }
?>