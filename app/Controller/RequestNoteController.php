<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class RequestNoteController extends AppController {

    public $name = 'RequestNote';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource','ProjectMember', 'ApplyMain', 'ApplyBaoxiaohuizong', 'ApprovalInformation','Department', 'ApplyPaidleave', 'ChailvfeiSqd', 'ApplyJiekuandan');

    public $layout = 'blank';
    public $components = array('Cookie', 'Approval');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

   /**
     * 公共方法
     */  
    
    // 项目下所属资源文件
    public function getsource(){
        if(empty($_POST['pd'])){
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $sourcelist = $sourceArr = array();
        $sourcelist =  $this->ResearchSource->getAll($_POST['pd']);
        foreach($sourcelist as $k => $v){
            $sourceArr[$v['ResearchSource']['id']] = $v['ResearchSource'];
        }
       
        if(empty($sourceArr)){
            $this->ret_arr['msg'] = '无文件数据';
            exit(json_encode($this->ret_arr));
        }else{
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = $sourceArr;
            exit(json_encode($this->ret_arr));
        }
        
    }
    
    
    
    /**
     * 科研项目费用报销
     */
    
    //汇总报销申批单
    public function huizongbaoxiao() {
        
        if ($this->request->is('ajax')) {
            $this->sub_declares($_POST);
        }else{
        //当前用户所属项目
        $conditions = array('user_id'=>$this->userInfo->id);
        $projectArr = $this->ResearchProject->getlist($conditions);
        
        $this->set('projectArr', $projectArr);
        $this->set('list', Configure::read('keyanlist'));
        
        $this->render();
        }
        
    }

     // 添加 汇总报销申批单
    private function sub_declares($datas) {
        if (empty($datas['ctime']) || empty($datas['page_number']) || empty($datas['projectname']) || empty($datas['filenumber']) || empty($datas['subject']) || empty($datas['rmb_capital']) || empty($datas['amount'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_baoxiaohuizong';

        $type = Configure::read('type_number');//行政费用
        $type = $type[0];
        $ret_arr = $this->get_create_approval_process_by_table_name($table_name,$type, $this->userInfo->department_id);

        if ($ret_arr[$this->code] == 1) {
            $this->ret_arr['msg'] = $ret_arr[$this->msg];
            exit(json_encode($this->ret_arr));
        }
        #附表入库
        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['page_number'] = $datas['page_number'];
        $attrArr['department_id'] = $datas['page_number'];
        $attrArr['department_name'] = $datas['page_number'];
        $attrArr['project_id'] = $datas['projectname'];
        $attrArr['subject'] = $datas['subject'];
        $attrArr['rmb_capital'] = $datas['rmb_capital'];
        $attrArr['amount'] = $datas['amount'];
        $attrArr['description'] = $datas['description'];
        $attrArr['user_id'] = $this->userInfo->id;

        # 开始入库
        $this->ApplyBaoxiaohuizong->begin();
        $attrId = $this->ApplyBaoxiaohuizong->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr[$this->res]['next_approver_id'];//下一个审批职务的id
        $mainArr['code'] = $ret_arr[$this->res]['approve_code'];//当前单子审批的状态码
        $mainArr['approval_process_id'] = $ret_arr[$this->res]['approval_process_id']; //审批流程id
        $mainArr['type'] = $type; 
        $mainArr['name'] = $datas['declarename'];
        $mainArr['project_id'] = $datas['projectname'];
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['attr_id'] = $attrId;
        $mainArr['ctime'] = $datas['ctime'];
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyBaoxiaohuizong->rollback();
        }
        $mainId ? $commitId = $this->ApplyBaoxiaohuizong->rollback() : $commitId = $this->ApplyBaoxiaohuizong->commit();


        if ($commitId) {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '申请成功';
        } else {
            $this->ret_arr['msg'] = '申请失败';
        }


        echo json_encode($this->ret_arr);
        exit;
    }
  
    
    
    /**
     * 行政部门费用报销
     */
   
    // 果树所出差审批单
     public function gss_evection() {
         
        if ($this->request->is('ajax')) {
            $this->gss_evection_save($_POST);
        }else{
        //当前用户所属项目
        $projectArr = $this->Department->findById($this->userInfo->department_id);
        $projectArr =  $projectArr['Department'];

        $this->set('projectArr', $projectArr);
        $this->set('list', Configure::read('xizhenglist'));
        
        $this->render();
        }
    }

   
       
    // 果树所借款单
     public function gss_loan() {
         
        if ($this->request->is('ajax')) {
            $this->gss_loan_save($_POST);
        }else{
        
        $this->render();
        }
    }
 
           
    // 果树所领款单
     public function gss_draw_money() {
         
        if ($this->request->is('ajax')) {
            $this->sub_declares($_POST);
        }else{
        
        $this->render();
        }
    }
    
    
    
               
    // 果树所职工带薪年审批单
     public function gss_furlough() {
         
        if ($this->request->is('ajax')) {
            $this->gss_furlough_save($_POST);
        }else{
        
        $this->render();
        }
    }
    
    //果树所职工带薪年审批单保存
    private function gss_furlough_save($datas) {
        if (empty($datas['company']) || empty($datas['start_work']) 
                || empty($datas['years']) || empty($datas['vacation_days']) 
                || empty($datas['start_time']) || empty($datas['end_time'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 't_apply_paidleave';
        $p_id = 3;//审批流id
        $project_id = 0;
        $type = 0;//类型暂定为0
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id);
        
//        $ret_arr = $this->get_create_approval_process_by_table_name($table_name,$type, $this->userInfo->department_id);
//
//        if ($ret_arr[$this->code] == 1) {
//            $this->ret_arr['msg'] = $ret_arr[$this->msg];
//            exit(json_encode($this->ret_arr));
//        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人
        
        $attrArr = array();
        $attrArr['company'] = $datas['company'];
        $attrArr['start_work'] = $datas['start_work'];

        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['vacation_days'] = $datas['vacation_days'];
        $attrArr['yx_vacation_days'] = $datas['yx_vacation_days'];
        $attrArr['start_time'] = $datas['start_time'];
        $attrArr['end_time'] = $datas['end_time'];
        $attrArr['total_days'] = $datas['total_days'];
        $attrArr['years'] = $datas['years'];
        $attrArr['grsq'] = $datas['grsq'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());
        
        # 开始入库
        $this->ApplyPaidleave->begin();
        $attrId = $this->ApplyPaidleave->add($attrArr);
        
        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id'];//下一个审批职务的id
        $mainArr['code'] = $ret_arr['code'];//当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type; 
        $mainArr['attachment'] = ''; 
        $mainArr['name'] = '果树所职工带薪年休假审批单';
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;        
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['total'] = 0;
        $mainArr['attr_id'] = $attrId;
        $mainArr['project_user_id'] = 0;
        $mainArr['project_team_user_id'] = 0;
        $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        $mainArr['ctime'] = date('Y-m-d H:i:s', time());
        $mainArr['subject'] = '';
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyPaidleave->rollback();
        }
        $mainId ? $commitId = $this->ApplyPaidleave->rollback() : $commitId = $this->ApplyPaidleave->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id']) ) {
                foreach ($ret_arr['code_id'] as $k=>$v) {
                    if ($v == $this->userInfo->id) {
                        $save_approve = array(
                            'main_id' => $mainId,
                            'position_id' => $this->userInfo->position_id,
                            'approve_id' => $this->userInfo->id,
                            'remarks' => '',
                            'name' => $this->userInfo->name,
                            'ctime' => date('Y-m-d H:i:s', time()),
                            'status' => 1
                        );
                    } else {
                        //根据id取出当前用户的信息
                        $userinfo = $this->User->findById($v);
                        $save_approve = array(
                            'main_id' => $mainId,
                            'position_id' => $userinfo['User']['position_id'],
                            'approve_id' => $v,
                            'remarks' => '',
                            'name' => $userinfo['User']['name'],
                            'ctime' => date('Y-m-d H:i:s', time()),
                            'status' => 1
                        );
                    }
                   $this->ApprovalInformation->add($save_approve);
                }
                
            } else {
                //其他审批人 暂时不处理
            }
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '申请成功';
        } else {
            $this->ret_arr['msg'] = '申请失败';
        }


        echo json_encode($this->ret_arr);
        exit;
    }
    //果树所出差审批单
    private function gss_evection_save($datas) {
        if (empty($datas['ctime']) || empty($datas['reason']) 
                || empty($datas['personnel']) || empty($datas['mode_route']) 
                || empty($datas['start_day']) || empty($datas['end_day'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'chailvfei_sqd';
        $p_id = 4;//审批流id
        $project_id = 0;
        $type = 0;//类型暂定为0
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id);
        
//        $ret_arr = $this->get_create_approval_process_by_table_name($table_name,$type, $this->userInfo->department_id);
//
//        if ($ret_arr[$this->code] == 1) {
//            $this->ret_arr['msg'] = $ret_arr[$this->msg];
//            exit(json_encode($this->ret_arr));
//        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人
        
        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['reason'] = $datas['reason'];

//        $attrArr['department_id'] = $department_id;
//        $attrArr['department_name'] = $department_name;
//        $attrArr['project_id'] = $project_id;
        $attrArr['personnel'] = $datas['personnel'];
        $attrArr['start_time'] = $datas['start_day'];
        $attrArr['end_time'] = $datas['end_day'];
        $attrArr['total_day'] = $datas['sum_day'];
        $attrArr['place'] = $datas['address'];
        $attrArr['way'] = $datas['mode_route'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());
        
        # 开始入库
        $this->ChailvfeiSqd->begin();
        $attrId = $this->ChailvfeiSqd->add($attrArr);
        
        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id'];//下一个审批职务的id
        $mainArr['code'] = $ret_arr['code'];//当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type; 
        $mainArr['attachment'] = ''; 
        $mainArr['name'] = '果树所出差审批单';
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;        
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['total'] = 0;
        $mainArr['attr_id'] = $attrId;
        $mainArr['project_user_id'] = 0;
        $mainArr['project_team_user_id'] = 0;
        $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        $mainArr['ctime'] = date('Y-m-d H:i:s', time());
        $mainArr['subject'] = '';
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ChailvfeiSqd->rollback();
        }
        $mainId ? $commitId = $this->ChailvfeiSqd->rollback() : $commitId = $this->ChailvfeiSqd->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id']) ) {
                foreach ($ret_arr['code_id'] as $k=>$v) {
                    if ($v == $this->userInfo->id) {
                        $save_approve = array(
                            'main_id' => $mainId,
                            'position_id' => $this->userInfo->position_id,
                            'approve_id' => $this->userInfo->id,
                            'remarks' => '',
                            'name' => $this->userInfo->name,
                            'ctime' => date('Y-m-d H:i:s', time()),
                            'status' => 1
                        );
                    } else {
                        //根据id取出当前用户的信息
                        $userinfo = $this->User->findById($v);
                        $save_approve = array(
                            'main_id' => $mainId,
                            'position_id' => $userinfo['User']['position_id'],
                            'approve_id' => $v,
                            'remarks' => '',
                            'name' => $userinfo['User']['name'],
                            'ctime' => date('Y-m-d H:i:s', time()),
                            'status' => 1
                        );
                    }
                   $this->ApprovalInformation->add($save_approve);
                }
                
            } else {
                //其他审批人 暂时不处理
            }
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '申请成功';
        } else {
            $this->ret_arr['msg'] = '申请失败';
        }


        echo json_encode($this->ret_arr);
        exit;
    }
    
    //果树所借款单
    private function gss_loan_save($datas) {
        if (empty($datas['ctime']) || empty($datas['loan_reason']) 
                || empty($datas['big_amount']) || empty($datas['small_amount']) 
                || empty($datas['big_approval_amount']) || empty($datas['small_approval_amount'])
                || empty($datas['repayment_plan'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_jiekuandan';
        $p_id = 5;//审批流id
        $project_id = 0;
        $type = 0;//类型暂定为0
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id);
        
//        $ret_arr = $this->get_create_approval_process_by_table_name($table_name,$type, $this->userInfo->department_id);
//
//        if ($ret_arr[$this->code] == 1) {
//            $this->ret_arr['msg'] = $ret_arr[$this->msg];
//            exit(json_encode($this->ret_arr));
//        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人
        
        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['reason'] = $datas['loan_reason'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
        
        $attrArr['apply_money_capital'] = $datas['big_amount'];
        $attrArr['apply_money'] = $datas['small_amount'];
        $attrArr['approve_money_capital'] = $datas['big_approval_amount'];
        $attrArr['approve_money'] = $datas['small_approval_amount'];
        $attrArr['repayment'] = $datas['repayment_plan'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());
        
        # 开始入库
        $this->ApplyJiekuandan->begin();
        $attrId = $this->ApplyJiekuandan->add($attrArr);
        
        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id'];//下一个审批职务的id
        $mainArr['code'] = $ret_arr['code'];//当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type; 
        $mainArr['attachment'] = ''; 
        $mainArr['name'] = '果树所出差审批单';
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;        
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['total'] = 0;
        $mainArr['attr_id'] = $attrId;
        $mainArr['project_user_id'] = 0;
        $mainArr['project_team_user_id'] = 0;
        $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        $mainArr['ctime'] = date('Y-m-d H:i:s', time());
        $mainArr['subject'] = '';
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyJiekuandan->rollback();
        }
        $mainId ? $commitId = $this->ApplyJiekuandan->rollback() : $commitId = $this->ApplyJiekuandan->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id']) ) {
                foreach ($ret_arr['code_id'] as $k=>$v) {
                    if ($v == $this->userInfo->id) {
                        $save_approve = array(
                            'main_id' => $mainId,
                            'position_id' => $this->userInfo->position_id,
                            'approve_id' => $this->userInfo->id,
                            'remarks' => '',
                            'name' => $this->userInfo->name,
                            'ctime' => date('Y-m-d H:i:s', time()),
                            'status' => 1
                        );
                    } else {
                        //根据id取出当前用户的信息
                        $userinfo = $this->User->findById($v);
                        $save_approve = array(
                            'main_id' => $mainId,
                            'position_id' => $userinfo['User']['position_id'],
                            'approve_id' => $v,
                            'remarks' => '',
                            'name' => $userinfo['User']['name'],
                            'ctime' => date('Y-m-d H:i:s', time()),
                            'status' => 1
                        );
                    }
                   $this->ApprovalInformation->add($save_approve);
                }
                
            } else {
                //其他审批人 暂时不处理
            }
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '申请成功';
        } else {
            $this->ret_arr['msg'] = '申请失败';
        }


        echo json_encode($this->ret_arr);
        exit;
    }

}
