<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class OfficeController extends AppController {

    public $name = 'Office';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource','ProjectMember', 'ApplyMain', 'ApplyBaoxiaohuizong');
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
     * 我的申请 项目
     */
    public function apply_project_list($pages = 1) {
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
     * 我的申请 申请
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
     * 待我审批 项目
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
     * 待我审批 申请
     */
    public function wait_approval_apply($pages = 1) {
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
     * 经我审批 项目
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
     * 经我审批 申请
     */
    public function my_approval_apply($pages = 1) {
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

    
    
    /**
     * 审核详情
     */
    public function apply_project($pid) {
        if (empty($pid)) {
            // header("Location:/homes/index");die;
        }
        $this->set('costList',Configure::read('keyanlist'));
        $this->set('pid', $pid);

        $pinfos = $this->ResearchProject->findById($pid);
        $pinfos = @$pinfos['ResearchProject'];
        $source = $this->ResearchSource->getAll($pid);

        $members = $this->ProjectMember->getList($pid);
        
        $cost = $this->ResearchCost->findByProjectId($pid);
        $cost = @$cost['ResearchCost'];

        $this->set('cost', $cost);

        $this->set('pinfos', $pinfos);
        $this->set('members', $members);
        $this->set('source', $source);
        
        $this->render();
    }
    
    /**
     * 报销单待审批
     */
    public function reimbursement($pages = 1) {
        $type = 1;
        //取出当前用户的职务
        $code = $this->get_reimbursement_code();

        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        $total =  $this->ApplyMain->query("select count(*) count from t_apply_main ApplyMain where `type`='$type' and code='$code'");
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ApplyMain->query("select * from t_apply_main ApplyMain where `type`='$type' and code='$code' limit ". ($pages -1) . "," . $limit);
        }
        
        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }
    
    /***
     * 根据当前用户的角色 返回报销单所要审批的code 当$filed为true时，返回要更改的字段
     */
    private function get_reimbursement_code($filed = false) {
        //根据当前帐号，返回不同的code
        $position_id = $this->userInfo->position_id;
        
        $code = -1;//默认取不到数据
        $return_filed = 0;//默认错误
        switch ($position_id) {
            case 11;
                //财务科长
                $code = 12;
                $return_filed = 'financial_officer_id';
                break;
            case 7:
                //财务
                $code = 10;
                $return_filed = 'charge_finance_id';
                break;
            case 6:
                //所长
                $code = 8;
                $return_filed = 'director_id';
                break;
            case 5:
                //分管副所长
                $code = 6;
                $return_filed = 'charge_id';
                break;
            case 4:
                //科室主任
                $code = 4;
                $return_filed = 'head_department_id';
                break;
            case 2:
                //项目负责人
                $code = 0;
                $return_filed = 'project_leader_id';
                break;
            default :
                break;
        }
        if (!$filed){
            return $code;
        }
        return $return_filed;
        
    }
    /**
     * 报销单审核详情
     */
    public function apply_project_reimbursement($main_id=0) {
        if (empty($main_id)) {
            // header("Location:/homes/index");die;
        }
        //根据main_id取出数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $attr_id = @$main_arr['ApplyMain']['attr_id'];
        $attr_arr = $this->ApplyBaoxiaohuizong->findById($attr_id);
        $this->set('main_arr', @$main_arr);
        $this->set('attr_arr', @$attr_arr);
        $this->render();
    }
    /**
     * 报销单审批
     */
    public function ajax_approve_reimbursement() {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $main_id = $this->request->data('main_id');
            $remarks = $this->request->data('remarks');
            $type = $this->request->data('type');
            $approve_id = $this->userInfo->id;
            $code = $this->request->data('code');
            //$tmp_code 表示之前的code状态，
            if ($type == 1) {
                //拒绝
                $tmp_code = $code -1;
            } else {
                $tmp_code = $code -2;
            }
            //先到表里查看一下，他之前的状态是否发生变化    $filed是取出应该改到哪个字段里
        if (!$this->ApplyMain->findByCode($tmp_code) || !($filed = $this->get_reimbursement_code(true))) {
                //有可能是不存在，也有可能是已经审批
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '参数有误，请重新再试';
                echo json_encode($this->ret_arr);
                exit;
            }
            //根据user_id获取当前审批所用的字段
            
            $save_arr = array(
                $filed => $approve_id,
                'code' => $code
            );
            if ($this->ApplyMain->edit($main_id, $save_arr)) {
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