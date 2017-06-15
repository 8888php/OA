<?php

App::uses('ResearchProjectController', 'AppController');
/* 科研项目 */

class ResearchProjectController extends AppController {

    public $name = 'ResearchProject';
    public $uses = array('ResearchProject');
    public $layout = 'blank';
    public $components = array('Cookie');  

    /**
     * 详情
     */
    public function index() {
        $this->render();
    }

    /**
     * 添加 项目详情
     */
    public function step1() {
        $this->render();
    }

    /**
     * 添加 任务书
     */
    public function step2() {
        $this->render();
    }
    
    /**
     * 添加 项目费用
     */
    public function step3() {
        $this->render();
    }
    
    
    /**
     * 把数据存入到cookie里
     */
    public function ajax_cookie() {
        
    }
    
    
    
    
    
    
    
    
    
    
}
