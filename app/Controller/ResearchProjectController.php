<?php

App::uses('ResearchProjectController', 'AppController');
/* 科研项目 */

class ResearchProjectController extends AppController {

    public $name = 'ResearchProject';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'Fixedassets', 'Storage', 'ApplyBaoxiaohuizong', 'ApplyMain', 'Department', 'TeamProject', 'ApprovalInformation');
    public $layout = 'blank';
    public $components = array('Cookie', 'Approval');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->request->is('get')) {
//获取参数
            $action = $this->request->params['action'];
            $left_use_show_actions = array(
                '', //默认index
                'index',
                'archives',
                'assets',
                'budget',
                'report_form',
                'storage',
                'declares'
            );
//左侧栏 科研项目显示
            if (in_array($action, $left_use_show_actions) || in_array(strtolower(substr($action, 0, 1)) . substr($action, 1), $left_use_show_actions)) {
                $pid = isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : 0;
                if ($pid > 0) {
                    $left_show_arr = $this->ResearchProject->query("select * from t_research_project ResearchProject where id='$pid' and code=4 limit 1");
                    $this->set('left_show_arr', $left_show_arr);
                }
            }
        }
    }

    /**
     * 当前用户是否参与项目
     */
    private function is_project_self($pid) {
// 所长、财务副所长、财务科长、科研科室主任、科研副所长 显示所有项目
        if ($this->is_who() != false) {
            return true;
        }
        return in_array($pid, $this->appdata['projectId']) ? true : false;
    }

    /**
     * 详情
     */
    public function index($pid = 0) {
        if (empty($pid) || !$this->is_project_self($pid)) {
            header("Location:/homes/index");
            die;
        }
        $this->set('pid', $pid);
        $this->set('uid', $this->userInfo->id);


        $pinfos = $this->ResearchProject->findById($pid);
        $pinfos = @$pinfos['ResearchProject'];

// 项目组
        $teaminfos = $this->TeamProject->findById($pinfos['project_team_id']);
        $pinfos['project_team_str'] = $teaminfos['TeamProject']['name'];

// 项目组负责人
        if ($teaminfos['TeamProject']['id'] == 1) {
// $pinfos['project_team_user'] = '';
            $uinfos = $this->User->findById($pinfos['user_id']);
        } else {
            $uinfos = $this->User->findById($teaminfos['TeamProject']['team_user_id']);
        }
        $pinfos['project_team_user'] = $uinfos['User']['name'];

        $source = $this->ResearchSource->getAll($pid);
        $members = $this->ProjectMember->getList($pid);

        $this->set('pinfos', $pinfos);
        $this->set('members', $members);
        $this->set('source', $source);
        $this->render();
    }

    /**
     * 详情 预算
     */
    public function budget($pid = 0) {
        $is_pro = in_array($pid, $this->appdata['projectId']) ? true : false;
        $this->set('is_pro', $is_pro);

        if (empty($pid) || !$this->is_project_self($pid)) {
            header("Location:/homes/index");
            die;
        }
        $this->set('costList', Configure::read('keyanlist'));
        $this->set('pid', $pid);

        $cost = $this->ResearchCost->findByProjectId($pid);
        $cost = @$cost['ResearchCost'];

        $minus = $applycost = $jk_attr = array();
        if (!empty($cost)) {
            $applycost = $this->ApplyMain->getSubjectTwo($pid);
            foreach ($applycost as $k => $v) {
                $overplus[$v['ApplyMain']['id']] = $v['ApplyMain']['subject'];
                ($v['ApplyMain']['table_name'] == 'apply_jiekuandan') && $jk_attr[$v['ApplyMain']['id']] = $v['ApplyMain']['attr_id'];
            }
            // 取借款单 最终审批金额
            if (count($jk_attr) > 0) {
                $jiekuandan = $this->ApplyJiekuandan->find('list', array('conditions' => array('id' => $jk_attr), 'fields' => array('id', 'approve_money')));
                foreach ($jk_attr as $k => $v) {
                    $jk_attr_val[$k] = $jiekuandan[$v];
                }
            }
  
            foreach ($overplus as $k => $v) {
                $units = json_decode($v, true);
                if (isset($jk_attr_val[$k])) {
                    foreach ($units as $uk => $uv) {
                        !isset($minus[$uk]) && $minus[$uk] = 0;
                        $minus[$uk] += $jk_attr_val[$k];
                    }
                } else {
                    foreach ($units as $uk => $uv) {
                        !isset($minus[$uk]) && $minus[$uk] = 0;
                        $minus[$uk] += $uv;
                    }
                }
            }
        }
        $this->set('cost', $cost);
        $this->set('minus', $minus);
        $this->set('sum_minus', $cost['total'] - array_sum($minus));
        $this->render();
    }

    /**
     * 详情 项目资产
     */
    public function assets($pid = 0) {
        $is_pro = in_array($pid, $this->appdata['projectId']) ? true : false;
        $this->set('is_pro', $is_pro);

        if (empty($pid) || !$this->is_project_self($pid)) {
            header("Location:/homes/index");
            die;
        }
//根据项目id取出他的固定资产列表
        $fixedassets = $this->Fixedassets->query('select Fixedassets.*,project.code,project.name from t_fixed_assets Fixedassets left join t_research_project project  on Fixedassets.project_id=project.id  where project_id=' . "$pid");
        $this->set('fixedassets', $fixedassets);
        $this->set('pid', $pid);


        $this->render();
    }

    /**
     * 详情 费用申报
     */
    public function declares($pid = 0) {
        $is_pro = in_array($pid, $this->appdata['projectId']) ? true : false;
        $this->set('is_pro', $is_pro);

        if (empty($pid) || !$this->is_project_self($pid)) {
            header("Location:/homes/index");
            die;
        }

        $proInfos = $this->ResearchProject->findById($pid);
        $proInfos = $proInfos['ResearchProject'];
        $this->set('proInfos', $proInfos);  // 预算费用

        $sql_fzr = '';
// 项目负责人、财务主任、财务副所长、所长、科研主任、科研副所长可查看全部、职员只看自己提交申请
        if ($this->userInfo->department_id != 3 && ($this->userInfo->position_id != 4 || $this->userInfo->position_id != 5)) {
            if ($proInfos['user_id'] != $this->userInfo->id && !in_array($this->userInfo->position_id, array(6, 13, 14))) {
                $sql_fzr = ' and m.user_id = ' . $this->userInfo->id;
            }
        }

//费用申报的内容
//        $declares_arr = $this->ResearchSource->query("SELECT m.*,b.page_number,b.id,b.subject,b.rmb_capital,b.amount,b.description,u.name,s.* FROM t_apply_main m LEFT JOIN t_apply_baoxiaohuizong b ON m.attr_id = b.id  LEFT JOIN t_user u ON m.user_id = u.id LEFT JOIN t_research_source s ON b.source_id = s.id  WHERE m.project_id = '$pid' $sql_fzr ");

        $declares_arr = $this->ResearchSource->query("SELECT m.*,u.name FROM t_apply_main m LEFT JOIN t_user u ON m.user_id = u.id WHERE type =1 and m.project_id = '$pid' $sql_fzr  and table_name in('apply_baoxiaohuizong','apply_lingkuandan','apply_chuchai_bxd','apply_jiekuandan') ");

        $mainArr = array();
        foreach ($declares_arr as $k => $v) {
            $mainArr[$v['m']['table_name']][$v['m']['id']] = $v['m']['attr_id'];
        }

//取各分表内容
        $attrArr = array();
        foreach ($mainArr as $k => $v) {
            $attrid = implode(',', $v);
            switch ($k) {
                case 'apply_baoxiaohuizong':  // 报销汇总单
                    $attrinfo = $this->ResearchSource->query("SELECT b.id,b.description,s.* FROM t_apply_baoxiaohuizong b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                    break;
                case 'apply_chuchai_bxd':  // 差旅费报销单
                    $attrinfo = $this->ResearchSource->query("SELECT b.id,b.reason description,s.* FROM t_apply_chuchai_bxd b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                    break;
                case 'apply_lingkuandan':  // 领款单
                    $attrinfo = $this->ResearchSource->query("SELECT b.id,b.json_str description,s.* FROM t_apply_lingkuandan b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                    break;
                case 'apply_jiekuandan':  // 借款单
                    $attrinfo = $this->ResearchSource->query("SELECT b.id,b.approve_money ,b.reason description,s.* FROM t_apply_jiekuandan b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                    break;
            }
            if (count($attrinfo) > 0) {
                if ($k == 'apply_lingkuandan') {
                    foreach ($attrinfo as $attk => $attv) {
                        $tmpdecp = json_decode($attv['b']['description'], true);
                        $attv['b']['description'] = $tmpdecp[0]['pro'];
                        $attrinfo[$attv['b']['id']] = $attv;
                    }
                } else if ($k == 'apply_jiekuandan') {
                    foreach ($attrinfo as $attk => $attv) {
                        $attrinfo[$attv['b']['id']] = $attv;
                        //   $declares_arr[]
                    }
                } else {
                    foreach ($attrinfo as $attk => $attv) {
                        $attrinfo[$attv['b']['id']] = $attv;
                    }
                }
                foreach ($v as $atk => $atv) {
                    $attrArr[$atk] = $attrinfo[$atv];
                }
            }
        }
//var_dump($mainArr,$attrArr);        
        $this->set('keyanlist', Configure::read('keyanlist'));
        $this->set('declares_arr', $declares_arr);
        $this->set('attr_arr', $attrArr);
        $this->set('pid', $pid);

        $this->render();
    }

    /**
     * 添加 费用申报
     */
    public function add_declares($pid = 0) {
        if (empty($pid) || !$this->is_project_self($pid)) {
            header("Location:/homes/index");
            die;
        }
        $this->set('pid', $pid);

        $projectInfo = $this->ResearchProject->findById($pid);
        $source = $this->ResearchSource->getAll($pid);
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $this->set('department_arr', $department_arr);
        $this->set('is_department', !empty($department_arr) ? $department_arr['Department']['type'] : 2);
        $this->set('xizhenglist', Configure::read('xizhenglist'));
        $this->set('keyanlist', Configure::read('keyanlist'));
        $this->set('projectInfo', $projectInfo['ResearchProject']);
        $this->set('source', $source);
        $this->render();
    }

    /**
     * 添加 费用报销单
     */
    public function sub_declares() {
        if (!$this->request->is('ajax')) {
            $this->ret_arr['msg'] = '请求不合法';
            exit(json_encode($this->ret_arr));
        }

        if (empty($_POST['ctime']) || empty($_POST['subject']) || empty($_POST['rmb_capital']) || empty($_POST['amount'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_baoxiaohuizong';

        $type = Configure::read('type_number'); //行政费用
//获取审批流id
//        $p_id = Configure::read('approval_process');
        $project_user_id = 0; //项目负责人user_id
        $project_team_user_id = 0; //项目组负责人user_id
        if ($_POST['projectname'] == 0) {
            $project_id = 0; //让他为0
            $type = $type[1];
            $p_id = 2;
        } else {
//项目
            $project_id = $_POST['projectname'];
            $type = $type[0];
            $p_id = 1;
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
        $attrArr['ctime'] = $_POST['ctime'];
        $attrArr['page_number'] = empty($_POST['page_number']) ? 0 : $_POST['page_number'];

        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['source_id'] = $_POST['filenumber'];
        $attrArr['subject'] = json_encode($_POST['subject']);
        $attrArr['rmb_capital'] = $_POST['rmb_capital'];
        $attrArr['amount'] = $_POST['amount'];
        $attrArr['description'] = $_POST['description'];
        $attrArr['applicant'] = $_POST['applicant'];
        $attrArr['user_id'] = $this->userInfo->id;

# 开始入库
        $this->ApplyBaoxiaohuizong->begin();
        $attrId = $this->ApplyBaoxiaohuizong->add($attrArr);

# 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id']; //下一个审批职务的id
        $mainArr['next_apprly_uid'] = $ret_arr['next_uid']; //下一个审批人id
        $mainArr['code'] = $ret_arr['code']; //当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type;
        $mainArr['attachment'] = $_POST['attachment'];
        $mainArr['name'] = $_POST['declarename'];
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['total'] = $_POST['amount'];
        $mainArr['attr_id'] = $attrId;
        $mainArr['project_user_id'] = $project_user_id;
        $mainArr['project_team_user_id'] = $project_team_user_id;
        $mainArr['department_fzr'] = $department_fzr; // 行政 申请所属部门负责人
        $mainArr['ctime'] = $_POST['ctime'];
        $mainArr['subject'] = json_encode($_POST['subject']);
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyBaoxiaohuizong->rollback();
        }
        $mainId ? $commitId = $this->ApplyBaoxiaohuizong->rollback() : $commitId = $this->ApplyBaoxiaohuizong->commit();


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

    /**
     * 详情 报表
     */
    public function report_form($pid = 0) {
        $is_pro = in_array($pid, $this->appdata['projectId']) ? true : false;
        $this->set('is_pro', $is_pro);
        
        if (empty($pid) || !$this->is_who()) {
            // 不属于五个职务的 请求用户
            if($is_pro == false){
                // 不属于五个职务的 且不是该项目成员的 请求用户
                 header("Location:".$_SERVER['HTTP_REFERER']);die; 
            }
            $proInfos = $this->ResearchProject->findById($pid);
            $proInfos = $proInfos['ResearchProject'];
            $this->set('proInfos', $proInfos);  // 预算费用
            // 不是项目负责人不可查看报表
            if ($proInfos['user_id'] != $this->userInfo->id) {
                header("Location:".$_SERVER['HTTP_REFERER']);
               // echo "<script> alert('抱歉！您没有查看权限！');location.href = '".$_SERVER['HTTP_REFERER']."' </script>"; 
                die;
            }
        }

       
//       $declares_arr = $this->ResearchSource->query("SELECT m.*,b.page_number,b.id,b.subject,b.rmb_capital,b.amount,b.description,u.name,s.* FROM t_apply_main m LEFT JOIN t_apply_baoxiaohuizong b ON m.attr_id = b.id  LEFT JOIN t_user u ON m.user_id = u.id LEFT JOIN t_research_source s ON b.source_id = s.id  WHERE m.project_id =  '$pid' and m.code = 10000 ");
        // 报表只取核算为1的数据
        $declares_arr = $this->ResearchSource->query("SELECT m.*,u.name FROM t_apply_main m LEFT JOIN t_user u ON m.user_id = u.id WHERE m.project_id = '$pid' and type = 1 and is_calculation = 1 and m.code = 10000 ");
// var_dump($declares_arr); 

        $mainArr = array();
        foreach ($declares_arr as $k => $v) {
            $mainArr[$v['m']['table_name']][$v['m']['id']] = $v['m']['attr_id'];
        }

//取各分表内容
        $attrArr = array();
        foreach ($mainArr as $k => $v) {
            $attrid = implode(',', $v);
            switch ($k) {
                case 'apply_baoxiaohuizong':  // 报销汇总单
                    $attrinfo = $this->ResearchSource->query("SELECT b.id,b.subject,b.amount,b.description,s.* FROM t_apply_baoxiaohuizong b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                    break;
                case 'apply_chuchai_bxd':  // 差旅费报销单
                    $attrinfo = $this->ResearchSource->query("SELECT b.id,b.total amount,b.reason description,s.* FROM t_apply_chuchai_bxd b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                    break;
                case 'apply_lingkuandan':  // 领款单
                    $attrinfo = $this->ResearchSource->query("SELECT b.id,b.small_total amount,b.json_str description,s.* FROM t_apply_lingkuandan b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                    break;
                case 'apply_jiekuandan':  // 借款单
                    $attrinfo = $this->ResearchSource->query("SELECT b.id,b.approve_money amount,b.reason description,s.* FROM t_apply_jiekuandan b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                    break;
            }
            if (count($attrinfo) > 0) {
                if ($k == 'apply_lingkuandan') {
                    foreach ($attrinfo as $attk => $attv) {
                        $tmpdecp = json_decode($attv['b']['description'], true);                     
                        $attv['b']['description'] = $tmpdecp[0]['pro'];
                        $attrinfo[$attv['b']['id']] = $attv;
                    }
                } else {
                    foreach ($attrinfo as $attk => $attv) {
                        $attrinfo[$attv['b']['id']] = $attv;
                    }
                }
                foreach ($v as $atk => $atv) {
                    $attrArr[$atk] = $attrinfo[$atv];
                }
            }
        }

        $this->set('keyanlist', Configure::read('keyanlist'));
        $this->set('declares_arr', $declares_arr);
        $this->set('attr_arr', $attrArr);
        $this->set('pid', $pid);


        $pcost = $this->ResearchCost->findByProjectId($pid);
        $pcost = $pcost['ResearchCost'];
        $this->set('pcost', $pcost);  // 预算费用

        $expent = array();  // 支出总计费用
        foreach ($declares_arr as $k => $v) {
            $zhichu = json_decode($v['m']['subject'], true);
            // 如是借款单 转换借款金额为批准金额
            if ($v['m']['table_name'] == 'apply_jiekuandan') {
                foreach ($zhichu as $zck => $zcv) {
                    $zhichu[$zck] = $attrArr[$v['m']['id']]['b']['amount'];
                }
            }
            foreach ($zhichu as $zk => $zv) {
                $expent[$zk] = isset($expent[$zk]) ? $expent[$zk] + $zv : $zv;
            }
        }
        $this->set('expent', $expent);  // 支出总计费用
        $this->render();
    }

    /**
     * 详情 档案
     */
    public function archives($pid = 0) {
        $is_pro = in_array($pid, $this->appdata['projectId']) ? true : false;
        $this->set('is_pro', $is_pro);

        if (empty($pid) || !$this->is_project_self($pid)) {
            header("Location:/homes/index");
            die;
        }
        $this->set('pid', $pid);

        $sourcelist = $this->ResearchSource->getAll($pid);

        $this->render();
    }

    /**
     * 详情 出入库
     */
    public function storage($pid = 0) {
        $is_pro = in_array($pid, $this->appdata['projectId']) ? true : false;
        $this->set('is_pro', $is_pro);

        if (empty($pid) || !$this->is_project_self($pid)) {
            header("Location:/homes/index");
            die;
        }
        $this->set('pid', $pid);

        $storagelist = $this->Storage->getList($pid);
        $this->set('storagelist', $storagelist);

        $this->render();
    }

    /**
     * 添加 添加项目成员列表
     */
    public function add_member($pid = 0) {
        if (empty($pid) || !$this->is_project_self($pid)) {
            header("Location:/homes/index");
            die;
        }

# 非项目内成员
        $notInMember = $this->User->not_project_member($pid);
        $this->set('notInMember', $notInMember);

#项目内成员
        $projectMember = $this->ProjectMember->getList($pid);
        $this->set('projectMember', $projectMember);
        $this->set('pid', $pid);
        $this->render();
    }

    /**
     * 添加 添加项目成员
     */
    public function member_operation() {
        if (empty($_POST['pid']) || (empty($_POST['member']) && empty($_POST['mid'])) || empty($_POST['type'])) {
            $this->ret_arr['msg'] = '参数有误';
        } else {
            $editArr = array();
            switch ($_POST['type']) {
                case 'add' :
                    $memberInfo = $this->User->findById($_POST['member']);
                    if (!$memberInfo)
                        exit(json_encode($this->ret_arr));

                    $isAdd = $this->ProjectMember->getmember($_POST['pid'], $_POST['member']);
                    if ($isAdd) {
                        $this->ret_arr['msg'] = '该用户已是项目成员';
                        exit(json_encode($this->ret_arr));
                    }

                    $editArr['user_id'] = $_POST['member'];
                    $editArr['project_id'] = $_POST['pid'];
                    $editArr['user_name'] = $memberInfo['User']['user'];
                    $editArr['name'] = $memberInfo['User']['name'];
                    $editArr['tel'] = $memberInfo['User']['tel'];
                    $editArr['type'] = $_POST['types'];
                    $editArr['ctime'] = date('Y-m-d');
                    $editArr['remark'] = $_POST['remark'];
                    $memberId = $this->ProjectMember->add($editArr);
                    break;
                case 'edit':
                    $editArr['remark'] = $_POST['remark'];
                    $memberId = $this->ProjectMember->edit($_POST['mid'], $editArr);
                    break;
                case 'del':
                    $memberId = $this->ProjectMember->del($_POST['pid'], $_POST['mid']);
                    break;
            }

            if ($memberId) {
                $this->ret_arr['code'] = 0;
            } else {
                $this->ret_arr['msg'] = '操作失败';
            }
        }

        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 添加 添加项目资金来源表
     */
    public function add_filenumber($pid = 0) {
        if (empty($pid)) {
            header("Location:/homes/index");
            die;
        }


#项目详情
        $proInfos = $this->ResearchProject->findById($pid);

// 是否项目负责人添加
        if ($proInfos['ResearchProject']['user_id'] != $this->userInfo->id) {
            header("Location:/homes/index");
            die;
        }

# 项目资金来源
        $proSource = $this->ResearchSource->getAll($pid);
        $this->set('proSource', $proSource);

        $this->set('pid', $pid);
        $this->render();
    }

    /**
     * 添加 添加项目资金来源
     */
    public function sub_filenumber() {
        if (empty($_POST['pid']) || empty($_POST['source_channel']) || empty($_POST['year']) || empty($_POST['file_number']) || empty($_POST['amount'])) {
            $this->ret_arr['msg'] = '参数有误';
        } else {
#项目详情
            $proInfos = $this->ResearchProject->findById($_POST['pid']);

// 是否项目负责人添加
            if ($proInfos['ResearchProject']['user_id'] != $this->userInfo->id) {
                $this->ret_arr['msg'] = '非项目负责人无权添加';
                echo json_encode($this->ret_arr);
                exit;
            }
# 项目资金来源 总额
            $proSource = $this->ResearchSource->query('select sum(amount) sum from t_research_source where project_id = ' . $_POST['pid']);
            if (($proSource[0][0]['sum'] + $_POST['amount']) > $proInfos['ResearchProject']['amount']) {
                $this->ret_arr['msg'] = '资金来源总额超过 项目总金额';
                echo json_encode($this->ret_arr);
                exit;
            }

            $editArr = array();
            switch ($_POST['type']) {
                case 'add' :
                    $editArr['project_id'] = $_POST['pid'];
                    $editArr['source_channel'] = $_POST['source_channel'];
                    $editArr['file_number'] = $_POST['file_number'];
                    $editArr['amount'] = $_POST['amount'];
                    $editArr['year'] = $_POST['year'];
                    $sourceId = $this->ResearchSource->add($editArr);
                    break;
                case 'edit':
//$sourceId = $this->ResearchSource->edit($_POST['mid'], $editArr);
                    break;
                case 'del':
//$sourceId = $this->ResearchSource->del($_POST['pid'], $_POST['mid']);
                    break;
            }

            if ($sourceId) {
                $this->ret_arr['code'] = 0;
            } else {
                $this->ret_arr['msg'] = '操作失败';
            }
        }

        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 添加 添加项目
     */
    public function step1() {

        $team = $this->TeamProject->find('list', array('conditions' => array('del' => 0), 'fields' => array('id', 'name')));
        $this->set('team', $team);
        $this->render();
    }

    /**
     * 添加 任务书
     */
    public function step2() {
        if ($this->request->isPost() && $this->request->data('step1') != 'step1') {
            header('Location:/ResearchProject/index');
        }

        $saveArr = array();
        $saveArr['user_id'] = $this->userInfo->id;
        $saveArr['name'] = $this->request->data('name');
        $saveArr['alias'] = $this->request->data('alias');
        $saveArr['amount'] = $this->request->data('sumamount');
        $saveArr['start_date'] = $this->request->data('start_date');
        $saveArr['end_date'] = $this->request->data('end_date');
        $saveArr['overview'] = $this->request->data('overview');
        $saveArr['remark'] = $this->request->data('remark');
        $saveArr['source'] = $this->request->data('source');
        $saveArr['project_team_id'] = $this->request->data('project_team_id'); //所属项目组id
        $saveArr['type'] = $this->request->data('type');

        $saveArr['qdly'] = $this->request->data('qdly'); //这里放的是数组
        $this->Cookie->write('research_project' . $this->userInfo->id, CookieEncode($saveArr), false, '7 day');
        $this->render();
    }

    /**
     * 添加 项目费用
     */
    public function step3() {
        if ($this->request->isPost() && $this->request->data('step2') != 'step2') {
            header('Location:/ResearchProject/index');
        }
        $savefiles = $this->request->data('file_upload');
        $this->Cookie->write('research_file' . $this->userInfo->id, CookieEncode($savefiles), false, '7 day');

        $this->set('list', Configure::read('keyanlist'));

        $this->render();
    }

    /**
     * 添加 项目 入库
     */
    public function substep3() {
        $project = $this->Cookie->read('research_project' . $this->userInfo->id);
        $files = $this->Cookie->read('research_file' . $this->userInfo->id);
        if (empty($project) || empty($files)) {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '数据不完整';
            echo json_encode($this->ret_arr);
            die;
        }

        $saveArr = array();
        if ($this->request->is('ajax') && $this->request->data('upstep') == 'step3') {
            !empty($_POST['data_fee']) && $saveArr['data_fee'] = $_POST['data_fee'];
            !empty($_POST['collection']) && $saveArr['collection'] = $_POST['collection'];
            !empty($_POST['facility']) && $saveArr['facility'] = $_POST['facility'];
            !empty($_POST['material']) && $saveArr['material'] = $_POST['material'];
            !empty($_POST['assay']) && $saveArr['assay'] = $_POST['assay'];
            !empty($_POST['elding']) && $saveArr['elding'] = $_POST['elding'];
            !empty($_POST['publish']) && $saveArr['publish'] = $_POST['publish'];
            !empty($_POST['property_right']) && $saveArr['property_right'] = $_POST['property_right'];
            !empty($_POST['office']) && $saveArr['office'] = $_POST['office'];
            !empty($_POST['vehicle']) && $saveArr['vehicle'] = $_POST['vehicle'];
            !empty($_POST['travel']) && $saveArr['travel'] = $_POST['travel'];
            !empty($_POST['meeting']) && $saveArr['meeting'] = $_POST['meeting'];
            !empty($_POST['travel']) && $saveArr['travel'] = $_POST['travel'];
            !empty($_POST['international']) && $saveArr['international'] = $_POST['international'];
            !empty($_POST['cooperation']) && $saveArr['cooperation'] = $_POST['cooperation'];
            !empty($_POST['labour']) && $saveArr['labour'] = $_POST['labour'];
            !empty($_POST['consult']) && $saveArr['consult'] = $_POST['consult'];
            !empty($_POST['indirect_manage']) && $saveArr['indirect_manage'] = $_POST['indirect_manage'];
            !empty($_POST['indirect_performance']) && $saveArr['indirect_performance'] = $_POST['indirect_performance'];
            !empty($_POST['indirect_other']) && $saveArr['indirect_other'] = $_POST['indirect_other'];
            !empty($_POST['other']) && $saveArr['other'] = $_POST['other'];
            !empty($_POST['other2']) && $saveArr['other2'] = $_POST['other2'];
            !empty($_POST['other3']) && $saveArr['other3'] = $_POST['other3'];

            $saveArr['total'] = array_sum($saveArr);  // 总额
            !empty($_POST['remarks']) && $saveArr['remarks'] = $_POST['remarks'];

            $projectArr = CookieDecode($project);
            $sourceArr = $projectArr['source'];
            unset($projectArr['source']);
            $filesArr = CookieDecode($files);
            $projectArr['filename'] = $filesArr[0];
            $projectArr['ctime'] = date("Y-m-d H:i:s");

# 开始入库
            $this->ResearchProject->begin();
            $this->ResearchProject->add($projectArr);

            if ($porijectId = $this->ResearchProject->id) {
                $saveSourceArr = array();
                $key = 0; //定义一个下标
                foreach ($sourceArr['file_number'] as $k => $v) {
                    if (!$v) {
                        continue;
                    }

                    $saveSourceArr[$key]['project_id'] = $porijectId;
                    $saveSourceArr[$key]['source_channel'] = $sourceArr['source_channel'][$k];
                    $saveSourceArr[$key]['year'] = $sourceArr['year'][$k];
                    $saveSourceArr[$key]['file_number'] = $sourceArr['file_number'][$k];
                    $saveSourceArr[$key]['amount'] = $sourceArr['amount'][$k];
                    $this->ResearchSource->add($saveSourceArr[$key++]);
                }
            } else {
                $this->ResearchProject->rollback();
            }

            $saveArr['project_id'] = $porijectId;
            !$this->ResearchSource->id ? $this->ResearchProject->rollback() : $costId = $this->ResearchCost->add($saveArr);

            !$this->ResearchCost->id ? $this->ResearchProject->rollback() : $commitId = $this->ResearchProject->commit();

            if ($costId) {
//添加成功，把自己写出到项目成员表里面
                /* 审核结束时会添加项目负责人
                  $editArr['user_id'] = $this->userInfo->id;
                  $editArr['project_id'] = $porijectId;
                  $editArr['user_name'] = $this->userInfo->user;
                  $editArr['name'] = $this->userInfo->name;
                  $editArr['tel'] =  $this->userInfo->tel;
                  $editArr['type'] = 1;//项目负责人
                  $editArr['ctime'] = date('Y-m-d');
                  $editArr['remark'] = '';
                  $memberId = $this->ProjectMember->add($editArr);
                 */
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = $commitId . '添加项目成功！请等待审核';
            } else {
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '添加项目失败！';
            }
        } else {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '参数有误';
        }

        echo json_encode($this->ret_arr);
        exit;
        $this->render();
    }

    /**
     * 把数据存入到cookie里
     */
    public function ajax_cookie() {
        $saveArr = array();

        if ($this->request->is('ajax')) {
            if ($this->request->data('upstep') == 'step1') {
                $saveArr['user_id'] = $this->userInfo->id;
                $saveArr['name'] = $this->request->data('name');
                $saveArr['alias'] = $this->request->data('alias');
                $saveArr['amount'] = $this->request->data('sumamount');
                $saveArr['start_date'] = $this->request->data('start_date');
                $saveArr['end_date'] = $this->request->data('end_date');
                $saveArr['overview'] = $this->request->data('overview');
                $saveArr['remark'] = $this->request->data('remark');
                $saveArr['project_team_id'] = $this->request->data('project_team_id'); //所属项目组id
                $saveArr['type'] = $this->request->data('type');
                $saveArr['qdly'] = $this->request->data('qdly'); //这里放的是数组
//                $saveArr['source_channel'] = $this->request->data('source_channel');
//                $saveArr['file_number'] = $this->request->data('file_number');
//                $saveArr['amount'] = $this->request->data('amount');
//                $saveArr['year'] = $this->request->data('year');
//这里取出用户的  id，把id也存入到cookie里面

                $this->Cookie->write('research_project' . $this->userInfo->id, CookieEncode($saveArr), false, '7 day');

                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = json_encode($saveArr);

                echo json_encode($this->ret_arr);
                die;
            }

            if ($this->request->data('upstep') == 'step2') {
                $saveArr['filename'] = $this->request->data('filename');
                $this->Cookie->write('research_file' . $this->userInfo->id, CookieEncode($saveArr), false, '7 day');
            }

            if ($this->request->data('upstep') == 'step3') {
                $saveArr['data_fee'] = $this->request->data('data_fee');
                $saveArr['facility1'] = $this->request->data('facility1');
                $saveArr['facility2'] = $this->request->data('facility2');
                $saveArr['facility3'] = $this->request->data('facility3');
                $saveArr['material1'] = $this->request->data('material1');
                $saveArr['material2'] = $this->request->data('material2');
                $saveArr['material3'] = $this->request->data('material3');
                $saveArr['material4'] = $this->request->data('material4');
                $saveArr['assay'] = $this->request->data('assay');
                $saveArr['elding'] = $this->request->data('elding');
                $saveArr['publish'] = $this->request->data('publish');
                $saveArr['property_right'] = $this->request->data('property_right');
                $saveArr['travel'] = $this->request->data('travel');
                $saveArr['meeting'] = $this->request->data('meeting');
                $saveArr['cooperation'] = $this->request->data('cooperation');
                $saveArr['labour'] = $this->request->data('labour');
                $saveArr['consult'] = $this->request->data('consult');
                $saveArr['other'] = $this->request->data('other');
                $saveArr['indirect'] = $this->request->data('indirect');
                $saveArr['train'] = $this->request->data('train');
                $saveArr['vehicle'] = $this->request->data('vehicle');
                $saveArr['collection'] = $this->request->data('collection');

                $saveArr['total'] = array_sum($saveArr);  // 总额
                $saveArr['remarks'] = $this->request->data('remarks');
                $this->Cookie->write('research_cost' . $this->userInfo->id, CookieEncode($saveArr), false, '7 day');
            }


//cookie write没有返回值
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '存入成功';

            echo json_encode($this->ret_arr);
            die;
        } else {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            die;
        }
    }

    public function upload_file() {
        $file_arr = $_FILES['file'];
        $name = $file_arr['name'];
        $tmp_name = $file_arr['tmp_name'];
        $error = $file_arr['error'];
        $size = $file_arr['size'];

        $new_file_name = WWW_ROOT . DS . 'files' . DS . $name;
//判断这个文件没有存在，如果存在就不上传
        if (file_exists($new_file_name)) {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '文件名重复';
            echo json_encode($this->ret_arr);
            exit;
        }
        if (move_uploaded_file($tmp_name, $new_file_name)) {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '上传成功';
        } else {
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '上传失败';
        }
        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 添加 出入库
     */
    public function add_storage($pid = 0, $sid = 0) {
        if (empty($pid)) {
            header("Location:/home/index");
        }

#项目详情
        $pinfos = $this->ResearchProject->findById($pid);
        $pinfos = @$pinfos['ResearchProject'];
        $this->set('pinfos', $pinfos);

        $storageInfo = array();
        if (!empty($sid) && $sid > 0) {
            $storageInfo = $this->Storage->findById($sid);
            $storageInfo = $storageInfo['Storage'];
        }
        $this->set('storageInfo', $storageInfo);
        $this->set('pid', $pid);
        $this->render();
    }

    /**
     * 提交 出入库
     */
    public function sub_storage() {
        if (empty($_POST['pid']) || (empty($_POST['spec']) && empty($_POST['nums'])) || empty($_POST['amount'])) {
            $this->ret_arr['msg'] = '参数有误';
        } else {
            $saveArr = array();
            $saveArr['project_id'] = $_POST['pid'];
            $saveArr['project_name'] = $_POST['pname'];
            $saveArr['spec'] = $_POST['spec'];
            $saveArr['nums'] = $_POST['nums'];
            $saveArr['amount'] = $_POST['amount'];
            $saveArr['abstract'] = $_POST['abstract'];
            $saveArr['ctime'] = date('Y-m-d');
            if ($_POST['sid']) {
                $storageId = $this->Storage->edit($_POST['sid'], $saveArr);
            } else {
                $storageId = $this->Storage->add($saveArr);
            }


            if ($storageId) {
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '添加成功';
                $_POST['sid'] && $this->ret_arr['class'] = 'edit';
            } else {
                $this->ret_arr['msg'] = '操作失败';
            }
        }

        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 删除 出入库
     */
    public function edit_storage() {
        if (empty($_POST['sid'])) {
            $this->ret_arr['msg'] = '参数有误';
        } else {
            $saveArr = array();
            $saveArr['del'] = 1;
            $storageId = $this->Storage->edit($_POST['sid'], $saveArr);

            if ($storageId) {
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '删除成功';
            } else {
                $this->ret_arr['msg'] = '删除失败';
            }
        }

        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 
     * 根据部门或者项目id取出对用的费用
     * @param type $type 类型 1为部门， 2为科研项目
     * @param type $project_id 项目id
     */
    public function add_declares_department_project($type, $project_id = 0) {
        if ($type == 1) {
//部门费用
            $feiyong = Configure::read('xizhenglist');
        } else {
//科研费用
            $feiyong = Configure::read('keyanlist');
        }
        $this->set('feiyong', $feiyong);
        $this->render();
    }

    /**
     * 预览 打印，费用申报列表的打印
     */
    public function budget_print($main_id = 0) {
        if (empty($main_id)) {
            header("Location:/homes/index");
            die;
        }
        $this->set('costList', Configure::read('keyanlist'));
        $main_arr = $this->ApplyMain->findById($main_id);

        $subject = array();
        if (!empty($main_arr['ApplyMain']['subject'])) {
            $subject = json_decode($main_arr['ApplyMain']['subject'], true);
        }
        $this->set('subject', $subject);
        $this->render();
    }

}
