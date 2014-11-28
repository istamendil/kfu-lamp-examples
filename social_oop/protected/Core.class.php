<?php

//namespace MvcOop;

/**
 *  System core class
 */
class Core {

  /**
   *
   * @var MvcOop\Core Instance of main class - it is singlton 
   */
  private static $_instance;
  
  // Should header and footer be included?
  const VIEW_FULL = 1;
  const VIEW_HTML = 2;
  private $viewType;
  
  /**
   *
   * @var Array Config array
   */
  private $config = array();
  
  // Core
  
  private $viewData = array();

  /**
   * Load framework, route to relevant controller
   */
  private function __construct() {
    
    // Load config and update dynamic values
    $config = file_get_contents(SOCIAL_SYSTEM_PATH . '/config.json');
    if(!$config){
      exit("Can't open config file.");
    }
    $config = json_decode($config);
    if(!isset($config->site_path)){
      exit("Configuration value site_path must be defined.");
    }
    $changeFrom = array('%SYSTEM_PATH%', '%SITE_PATH%');
    $changeTo   = array(SOCIAL_SYSTEM_PATH, $config->site_path);
    foreach($config as $name => $value){
      $config->$name = str_replace($changeFrom, $changeTo, $value);
    }
    $this->config = $config;
    
    // View type
    $this->viewType = self::VIEW_FULL;
    
    // LOAD FRAMEWORK
     
    // Load everything we need
    require_once(SOCIAL_SYSTEM_PATH . '/usefull.func.php');     // Some usefull functions (Functions are relevant too sometimes)
    require_once(SOCIAL_SYSTEM_PATH . '/Controller.class.php'); //Base controller class
    require_once(SOCIAL_SYSTEM_PATH . '/model.func.php');       //ToDo: make proper OOP models
    
    // START SESSION AND CHECK IF USER HAS BEEN LOGGED IN
    session_start();
    
    // ROUTE TO RELEVANT CONTROLLER
    //Route for request URL
    $this->route($_SERVER['REQUEST_URI']);
  }
  
  /**
   * Get some configuration parameter
   * 
   * @param string $name Name of parameter
   * @return mixed|NULL Value of parameter
   */
  public function get($name){
    $value = NULL;
    if(isset($this->config->$name)){
      $value = $this->config->$name;
;    }
    return $value;
  }
  
  // ROUTING
  
  /**
   * Find and call required controller
   */
  public function route($requestString) {
    // Delete some unnecessary path startings
    $requestString = str_replace($this->get('site_path'), '', $requestString);
    // Parse URL etc
    $urlArray = parse_url($requestString);
    $pathArray = explode('/', $urlArray['path']);
    unset($pathArray[0]);
    if (empty($pathArray[1])) {
      $this->route($this->get('default_path') . (isset($urlArray['query']) ? ('?' . $urlArray['query']) : ''));
    } else {
      $controllerName     = str_replace('.', '', $pathArray[1]);
      $controllerFullName = ucfirst($controllerName).'Controller';
      if (!empty($controllerName) && file_exists(SOCIAL_SYSTEM_PATH . '/Controllers/' . $controllerFullName . '.class.php')) {
        require_once(SOCIAL_SYSTEM_PATH . '/Controllers/' . $controllerFullName . '.class.php');
        $methodName     = empty($pathArray[2]) ? 'index' : $pathArray[2];
        $methodFullName = $methodName.'Action';
        $controller = new $controllerFullName($this);
        if (method_exists($controllerName.'Controller', $methodName.'Action')) {
          $this->viewData = call_user_func_array(array($controller, $methodFullName), array_slice($pathArray, 2));
          if (!is_array($this->viewData)) {
            $this->return500("Cotroller has to return an array for view data.");
          }
          // AJAX?
          if(!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest' && $this->viewType !== self::VIEW_HTML ){
            // Then JSON
            echo json_encode($this->viewData);
          }
          else{
            // Simpel template
            $this->show_view($controllerName, $methodName);
          }
        } else {
          $this->return404("No such action.");
        }
      } else {
        $this->return404("No such controller.");
      }
    }
  }
  
  /**
   * Load another cotroller
   */
  public function forward($controller, $action, $params){
    $this->route($this->generate_path($controller, $action, $params));
    exit();
  }
  /**
   * Send 302 and location header
   */
  public function redirect($controller, $action, $params){
    $url = $this->generate_path($controller, $action, $params);
    header('Location: '.$url);
    exit();
  }
  
  /**
   * Generate link paths
   */
  public function generate_path($controller, $method, $data = array()) {
    $url = $this->get('site_path') . '/' . $controller . '/';
    if( $method !== 'index' || count($data) !== 0 ){
      $url .= $method . '/' . implode('/', $data);
    }
    return $url;
  }
  
  public function return404($message = 'Page was not found.') {
    http_response_code(404);
    echo '<h3 style="color:#161">404. ' . $message . '</h3>';
    exit();
  }

  public function return500($message = 'Unkown error.') {
    http_response_code(500);
    echo '<h3 style="color:#611">500. Server error. ' . $message . '</h3>';
    exit();
  }
  
  // VIEW
  
  /**
   * Show view
   */
  private function show_view($controllerName, $methodName) {
    if (file_exists(SOCIAL_SYSTEM_PATH . '/Views/' . $controllerName . '_' . $methodName . '.view.php')) {
      if($this->viewType === self::VIEW_FULL){
        require SOCIAL_SYSTEM_PATH . '/Views/_header.php';
      }
      require SOCIAL_SYSTEM_PATH . '/Views/' . $controllerName . '_' . $methodName . '.view.php';
      if($this->viewType === self::VIEW_FULL){
        require SOCIAL_SYSTEM_PATH . '/Views/_footer.php';
      }
    } else {
      $this->return500("No such view file.");
    }
  }
  private function include_view($name){
    if (file_exists(SOCIAL_SYSTEM_PATH . '/Views/_' . $name . '.view.php')) {
      require SOCIAL_SYSTEM_PATH . '/Views/_' . $name . '.view.php';
    } else {
      $this->return500("No such view file.");
    }
  }
  private function getViewData($name){
    $data = NULL;
    if(isset($this->viewData[$name])){
      $data = $this->viewData[$name];
    }
    return $data;
  }
  public function getUserData($name){
    $data = NULL;
    if(isset($_SESSION['user'][$name])){
      $data = $_SESSION['user'][$name];
    }
    return $data;
  }
  public function getUserFullData(){
    $data = NULL;
    if(isset($_SESSION['user'])){
      $data = $_SESSION['user'];
    }
    return $data;
  }
  public function isLogged(){
    return ($this->getUserData('id') !== NULL);
  }
  public function logout(){
    $_SESSION['user'] = NULL;
  }
  
  public function setViewType($type){
    if(in_array($type, array(self::VIEW_FULL, self::VIEW_HTML))){
      $this->viewType = $type;
    }
    else{
      trigger_error("Unsuported View type value.", E_USER_ERROR);
    }
  }

  
  // OTHERS
  
  // Singleton
  
  // Get the only one object
  public static function getInstance() {
    if (null === self::$_instance) {
      // The very first instance
      self::$_instance = new self();
    }
    return self::$_instance;
  }
  // Singltone object can't be cloned, it has to have 1 instance
  private function __clone() {
    
  }

}