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
        $uinfo = (array) $uinfo;
        if ($uinfo['can_approval'] != 2) {
            return false;
        }

        // 获取申请详情
        $applyinfo = $this->apply_info($apply_id);
        // 获取审批流
        $apply_liu = $this->apply_process($applyinfo['approval_process_id']);
        $liuArr = explode(',', $apply_liu['approve_ids']);


        // 项目负责人、项目组负责人 特殊处理
        // 获取审批流中为11，12角色的 code值
        $before_11_code = $before_12_code = -1;
        foreach ($liuArr as $kx => $vx) {
            if ($vx == 11) { 
                $before_11_code = ($kx-1) >= 0 ? $liuArr[$kx-1]*2 :0;
            }
            if ($vx == 12) { 
                $before_12_code = ($kx-1) >= 0 ? $liuArr[$kx-1]*2 :0;
            }
        }

        if($applyinfo['code'] == $before_11_code &&  $uinfo['id'] == $applyinfo['project_user_id']){
            $uinfo['position_id'] = 11 ;
        }
        if($applyinfo['code'] == $before_12_code &&  $uinfo['id'] == $applyinfo['project_team_user_id']){
            $uinfo['position_id'] = 12 ;
        }


        // 当前用户角色是否有审核权
        if ($uinfo['position_id'] != $applyinfo['next_approver_id']) {
            return false;
        }
        // 当前申请已通过
        if ($applyinfo['code'] == 10000) {
            return false;
        }

        $contents = array('code' => '', 'next_id' => '', 'code_id' => '');
        $contents['code_id'][0] = $uinfo['id'];

        switch ($applytype) {
            case 2:
                $contents['code'] = $uinfo['position_id'] * 2 - 1;
                return $contents;
                break;
            case 1:
                foreach ($liuArr as $k => $v) {
                    if ($v == $uinfo['position_id']) {
                        $next_id = isset($liuArr[$k + 1]) ? $liuArr[$k + 1] : $v;  // 下一审批职务
                        $next_next_id = isset($liuArr[$k + 2]) ? $liuArr[$k + 2] : $next_id; // 下下一审批职务
                        $next_three_id = isset($liuArr[$k + 3]) ? $liuArr[$k + 3] : $next_next_id; // 下下下一审批职务
                        break;
                    }
                }

              
                if ($next_three_id == $uinfo['position_id']) {
                    // 当前审批角色已是审批流中最后一个
                    $contents['code'] = 10000;
                    $contents['next_id'] = $next_id;  // 最后一个审核人
                    $contents['code_id'][1] = $uinfo['id'];
                } else {
                    $action_data = array(
                        'pid' => $applyinfo['project_id'], // 申请所属项目id
                        'uid' => $applyinfo['user_id'], // 申请人id
                        'department_id' => $applyinfo['department_id'], // 申请所属部门
                        'type' => $applyinfo['type'], // 申请类型
                        'total' => $applyinfo['total'], // 申请总费用
                    );
                    $apply_yz = $this->apply_action($next_id, $action_data);   // 下一审核人是否跳过 ture跳过
                    

                    if ($apply_yz) {
                          $contents['code_id'][2] = $apply_yz;
                        // 跳过下一审核人审核  验证下下一审核人是否跳过
                         $next_apply_yz = $this->apply_action($next_next_id, $action_data);  

                         if($next_apply_yz) {
                            $contents['code'] = ($next_next_id == $next_three_id) ? 10000 : $next_next_id * 2; 
                            $contents['next_id'] = $next_three_id; 
                            $contents['code_id'][3] = $next_apply_yz;
                         }else{
                            $contents['code'] = ($next_next_id == $next_id) ? 10000 : $next_id * 2;  // 如果下一审核角色和下下一审核角色相同，说明审批流已完成
                            $contents['next_id'] = $next_next_id;  // 如果跳过下一审核人则取下下一审核人
                         }
                    } else {
                        // 不跳过下一审核人
                        $contents['code'] = $uinfo['position_id'] * 2;
                        $contents['next_id'] = $next_id;
                    }
                }
                return $contents;
                break;
            default:
                return false;
        }
    }

    /**
     *  创建申请时验证 下一审核人角色
     *  @params: $apply_process_id 申请单审批流id; $uinfo 当前审核人信息;$project_id 项目id
     *  @response:
     */
    public function apply_create($apply_process_id, $uinfo, $project_id = 0) {
        // 获取审批流
        $uinfo = (array) $uinfo;
        $apply_liu = $this->apply_process($apply_process_id);
        $liuArr = explode(',', $apply_liu['approve_ids']);

        $contents = array('code' => '', 'next_id' => '', 'code_id' => '');

        foreach ($liuArr as $k => $v) {
            // 需科研角色审核
            switch ($v) {
                case 11:
                    $fzr = $this->apply_11($project_id, $uinfo['id']);
                    if ($fzr) { // 跳过 code_id 取当前角色
                        $contents['code_id'][] = $uinfo['id'];
                        break;
                    } else { // 不跳过 code_id取上一审核角色 返回
                        $contents['code'] = isset($liuArr[$k - 1]) ? $liuArr[$k - 1] * 2 : 0;
                        $contents['next_id'] = 11;  // 下一审批职务
                        break 2;
                    }
                case 12:
                    $xmzfzr = $this->apply_12($project_id, $uinfo['id']);
                    if ($xmzfzr) { // 跳过
                        $contents['code_id'][] = $uinfo['id'];
                        break;
                    } else { // 不跳过 返回
                        $contents['code'] = isset($liuArr[$k - 1]) ? $liuArr[$k - 1] * 2 : 0;
                        $contents['next_id'] = 12;  // 下一审批职务
                        break 2;
                    }
                    break;
                default:
                    if ($v == $uinfo['position_id']) {
                        $next_id = isset($liuArr[$k + 1]) ? $liuArr[$k + 1] : $v;  // 下一审批职务
                        $contents['code'] = isset($liuArr[$k + 1]) ? $uinfo['position_id'] * 2 : 10000;
                        $contents['next_id'] = $next_id;
                        $contents['code_id'][] = $uinfo['id'];
                    } else {
                        $contents['code'] = isset($liuArr[$k - 1]) ? $liuArr[$k - 1] * 2 : 0;;
                        $contents['next_id'] = $v;
                    }
                    break 2;
            }
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
            case 4:
                return $this->apply_4($data['department_id'], $data['type'], $data['uid']);
                break;
            case 5:
                return $this->apply_5($data['department_id'], $data['type'], $data['uid']);
                break;
            case 6:
                return $this->apply_6($data['total'], $data['uid']);
                break;
            case 13:
                return $this->apply_13($data['uid']);
                break;
            case 14:
                return $this->apply_14($data['uid']);
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
            return $pinfo['user_id'];
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
        $Team = $Project->query('select * from t_team_project as team where id = ' . $pinfo['project_team_id'] . ' and del = 0 '); 
        if(empty($Team) || $Team[0]['team']['id'] == 1){  // 无项目组 单个项目 返回项目负责人id
            return $pinfo['user_id'];
        }
        $team = $Team[0]['team'];
        // 项目组不为1 且 项目组负责人与申请人id相同 
        if ($team['id'] > 1 && $team['team_user_id'] == $uid) {
            return $team['team_user_id'];
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
                $zhuren = $Uinfo->find('list', array('conditions' => array('department_id' => 3, 'position_id' => 4 ,'del'=>0), 'fields' => array('id')));
                break;
            case 2:
                // 找对应行政部门 办公室主任
                $Uinfo = new User();
                $zhuren = $Uinfo->find('list', array('conditions' => array('department_id' => $department_id, 'position_id' => 4 ,'del'=>0), 'fields' => array('id')));
                break;
            default:
                return false;
        }

        if (in_array($uid, $zhuren)) {
            return $uid;
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
                require_once('../Model/User.php');
                $Uinfo = new User();
                $fusuozhang = $Uinfo->find('list', array('conditions' => array('department_id' => 3, 'position_id' => 5 ,'del'=>0), 'fields' => array('id')));
                if (in_array($uid, $fusuozhang)) {
                    return $uid;
                } else {
                    return false;
                }
                break;
            case 2:
                // 找对应行政部门 分管领导 副所长  ??????  科研部门、财务部门申请不需要本部门领导审批？？？？
                if (in_array($department_id, array(3, 5))) { //如果是科研部门、财务部门 则直接跳过
                    return $uid;
                }

                //部门分管副所长
                require_once('../Model/Department.php');
                $Department = new Department();
                $fusuozhang = $Department->findById($department_id);
                $fusuozhang = $fusuozhang['Department'];
                if ($uid == $fusuozhang['sld']) {
                    return $fusuozhang['sld'];
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

        require_once('../Model/User.php');
        $Uinfo = new User();
        $userinfo = $Uinfo->find('first', array('conditions' => array('position_id' => 6 ,'del'=>0), 'fields' => array('id')));        
        $userid = $userinfo['User']['id'];
        if ($total < 20000 || $uid == $userid) {  //小于2W 或申请人是所长
            return $userid;
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
        $userinfo = $Uinfo->find('list', array('conditions' => array('department_id' => 5, 'position_id' => 13 ,'del'=>0), 'fields' => array('id')));

        if (in_array($uid, $userinfo)) {
            return $uid;
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
        $userinfo = $Uinfo->find('list', array('conditions' => array('department_id' => 5, 'position_id' => 14 ,'del'=>0), 'fields' => array('id')));

        if (in_array($uid, $userinfo)) {
            return $uid;
        } else {
            return false;
        }
    }

}
