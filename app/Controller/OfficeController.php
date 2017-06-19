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
    public function wait_approval($pages = 1) {
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $total = $this->ResearchProject->find('count');
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll(array('code' => 0), $limit,$pages);
        }

        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
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
