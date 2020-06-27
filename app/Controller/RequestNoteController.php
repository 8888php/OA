<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class RequestNoteController extends AppController {

    public $name = 'RequestNote';

    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'ApplyMain', 'ApplyBaoxiaohuizong', 'ApprovalInformation', 'Department', 'ApplyPaidleave', 'ChailvfeiSqd', 'ApplyJiekuandan', 'ApplyLingkuandan', 'ApplyLeave', 'ApplyChuchaiBxd', 'ApplyCaigou','ApplyChuchai', 'ApplyBaogong','Team', 'ApplyEndlessly','ApplySeal','ApplyReceived','ApplyBorrow','ApplyDispatch', 'ApplyNews', 'ApplyRequestReport');


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
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4, 'is_finish' => 0), 'fields' => array('id', 'name'));
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

    // 添加 汇总报销申批单  项目内调用方法
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
        $attrArr['source_id'] = $datas['filenumber'];
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
        $mainArr['is_calculation'] = $datas['is_calculation'] == 1 ? 1 : 0;
        $mainArr['name'] = $datas['declarename'];
        $mainArr['project_id'] = $datas['projectname'];
        $attrArr['source_id'] = $datas['filenumber'];
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
    public function gss_evection($mid = 0) {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_evection_save($_POST);
        } else {
            //当前用户所属部门
            $department = $this->Department->find('first',array('conditions'=>array('id' => $this->userInfo->department_id, 'type' => 1)));
            // 当前用户所参与科研项目    取消科研项目 改为 团队
//            $pro_conditions = array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id'));
//            $proArr = $this->ProjectMember->find('list', $pro_conditions);
//            // 所参与项目 详情
//            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4, 'is_finish' => 0), 'fields' => array('id', 'name'));
//            $projectInfo = $this->ResearchProject->find('list', $conditions);
//            
//            if ($department) {
//                //李捷 2、吕英忠 6、乔永胜 5、李全 8、李登科 9、赵旗峰 7、李志平 3 这几个人的话是用科研项目
//                if (in_array($this->userInfo->id, array(2, 6, 5, 8, 9, 7, 3))) {
//                    $department = array();
//                } else {
//                    //如果当前用户行政部门，只能选行政
//                    $projectInfo = array();
//                }
//            }
//            $this->set('projectInfo', $projectInfo);
//            
            //李捷 2、李志平 3 这几个人的话是用科研项目
            // 吕英忠 6、乔永胜 5、李全 8、李登科 9、赵旗峰 7 移除
            if ($department && in_array($this->userInfo->id, array(2, 3)) ) {
                    $department = array();
            }
            $this->set('department', $department);
            
            // 当前用户所属团队  行政部门成员不用查团队 && 吕英忠 6、乔永胜 5、李全 8、李登科 9、赵旗峰 7这5人取所有团队
            $dep_type = $this->Department->find('first', array('conditions' => array('id' => $this->userInfo->department_id), 'fields' => array('type')));
            if($dep_type['Department']['type'] != 2 && !in_array($this->userInfo->id, [5,6,7,8,9])){
                $this->set('team_arr', []);
            }else{
                $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$this->userInfo->id}'";
                $team_arr = $this->ApplyMain->query($sql);
                $this->set('team_arr', $team_arr);
            }

            //获取所有团队
           
            $this->set('list', Configure::read('xizhenglist'));

            // 重新提交申请  获取旧申请数据
            if ($mid) {
                $applyArr = $this->applyInfos($mid, 'ApplyChuchai');
                $this->set('mainInfo', $applyArr['ApplyMain']);
                $this->set('attrInfo', $applyArr['ApplyChuchai']);
            }
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
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4, 'is_finish' => 0), 'fields' => array('id', 'name'));
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
        //$this->render();

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_evection_expense_save($_POST);
        } else {
            // 当前用所参与科研项目
            $pro_conditions = array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id'));
            $proArr = $this->ProjectMember->find('list', $pro_conditions);
            // 所参与项目 详情
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4, 'is_finish' => 0), 'fields' => array('id', 'name'));
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
    public function gss_purchase($mid = 0) {
        if ( !empty($_POST['declarename']) && ($_POST['team'] >= 0)  && !empty($_POST['project']) && !empty($_POST['file_number']) && !empty($_POST['material_name']) && !empty($_POST['reason']) ) {
            $this->gss_purchase_save($_POST, $_FILES);
        } else {

            //获取部门和 参与项目
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);

           // $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $sql = "select p.id,p.name from t_research_project p left join t_project_member m on p.id=m.project_id where p.del=0 and p.is_finish=0 and p.code=4 and m.user_id='{$user_id}'";
            $pro_arr = $this->ApplyMain->query($sql);
             // 重新提交申请  获取旧申请数据
            if ($mid) {
                $applyArr = $this->applyInfos($mid, 'ApplyCaigou');
                //var_dump($applyArr);die;
                $this->set('mainInfo', $applyArr['ApplyMain']);
                $this->set('attrInfo', $applyArr['ApplyCaigou']);
            }
            $this->set('pro_arr', $pro_arr);
            $this->set('department_arr', $department_arr);
            $this->set('is_department', empty($department_arr['Department']['id']) ? 0 : 1 );
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
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4, 'is_finish' => 0), 'fields' => array('id', 'name'));
            $projectInfo = $this->ResearchProject->find('list', $conditions);
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
    public function gss_leave($mid = 0) {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_leave_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->find('first',array('conditions' => array('id' => $department_id,'type'=>1 )));

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            
            // 重新提交申请  获取旧申请数据
            if ($mid) {
                $applyArr = $this->applyInfos($mid, 'ApplyLeave');
                $this->set('mainInfo', $applyArr['ApplyMain']);
                $this->set('attrInfo', $applyArr['ApplyLeave']);
            }
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
            $department_arr = $this->Department->find('first',array('conditions' => array('id' => $department_id,'type'=>1 )));

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    //果树所职工带薪年审批单保存
    private function gss_furlough_save($datas) { 
        if (empty($datas['start_work']) || empty($datas['years']) || empty($datas['vacation_days']) || empty($datas['start_time']) || empty($datas['end_time']) || empty($datas['sum_days'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_paidleave';
        $p_id = 0; //审批流id

        if (!$datas['depname']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['depname'];
            $team_arr = $this->Team->findById($team_id);
        }
        $project_id = 0;

        $ret_arr = $this->ApplyPaidleave->apply_create($type, $datas, (array)$this->userInfo);

        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['start_work'] = $datas['start_work'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = ($type == 3) ? $team_arr['Team']['name'] : $department_name;
        $attrArr['team_id'] = $team_id;
        $attrArr['vacation_days'] = $datas['vacation_days'];
        $attrArr['yx_vacation_days'] = $datas['yx_vacation_days'];
        $attrArr['start_time'] = $datas['start_time'];
        $attrArr['end_time'] = $datas['end_time'];
        $attrArr['total_days'] = $datas['sum_days'];
        $attrArr['years'] = $datas['years'];
        $attrArr['grsq'] = $datas['personal_apply'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['user_name'] = $this->userInfo->name;
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

    //果树所差旅审批单
    private function gss_evection_save($datas) {
        if (empty($datas['ctime']) || empty($datas['reason']) || empty($datas['personnel']) || empty($datas['mode_route']) || empty($datas['start_day']) || empty($datas['end_day'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_chuchai';
        //$p_id = 0; //审批流id
        if (!$datas['dep_pro']) {
            //说明是部门 
            $type = 2; //行政
            $project_id = 0;
            $team_id = 0;
        } else {
//            $type = 1; //科研   
//            $project_id = $datas['dep_pro'];
//            $project_arr = $this->ResearchProject->findById($project_id);
            $type = 3;  //改为团队
            $project_id = 0;
            $team_id = $datas['dep_pro'];
            $datas['team_id'] = $datas['dep_pro'];
        }
       
        //获取审批信息
        $ret_arr = $this->ApplyChuchai->apply_create($type, $datas, (array)$this->userInfo);

        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }

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
        $attrArr['department_name'] = ($type == 1) ? $project_arr['ResearchProject']['name'] : $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['team_id'] = $team_id;
        $attrArr['personnel'] = $datas['personnel'];
//        $attrArr['personnel'] = $this->userInfo->name ;
        $attrArr['start_date'] = $datas['start_day'];
        $attrArr['end_date'] = $datas['end_day'];
        $attrArr['days'] = $datas['sum_day'];
        $attrArr['place'] = $datas['address'];
        $attrArr['mode_route'] = $datas['mode_route'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyChuchai->begin();
        $attrId = $this->ApplyChuchai->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = 0; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '果树所差旅审批单';
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;
        $mainArr['team_id'] = $team_id;
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
            $this->ApplyChuchai->rollback();
        }
        $mainId ? $commitId = $this->ApplyChuchai->rollback() : $commitId = $this->ApplyChuchai->commit();


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
         // 统计 该资金来源 （已审批、未审批） 剩余资金不足
        $source = $this->ResearchSource->getamount($_POST['filenumber']);
        $surplus = $this->ApplyMain->getSourceTotal($_POST['filenumber'], $source['ResearchSource']['amount'], $_POST['small_amount']);
        if($surplus < 0){
            $this->ret_arr['msg'] = '当前已超出资金来源剩余金额';
            exit(json_encode($this->ret_arr));
        }
        
        //验证审批单申请 单科目费用 是否超过 项目对应单科目总金额
        if ($_POST['dep_pro'] != 0) {
            $checkcost = $this->check_subject_cost_submit($_POST['dep_pro'], array($datas['subject'] => $datas['small_amount']));
            if ($checkcost['code'] == -1) {
                $this->ret_arr['msg'] = $checkcost['msg'];
                exit(json_encode($this->ret_arr));
            }
        }

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
             $select_user_id_sql = "select p.user_id,tm.user_id from t_research_project p left join t_team tp on p.project_team_id=tp.id  left join t_team_member tm on tp.fzr = tm.id where p.id='$project_id'";

            $project_and_team_arr = $this->ApplyMain->query($select_user_id_sql);
            $project_user_id = $project_and_team_arr[0]['p']['user_id']; //项目负责人user_id
            $project_team_user_id = empty($project_and_team_arr[0]['tm']['user_id']) ? 1 : $project_and_team_arr[0]['tm']['user_id'] ; //项目组负责人user_id 若为空则为单个项目
        }
        //默认正数
        $negative = false;
        if ($datas['small_amount'] < 0) {
            $negative = true;
        }
        $applyArr = array('type' => $type, 'project_team_user_id' => $project_team_user_id, 'project_user_id' => $project_user_id);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr, $negative);
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人
        // 是否修改申请

        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['reason'] = $datas['loan_reason'];
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['source_id'] = $datas['filenumber'];
        $attrArr['apply_money_capital'] = $datas['big_amount'];
        $attrArr['apply_money'] = $datas['small_amount'];
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

        // # 主表入库
        // $mainArr = array();
        // $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        // $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        // $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        // $mainArr['approval_process_id'] = $p_id; //审批流程id
        // $mainArr['type'] = $type;
        // $mainArr['is_calculation'] = 1;  // 是否合算  1是
        // $mainArr['attachment'] = $datas['attachment'];
        // $mainArr['source_id'] = $datas['filenumber'];
        // $mainArr['name'] = $datas['declarename'];
        // $mainArr['project_id'] = $project_id;
        // $mainArr['department_id'] = $department_id;
        // $mainArr['table_name'] = $table_name;
        // $mainArr['user_id'] = $this->userInfo->id;
        // $mainArr['total'] = $datas['small_amount'];
        // $mainArr['attr_id'] = $attrId;
        // $mainArr['project_user_id'] = $project_user_id;
        // $mainArr['project_team_user_id'] = $project_team_user_id;
        // $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        // $mainArr['ctime'] = date('Y-m-d H:i:s', time());
        // $mainArr['subject'] = json_encode(array($datas['subject'] => $datas['small_amount']));

        if ($attrId) {
            //主表入库
            $datas['pid'] = $p_id;  //审批流程id
            $datas['type'] = $type;
            $datas['is_calculation'] = 1;  // 是否合算  1是
            $datas['project_id'] = $project_id;
            $datas['department_id'] = $department_id;
            $datas['table_name'] = $table_name;
            $datas['user_id'] = $this->userInfo->id;
            $datas['total'] = $datas['small_amount'];
            $datas['attr_id'] = $attrId;
            $datas['project_user_id'] = $project_user_id;
            $datas['project_team_user_id'] = $project_team_user_id;
            $datas['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
            $datas['ctime'] = date('Y-m-d H:i:s', time());
            $datas['subject'] = json_encode(array($datas['subject'] => $datas['small_amount']));

            $mainArr = array();
            $mainArr = $this->ApplyMain->add_main_fields($ret_arr, $datas);

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
             //删除老的单子信息，主表，附表
            DELETE_OLD:{
                //方法在AppController.php
                $this->delete_old();
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
         // 统计 该资金来源 （已审批、未审批） 剩余资金不足
        $source = $this->ResearchSource->getamount($_POST['filenumber']);
        $surplus = $this->ApplyMain->getSourceTotal($_POST['filenumber'], $source['ResearchSource']['amount'], $_POST['small_total']);
        if($surplus < 0){
            $this->ret_arr['msg'] = '当前已超出资金来源剩余金额';
            exit(json_encode($this->ret_arr));
        }
        
        //验证审批单申请 单科目费用 是否超过 项目对应单科目总金额
        if ($_POST['dep_pro'] != 0) {
            $checkcost = $this->check_subject_cost_submit($_POST['dep_pro'], array($datas['subject'] => $datas['small_total']));
            if ($checkcost['code'] == -1) {
                $this->ret_arr['msg'] = $checkcost['msg'];
                exit(json_encode($this->ret_arr));
            }
        }
        
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
             $select_user_id_sql = "select p.user_id,tm.user_id from t_research_project p left join t_team tp on p.project_team_id=tp.id  left join t_team_member tm on tp.fzr = tm.id where p.id='$project_id'";

            $project_and_team_arr = $this->ApplyMain->query($select_user_id_sql);
            $project_user_id = $project_and_team_arr[0]['p']['user_id']; //项目负责人user_id
            $project_team_user_id = empty($project_and_team_arr[0]['tm']['user_id']) ? 1 : $project_and_team_arr[0]['tm']['user_id'] ; //项目组负责人user_id 若为空则为单个项目
        }
        //默认正数
        $negative = false;
        if ($datas['small_total'] < 0) {
            $negative = true;
        }
        
        $applyArr = array('type' => $type, 'project_team_user_id' => $project_team_user_id, 'project_user_id' => $project_user_id);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr, $negative);

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

        // # 主表入库
        // $mainArr = array();
        // $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        // $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        // $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        // $mainArr['approval_process_id'] = $p_id; //审批流程id
        // $mainArr['type'] = $type;
        // $mainArr['is_calculation'] = 1;  // 是否合算  1是
        // $mainArr['attachment'] = $datas['attachment'];
        // $mainArr['source_id'] = $datas['filenumber'];
        // $mainArr['name'] = $datas['declarename'];
        // $mainArr['project_id'] = $project_id;
        // $mainArr['department_id'] = $department_id;
        // $mainArr['table_name'] = $table_name;
        // $mainArr['user_id'] = $this->userInfo->id;
        // $mainArr['total'] = $datas['small_total'];
        // $mainArr['attr_id'] = $attrId;
        // $mainArr['project_user_id'] = $project_user_id;
        // $mainArr['project_team_user_id'] = $project_team_user_id;
        // $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        // $mainArr['ctime'] = date('Y-m-d H:i:s', time());
        // $mainArr['subject'] = json_encode(array($datas['subject'] => $datas['small_total']));

        if ($attrId) {
            //主表入库
            $datas['pid'] = $p_id;  //审批流程id
            $datas['type'] = $type;
            $datas['is_calculation'] = 1;  // 是否合算  1是
            $datas['project_id'] = $project_id;
            $datas['department_id'] = $department_id;
            $datas['table_name'] = $table_name;
            $datas['user_id'] = $this->userInfo->id;
            $datas['total'] = $datas['small_total'];
            $datas['attr_id'] = $attrId;
            $datas['project_user_id'] = $project_user_id;
            $datas['project_team_user_id'] = $project_team_user_id;
            $datas['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
            $datas['ctime'] = date('Y-m-d H:i:s', time());
            $datas['subject'] = json_encode(array($datas['subject'] => $datas['small_total']));

            $mainArr = array();
            $mainArr = $this->ApplyMain->add_main_fields($ret_arr, $datas);

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
             //删除老的单子信息，主表，附表
            DELETE_OLD:{
                //方法在AppController.php
                $this->delete_old();
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

        //$attrArr['applyname'] = $datas['applyname']; //请假人姓名
        $attrArr['applyname'] = $this->userInfo->name ;
        $attrArr['about'] = $datas['reason']; //事由
        
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
        $mainArr['attachment'] = $datas['attachment'];
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

        // 获取资金来源剩余金额
        $sourcesArr = array('sourceId'=>array(),'amount'=>array());
        $year = date('Y', time());
    
        foreach ($souces as $key => $value) {
            if ($pid == 0) {
                if ($value['ResearchSource']['year'] != $year) {
                    //不是本年的去掉 部门
                    unset($souces[$key]);
                    continue;
                }
            }
            
        	$sourcesArr['sourceId'][$key] = $value['ResearchSource']['id'];
        	$sourcesArr['amount'][$value['ResearchSource']['id']] = $value['ResearchSource']['amount'];
        }

        // 如果资金来源为空 直接返回空
        if(empty($sourcesArr['sourceId'])){
            echo json_encode(array(
                'html' => ''
            ));
            exit;
        }

        // 资金来源剩余金额
        $surplusArr = $this->ApplyMain->getSurplusnew($sourcesArr);

        if (!empty($souces)) {
            foreach ($souces as $k => $v) {
                // 剩余金额大于0则显示
//                if($surplusArr[$v['ResearchSource']['id']] > 0){
                $selected = ($souid == $v['ResearchSource']['id']) ? 'selected' : '';
                $ret_option .= '<option value="' . $v['ResearchSource']['id'] . '"  ';
                $ret_option .= $selected . ' > ';
                $ret_option .= '【' . $v['ResearchSource']['source_channel'] . ' （' . $v['ResearchSource']['file_number'] . '） ￥' . $surplusArr[$v['ResearchSource']['id']] . '】</option>';
//                }
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
        
        // 统计 该资金来源 （已审批、未审批） 剩余资金不足 || 单子不参与核算的不统计
        if($_POST['is_calculation'] == 1){
            $source = $this->ResearchSource->getamount($_POST['filenumber']);
            $surplus = $this->ApplyMain->getSourceTotal($_POST['filenumber'], $source['ResearchSource']['amount'], $_POST['small_total']);
            if($surplus < 0){
                $this->ret_arr['msg'] = '当前已超出资金来源剩余金额';
                exit(json_encode($this->ret_arr));
            }
            //验证审批单申请 单科目费用 是否超过 项目对应单科目总金额
            if ($_POST['dep_pro'] != 0) {
//                $checkcost = $this->check_subject_cost_submit($_POST['dep_pro'], array('travel' => $datas['small_total']));
                $checkcost = $this->check_subject_cost_submit($_POST['dep_pro'], array($datas['subject'] => $datas['small_total']));
                if ($checkcost['code'] == -1) {
                    $this->ret_arr['msg'] = $checkcost['msg'];
                    exit(json_encode($this->ret_arr));
                }
            }
        }

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
            $select_user_id_sql = "select p.user_id,tm.user_id from t_research_project p left join t_team tp on p.project_team_id=tp.id  left join t_team_member tm on tp.fzr = tm.id where p.id='$project_id'";

            $project_and_team_arr = $this->ApplyMain->query($select_user_id_sql);
            $project_user_id = $project_and_team_arr[0]['p']['user_id']; //项目负责人user_id
            $project_team_user_id = empty($project_and_team_arr[0]['tm']['user_id']) ? 1 : $project_and_team_arr[0]['tm']['user_id'] ; //项目组负责人user_id 若为空则为单个项目
        }
        //默认正数
        $negative = false;
        if ($datas['small_total'] < 0) {
            $negative = true;
        }
        
        $applyArr = array('type' => $type, 'project_team_user_id' => $project_team_user_id, 'project_user_id' => $project_user_id);
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo, $project_id, $applyArr, $negative);


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

//         # 主表入库
//         $mainArr = array();
//         $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
//         $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
//         $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码

//         $mainArr['approval_process_id'] = $p_id; //审批流程id
//         $mainArr['type'] = $type;
//         $mainArr['attachment'] = $datas['attachment'];
//         $mainArr['is_calculation'] = $datas['is_calculation'] == 1 ? 1 : 0;
//         $mainArr['source_id'] = $datas['filenumber'];
//         $mainArr['name'] = $datas['declarename'];
//         $mainArr['project_id'] = $project_id;
//         $mainArr['department_id'] = $department_id;
//         $mainArr['table_name'] = $table_name;
//         $mainArr['user_id'] = $this->userInfo->id;
//         $mainArr['total'] = $datas['small_total'];
//         $mainArr['attr_id'] = $attrId;
//         $mainArr['project_user_id'] = $project_user_id;
//         $mainArr['project_team_user_id'] = $project_team_user_id;
//         $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
//         $mainArr['ctime'] = date('Y-m-d H:i:s', time());
// //        $mainArr['subject'] = json_encode(array('travel' => $datas['small_total']));
//         $mainArr['subject'] = json_encode(array($datas['subject'] => $datas['small_total']));

        if ($attrId) {
            //主表入库
            $datas['pid'] = $p_id;  //审批流程id
            $datas['type'] = $type;
            $datas['project_id'] = $project_id;
            $datas['department_id'] = $department_id;
            $datas['table_name'] = $table_name;
            $datas['user_id'] = $this->userInfo->id;
            $datas['total'] = $datas['small_total'];
            $datas['attr_id'] = $attrId;
            $datas['project_user_id'] = $project_user_id;
            $datas['project_team_user_id'] = $project_team_user_id;
            $datas['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
            $datas['ctime'] = date('Y-m-d H:i:s', time());
            $datas['subject'] = json_encode(array($datas['subject'] => $datas['small_total']));
            $mainArr = array();
            $mainArr = $this->ApplyMain->add_main_fields($ret_arr, $datas);

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
            //删除老的单子信息，主表，附表
            DELETE_OLD:{
                //方法在AppController.php
                $this->delete_old();
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
//        print_r($datas);die;
        header("Content-type: text/html; charset=utf-8");

        if ( (empty($datas['team']) && $datas['team'] != 0) || empty($datas['ctime']) || empty($datas['file_number']) || empty($datas['material_name']) || empty($datas['unit']) || empty($datas['nums']) || empty($datas['price']) || empty($datas['reason']) ) {
            echo "<script>alert('参数有误'); window.location = '/office/draf';</script>";
            exit;
        }

        $table_name = 'apply_caigou';
        $p_id = 0; //审批流id
        if (!$datas['team']) {
            $type = 2; // 部门类型
        	$project_id = 0;
        } else {
            $type = 1; //项目类型
        	$project_id = $datas['team'];
        }

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->ApplyCaigou->apply_create($type, $datas, (array) $this->userInfo);

        if (!empty($ret_arr['msg'])) {
            echo "<script>alert('".$ret_arr['msg']."'); window.location = '/office/draf'</script>";
            exit;
        }
        
        // 保存上传文件
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
            $new_file_name = WWW_ROOT . 'files' . DS . 'caigou' . DS . $new_name;

            if (!move_uploaded_file($tmp_file, $new_file_name)) {
                $new_name = ''; //如果没有上传成功，先不处理
            }
        }

        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = $department_arr['Department']['name'];
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        // 如果是 项目类型  取对应项目信息
        if($type == 1){
        	$project_arr = $this->ResearchProject->findById($datas['team']);
        	$name_str = $project_arr['ResearchProject']['name'];	// 支出项目名
        	$teams_str = $project_arr['ResearchProject']['project_team_id'];	// 所属项目组
            $team_id = $datas['team'];
        }else{
        	$name_str = $department_name;	// 支出项目名
        	$teams_str = 0;   // 所属项目组
            $team_id = $department_id;
        }
        
        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['team_id'] = $team_id;
        $attrArr['team_name'] = $name_str;
        $attrArr['project'] = $datas['project'];
        $attrArr['type'] = $type;
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
        $mainArr['name'] = '果树所采购申请单';
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;
        $mainArr['team_id'] = 0;
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
            //删除老的单子信息，主表，附表
            DELETE_OLD:{
                //方法在AppController.php
                $this->delete_old();
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

    }

    // 田间作业包工申请表
    public function gss_contractor() {

        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_contractor_save($_POST);
        } else {
            //取出自己的团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            
            $this->render();
        }
    }

    private function gss_contractor_save($datas) {
        if (empty($datas['number']) || empty($datas['dep_pro']) || empty($datas['personnel']) || empty($datas['time_address']) || empty($datas['content'])
        ) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_baogong';
        $p_id = 0; //审批流id

       
        $type = 3; //团队类型
        $team_id = $datas['dep_pro'];
        
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->ApplyBaogong->apply_create($type, $datas, (array)$this->userInfo);
        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }
        

        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['number'] = $datas['number'];
        $attrArr['team_id'] = $team_id;
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;


        $attrArr['personnel'] = $datas['personnel'];
        $attrArr['time_address'] = $datas['time_address'];
        $attrArr['content'] = $datas['content'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyBaogong->begin();
        $attrId = $this->ApplyBaogong->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '田间作业包工申请表';
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
            $this->ApplyBaogong->rollback();
        }
        $mainId ? $commitId = $this->ApplyBaogong->rollback() : $commitId = $this->ApplyBaogong->commit();


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
            $department_arr = $this->Department->find('first',array('conditions' => array('id' => $department_id,'type'=>1 )));
            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    // 职工因公不休或不全休带薪假审批表
    private function gss_endlessly_save($datas) {
        if (empty($datas['declarename']) || empty($datas['ctime']) || empty($datas['applyname']) || empty($datas['start_time']) 
                || empty($datas['years']) || empty($datas['vacation_days'])
                || empty($datas['days_off']) || empty($datas['rest_days'])
                || empty($datas['reason'])
                ) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_endlessly';
        $p_id = 0; //审批流id

        if (!$datas['dep_pro']) {
            //说明是部门
            $type = 2; //行政部门
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['dep_pro'];
        }
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->ApplyEndlessly->apply_create($type, $datas, (array)$this->userInfo);
        
        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['start_work'] = $datas['start_work'];

        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['team_id'] = $team_id;
        $attrArr['start_time'] = $datas['start_time'];
        $attrArr['yx_vacation_days'] = $datas['yx_vacation_days'];
        $attrArr['years'] = $datas['years'];
        $attrArr['vacation_days'] = $datas['vacation_days'];
        $attrArr['days_off'] = $datas['days_off'];
        $attrArr['rest_days'] = $datas['rest_days'];
        $attrArr['reason'] = $datas['reason'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyEndlessly->begin();
        $attrId = $this->ApplyEndlessly->add($attrArr);
        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '职工因公不休或不全休带薪假审批表';
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
            $this->ApplyEndlessly->rollback();
        }
        $mainId ? $commitId = $this->ApplyEndlessly->rollback() : $commitId = $this->ApplyEndlessly->commit();


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
            $this->gss_seal_save($_POST);
        } else {
             //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            if ($department_arr['Department']['type'] != 1) {
                $department_arr = array();//只取行政
            }
            $dep_list = $this->Department->deplist();

            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);
            
            $seal_count = $this->ApplySeal->find('count');
            $this->set('number',sprintf('%04s',$seal_count+1));
            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->set('dep_list', $dep_list[1]);
            $this->render();
        }
    }

    //印信使用签批单保存
    private function gss_seal_save($datas) {
        if (empty($datas['applyname']) || empty($datas['oddnum']) || empty($datas['sealtype']) || empty($datas['filetype']) /*|| empty($datas['filenum'])*/) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_seal';
        $p_id = 0; //审批流id

        if (!$datas['dep_team']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['dep_team'];
        }
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->ApplySeal->apply_create($type, $datas, (array)$this->userInfo);
        
        
         if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['applyname'] = $datas['applyname'];
        $attrArr['oddnum'] = $datas['oddnum'];
        $attrArr['department'] = $datas['department'];
        $attrArr['dep_name'] = $datas['department_name'];
        $attrArr['dep_team'] = $datas['dep_team'];
        $attrArr['dep_team_name'] = $datas['dep_team_name'];
        $attrArr['sealtype'] = $datas['sealtype'];
        $attrArr['filetype'] = json_encode( $datas['filetype'] );
        $attrArr['filenum'] = $datas['filenum'];
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplySeal->begin();
        $attrId = $this->ApplySeal->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '印信使用签批单';
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
            $this->ApplySeal->rollback();
        }
        $mainId ? $commitId = $this->ApplySeal->rollback() : $commitId = $this->ApplySeal->commit();


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
             if (empty($datas['type']) || empty($datas['datestr']) || empty($datas['company']) || empty($datas['hierarchy']) || empty($datas['urgency']) || empty($datas['num']) || empty($datas['document_number']) || empty($datas['file_title'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_received';
        $p_id = 0; //审批流id

        // 所办 、党办 $type=2、$team_id = 0
        $type = 0; 
        $team_id = 0;  
        $project_id = 0;

        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->ApplyReceived->apply_create($datas['type'], $datas, (array)$this->userInfo);
        
        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }

        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['hierarchy'] = $datas['hierarchy'];
        $attrArr['text1'] = $datas['text1'];
        $attrArr['urgency'] = $datas['urgency'];
        $attrArr['num'] = $datas['num'];
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['ctime'] = $datas['datestr'];
        $attrArr['type'] = $datas['type'];
        $attrArr['company'] = $datas['company'];
        $attrArr['document_number'] = $datas['document_number'];
        $attrArr['file_title'] = $datas['file_title'] ;
        $attrArr['tel'] = $datas['tel'];
        $attrArr['user_cbr'] = $datas['user_cbr'];
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());
        # 开始入库
        $this->ApplyReceived->begin();
        $attrId = $this->ApplyReceived->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['attachment'] = $datas['attachment'];
        $mainArr['name'] = '果树所来文批办单';
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
            $this->ApplyReceived->rollback();
        }
        $mainId ? $commitId = $this->ApplyReceived->rollback() : $commitId = $this->ApplyReceived->commit();


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

    //发文 
    public function gss_send() {
        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_send_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            if ($department_arr['Department']['type'] == 2) {
                $department_arr = array();//只取行政
            }
            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }

    //保存发文
    private function gss_send_save($datas) {
        if (empty($datas['num']) || empty($datas['datastr']) || empty($datas['file_title']) || empty($this->userInfo->id) ) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_dispatch';
        $p_id = 0; //审批流id
        if (!$datas['dep']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $team_id = 0;
        } else {
            $type = 3; //团队类型
            $team_id = $datas['dep'];
        }
        $project_id = 0;
        
        $applyArr = array('type' => $type, 'project_team_user_id' => 0, 'project_user_id' => 0);
        $ret_arr = $this->ApplyDispatch->apply_create($type, $datas, (array)$this->userInfo);
        
        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['ctime'] = $datas['datastr'];
        $attrArr['num'] = $datas['num'];
        $attrArr['dep_id'] = $datas['dep'];
        $attrArr['compay'] = $datas['compay'];
        $attrArr['file_title'] = $datas['file_title'];
        $attrArr['user_name'] = $this->userInfo->name;
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyDispatch->begin();
        $attrId = $this->ApplyDispatch->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['attachment'] = $datas['attachment'];
        $mainArr['name'] = '果树所发文处理单';
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
            $this->ApplyDispatch->rollback();
        }
        $mainId ? $commitId = $this->ApplyDispatch->rollback() : $commitId = $this->ApplyDispatch->commit();


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

    //借阅
    public function gss_borrow() {
        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_borrow_save($_POST);
        } else {
            //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            if ($department_arr['Department']['type'] != 1) {
                $department_arr = array();//只取行政
            }
            $sql = "select team.* from t_team team left join t_team_member team_member on team.id=team_member.team_id where team.del=0 and team_member.user_id='{$user_id}'";
            $team_arr = $this->ApplyMain->query($sql);

            $this->set('team_arr', $team_arr);
            $this->set('department_arr', $department_arr);
            $this->render();
        }
    }
  
    //保存借阅
    private function gss_borrow_save($datas) {
        if ( empty($datas['datestr']) || empty($datas['content']) || empty($datas['purpose']) || empty($datas['borrow_user']) ) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_borrow';
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

        $ret_arr = $this->ApplyBorrow->apply_create($type, $datas, (array)$this->userInfo);
        
        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['user_name'] = $this->userInfo->name;
        $attrArr['company'] = $datas['company'];
        $attrArr['btime'] = $datas['datestr'];
        $attrArr['content'] = $datas['content'];
        $attrArr['purpose'] = $datas['purpose'];
        $attrArr['dep_id'] = $datas['dep_pro'];
        $attrArr['borrow_user'] = $datas['borrow_user'];
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyBorrow->begin();
        $attrId = $this->ApplyBorrow->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = '';
        $mainArr['name'] = '档案借阅申请表';
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
            $this->ApplyBorrow->rollback();
        }
        $mainId ? $commitId = $this->ApplyBorrow->rollback() : $commitId = $this->ApplyBorrow->commit();


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
    /*
     * 山西省农业科学院果树研究所门户网站新闻信息发布审批卡
     */
    public function gss_news() {
        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_news_save($_POST);
        } else {
             //获取部门和团队
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            if ($department_arr['Department']['type'] != 1) {
                //$department_arr = array();//只取行政
            }
//            print_r($department_arr['Department']['name']);die;
            $this->set('department_arr', $department_arr);
            $this->render();
        }
        
    }
     //保存新闻卡
    private function gss_news_save($datas) {
        if ( empty($datas['title']) || empty($datas['content'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_news';
        $p_id = 0; //审批流id
        $type = 2; //部门
        $team_id = 0;
//        if (!$datas['dep_pro']) {
//            //说明是部门
//            $type = 2; //类型暂定为0
//            $team_id = 0;
//        } else {
//            $type = 3; //团队类型
//            $team_id = $datas['dep_pro'];
//        }
        $project_id = 0;

        $ret_arr = $this->ApplyNews->apply_create($type, $datas, (array)$this->userInfo);
        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['title'] = $datas['title'];
        $attrArr['content'] = $datas['content'];
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyNews->begin();
        $attrId = $this->ApplyNews->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = $datas['attachment'];
        $mainArr['name'] = '新闻签发卡';
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
            $this->ApplyNews->rollback();
        }
        $mainId ? $commitId = $this->ApplyNews->rollback() : $commitId = $this->ApplyNews->commit();


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
    
    //果树研究所请示报告卡片
    public function gss_request_report($mid = 0) {
        if ($this->request->is('ajax') && !empty($_POST['declarename'])) {
            $this->gss_request_report_save($_POST);
        } else {
            // 当前用所参与科研项目
            $pro_conditions = array('conditions' => array('user_id' => $this->userInfo->id), 'fields' => array('project_id'));
            $proArr = $this->ProjectMember->find('list', $pro_conditions);
            // 所参与项目 详情
            $conditions = array('conditions' => array('id' => $proArr, 'del' => 0, 'code' => 4, 'is_finish' => 0), 'fields' => array('id', 'name'));
            $projectInfo = $this->ResearchProject->find('list', $conditions);
            //print_r($projectInfo);die;
             //获取部门
            $user_id = $this->userInfo->id;
            $department_id = $this->userInfo->department_id;
            $department_arr = $this->Department->findById($department_id);
            if ($department_arr['Department']['type'] != 1) {
                //$department_arr = array();//只取行政
            }
            $this->set('is_department', !empty($department_arr) ? $department_arr['Department']['type'] : 2);
            $this->set('pro_arr', $proArr);
            $this->set('department_arr', $department_arr);
            $this->set('projectInfo', $projectInfo);
            // 重新提交申请  获取旧申请数据
            if ($mid) {
                $applyArr = $this->applyInfos($mid, 'ApplyRequestReport');
                $this->set('mainInfo', $applyArr['ApplyMain']);
                $this->set('attrInfo', $applyArr['ApplyRequestReport']);
//                print_r($applyArr['ApplyRequestReport']);die;
            }
            $this->render();
        }
    }
    
    //果树研究所请示报告卡片
    private function gss_request_report_save($datas) {
        if (empty($datas['content'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_request_report';
        $p_id = 0; //审批流id
        $type = 2; //部门
        $team_id = 0;
        if (!$datas['dep_pro']) {
            //说明是部门
            $type = 2; //类型暂定为0
            $project_id = 0;
        } else {
            $type = 1; //科研
            $project_id = $datas['dep_pro'];
            $project_arr = $this->ResearchProject->findById($project_id);
        }
        //$project_id = 0;

        $ret_arr = $this->ApplyRequestReport->apply_create($type, $datas, (array)$this->userInfo);
        //print_r($ret_arr);die;
        if (!empty($ret_arr['msg'])) {
            //说明出问题了
            $this->ret_arr['msg'] = $ret_arr['msg'];
            echo json_encode($this->ret_arr);
            exit;
        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        $department_fzr = !empty($department_arr) ? $department_arr['Department']['user_id'] : 0;  // 部门负责人

        $attrArr = array();
        $attrArr['user_id'] = $this->userInfo->id;
        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = ($type == 1) ? $project_arr['ResearchProject']['name'] : $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['content'] = $datas['content'];
        $attrArr['create_time'] = date('Y-m-d H:i:s', time());

        # 开始入库
        $this->ApplyRequestReport->begin();
        $attrId = $this->ApplyRequestReport->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = $datas['attachment'];
        $mainArr['name'] = '果树研究所请示报告卡片';
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
            $this->ApplyRequestReport->rollback();
        }
        $mainId ? $commitId = $this->ApplyRequestReport->rollback() : $commitId = $this->ApplyRequestReport->commit();


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
            //删除老的单子信息，主表，附表
            DELETE_OLD:{
                //方法在AppController.php
                $this->delete_old();
            }
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '申请成功';
        } else {
            $this->ret_arr['msg'] = '申请失败';
        }
        echo json_encode($this->ret_arr);
        exit;    
    }
    
    /**
     * ajax 根据部门id或是项目id取出user
     */
    public function get_users_by_dep() {
        if ($this->request->is('ajax')) {
            $pid = $_POST['pid'];
            if ($pid === '') {
                echo json_encode(array(
                    'code' => 0,
                    'data' => array()
                ));
                exit;
            }
            if ($pid === '0') {
                //部门
                $user_dep_id = $this->userInfo->department_id;
                $users = $this->User->query("select *from t_user User where department_id='{$user_dep_id}'");
//                var_dump(count($users));die;
            } else {
                //李捷 2、吕英忠 6、乔永胜 5、李全 8、李登科 9、赵旗峰 7、李志平 3 这几个人的话是用科研项目
                //array(2, 6, 5, 8, 9, 7, 3)
                //去除部门
                $sql = "select User.* from t_user User left join t_project_member t_p_m on User.id=t_p_m.user_id left join t_department t_d on t_d.id=User.department_id where (t_d.type!=1 or (t_d.type=1 and User.id in (2, 6, 5, 8, 9, 7, 3))) and t_p_m.project_id={$pid}";
//                echo $sql;die;
                $users = $this->User->query($sql);
            }
            echo json_encode(array(
                'code' => 0,
                'data' => $users
            ));
            exit;
        }
        
    }
    
    /**
     * ajax 根据部门id或是团队id取出user
     */
    public function get_users_by_team() {
        if ($this->request->is('ajax')) {
            $pid = $_POST['pid'];
            if ($pid === '') {
                echo json_encode(array(
                    'code' => 0,
                    'data' => array()
                ));
                exit;
            }
            if ($pid === '0') {
                //部门
                $user_dep_id = $this->userInfo->department_id;
                $users = $this->User->query("select *from t_user User where department_id='{$user_dep_id}'");
            } else {
                //团队
                $sql = "select User.name from t_team_member User where User.team_id = {$pid} ;" ;
                $users = $this->User->query($sql);
            }
            echo json_encode(array(
                'code' => 0,
                'data' => $users
            ));
            exit;
        }
        
    }
    
    
}
