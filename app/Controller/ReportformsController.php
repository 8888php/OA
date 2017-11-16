<?php

App::uses('AppController', 'Controller');
/* 汇总报表 */

class ReportformsController extends AppController {

    public $name = 'Reportforms';
    //var $uses=array('User'); 
    /* 左 */
    
    public function index() {
        $this->layout = 'blank';
       // var_dump( $this->appdata );
        $this->render();
 
    }

   

}
