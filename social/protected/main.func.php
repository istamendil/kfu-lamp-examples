<?php

//init system
function init(){
  //load framework
  loadFramework();
  
  //get routing dir
  $requestString = str_replace(SOCIAL_SITE_SUBPATH, '', $_SERVER['REQUEST_URI']);
  
  //Route for request URL
  route($requestString);
}

function loadFramework(){
  require_once(SOCIAL_SYSTEM_PATH.'/config.php');
  require_once(SOCIAL_SYSTEM_PATH.'/usefull.func.php');
  require_once(SOCIAL_SYSTEM_PATH.'/model.func.php');
}

//Find and call required controller
function route($requestString){
  $urlArray = parse_url($requestString);
  $pathArray = explode('/', $urlArray['path']);
  unset($pathArray[0]);
  if(empty($pathArray[1])){
    route(SOCIAL_DEFAULT_PATH.(isset($urlArray['query'])?('?'.$urlArray['query']):''));
  }
  else{
    $controllerName = str_replace('.', '', $pathArray[1]);
    if(!empty($controllerName) && file_exists(SOCIAL_SYSTEM_PATH.'/Controllers/'.$controllerName.'.controller.php')){
      require_once(SOCIAL_SYSTEM_PATH.'/Controllers/'.$controllerName.'.controller.php');
      $methodName = empty($pathArray[2]) ? 'index' : $pathArray[2];
      if(function_exists('controller_'.$controllerName.'_'.$methodName)){
        $viewData = call_user_func_array('controller_'.$controllerName.'_'.$methodName, array_slice($pathArray, 2));
        if(!is_array($viewData)){
          $viewData = array();
        }
        show_view($controllerName, $methodName, $viewData);
      }
      else{
        return404();
      }
    }
    else{
      return404();
    }
  }
}
function generate_path($controllerName, $methodName, $data){
  return SOCIAL_SITE_SUBPATH.'/'.$controllerName.'/'.$methodName.'/'.implode('/', $data);
}

//show view
function show_view($controllerName, $methodName, $data){
  if(file_exists(SOCIAL_SYSTEM_PATH.'/Views/'.$controllerName.'_'.$methodName.'.view.php')){
    require SOCIAL_SYSTEM_PATH.'/Views/'.$controllerName.'_'.$methodName.'.view.php';
  }
  else{
    return500();
  }
}

function return404($message = '404. Page was not found.'){
  echo '<h3 style="color:#161">'.$message.'</h3>';
}
function return500($message = 'Ошибка сервера'){
  echo '<h3 style="color:#611">'.$message.'</h3>';
}