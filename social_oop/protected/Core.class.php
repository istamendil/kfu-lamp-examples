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
  
  /**
   *
   * @var Array Config array
   */
  private $config = array(
      'site_subpath' => '/social_oop',
      'default_path' => '/social_oop/main/index',
  );
  
  // Core
  
  private $viewData = array();

  /**
   * Load framework, route to relevant controller
   */
  private function __construct() {
    
    // We can load config from external file to $this->config here
    
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
    if(isset($this->config[$name])){
      $value = $this->config[$name];
    }
    return $value;
  }
  
  // ROUTING
  
  /**
   * Find and call required controller
   */
  public function route($requestString) {
    // Delete some unnecessary path startings
    $requestString = str_replace($this->get('site_subpath'), '', $requestString);
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
          $this->show_view($controllerName, $methodName);
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
  public function generate_path($controllerName, $methodName, $data) {
    return $this->get('site_subpath') . '/' . $controllerName . '/' . $methodName . '/' . implode('/', $data);
  }
  
  public function return404($message = 'Page was not found.') {
    echo '<h3 style="color:#161">404. ' . $message . '</h3>';
    exit();
  }

  public function return500($message = 'Unkown error.') {
    echo '<h3 style="color:#611">500. Server error. ' . $message . '</h3>';
    exit();
  }
  
  // VIEW
  
  /**
   * Show view
   */
  private function show_view($controllerName, $methodName) {
    if (file_exists(SOCIAL_SYSTEM_PATH . '/Views/' . $controllerName . '_' . $methodName . '.view.php')) {
      require SOCIAL_SYSTEM_PATH . '/Views/_header.php';
      require SOCIAL_SYSTEM_PATH . '/Views/' . $controllerName . '_' . $methodName . '.view.php';
      require SOCIAL_SYSTEM_PATH . '/Views/_footer.php';
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
    $this->redirect('main', 'index', array());
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