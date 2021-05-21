<?php
    require_once('vendor/autoload.php');
    
    if(empty($_GET['controller']) && empty($_GET['action'])){
        $controller = 'home';
        $action = 'index';
    }
    
    else if(!empty($_GET['controller'])){
        $controller = $_GET['controller'];
        if(!empty($_GET['action'])){
            $action = $_GET['action'];
        }else{
            $action = 'index';
        }
    }

    $controller = ucfirst($controller)."Controller";
    
    if(!class_exists($controller)){
        $controller = 'HomeController';
        $action = 'error';
    }

    $obj = new $controller();
    if(!method_exists($obj, $action)){
        $obj = new HomeController();
        $action = 'error';
    }
    
    $obj->$action();
?>
