<?php

App::uses('AppController', 'Controller');
/* 科研项目 */

class ResearchprojectController extends AppController {

    public $name = 'Researchproject';

    //var $uses=array('SysMenus'); 
    /* 左 */

    /**
     * 列表
     */
    public function index() {
        $this->render();
    }

    /**
     * 添加/修改
     */
    public function add() {
        $this->render();
    }

}
