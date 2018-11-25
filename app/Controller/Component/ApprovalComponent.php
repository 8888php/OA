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

        // 获取申请详情
        $applyinfo = $this->apply_info($apply_id);

        // 先判断当前节点是否有加签审批人，如果有走加签审批流程
        if ($applyinfo['add_lots'] != '0') {
            $this->addLotsApply($uinfo, $applyinfo);
        }

        // 当前用户无审核权 
        $uinfo = (array) $uinfo;
        if ($uinfo['can_approval'] != 2) {
            return false;
        }

        // 不属当前用户审核
        if ($applyinfo['next_apprly_uid'] != $uinfo['id']) {
            return false;
        }
        // 获取审批流
        $apply_liu = $this->apply_process($applyinfo['approval_process_id']);
        $liuArr = explode(',', $apply_liu['approve_ids']);


        // 项目负责人、项目组负责人 特殊处理
        // 获取审批流中为11，12角色的 code值
        $before_11_code = $before_12_code = $before_15_code = -1;
        foreach ($liuArr as $kx => $vx) {
            if ($vx == 11) {
                $before_11_code = ($kx - 1) >= 0 ? $liuArr[$kx - 1] * 2 : 0;
            }
            if ($vx == 12) {
                $before_12_code = ($kx - 1) >= 0 ? $liuArr[$kx - 1] * 2 : 0;
            }
            if ($vx == 15) {
                $before_15_code = ($kx - 1) >= 0 ? $liuArr[$kx - 1] * 2 : 0;
            }
        }

        if ($applyinfo['code'] == $before_11_code && $uinfo['id'] == $applyinfo['project_user_id']) {
            $uinfo['position_id'] = 11;
        }
        if ($applyinfo['code'] == $before_12_code && $uinfo['id'] == $applyinfo['project_team_user_id']) {
            $uinfo['position_id'] = 12;
        }
        if ($applyinfo['code'] == $before_15_code && $uinfo['id'] == $applyinfo['department_fzr']) {
            $uinfo['position_id'] = 15;
        }

        // 如果申请单是部门类中财务科提交的申请，则部门负责人、所领导 同审批流中 分管财务所长、财务科长相同，这两个角色在第一次审批时更改他们的 position_id 为负责人、所领导
        if ($applyinfo['type'] == 2 && $applyinfo['department_id'] == 5) {
            // 财务科长  转  部门负责人
            if ($applyinfo['code'] == 0 && $uinfo['position_id'] == 14) {
                $uinfo['position_id'] = 15;
            }
            // 分管财务所长  转  所领导
            if ($applyinfo['code'] == 30 && $uinfo['position_id'] == 13) {
                $uinfo['position_id'] = 5;
            }
        }


        // 当前用户角色是否有审核权 审核到分管副所长时不验证 (验证下一审核人职务id)
        // if ($applyinfo['next_approver_id'] !=5 && $uinfo['position_id'] != $applyinfo['next_approver_id']) {
        //     return false;
        // }
        // 当前用户角色是否有审核权 审核到分管副所长时不验证 (验证下一审核人uid)
        if ($applyinfo['next_approver_id'] != 5 && $uinfo['id'] != $applyinfo['next_apprly_uid']) {
            return false;
        }

        // 当前申请已通过
        if ($applyinfo['code'] == 10000) {
            return false;
        }

        $contents = array('code' => '', 'next_id' => 0, 'code_id' => array(), 'next_uid' => 0);
        $contents['code_id'][0] = $uinfo['id'];

        switch ($applytype) {
            case 2:
                // $contents['code'] = $uinfo['position_id'] * 2 - 1;
                //审核人可能是一人多角色
                $contents['code'] = $applyinfo['next_approver_id'] * 2 - 1;
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

                if ($next_three_id == $uinfo['position_id'] && $next_three_id != 15) {
                    // 当前审批角色已是审批流中最后一个
                    $contents['code'] = 10000;
                    $contents['next_id'] = $next_id;  // 最后一个审核人
                    //  $contents['code_id'][1] = $uinfo['id'];
                } else {
                    $action_data = array(
                        'pid' => $applyinfo['project_id'], // 申请所属项目id
                        'uid' => $uinfo['id'], // 当前审核人id
                        'department_id' => $applyinfo['department_id'], // 申请所属部门
                        'type' => $applyinfo['type'], // 申请类型
                        'total' => $applyinfo['total'], // 申请总费用
                    );
                    $apply_yz = $this->apply_action($next_id, $action_data);   // 下一审核人是否跳过 ture跳过


                    if ($apply_yz) {
                        //  $contents['code_id'][2] = $apply_yz;
                        // 跳过下一审核人审核  验证下下一审核人是否跳过
                        $next_apply_yz = $this->apply_action($next_next_id, $action_data);

                        if ($next_apply_yz) {
                            $contents['code'] = ($next_next_id == $next_three_id) ? 10000 : $next_next_id * 2;
                            $contents['next_id'] = $next_three_id;
                            //   $contents['code_id'][3] = $next_apply_yz;
                        } else {
                            $contents['code'] = ($next_next_id == $next_id) ? 10000 : $next_id * 2;  // 如果下一审核角色和下下一审核角色相同，说明审批流已完成
                            $contents['next_id'] = $next_next_id;  // 如果跳过下一审核人则取下下一审核人
                        }
                    } else {
                        // 不跳过下一审核人
                        //$contents['code'] = $uinfo['position_id'] * 2;
                        $contents['code'] = $applyinfo['next_approver_id'] * 2;
                        $contents['next_id'] = $next_id;
                    }
                }

                break;
            default:
                return false;
        }
        

        // 审核成功 根据 申请所属部门 和 下一审核角色 取 下一审核人id
        if (empty($contents['next_id'])) {
            return false;
        } else {
            $contents['next_uid'] = $this->applyUser($applyinfo, $contents);
        }
        return $contents;
    }

    /**
     *  获取审核人id
     *  @params: $applyinfo 申请单详情; $contents 返回审核信息
     *  @response:
     */
    public function applyUser($applyinfo, $contents) {
        switch ($contents['next_id']) {
            case 11:
                //项目负责人
                return $applyinfo['project_user_id'];
                break;
            case 12:
                //项目组负责人
                return $applyinfo['project_team_user_id'];
                break;
            case 4:
                //科室主任  部门负责人
                require_once('../Model/Department.php');
                $depinfo = new Department();
                switch ($applyinfo['type']) {
                    case 1:
                        $depinfo = $depinfo->findById(3);
                        break;
                    case 2:
                        $depinfo = $depinfo->findById($applyinfo['department_id']);
                        break;
                }
                return $depinfo['Department']['user_id'];
                break;
            case 5:
                //分管副所长
                switch ($applyinfo['type']) {
                    case 1:
                        // 科研项目申请单，查找该项目分管所领导
                        require_once('../Model/ResearchProject.php');
                        $depinfo = new ResearchProject();
                        $dep_info = $depinfo->findById($applyinfo['project_id']);
                        return $dep_info['ResearchProject']['approval_sld'];
                        break;
                    case 2:
                        // 部门申请单，查找该部门分管所领导
                        require_once('../Model/Department.php');
                        $depinfo = new Department();
                        $dep_info = $depinfo->findById($applyinfo['department_id']);
                        return $dep_info['Department']['sld'];
                        break;
                }
                break;
            case 6:
                //所长
                require_once('../Model/User.php');
                $uinfo = new User();
                $u_info = $uinfo->findByPositionId(6);
                return $u_info['User']['id'];
                break;
            case 13:
                //财务副所长
                require_once('../Model/Department.php');
                $depinfo = new Department();
                $dep_info = $depinfo->findById(5);
                return $dep_info['Department']['sld'];
                break;
            case 14:
                // 财务办公室主任
                require_once('../Model/Department.php');
                $depinfo = new Department();
                $dep_info = $depinfo->findById(5);
                return $dep_info['Department']['user_id'];
                break;
            case 15:
                //部门负责人
                require_once('../Model/Department.php');
                $depinfo = new Department();
                $dep_info = $depinfo->findById($applyinfo['department_id']);
                return $dep_info['Department']['user_id'];
                break;
            default:
                return false;
        }
    }

    /**
     *  创建申请时验证 下一审核人角色
     *  @params: $apply_process_id 申请单审批流id; $uinfo 当前审核人信息;$project_id 项目id;$applyArr 申请附属信息 $negative如果是负数则取最后一位审批人
     *  @response:
     */
    public function apply_create($apply_process_id, $uinfo, $project_id = 0, $applyArr, $negative = false) {
        // 获取审批流
        $uinfo = (array) $uinfo;
        $apply_liu = $this->apply_process($apply_process_id);
        $liuArr = explode(',', $apply_liu['approve_ids']);

        $contents = array('code' => '', 'next_id' => 0, 'code_id' => '', 'next_uid' => 0);
        if ($negative) {
            $contents['code'] = 0;
            $contents['next_id'] = 14;
            $contents['code_id'] = 0;
            $contents['next_uid'] = 4;
            return $contents;
        }
        foreach ($liuArr as $k => $v) {
            // 需科研角色审核
            switch ($v) {
                case 11:
                    $fzr = $this->apply_11($project_id, $uinfo['id']);
                    if ($fzr) { // 跳过 code_id 取当前角色
                        // $contents['code_id'][] = $uinfo['id'];
                        break;
                    } else { // 不跳过 code_id取上一审核角色 返回
                        $contents['code'] = isset($liuArr[$k - 1]) ? $liuArr[$k - 1] * 2 : 0;
                        $contents['next_id'] = 11;  // 下一审批职务
                        break 2;
                    }
                case 12:
                    $xmzfzr = $this->apply_12($project_id, $uinfo['id']);
                    if ($xmzfzr) { // 跳过
                        //  $contents['code_id'][] = $uinfo['id'];
                        break;
                    } else { // 不跳过 返回
                        $contents['code'] = isset($liuArr[$k - 1]) ? $liuArr[$k - 1] * 2 : 0;
                        $contents['next_id'] = 12;  // 下一审批职务
                        break 2;
                    }
                    break;
                case 15:
                    $bmfzr = $this->apply_15($uinfo['department_id'], $uinfo['id']);
                    if ($bmfzr) { // 跳过
                        // $contents['code_id'][] = $uinfo['id'];
                        break;
                    } else { // 不跳过 返回
                        $contents['code'] = isset($liuArr[$k - 1]) ? $liuArr[$k - 1] * 2 : 0;
                        $contents['next_id'] = $v;  // 下一审批职务
                        break 2;
                    }
                    break;
                default:
                    if ($v == $uinfo['position_id']) {
                        $next_id = isset($liuArr[$k + 1]) ? $liuArr[$k + 1] : $v;  // 下一审批职务
                        $contents['code'] = isset($liuArr[$k + 1]) ? $uinfo['position_id'] * 2 : 10000;
                        $contents['next_id'] = $next_id;
                        //   $contents['code_id'][] = $uinfo['id'];
                    } else {
                        $contents['code'] = isset($liuArr[$k - 1]) ? $liuArr[$k - 1] * 2 : 0;
                        ;
                        $contents['next_id'] = $v;
                        break 2;
                    }
            }
        }


        // 审核成功 根据 申请所属部门 和 下一审核角色 取 下一审核人id
        if (empty($contents['next_id'])) {
            return false;
        } else {
            $applyinfo = array();
            $applyinfo['type'] = $applyArr['type'];
            $applyinfo['project_user_id'] = $applyArr['project_user_id'];
            $applyinfo['department_id'] = $uinfo['department_id'];
            $applyinfo['project_team_user_id'] = $applyArr['project_team_user_id'];
            $contents['next_uid'] = $this->applyUser($applyinfo, $contents);
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
                return $this->apply_5($data);
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
            case 15:
                return $this->apply_15($data['department_id'], $data['uid']);
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
        if ($pinfo['project_team_id'] == 0) {  // 单个项目 返回项目负责人id
            return $pinfo['user_id'];
        }

        // $Team = $Project->query('select * from t_team_member as team where id = ' . $pinfo['project_team_id'] . '; ');

        $Team = $Project->query('select team.* from t_team t left join t_team_member team on t.fzr = team.id  where t.id = ' . $pinfo['project_team_id'] . '; ');

        if (empty($Team)) {  // 无项目组 返回项目负责人id
            return $pinfo['user_id'];
        }

        $team = $Team[0]['team'];
        //  项目组负责人与申请人id相同 
        if ($team['user_id'] == $uid) {
            return $team['user_id'];
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

        /*
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
         */

        if ($type = 1) {
            // 找科研主任
            $Uinfo = new User();
            $zhuren = $Uinfo->find('list', array('conditions' => array('department_id' => 3, 'position_id' => 4, 'del' => 0), 'fields' => array('id')));
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
     *  @params: $applyinfo 申请单信息 $department_id 部门id;$type 申请类型：1科研、2行政; $uid 申请人id
     *  @response:
     */
    public function apply_5($applyinfo) {

        // 根据$type 为1 去查项目所属 科研主任($applyinfo['department_id']:3)
        // 为2 则去查 对应行政部门 办公室主任($applyinfo['position_id']:4)
        switch ($applyinfo['type']) {
            case 1:
                // 科研项目申请单，查找该项目分管科研所领导
                require_once('../Model/ResearchProject.php');
                $depinfo = new ResearchProject();
                $dep_info = $depinfo->findById($applyinfo['pid']);
                return ($applyinfo['uid'] == $dep_info['ResearchProject']['approval_sld']) ? $applyinfo['uid'] : false;
                break;
            case 2:
                // 找对应行政部门 分管领导 副所长  ??????  科研部门、财务部门申请不需要本部门领导审批？？？？
                /* if (in_array($applyinfo['department_id'], array(3, 5))) { //如果是科研部门、财务部门 则直接跳过
                  return $uid;
                  }
                 */

                //部门分管副所长
                require_once('../Model/Department.php');
                $Department = new Department();
                $fusuozhang = $Department->findById($applyinfo['department_id']);
                $fusuozhang = $fusuozhang['Department'];
                if ($applyinfo['uid'] == $fusuozhang['sld']) {
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
        $userinfo = $Uinfo->find('first', array('conditions' => array('position_id' => 6, 'del' => 0), 'fields' => array('id')));
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
        $userinfo = $Uinfo->find('list', array('conditions' => array('department_id' => 5, 'position_id' => 13, 'del' => 0), 'fields' => array('id')));

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
        $userinfo = $Uinfo->find('list', array('conditions' => array('department_id' => 5, 'position_id' => 14, 'del' => 0), 'fields' => array('id')));

        if (in_array($uid, $userinfo)) {
            return $uid;
        } else {
            return false;
        }
    }

    /**
     *  1、审批人是否项目申请人，是：直接跳过
     *
     *   部门负责人
     *  @params:  $department_id 部门id;$type 申请类型：1科研、2行政; $depid 部门负责人id
     *  @response:
     */
    public function apply_15($department_id = 0, $depid = 0) {

        require_once('../Model/Department.php');
        $Dinfo = new Department();
        $Depinfo = $Dinfo->find('first', array('conditions' => array('id' => $department_id, 'user_id' => $depid, 'del' => 0), 'fields' => array('id')));

        if (!empty($Depinfo)) {  // 存在 则说明 是部门负责人
            return $Depinfo['Department']['id'];
        } else {
            return false;
        }
    }

    /**
     *  加签审批人审批
     *  @params:  $uinfo 审批人信息;$applyinfo 审批单信息
     *  @response:
     */
    public function addLotsApply($uinfo, $applyinfo) {
        require_once('../Model/AddLots.php');
        $AddLots = new AddLots();
        $reserve = $AddLots->addLotsApply($uinfo, $applyinfo) ; 
        exit( json_encode($reserve) );
    }

 
    

    /**
     *  以下所用方法 都需先获取 申请费用信息
     *   返回code、下一审核人角色
     *   获取申请项目信息
     *  @params: $apply_id 申请费用id; $uinfo 当前审核人信息;$applytype 审批状态
     *  @response:
     */
    public function apply_test($apply_id, $uinfo, $applytype) {
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
                $before_11_code = ($kx - 1) >= 0 ? $liuArr[$kx - 1] * 2 : 0;
            }
            if ($vx == 12) {
                $before_12_code = ($kx - 1) >= 0 ? $liuArr[$kx - 1] * 2 : 0;
            }
        }

        if ($applyinfo['code'] == $before_11_code && $uinfo['id'] == $applyinfo['project_user_id']) {
            $uinfo['position_id'] = 11;
        }
        if ($applyinfo['code'] == $before_12_code && $uinfo['id'] == $applyinfo['project_team_user_id']) {
            $uinfo['position_id'] = 12;
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
                $action_data = array(
                    'pid' => $applyinfo['project_id'], // 申请所属项目id
                    'uid' => $applyinfo['user_id'], // 申请人id
                    'department_id' => $applyinfo['department_id'], // 申请所属部门
                    'type' => $applyinfo['type'], // 申请类型
                    'total' => $applyinfo['total'], // 申请总费用
                );

                $endval = $liuArr[count($liuArr) - 1]; // 审批流最后一个val

                foreach ($liuArr as $k => $v) {
                    if ($v == $uinfo['position_id']) {
                        $next_id = isset($liuArr[$k + 1]) ? $liuArr[$k + 1] : $v;  // 下一审批职务
                        $next_next_id = isset($liuArr[$k + 2]) ? $liuArr[$k + 2] : $next_id; // 下下一审批职务
                        $next_three_id = isset($liuArr[$k + 3]) ? $liuArr[$k + 3] : $next_next_id; // 下下下一审批职务
                        break;
                    }

                    $apply_yz = $this->apply_action($next_id, $action_data);   // 下一审核人是否跳过 ture跳过

                    if ($apply_yz) {
                        $contents['code_id'][$k + 1] = $apply_yz;
                        // 跳过下一审核人审核  验证下下一审核人是否跳过
                        /* $next_apply_yz = $this->apply_action($next_next_id, $action_data);  

                          if($next_apply_yz) {
                          $contents['code'] = ($next_next_id == $next_three_id) ? 10000 : $next_next_id * 2;
                          $contents['next_id'] = $next_three_id;
                          $contents['code_id'][3] = $next_apply_yz;
                          }else{
                          $contents['code'] = ($next_next_id == $next_id) ? 10000 : $next_id * 2;  // 如果下一审核角色和下下一审核角色相同，说明审批流已完成
                          $contents['next_id'] = $next_next_id;  // 如果跳过下一审核人则取下下一审核人

                          } */
                    } else {
                        // 不跳过下一审核人
                        $contents['code'] = ($endval == $v) ? 10000 : $liuArr[$k - 1] * 2;
                        $contents['next_id'] = $next_id;
                        break;
                    }
                }

                return $contents;
                break;


                if ($next_three_id == $uinfo['position_id'] && $next_three_id != 15) {
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

                        if ($next_apply_yz) {
                            $contents['code'] = ($next_next_id == $next_three_id) ? 10000 : $next_next_id * 2;
                            $contents['next_id'] = $next_three_id;
                            $contents['code_id'][3] = $next_apply_yz;
                        } else {
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

    
    
}
