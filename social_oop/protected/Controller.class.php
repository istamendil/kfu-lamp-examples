<?php

//namespace MvcOop;

/**
 *  System base controller
 */
class Controller {

  private $core;
  
  /**
   * Initialize everything
   */
  public function __construct($core) {
    $this->core = $core;
  }
  
  public function __toString(){
    return get_class($this);
  }

}