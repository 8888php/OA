<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class OfficeController extends AppController {

    public $name = 'Office';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource');
    public $layout = 'blank';
    public $components = array('Cookie');

    public function index() {
        
    }

    /**
     * 起草申请
     */
    public function draf() {
        $this->render();
    }

    /**
     * 我的申请
     */
    public function apply() {
        $this->render();
    }

    /**
     * 待我审批
     */
    public function wait_approval() {

        $lists = $this->ResearchProject->getAll($conditions = array('code' => 0));
        $this->set('lists', $lists);
        $this->render();
    }

    /**
     * 经我审批
     */
    public function my_approval() {
        $this->render();
    }

    /**
     * 系统消息
     */
    public function system_message() {
        $this->render();
    }

}
