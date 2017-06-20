<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class OfficeController extends AppController {

    public $name = 'Office';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource');
    public $layout = 'blank';
    public $components = array('Cookie');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

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
    public function apply($pages = 1) {
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where del=0 and  project_approver_id=' . $this->userInfo->id);
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll(array('del' => 0, 'project_approver_id' => $this->userInfo->id), $limit, $pages);
        }

        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
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
        $lists = array();
        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where code=0 and del=0');
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll(array('code' => 0, 'del' => 0), $limit, $pages);
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
    public function my_approval($pages = 1) {
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where del=0 and  project_approver_id=' . $this->userInfo->id);
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll(array('del' => 0, 'project_approver_id' => $this->userInfo->id), $limit, $pages);
        }

        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 系统消息
     */
    public function system_message() {
        $this->render();
    }

    /**
     * 审批
     */
    public function ajax_approve() {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $pid = $this->request->data('p_id');
            $remarks = $this->request->data('remarks');
            $type = $this->request->data('type');
            $approve_id = $this->userInfo->id;
            if (!$this->ResearchProject->query('select * from t_research_project where id=' . $pid . ' and code=0 and del=0')) {
                //有可能是不存在，也有可能是已经审批
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '参数有误，请重新再试';
                echo json_encode($this->ret_arr);
                exit;
            }
            $save_arr = array(
                'project_approver_remarks' => $remarks,
                'project_approver_id' => $approve_id,
                'code' => $type == 1 ? $type : 2
            );
            if ($this->ResearchProject->edit($pid, $save_arr)) {
                //成功
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '审批成功';
                echo json_encode($this->ret_arr);
                exit;
            } else {
                //失败
                $this->ret_arr['code'] = 2;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
            }
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            exit;
        }
    }

}
