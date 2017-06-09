<?php

App::uses('AppController', 'Controller');
/* 党政部门 */

class DepartmentController extends AppController {

    public $name = 'Department';
    public $uses=array('User'); 
    public $layout = 'blank';
    /* 左 */
    
    
    /**
     * 部门管理
     */
    public function index() {

        $this->render();
    }

    /**
     * 部门编辑
     */
    public function add() {

        $this->render();
    }


   

}
