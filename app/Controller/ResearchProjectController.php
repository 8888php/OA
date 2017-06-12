<?php

App::uses('ResearchProjectController', 'AppController');
/* 科研项目 */

class ResearchProjectController extends AppController {

    public $name = 'ResearchProject';
    public $uses = array('ResearchProject');
    public $layout = 'blank';

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
