<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class RequestNoteController extends AppController {

    public $name = 'RequestNote';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'ApplyMain', 'ApplyBaoxiaohuizong', 'ApprovalInformation', 'Department', 'ApplyPaidleave', 'ChailvfeiSqd', 'ApplyJiekuandan', 'ApplyLingkuandan', 'ApplyLeave', 'ApplyChuchaiBxd', 'ApplyCaigou');
    public $layout = 'blank';
    public $components = array('Cookie', 'Approval');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

    /**
     * 公共方法
     */
    // 项目下所属资源文件
    public function getsource() {
        if (empty($_POST['pd'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $sourcelist = $sourceArr = array();
        $sourcelist = $this->ResearchSource->getAll($_POST['pd']);
        foreach ($sourcelist as $k => $v) {
            $sourceArr[$v['ResearchSource']['id']] = $v['ResearchSource'];
        }

        if (empty($sourceArr)) {
            $this->ret_arr['msg'] = '无文件数据';
            exit(json_encode($this->ret_arr));
        } else {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = $sourceArr;
            exit(json_encode($this->ret_arr));
        }
    }

    /**
     * 科研项目费用报销
     */
    //汇总报销申批单
    public function huizongbaoxiao($mid = 0) {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->sub_declares($_POST);
        } else {
            //当前用户所属项目
//        $conditions = array('user_id'=>$this->userInfo->id);
//        $projectArr = $this->ResearchProject->getlist($conditions);
//        
//        $this->set('projectArr', $projectArr);
//        $this->set('list', Configure::read('keyanlist'));
//        
//        $this->render();
            $pid = 0;
            $this->set('pid', $pid);
            // 当前用所参与科研项目
            $pro_conditions = array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id'));
            $proArr = $this->ProjectMember->find('list', $pro_conditions);
            // 所参与项目 详情
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4), 'fields' => array('id', 'name'));
            $projectInfo = $this->ResearchProject->find('list', $conditions);
//        $source = $this->ResearchSource->getAll($pid);
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            $this->set('department_arr', $department_arr);
            $this->set('is_department', !empty($department_arr) ? $department_arr['Department']['type'] : 2);
            $this->set('xizhenglist', Configure::read('xizhenglist'));
            $this->set('keyanlist', Configure::read('keyanlist'));
            $this->set('projectInfo', $projectInfo);
//        $this->set('source', $source);
            // 重新提交申请  获取旧申请数据
            if ($mid) {
                $applyArr = $this->applyInfos($mid, 'ApplyBaoxiaohuizong');
                $this->set('mainInfo', $applyArr['ApplyMain']);
                $this->set('attrInfo', $applyArr['ApplyBaoxiaohuizong']);
            }

            $this->render();
        }
    }

    // 添加 汇总报销申批单
    private function sub_declares($datas) {
        if (empty($datas['ctime']) || (empty($datas['page_number']) && $datas['page_number'] != 0) || empty($datas['projectname']) || empty($datas['filenumber']) || empty($datas['subject']) || empty($datas['rmb_capital']) || empty($datas['amount'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_baoxiaohuizong';

        $type = Configure::read('type_number'); //行政费用
        $type = $type[0];
        $ret_arr = $this->get_create_approval_process_by_table_name($table_name, $type, $this->userInfo->department_id);

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
        $mainArr['next_approver_id'] = $ret_arr[$this->res]['next_approver_id']; //下一个审批职务的id
        $mainArr['code'] = $ret_arr[$this->res]['approve_code']; //当前单子审批的状态码
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

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_evection_save($_POST);
        } else {
            //当前用户所属部门和所参加的项目
            $department = $this->Department->findById($this->userInfo->department_id);
            // 当前用所参与科研项目
            $pro_conditions = array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id'));
            $proArr = $this->ProjectMember->find('list', $pro_conditions);
            // 所参与项目 详情
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4), 'fields' => array('id', 'name'));
            $projectInfo = $this->ResearchProject->find('list', $conditions);

            $this->set('department', $department);

            $this->set('projectInfo', $projectInfo);
            $this->set('list', Configure::read('xizhenglist'));

            $this->render();
        }
    }

    // 果树所借款单
    public function gss_loan($mid = 0) {
        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {

            $this->gss_loan_save($_POST);
        } else {
            // 当前用所参与科研项目
            $pro_conditions = array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id'));
            $proArr = $this->ProjectMember->find('list', $pro_conditions);
            // 所参与项目 详情
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4), 'fields' => array('id', 'name'));
            $projectInfo = $this->ResearchProject->find('list', $conditions);

//            $conditions = array( 'conditions' => array('user_id'=>$this->userInfo->id, 'del' => 0, 'code' => 4), 'fields' => array('id', 'name'));
//            $projectInfo = $this->ResearchProject->find('list' ,$conditions);

            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            $this->set('department_arr', $department_arr);
            $this->set('is_department', !empty($department_arr) ? $department_arr['Department']['type'] : 2);
            $this->set('projectInfo', $projectInfo);

            // 重新提交申请  获取旧申请数据
            if ($mid) {
                $applyArr = $this->applyInfos($mid, 'ApplyJiekuandan');
                $this->set('mainInfo', $applyArr['ApplyMain']);
                $this->set('attrInfo', $applyArr['ApplyJiekuandan']);
            }
            $this->render();
        }
    }

    // 果树所差旅费报销单
    public function gss_evection_expense($mid = 0) {
        $this->render();

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_evection_expense_save($_POST);
        } else {
            // 当前用所参与科研项目
            $pro_conditions = array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id'));
            $proArr = $this->ProjectMember->find('list', $pro_conditions);
            // 所参与项目 详情
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4), 'fields' => array('id', 'name'));
            $projectInfo = $this->ResearchProject->find('list', $conditions);

//            $conditions = array( 'conditions' => array('user_id'=>$this->userInfo->id, 'del' => 0, 'code' => 4), 'fields' => array('id', 'name'));
//            $projectInfo = $this->ResearchProject->find('list' ,$conditions);
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            $this->set('department_arr', $department_arr);
            $this->set('is_department', !empty($department_arr) ? $department_arr['Department']['type'] : 2);
            $this->set('projectInfo', $projectInfo);

            // 重新提交申请  获取旧申请数据
            if ($mid) {
                $applyArr = $this->applyInfos($mid, 'ApplyChuchaiBxd');
                $this->set('mainInfo', $applyArr['ApplyMain']);
                $applyArr['ApplyChuchaiBxd']['json_str'] = json_decode($applyArr['ApplyChuchaiBxd']['json_str'], true);
                $this->set('attrInfo', $applyArr['ApplyChuchaiBxd']);
            }

            $this->render();
        }
    }

    // 果树所采购申请单
    public function gss_purchase() {
        $this->render();

        if (/* $this->request->is('ajax') && */!empty($_POST['declarename'])) {
            $this->gss_purchase_save($_POST, $_FILES);
        } else {

            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    // 果树所领款单
    public function gss_draw_money($mid = 0) {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_draw_money_save($_POST);
        } else {

            // 当前用所参与科研项目
            $pro_conditions = array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id'));
            $proArr = $this->ProjectMember->find('list', $pro_conditions);
            // 所参与项目 详情
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4), 'fields' => array('id', 'name'));
            $projectInfo = $this->ResearchProject->find('list', $conditions);
//            $conditions = array( 'conditions' => array('user_id'=>$this->userInfo->id, 'del' => 0, 'code' => 4), 'fields' => array('id', 'name'));
//            $projectInfo = $this->ResearchProject->find('list' ,$conditions);
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            $this->set('department_arr', $department_arr);
            $this->set('is_department', !empty($department_arr) ? $department_arr['Department']['type'] : 2);
            $this->set('projectInfo', $projectInfo);

            // 重新提交申请  获取旧申请数据
            if ($mid) {
                $applyArr = $this->applyInfos($mid, 'ApplyLingkuandan');
                $this->set('mainInfo', $applyArr['ApplyMain']);
                $applyArr['ApplyLingkuandan']['json_str'] = json_decode($applyArr['ApplyLingkuandan']['json_str'], true);
                $this->set('attrInfo', $applyArr['ApplyLingkuandan']);
            }

            $this->render();
        }
    }

    // 果树所请假单
    public function gss_leave() {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_leave_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    // 果树所职工带薪年审批单
    public function gss_furlough() {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_furlough_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    //果树所职工带薪年审批单保存
    private function gss_furlough_save($datas) {
        if (empty($datas['company']) || empty($datas['start_work']) || empty($datas['years']) || empty($datas['vacation_days']) || empty($datas['start_time']) || empty($datas['end_time'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 't_apply_paidleave';
        $p_id = 3; //审批流id
        $p_id = 0; //审批流id

        if (!$datas['depname']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['depname'];
        }
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);
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
//        $attrArr['company'] = $datas['company'];
        $attrArr['start_work'] = $datas['start_work'];

        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['team_id'] = $team_id;
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
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '果树所职工带薪年休假审批单';
        $mainArr['project_id'] = $project_id;
        $mainArr['team_id'] = $team_id;
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
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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
        if (empty($datas['ctime']) || empty($datas['reason']) || empty($datas['personnel']) || empty($datas['mode_route']) || empty($datas['start_day']) || empty($datas['end_day'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'chailvfei_sqd';
        $p_id = 4; //审批流id
        if (!$datas['dep_pro']) {
            //说明是部门
            $type = 2; //行政
            $project_id = 0;
        } else {
            $type = 1; //科研
            $project_id = $datas['dep_pro'];
        }


        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);
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

        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
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
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
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
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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
        if (empty($datas['ctime']) || empty($datas['loan_reason']) || empty($datas['big_amount']) || empty($datas['small_amount']) || empty($datas['repayment_plan']) || empty($datas['subject']) || empty($datas['filenumber'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_jiekuandan';

        $project_user_id = 0; //项目负责人user_id
        $project_team_user_id = 0; //项目组负责人user_id
        $_POST['projectname'] = $_POST['dep_pro'];
        $type = Configure::read('type_number'); //行政费用
        if ($_POST['projectname'] == 0) {
            $project_id = 0; //让他为0
            $type = $type[1];
            $p_id = 10; //行政审批流id
        } else {
            $type = $type[0];
            $p_id = 5; //科研审批流id
            //项目
            $project_id = $_POST['projectname'];
            //根据项目取出，项目负责人user_id,和项目组负责人user_id
            $select_user_id_sql = "select p.user_id,tp.team_user_id from t_research_project p left join t_team_project tp on p.project_team_id=tp.id where p.id='$project_id'";

            $project_and_team_arr = $this->ApplyMain->query($select_user_id_sql);
            $project_user_id = $project_and_team_arr[0]['p']['user_id']; //项目负责人user_id
            $project_team_user_id = $project_and_team_arr[0]['tp']['team_user_id']; //项目组负责人user_id
        }

        $applyArr = array('type' => $type, 'project_team_user_id' => $project_team_user_id, 'project_user_id' => $project_user_id);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);

        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人
        // 是否修改申请
        // $checkapply = $this->Cookie->read('checkapply');

        $attrArr = array();
        // isset($checkapply['mainid']) && $checkapply['attrtable'] == 'ApplyJiekuandan' && $attrArr['id'] = $checkapply['mainid'];
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['reason'] = $datas['loan_reason'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['source_id'] = $_POST['filenumber'];
        $attrArr['apply_money_capital'] = $datas['big_amount'];
        $attrArr['apply_money'] = $datas['small_amount'];
//        $attrArr['approve_money_capital'] = $datas['big_approval_amount'];
//        $attrArr['approve_money'] = $datas['small_approval_amount'];
        //创建时没有金额
        $attrArr['approve_money_capital'] = '';
        $attrArr['approve_money'] = 0;
        $attrArr['repayment'] = $datas['repayment_plan'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['applicant'] = $datas['applicant'];
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());
        //       var_dump($datas,$attrArr);die;
        # 开始入库
        $this->ApplyJiekuandan->begin();
        $attrId = $this->ApplyJiekuandan->add($attrArr);

        # 主表入库
        $mainArr = array();
        // isset($checkapply['attrid']) && $checkapply['attrtable'] == 'ApplyJiekuandan' && $mainArr['id'] = $checkapply['attrid'];
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = $datas['declarename'];
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['total'] = $datas['small_amount'];
        $mainArr['attr_id'] = $attrId;
        $mainArr['project_user_id'] = $project_user_id;
        $mainArr['project_team_user_id'] = $project_team_user_id;
        $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        $mainArr['ctime'] = date('Y-m-d H:i:s', time());
        $mainArr['subject'] = json_encode(array($datas['subject'] => $datas['small_amount']));
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyJiekuandan->rollback();
        }
        $mainId ? $commitId = $this->ApplyJiekuandan->rollback() : $commitId = $this->ApplyJiekuandan->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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

    // 果树所领款单
    private function gss_draw_money_save($datas) {
        if (empty($datas['ctime']) || (empty($datas['sheets_num']) && $datas['sheets_num'] != 0) || empty($datas['subject']) || empty($datas['filenumber'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_lingkuandan';

        $project_user_id = 0; //项目负责人user_id
        $project_team_user_id = 0; //项目组负责人user_id
        $_POST['projectname'] = $_POST['dep_pro'];
        $type = Configure::read('type_number'); //行政费用
        if ($_POST['projectname'] == 0) {
            $project_id = 0; //让他为0
            $type = $type[1];
            $p_id = 11; //行政审批流id
        } else {
            $type = $type[0];
            $p_id = 6; //科研审批流id
            //项目
            $project_id = $_POST['projectname'];
            //根据项目取出，项目负责人user_id,和项目组负责人user_id
            $select_user_id_sql = "select p.user_id,tp.team_user_id from t_research_project p left join t_team_project tp on p.project_team_id=tp.id where p.id='$project_id'";

            $project_and_team_arr = $this->ApplyMain->query($select_user_id_sql);
            $project_user_id = $project_and_team_arr[0]['p']['user_id']; //项目负责人user_id
            $project_team_user_id = $project_and_team_arr[0]['tp']['team_user_id']; //项目组负责人user_id
        }

        $applyArr = array('type' => $type, 'project_team_user_id' => $project_team_user_id, 'project_user_id' => $project_user_id);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);

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
        $attrArr['page_number'] = $datas['sheets_num'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['source_id'] = $datas['filenumber'];
        $attrArr['json_str'] = json_encode($datas['dp_json_str']);
        $attrArr['big_total'] = $datas['big_total'];
        $attrArr['small_total'] = $datas['small_total'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['applicant'] = $datas['applicant'];
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyLingkuandan->begin();
        $attrId = $this->ApplyLingkuandan->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = $datas['declarename'];
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['total'] = $datas['small_total'];
        $mainArr['attr_id'] = $attrId;
        $mainArr['project_user_id'] = $project_user_id;
        $mainArr['project_team_user_id'] = $project_team_user_id;
        $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        $mainArr['ctime'] = date('Y-m-d H:i:s', time());
        $mainArr['subject'] = json_encode(array($datas['subject'] => $datas['small_total']));
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyLingkuandan->rollback();
        }
        $mainId ? $commitId = $this->ApplyLingkuandan->rollback() : $commitId = $this->ApplyLingkuandan->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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

    // 果树所请假单
    private function gss_leave_save($datas) {
//        var_dump($datas);die;
        /*
         * 'ctime' => string '2017-07-31' (length=10)
          'applyname' => string 'admin123' (length=8)
          'dep_pro' => string '23r' (length=3)
          'leave_type' => string '1' (length=1)
          'reason' => string 'sdfasd' (length=6)
          'start_time' => string '2017-06-27' (length=10)
          'end_time' => string '2017-07-05' (length=10)
          'sum_days' => string '0' (length=1)
         */
        if (empty($datas['ctime']) || empty($datas['reason']) || empty($datas['start_time']) || empty($datas['end_time']) || empty($datas['leave_type'])
        ) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_leave';
        $p_id = 0; //审批流id

        if (!$datas['dep_pro']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['dep_pro'];
        }
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
//        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);
        $ret_arr = $this->ApplyLeave->apply_create($type, $datas, (array)$this->userInfo);
        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }
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
        $attrArr['type_id'] = $datas['leave_type'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
//        $attrArr['project_id'] = $project_id;
        $attrArr['team_id'] = $team_id;

        $attrArr['applyname'] = $datas['applyname']; //请假人姓名

        $attrArr['start_time'] = $datas['start_time'];
        $attrArr['end_time'] = $datas['end_time'];
        $attrArr['total_days'] = $datas['sum_days'];
        $attrArr['reason'] = $datas['reason'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyLeave->begin();
        $attrId = $this->ApplyLeave->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '果树所请假单';
        $mainArr['team_id'] = $team_id;
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
            $this->ApplyLeave->rollback();
        }
        $mainId ? $commitId = $this->ApplyLeave->rollback() : $commitId = $this->ApplyLeave->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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

    //根据项目id获取souce
    public function ajax_get_souce() {
        $pid = $_POST['pid'];
        $souid = $_POST['sid'];
        $depid = $_POST['depid'];
        // 如果是部门取部门资金来源
        $souces = ($pid == 0 && isset($depid)) ? $this->ResearchSource->getDepAll($depid) : $this->ResearchSource->getAll($pid);
        $ret_option = '';
        if (!empty($souces)) {
            foreach ($souces as $k => $v) {
                $selected = ($souid == $v['ResearchSource']['id']) ? 'selected' : '';
                $ret_option .= '<option value="' . $v['ResearchSource']['id'] . '"  ';
                $ret_option .= $selected . ' > ';
                $ret_option .= '【' . $v['ResearchSource']['source_channel'] . ' （' . $v['ResearchSource']['file_number'] . '） ' . $v['ResearchSource']['year'] . '】</option>';
            }
        } else {
            $ret_option = '<option></option>';
        }

        echo json_encode(array(
            'html' => $ret_option
        ));
        exit;
    }

    // 果树所差旅费报销单
    private function gss_evection_expense_save($datas) {

        if (empty($datas['ctime']) || empty($datas['reason']) || (empty($datas['sheets_num']) && $datas['sheets_num'] != 0) || empty($datas['filenumber'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_chuchai_bxd';

        $project_user_id = 0; //项目负责人user_id
        $project_team_user_id = 0; //项目组负责人user_id
        $_POST['projectname'] = $_POST['dep_pro'];
        $type = Configure::read('type_number'); //行政费用
        if ($_POST['projectname'] == 0) {
            $project_id = 0; //让他为0
            $type = $type[1];
            $p_id = 12; //行政审批流id
        } else {
            $type = $type[0];
            $p_id = 8; //科研审批流id
            //项目
            $project_id = $_POST['projectname'];
            //根据项目取出，项目负责人user_id,和项目组负责人user_id
            $select_user_id_sql = "select p.user_id,tp.team_user_id from t_research_project p left join t_team_project tp on p.project_team_id=tp.id where p.id='$project_id'";

            $project_and_team_arr = $this->ApplyMain->query($select_user_id_sql);
            $project_user_id = $project_and_team_arr[0]['p']['user_id']; //项目负责人user_id
            $project_team_user_id = $project_and_team_arr[0]['tp']['team_user_id']; //项目组负责人user_id
        }

        $applyArr = array('type' => $type, 'project_team_user_id' => $project_team_user_id, 'project_user_id' => $project_user_id);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);


        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['page_number'] = $datas['sheets_num'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['source_id'] = $datas['filenumber'];
        $attrArr['business_traveller_id'] = $datas['personnel'];
        $attrArr['total_number'] = $datas['sums'];
        $attrArr['reason'] = $datas['reason'];
        $attrArr['json_str'] = json_encode($datas['json_str']);
        $attrArr['total'] = $datas['small_total'];
        $attrArr['total_capital'] = $datas['big_total'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['applicant'] = $datas['applicant'];
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyChuchaiBxd->begin();
        $attrId = $this->ApplyChuchaiBxd->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = $datas['declarename'];
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['total'] = $datas['small_total'];
        $mainArr['attr_id'] = $attrId;
        $mainArr['project_user_id'] = $project_user_id;
        $mainArr['project_team_user_id'] = $project_team_user_id;
        $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        $mainArr['ctime'] = date('Y-m-d H:i:s', time());
        $mainArr['subject'] = json_encode(array('travel' => $datas['small_total']));
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyChuchaiBxd->rollback();
        }
        $mainId ? $commitId = $this->ApplyChuchaiBxd->rollback() : $commitId = $this->ApplyChuchaiBxd->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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

    // 果树所采购申请单
    private function gss_purchase_save($datas, $uploadfile = array()) {
        if (
                empty($datas['ctime']) || empty($datas['file_number']) || empty($datas['material_name']) || empty($datas['unit']) || empty($datas['nums']) || empty($datas['price']) || empty($datas['reason'])
        ) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        header("Content-type: text/html; charset=utf-8");
        $table_name = 'apply_caigou';
        $p_id = 9; //审批流id
        $project_id = 0;
        $type = 2; //类型暂定为0

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);

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

        $new_name = '';
        if (!empty($uploadfile['descripttion']['name']) && $uploadfile['descripttion']['error'] == 0) {
            $org_name = $uploadfile['descripttion']['name']; //原始名字
            $tmp_file = $uploadfile['descripttion']['tmp_name']; //临时文件
            $ext = pathinfo($org_name, PATHINFO_EXTENSION); //后缀
            $base_name = basename($org_name, $ext ? '.' . $ext : '');
            if (empty($ext)) {
                $new_name = $base_name . '_' . time();
            } else {
                $new_name = $base_name . '_' . time() . '.' . $ext;
            }
            $new_file_name = WWW_ROOT . 'files' . DS . $new_name;

            if (!move_uploaded_file($tmp_file, $new_file_name)) {
                $new_name = ''; //如果没有上传成功，先不处理
            }
        }
        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;

        $attrArr['channel_id'] = $datas['type'];
        $attrArr['file_number'] = $datas['file_number'];
        $attrArr['purchase_name'] = $datas['material_name'];
        $attrArr['company'] = $datas['unit'];
        $attrArr['number'] = $datas['nums'];
        $attrArr['price'] = $datas['price'];
        $attrArr['amount'] = $datas['total'];
        $attrArr['reason'] = $datas['reason'];
        $attrArr['attachment'] = $new_name; //附件   
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyCaigou->begin();
        $attrId = $this->ApplyCaigou->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '果树所采购申请单';
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
        $mainArr['attachment'] = $new_name;
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyCaigou->rollback();
        }
        $mainId ? $commitId = $this->ApplyCaigou->rollback() : $commitId = $this->ApplyCaigou->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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
            echo "<script>alert('申请成功'); window.location = '/office/draf'</script>";
            exit;
        } else {
            $this->ret_arr['msg'] = '申请失败';
            echo "<script>alert('申请失败'); window.location = '/office/draf'</script>";
            exit;
        }


//        echo json_encode($this->ret_arr);
//        exit;
    }

    // 田间作业包工申请表
    public function gss_contractor() {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_contractor_save($_POST);
        } else {

            $this->render();
        }
    }

    private function gss_contractor_save($datas) {

        if (empty($datas['ctime']) || empty($datas['reason']) || empty($datas['start_time']) || empty($datas['end_time']) || empty($datas['leave_type'])
        ) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_leave';
        $p_id = 0; //审批流id

        if (!$datas['dep_pro']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['dep_pro'];
        }
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);

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
        $attrArr['type_id'] = $datas['leave_type'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
//        $attrArr['project_id'] = $project_id;
        $attrArr['team_id'] = $team_id;

        $attrArr['start_time'] = $datas['start_time'];
        $attrArr['end_time'] = $datas['end_time'];
        $attrArr['total_days'] = $datas['sum_days'];
        $attrArr['reason'] = $datas['reason'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyLeave->begin();
        $attrId = $this->ApplyLeave->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '果树所请假单';
        $mainArr['team_id'] = $team_id;
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
            $this->ApplyLeave->rollback();
        }
        $mainId ? $commitId = $this->ApplyLeave->rollback() : $commitId = $this->ApplyLeave->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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

    // 职工因公不休或不全休带薪假审批表
    public function gss_endlessly() {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_endlessly_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    // 职工因公不休或不全休带薪假审批表
    private function gss_endlessly_save($datas) {
        if (empty($datas['company']) || empty($datas['start_work']) || empty($datas['years']) || empty($datas['vacation_days']) || empty($datas['start_time']) || empty($datas['end_time'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 't_apply_paidleave';
        $p_id = 3; //审批流id
        $p_id = 0; //审批流id

        if (!$datas['depname']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['depname'];
        }
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);
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
//        $attrArr['company'] = $datas['company'];
        $attrArr['start_work'] = $datas['start_work'];

        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['team_id'] = $team_id;
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
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '果树所职工带薪年休假审批单';
        $mainArr['project_id'] = $project_id;
        $mainArr['team_id'] = $team_id;
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
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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

    // 印信使用签批单
    public function gss_seal() {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_furlough_save($_POST);
        } else {
            $this->render();
        }
    }

    //印信使用签批单保存
    private function gss_seal_save($datas) {
        if (empty($datas['applyname']) || empty($datas['oddnum']) || empty($datas['sealtype']) || empty($datas['filetype']) || empty($datas['filenum'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 't_apply_seal';
        $p_id = 3; //审批流id
        $p_id = 0; //审批流id

        if (!$datas['depname']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['depname'];
        }
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr);
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
//        $attrArr['company'] = $datas['company'];
        $attrArr['start_work'] = $datas['start_work'];

        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['team_id'] = $team_id;
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
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '果树所职工带薪年休假审批单';
        $mainArr['project_id'] = $project_id;
        $mainArr['team_id'] = $team_id;
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
            if (!empty($ret_arr['code_id'])) {
                foreach ($ret_arr['code_id'] as $k => $v) {
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

    //来文
    public function gss_received() {
        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_received_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    //保存来文
    private function gss_received_save($datas) {
        
    }

    //发文 
    public function gss_send() {
        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_send_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    //保存发文
    private function gss_send_save($datas) {
        
    }

    //借阅
    public function gss_borrow() {
        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_borrow_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    //保存借阅
    private function gss_borrow_save($datas) {
        
    }

}
