<?php

class ApprovalComponent extends Component {

    public $controller = true;
    public $component = array('Cookie');

    /**
     *  以下所用方法 都需先获取 申请费用信息
     *   返回code、下一审核人角色
     *   获取申请项目信息
     *  @params: $apply_id 申请费用id; $uinfo 当前审核人信息;$applytype 审批状态
     *  @response:
     */
    public function apply($apply_id, $uinfo, $applytype) {
        // 当前用户无审核权 
        $uinfo = (array)$uinfo;
        if ($uinfo['can_approval'] != 2) {
            return false;
        }

        // 获取申请详情
        $applyinfo = $this->apply_info($apply_id);

        // 当前用户角色是否有审核权
        if ($uinfo['position_id'] != $applyinfo['next_approver_id']) {
            return false;
        }
        // 当前申请已通过
        if ($applyinfo['next_approver_id'] == 10000) {
            return false;
        }
        // 获取审批流
        $apply_liu = $this->apply_process($applyinfo['approval_process_id']);
        $liuArr = explode(',', $apply_liu['approve_ids']);

        $contents = array('code' => '', 'next_id' => '');

        switch ($applytype) {
            case 2:
                $contents['code'] = $uinfo['position_id'] * 2 - 1;
                return $contents;
                break;
            case 1:
                foreach ($liuArr as $k => $v) {
                    if ($v == $uinfo['position_id']) {
                        $next_id = isset($liuArr[$k + 1]) ? $liuArr[$k + 1] : 10000;  // 下一审批职务
                        $next_next_id = isset($liuArr[$k + 2]) ? $liuArr[$k + 2] : 10000; // 下下一审批职务
                        break;
                    }
                }

                $action_data = array(
                    'pid' => $applyinfo['project_id'], // 申请所属项目id
                    'uid' => $applyinfo['user_id'], // 申请人id
                    'department_id' => $applyinfo['department_id'], // 申请所属部门
                    'type' => $applyinfo['type'], // 申请类型
                    'total' => $applyinfo['total'], // 申请总费用
                );
                $apply_yz = $this->apply_action($next_id, $action_data);   // 下一审核人是否跳过 ture跳过

                $contents['code'] = $uinfo['position_id'] * 2;
                $contents['next_id'] = $apply_yz ? $next_next_id : $next_id;  // 如果跳过下一审核人则取下下一审核人
                return $contents;
                break;
            default:
                return false;
        }
    }

    /**
     *  创建申请时验证 下一审核人角色
     *  @params: $apply_process_id 申请单审批流id; $uinfo 当前审核人信息;
     *  @response:
     */
    public function apply_create($apply_process_id, $uinfo) {
        // 获取审批流
        $uinfo = (array) $uinfo;
        $apply_liu = $this->apply_process($apply_process_id);
        $liuArr = explode(',', $apply_liu['approve_ids']);
        
        $contents = array('code' => '', 'next_id' => '');
        foreach ($liuArr as $k => $v) {
            if ($v == $uinfo['position_id']) {
                $next_id = isset($liuArr[$k + 1]) ? $liuArr[$k + 1] : 10000;  // 下一审批职务
                $contents['code'] = $uinfo['position_id'] * 2;
                $contents['next_id'] = $next_id; 
            } else {
                $contents['code'] = 0;
                $contents['next_id'] = $v; 
            }
            break;
        }

        return $contents;  // 如果跳过下一审核人则取下下一审核人
    }

    /**
     *   获取申请验证信息
     *  @params: $aid 进度id; $data 验证信息
     *  @response:
     */
    private function apply_action($aid, $data) {
        switch ($aid) {
            case 11:
                return $this->apply_11($data['pid'], $data['uid']);
                break;
            case 12:
                return $this->apply_12($data['pid'], $data['uid']);
                break;
            case 13:
                return $this->apply_13($data['uid']);
                break;
            case 14:
                return $this->apply_14($data['uid']);
                break;
            case 4:
                return $this->apply_4($data['department_id'], $data['type'], $data['uid']);
                break;
            case 5:
                return $this->apply_5($data['department_id'], $data['type'], $data['uid']);
                break;
            case 6:
                return $this->apply_6($data['total'], $data['uid']);
                break;
        }
    }

    /**
     *  以下所用方法 都需先获取 申请费用信息
     *
     *   获取申请项目信息
     *  @params: $apply_id 申请费用id; 
     *  @response:
     */
    public function apply_info($apply_id = 0) {
        $info = array();
        if (!empty($apply_id)) {
            require_once('../Model/ApplyMain.php');
            $applyInfo = new ApplyMain();
            $info = $applyInfo->findById($apply_id);
            $info = $info['ApplyMain'];
        }
        return $info;
    }

    /**
     *  以下所用方法 都需先获取 申请审批流
     *
     *   获取申请审批流
     *  @params: $process_id 申请流id; 
     *  @response:
     */
    public function apply_process($process_id = 0) {
        $info = array();
        if (!empty($process_id)) {
            require_once('../Model/ApprovalProcess.php');
            $Process = new ApprovalProcess();
            $info = $Process->findById($process_id);
            $info = $info['ApprovalProcess'];
        }
        return $info;
    }

    /**
     *  1、审批人是否项目申请人，是：直接跳过
     *  2、审批人是否申请人对应部门 办公室主任、副所长
     *
     *   项目负责人审核
     *  @params: $pid 申请所属项目id; $uid 申请人id
     *  @response:
     */
    public function apply_11($pid = 0, $uid = 0) {
        if (!empty($pid)) {
            require_once('../Model/ResearchProject.php');
            $Project = new ResearchProject();
            $pinfo = $Project->findById($pid);
            $pinfo = $pinfo['ResearchProject'];
        }

        if ($uid == $pinfo['user_id']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  1、审批人是否项目申请人，是：直接跳过
     *  2、审批人是否申请人对应部门 办公室主任、副所长
     *  3、申请项目是否属于项目组下
     *
     *   项目组负责人审核
     *  @params: $pid 申请所属项目id; $uid 申请人id
     *  @response:
     */
    public function apply_12($pid = 0, $uid = 0) {
        if (!empty($pid)) {
            require_once('../Model/ResearchProject.php');
            $Project = new ResearchProject();
            $pinfo = $Project->findById($pid);
            $pinfo = $pinfo['ResearchProject'];
        }

        // 如果项目属于项目组则取找项目组负责人
        // 否则 直接返回 
        $Team = $Project->query('select * from t_team_project as team where id = '.$pinfo['project_team_id'].' and del = 0 ');
        $team = $Team[0]['team'];
        // 项目组不为1 且 项目组负责人与申请人id相同 
        if ($team['id'] > 1 && $team['team_user_id'] == $uid ) {
            return true;
        } else {
            return false;
        }

        // 验证uid 是否 项目组负责人 project_team_uid 项目组负责人id
        if ($uid == $project_team_uid) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  1、审批人是否项目申请人，是：直接跳过
     *  2、审批人是否申请人对应部门 办公室主任、副所长
     *
     *   对应办公室主任（科研办公室主任）
     *  @params: $department_id 部门id; $type 申请类型：1科研、2行政; $uid 申请人id
     *  @response:
     *   $info = $this->apply_info($apply_id); // 先获取审报费用信息
     */
    public function apply_4($department_id = 0, $type = 0, $uid = 0) {

        // 根据$type 为1 去查项目所属 科研主任(department_id:3)
        // 为2 则去查 对应行政部门 办公室主任(position_id:4)
        switch ($type) {
            case 1:
                // 找科研主任
                $Uinfo = new User();
                $zhuren = $Uinfo->find('list', array('conditions' => array('department_id' => 3, 'position_id' => 4), 'fields' => array('id')));
                break;
            case 2:
                // 找对应行政部门 办公室主任
                $Uinfo = new User();
                $zhuren = $Uinfo->find('list', array('conditions' => array('department_id' => $department_id, 'position_id' => 4), 'fields' => array('id')));
                break;
            default:
                return false;
        }

        if (in_array($uid, $zhuren)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  1、审批人是否项目申请人，是：直接跳过
     *  2、审批人是否申请人对应部门 办公室主任、副所长
     *  3、
     *
     *   对应副所长（科研副所长）
     *  @params: $department_id 部门id;$type 申请类型：1科研、2行政; $uid 申请人id
     *  @response:
     */
    public function apply_5($department_id = 0, $type = 0, $uid = 0) {

        // 根据$type 为1 去查项目所属 科研主任(department_id:3)
        // 为2 则去查 对应行政部门 办公室主任(position_id:4)
        switch ($type) {
            case 1:
                // 找科研副所长
                $Uinfo = new User();
                $fusuozhang = $Uinfo->find('list', array('conditions' => array('department_id' => 3, 'position_id' => 5), 'fields' => array('id')));
                if (in_array($uid, $fusuozhang)) {
                    return true;
                } else {
                    return false;
                }
                break;
            case 2:
                // 找对应行政部门 分管领导 副所长  ??????  科研部门、财务部门申请不需要本部门领导审批？？？？
                if (in_array($department_id, array(3, 5))) { //如果是科研部门、财务部门 则直接跳过
                    return true;
                }

                //部门分管副所长
                $Department = new Department();
                $fusuozhang = $Department->findById($department_id);
                $fusuozhang = $fusuozhang['Department'];
                if ($uid == $fusuozhang['sld']) {
                    return true;
                } else {
                    return false;
                }
                break;
            default:
                return false;
        }
    }

    /**
     *  1、审批人是否项目申请人，是：直接跳过
     *  2、申报金额低于2W 所长直接通过
     *
     *    所长
     *  @params: $total 申请总费用; $uid 申请人id
     *  @response:
     */
    public function apply_6($total = 0, $uid = 0) {
        if ($total < 20000) {  //小于2W
            return true;
        }

        require_once('../Model/User.php');
        $Uinfo = new User();
        $userinfo = $Uinfo->findByPositionId(6);
        $userinfo = $userinfo['User'];

        if ($uid == $userinfo['id']) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  1、审批人是否项目申请人，是：直接跳过
     *
     *   财务副所长
     *  @params:  $uid 申请人id
     *  @response:
     */
    public function apply_13($uid = 0) {

        require_once('../Model/User.php');
        $Uinfo = new User();
        $userinfo = $Uinfo->find('list', array('conditions' => array('department_id' => 5, 'position_id' => 5), 'fields' => array('id')));
        $userinfo = $userinfo['User'];


        if (in_array($uid, $userinfo)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     *  1、审批人是否项目申请人，是：直接跳过
     *
     *   财务办公室主任
     *  @params:  $uid 申请人id
     *  @response:
     */
    public function apply_14($uid = 0) {

        require_once('../Model/User.php');
        $Uinfo = new User();
        $userinfo = $Uinfo->find('list', array('conditions' => array('department_id' => 5, 'position_id' => 4), 'fields' => array('id')));
        $userinfo = $userinfo['User'];

        if (in_array($uid, $userinfo)) {
            return true;
        } else {
            return false;
        }
    }

}
