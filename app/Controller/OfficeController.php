<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class OfficeController extends AppController {

    public $name = 'Office';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'ApplyMain', 'ApplyBaoxiaohuizong', 'ApprovalInformation', 'DepartmentCost', 'Department', 'ApplyJiekuandan', 'ApplyLeave', 'ApplyChuchai', 'ApplyBaogong', 'ApplyPaidleave', 'ApplyEndlessly', 'ApplyCaigou', 'ApplySeal', 'ApplyReceived', 'ApplyBorrow', 'ApplyDispatch', 'Team', 'ApplyNews', 'ApplyRequestReport');
    public $layout = 'blank';
    public $components = array('Cookie', 'Approval');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

    const Table_fix = 't_'; //表前缀

    public function index() {
        
    }

    /**
     * 起草申请
     */
    public function draf() {
//var_dump($this->Approval->apply_create(1, $this->userInfo, 8));
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
        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where del=0 and  user_id=' . $this->userInfo->id);
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll(array('del' => 0, 'user_id' => $this->userInfo->id), $limit, $pages);
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
    public function apply($pages = 1, $table = '') {
        $user_id = $this->userInfo->id;
//        $type_arr = Configure::read('type_number');

        if (!isset(Configure::read('select_apply')[$table])) {
            //没有设置默认空
            $table = 'fish';
            $table_sql = '';
        } else {
            $table_sql = " and table_name='{$table}'";
        }
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        //有审批权限
        $total = $this->ApplyMain->query("select count(*) count from t_apply_main ApplyMain where user_id='{$user_id}' {$table_sql} ");
        $total = $total[0][0]['count'];
        $all_user_arr = $this->User->get_all_user_id_name();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ApplyMain->query("select ApplyMain.id,ApplyMain.name,ApplyMain.ctime,ApplyMain.table_name,ApplyMain.code,ApplyMain.user_id,ApplyMain.attachment,u.name from t_apply_main ApplyMain left join t_user u on ApplyMain.next_apprly_uid = u.id where user_id='{$user_id}' {$table_sql} order by id desc limit " . ($pages - 1) * $limit . ", $limit");
        }
        $this->set('table', $table);
        $this->set('all_user_arr', $all_user_arr);
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

        // 行政审批 还是财务审批
        $isWho = $this->is_who();
        if ($isWho == 'keyanzhuren') {
            $wherestr = ' and code = 0 ';
            $conditions = array('code' => 0, 'del' => 0);
        } else if ($isWho == 'caiwukezhang') {
            $wherestr = ' and code = 2 ';
            $conditions = array('code ' => 2, 'del' => 0);
        } else {
            $wherestr = ' and code = -1 ';
            $conditions = array('code' => '-1', 'del' => 0);
        }

        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where del=0 ' . $wherestr);
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll($conditions, $limit, $pages);
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
    public function wait_approval_apply_old($pages = 1) {
        //取出当前用户职务id以及审批权限
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;
        $type_arr = Configure::read('type_number');
        $user_department_id = $this->userInfo->department_id;

        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();
        //在这里得加上部门的验证，如果是特殊职务，如所长，账务科长不验证部门
        $department_str = "department_id ='$user_department_id' "; //默认找不到部门
        $bumensql = ($position_id != 4) ? '' : ' and type = 2 ';   // 部门负责人只能审核 行政申请
        if ($can_approval == 2) {
            // 财务副所长、账务科主任 验证所有申请（审批流中包含这两个角色）
            /*
              if (in_array($position_id, $this->not_department_arr)) {
              //所长，账务科长不验证部门
              $department_str = ' 1 ';
              }
             */
            //有审批权限
            $sql = "select count(*) count from t_apply_main ApplyMain where ( ";

            $wheresql = '';
            // 项目负责人审核
            $wheresql .= "(next_approver_id=11 and project_user_id='{$this->userInfo->id}') ";
            // 项目组负责人审核
            $wheresql .= "or (next_approver_id=12 and project_team_user_id='{$this->userInfo->id}') ";
            // 部门负责人审核
            $wheresql .= "or (next_approver_id=15 and department_fzr='{$this->userInfo->id}') ";
            // 科研项目由科研主任、科研副所长审核
            if ($this->userInfo->department_id == 3) {
                $wheresql .= "or (type=1 and next_approver_id='{$this->userInfo->position_id}') ";
            }
            // 财务科副所长、财务科办公室主任 审核
            if ($this->userInfo->position_id == 13 || $this->userInfo->position_id == 14) {
                $wheresql .= "or (next_approver_id='{$this->userInfo->position_id}') ";
            } else if ($this->userInfo->position_id == 6) {
                // 所长 审核
                $wheresql .= "or (next_approver_id = 6 ) ";
            }
            // 部门所属分管副所长  审核
            $dep_lsd = $this->Department->find('list', array('conditions' => array('sld' => $this->userInfo->id, 'del' => 0), 'fields' => array('id')));
            if (count($dep_lsd) > 0) {
                $dep_lsd_str = implode($dep_lsd, ',');
                $wheresql .= "or (next_approver_id = 5 and department_id in($dep_lsd_str)) ";
            }
            /*  else{
              // 部门申请筛选条件
              //   $wheresql .= " or ({$department_str} and next_approver_id='$position_id' $bumensql ) " ;
              }
             */
            $sql .= $wheresql;
            $sql .= ") and code%2=0 and code !='$this->succ_code'";
//echo $sql;
            $total = $this->ApplyMain->query($sql);
            $total = $total[0][0]['count'];
        } else {
            //没有审批权限
            $total = 0;
        }
        $all_user_arr = $this->User->get_all_user_id_name();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }

            $sql = "select * from t_apply_main ApplyMain where ( ";
            $sql .= $wheresql;
            $sql .= ") and code%2=0 and code !='$this->succ_code' order by id desc limit " . ($pages - 1) * $limit . ", $limit";
            $lists = $this->ApplyMain->query($sql);
        }
        $this->set('all_user_arr', $all_user_arr);
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
    public function wait_approval_apply($pages = 1, $table = '') {
        //取出当前用户职务id以及审批权限
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;
        $type_arr = Configure::read('type_number');
        $user_department_id = $this->userInfo->department_id;

        if (!isset(Configure::read('select_apply')[$table])) {
            //没有设置默认空
            $table = 'fish';
            $table_sql = '';
        } else {
            $table_sql = " and table_name='{$table}'";
        }
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 20;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();

        if ($can_approval == 2) {

            //有审批权限
            $sql = "select count(*) count from t_apply_main ApplyMain where ( ";
            $wheresql = ' next_apprly_uid = ' . $this->userInfo->id;
            $sql .= $wheresql;
            $sql .= " ) and code%2=0 {$table_sql} and code !='$this->succ_code'";

            $total = $this->ApplyMain->query($sql);
            $total = $total[0][0]['count'];
        } else {
            //没有审批权限
            $total = 0;
        }
        $all_user_arr = $this->User->get_all_user_id_name();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }

            $sql = "select * from t_apply_main ApplyMain where ( ";
            $sql .= $wheresql;
            $sql .= ") and code%2=0 {$table_sql} and code !='$this->succ_code' order by id desc limit " . ($pages - 1) * $limit . ", $limit";
            $lists = $this->ApplyMain->query($sql);
        }
        $this->set('table', $table);
        $this->set('all_user_arr', $all_user_arr);
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

        // 行政审批 还是财务审批
        $isWho = $this->is_who();
        if ($isWho == 'keyanzhuren') {
            $wherestr = 'project_approver_id';
        } else if ($isWho == 'caiwukezhang') {
            $wherestr = 'financial_approver_id';
        } else {
            $wherestr = 'del';
        }

        $total = $this->ResearchProject->query('select count(*) as count from t_research_project where del=0 and ' . $wherestr . '=' . $this->userInfo->id);
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ResearchProject->getAll(array('del' => 0, $wherestr => $this->userInfo->id), $limit, $pages);
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
    public function my_approval_apply($pages = 1, $table = '', $shqren = '') {
        //取出当前用户职务id以及审批权限
        $user_id = $this->userInfo->id;
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;
        $type_arr = Configure::read('type_number');
        $user_department_id = $this->userInfo->department_id;

        if (!isset(Configure::read('select_apply')[$table])) {
            //没有设置默认空
            $table = 'fish';
            $table_sql = '';
        } else {
            $table_sql = " and table_name='{$table}'";
        }
        if (empty($shqren)) {
            $shqren = 0;
            $table_sql .= " " ;
        } else {
            $table_sql .= "  and ApplyMain.user_id='{$shqren}'" ;
        }
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 10;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $lists = array();

        $total = $this->ApplyMain->query("select count(distinct ApplyMain.id) count from t_apply_main ApplyMain left join t_approval_information ApprovalInformation on ApplyMain.id=ApprovalInformation.main_id where ApprovalInformation.approve_id='$user_id' {$table_sql} ");
        $total = $total[0][0]['count'];
        $all_user_arr = $this->User->get_all_user_id_name();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ApplyMain->query("select ApplyMain.*, ApprovalInformation.ctime from t_apply_main ApplyMain left join t_approval_information ApprovalInformation on ApplyMain.id=ApprovalInformation.main_id where ApprovalInformation.approve_id='$user_id' {$table_sql} group by ApplyMain.id order by ApprovalInformation.id desc limit " . ($pages - 1) * $limit . "," . $limit);
        }
        $this->set('table', $table);
        $this->set('shqren', $shqren);
        $this->set('all_user_arr', $all_user_arr);
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

            $isWho = $this->is_who();  // 当前用户身份
            switch ($isWho) {
                case 'keyanzhuren' : $codestatus = 0;
                    break;
                case 'caiwukezhang' : $codestatus = 2;
                    break;
                default :
                    $codestatus = '-1';
            }
            $pinfos = $this->ResearchProject->query('select p.id,u.id,u.user,u.name,u.tel from t_research_project p left join t_user u on p.user_id = u.id  where p.id=' . $pid . ' and p.code=' . $codestatus . ' and p.del=0');
            if (!$pinfos) {
                //有可能是不存在，也有可能是已经审批
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '参数有误，请重新再试';
                echo json_encode($this->ret_arr);
                exit;
            }
            $approveStatus = 0;

            //判断当前用户是 科研办公室 主任3 4、财务科 科长5 11
            if ($isWho == 'keyanzhuren') {
                // 科研办公室 主任
                $save_arr = array(
                    'project_approver_remarks' => $remarks,
                    'project_approver_id' => $approve_id,
                    'code' => $type == 2 ? 2 : 1,
                );
                $approveStatus = $this->ResearchProject->edit($pid, $save_arr);
            } else if ($isWho == 'caiwukezhang') {
                // 财务科 科长
                $save_arr = array(
                    'financial_remarks' => $remarks,
                    'financial_approver_id' => $approve_id,
                    'code' => $type == 2 ? 4 : 3,
                );
                $approveStatus = $this->ResearchProject->edit($pid, $save_arr);

                // 财务科审批通过后  添加申请人为 项目负责人
                if ($approveStatus) {
                    $pMember = array(
                        'user_id' => $pinfos[0]['u']['id'],
                        'project_id' => $pid,
                        'user_name' => $pinfos[0]['u']['user'],
                        'name' => $pinfos[0]['u']['name'],
                        'tel' => $pinfos[0]['u']['tel'],
                        'type' => 1,
                        'ctime' => date('Y-m-d'),
                    );
                    $this->ProjectMember->add($pMember);
                }
            } else {
                $this->ret_arr['code'] = 2;
                $this->ret_arr['msg'] = '无审批权限';
                exit(json_encode($this->ret_arr));
            }

            if ($approveStatus) {
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
        }
        exit;
    }

    /**
     * 审核详情
     */
    public function apply_project($pid) {
        if (empty($pid)) {
            // header("Location:/homes/index");die;
        }
        $this->set('costList', Configure::read('keyanlist'));
        $this->set('pid', $pid);

        $pinfos = $this->ResearchProject->findById($pid);
        $create_user_info = $this->User->findById($pinfos['ResearchProject']['user_id']);
        $team_name = '单个项目';
        $team_arr = $this->Team->findById($pinfos['ResearchProject']['project_team_id']);
        if (!empty($team_arr)) {
            $team_name = $team_arr['Team']['name'];
        }
        $this->set('team_name', $team_name);
        $this->set('create_user_info', $create_user_info);
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
        $total = $this->ApplyMain->query("select count(*) count from t_apply_main ApplyMain where `type`='$type' and code='$code'");
        $total = $total[0][0]['count'];
        $userArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }
            $lists = $this->ApplyMain->query("select * from t_apply_main ApplyMain where `type`='$type' and code='$code' limit " . ($pages - 1) . "," . $limit);
        }

        $this->set('lists', $lists);
        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /*     * *
     * 根据当前用户的角色 返回报销单所要审批的code 当$filed为true时，返回要更改的字段
     */

    private function get_reimbursement_code($filed = false) {
        //根据当前帐号，返回不同的code
        $position_id = $this->userInfo->position_id;

        $code = -1; //默认取不到数据
        $return_filed = 0; //默认错误
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
        if (!$filed) {
            return $code;
        }
        return $return_filed;
    }

    /**
     * 报销单审核详情
     */
    public function apply_baoxiaohuizong_print($main_id = 0, $code = '') {
        $this->set('seecode', $code);
        if (empty($main_id)) {
            header("Location:/homes/index");
            die;
        }
        //根据main_id取出数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $main_arr['ApplyMain']['subject'] = json_decode($main_arr['ApplyMain']['subject'], true);
        $attr_id = @$main_arr['ApplyMain']['attr_id'];
        $attr_arr = $this->ApplyBaoxiaohuizong->findById($attr_id);
        $create_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        $this->set('main_arr', @$main_arr['ApplyMain']);
        $this->set('createName', @$create_arr['User']['name']);
        $this->set('attr_arr', @$attr_arr['ApplyBaoxiaohuizong']);


        $kemuStr = '';
        if ($main_arr['ApplyMain']['type'] == 1) {
            $kemuArr = $this->ResearchProject->findById($main_arr['ApplyMain']['project_id']);
            $kemuStr = $kemuArr['ResearchProject']['name'];
            $kemuSourceArr = $this->ResearchSource->findById($main_arr['ApplyMain']['source_id']);
            $kemuStr .= ' |【' . $kemuSourceArr['ResearchSource']['source_channel'] . ' （' . $kemuSourceArr['ResearchSource']['file_number'] . '） ' . $kemuSourceArr['ResearchSource']['year'] . '】';
 
            if($main_arr['ApplyMain']['code'] < 10000 && $main_arr['ApplyMain']['code'] % 2 == 0 && $main_arr['ApplyMain']['is_calculation'] == 1){

            // 科研类费用 检查所申请金额是否超项目总额
            // $sourcelist = $this->residual_project_cost($main_arr, $kemuArr['ResearchProject']['amount']);
            // if ($sourcelist['code'] == 0) {
                // 科研类费用 检查所申请来源资金是否超额
                $residual = $this->residual_cost($main_arr, $attr_arr['ApplyBaoxiaohuizong']['source_id']);
                if ($residual['code'] == 0) {
                    //检查 单科目费用是否超过 科目总额
                    $is_subject_check_cost = $this->check_subject_cost($main_arr['ApplyMain']['project_id'], $main_arr['ApplyMain']['subject']);
                    $this->set('feedback', $is_subject_check_cost);
                } else {
                    $this->set('project_sum', $residual);
                    $this->set('feedback', $residual);
                }
            // } else {
            //     $this->set('project_sum', $sourcelist);
            //     $this->set('feedback', $sourcelist);
            // }
        }
        }


        if ($main_arr['ApplyMain']['type'] == 2) { // 部门
            $kemuArr = $this->Department->findById($main_arr['ApplyMain']['department_id']);
            $kemuStr = $kemuArr['Department']['name'];
            $kemuSourceArr = $this->ResearchSource->findById($main_arr['ApplyMain']['source_id']);
            $kemuStr .= ' |【' . $kemuSourceArr['ResearchSource']['source_channel'] . ' （' . $kemuSourceArr['ResearchSource']['file_number'] . '） ' . $kemuSourceArr['ResearchSource']['year'] . '】';
 
            if($main_arr['ApplyMain']['code'] < 10000 && $main_arr['ApplyMain']['code'] % 2 == 0 && $main_arr['ApplyMain']['is_calculation'] == 1){
            // 部门类型费用 检查申请单金额是否超总额
            //  $sourcelist = $this->residual_department_cost($main_arr);
            // if ($sourcelist['code'] == 0) {
                // 部门类费用 检查所申请来源资金是否超额
                $residual = $this->residual_department($main_arr, $attr_arr['ApplyBaoxiaohuizong']['source_id']);
                if ($residual['code'] == 1) {
                    $this->set('project_sum', $residual);
                    $this->set('feedback', $residual);
                }
            // } else {
            //     $this->set('project_sum', $sourcelist);
            //     $this->set('feedback', $sourcelist);
            // }
        }
        } 
        $this->set('kemuStr', $kemuStr);


        // 审核记录
        $this->cwk_show_shenpi($main_arr);

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
            $status = $this->request->data('type');
            $approve_id = $this->userInfo->id;

            $ret_arr = $this->Approval->apply($main_id, $this->userInfo, $status);

            if ($ret_arr == false) {
                //说明审批出错
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
                exit;
            }

            //保存主表的数据
            $save_main = array(
                'code' => $ret_arr['code'],
                'next_approver_id' => $ret_arr['next_id'],
                'next_apprly_uid' => $ret_arr['next_uid']
            );
            
            //保存审批的数据
            $save_approve = array(
                'main_id' => $main_id,
                'approve_id' => $approve_id,
                'remarks' => !$remarks ? '' : $remarks,
                'name' => $this->userInfo->name,
                'ctime' => date('Y-m-d H:i:s', time()),
                'status' => $status
            );

            // 获取申请详情 取出审核前下一审核角色id
            $mainInfos = $this->ApplyMain->findById($main_id);
            $approve_position_id = $mainInfos['ApplyMain']['next_approver_id'];
            
            //判断如果有审批金额则写到表里面
                if ($this->request->data('small_approval_amount')) {
                    $save_main['total'] = $this->request->data('small_approval_amount');
                    $main_subject = json_decode($mainInfos['ApplyMain']['subject'],true);
                    foreach($main_subject as $mk => $mv){
                        $main_subject[$mk] = $this->request->data('small_approval_amount');
                    }
                    $save_main['subject'] = json_encode($main_subject);
                }            

            //开启事务
            $this->ApplyMain->begin();
            if ($this->ApplyMain->edit($main_id, $save_main)) {
                $this->ApplyMain->commit();

                //如果审批通过，且跳过下个则在表里记录一下
                if (isset($ret_arr['code_id'])) {
                    foreach ($ret_arr['code_id'] as $k => $v) {
                        if ($v == $this->userInfo->id) {
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $this->userInfo->id,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $this->userInfo->name,
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        } else {
                            //根据id取出当前用户的信息
                            $userinfo = $this->User->findById($v);
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $v,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $userinfo['User']['name'],
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        }
                    }
                    $this->ApprovalInformation->saveAll($save_approve_log);
                }
                //判断如果有审批金额则写到表里面
                if ($this->request->data('small_approval_amount')) {
                    $small_approval_amount = $this->request->data('small_approval_amount');
                    $big_approval_amount = $this->request->data('big_approval_amount');
                    $attr_id = $mainInfos['ApplyMain']['attr_id'];
                    $save_arr = array(
                        'approve_money' => $small_approval_amount,
                        'approve_money_capital' => $big_approval_amount
                    );
                    $attr_arr = $this->ApplyJiekuandan->edit($attr_id, $save_arr);
                }

                //成功
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '审批成功';
                echo json_encode($this->ret_arr);
                exit;
            }
            $this->ApplyMain->rollback();
            //失败
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '审批失败';
            echo json_encode($this->ret_arr);
            exit;
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            exit;
        }
    }

    /**
     * 果树所借款单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_jiekuandan_print($main_id, $flag = '') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name . " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        //取项目信息
        $projecct_id = $attr_arr[0][$table_name]['project_id'];
        $subject = $main_arr['ApplyMain']['subject'];
        $feiyong = '';
        if ($projecct_id) {
            $project_arr = $this->ResearchProject->findById($projecct_id);
            $this->set('project_arr', $project_arr);
            //科研
            if (!empty($subject)) {
                $subject = json_decode($subject);
                foreach ($subject as $k=>$v) {
                    foreach (Configure::read('keyanlist') as $ky=>$vy) {
                        if (array_key_exists($k, $vy)) {
                            $feiyong = $vy[$k];
                            break;
                        }
                    }
                    break;
                }
                
            }
        } else {
            //行政
            if (!empty($subject)) {
                $subject = json_decode($subject);
                foreach ($subject as $k=>$v) {
                    foreach (Configure::read('xizhenglist') as $ky=>$vy) {
                        if (array_key_exists($k, $vy)) {
                            $feiyong = $vy[$k];
                            break;
                        }
                    }
                    break;
                }
                
            }
        }
        $this->set('feiyong', $feiyong);
        $source_id = $attr_arr[0][$table_name]['source_id'];
        $source_arr = $this->ResearchSource->findById($source_id);
        $this->set('source_arr', $source_arr);
        $kemuStr = '';
        if ($main_arr['ApplyMain']['type'] == 1 ) {
            $kemuArr = $this->ResearchProject->findById($main_arr['ApplyMain']['project_id']);
            $kemuStr = $kemuArr['ResearchProject']['name'];
            $kemuSourceArr = $this->ResearchSource->findById($main_arr['ApplyMain']['source_id']);
            $kemuStr .= ' |【' . $kemuSourceArr['ResearchSource']['source_channel'] . ' （' . $kemuSourceArr['ResearchSource']['file_number'] . '） ' . $kemuSourceArr['ResearchSource']['year'] . '】';

            if($main_arr['ApplyMain']['code'] < 10000 && $main_arr['ApplyMain']['code'] % 2 == 0 && $main_arr['ApplyMain']['is_calculation'] == 1){
        
            // 科研类费用 检查所申请金额是否超项目总额            
            // $sourcelist = $this->residual_project_cost($main_arr, $kemuArr['ResearchProject']['amount']);
            // if ($sourcelist['code'] == 0) {
                // 科研类费用 检查所申请来源资金是否超额
                $residual = $this->residual_cost($main_arr, $attr_arr[0][$table_name]['source_id']);
                if ($residual['code'] == 0) {
                    //检查 单科目费用是否超过 科目总额
                    $is_subject_check_cost = $this->check_subject_cost($main_arr['ApplyMain']['project_id'], json_decode($main_arr['ApplyMain']['subject'], true));
                    $this->set('feedback', $is_subject_check_cost);
                } else {
                    $this->set('project_sum', $residual);
                    $this->set('feedback', $residual);
                }
            // } else {
            //     $this->set('project_sum', $sourcelist);
            //     $this->set('feedback', $sourcelist);
            // }
        }
        }

        //账务科长 得填写审批金额
        $caiwukezhang_flag = false; //默认不是账务科长
        if ($main_arr['ApplyMain']['next_approver_id'] == 14) {
            $caiwukezhang_flag = true;
        }
        $this->set('caiwukezhang_flag', $caiwukezhang_flag);
        // 审核记录
        $this->cwk_show_shenpi($main_arr);


        if ($main_arr['ApplyMain']['type'] == 2 ) { // 部门
            $kemuArr = $this->Department->findById($main_arr['ApplyMain']['department_id']);
            $kemuStr = $kemuArr['Department']['name'];

            if($main_arr['ApplyMain']['code'] < 10000 && $main_arr['ApplyMain']['code'] % 2 == 0 && $main_arr['ApplyMain']['is_calculation'] == 1){
            // 部门类型费用 检查申请单金额是否超总额
            //  $sourcelist = $this->residual_department_cost($main_arr);
            // if ($sourcelist['code'] == 0) {
                // 部门类费用 检查所申请来源资金是否超额
                $residual = $this->residual_department($main_arr, $attr_arr[0][$table_name]['source_id']);
                if ($residual['code'] == 1) {
                    $this->set('project_sum', $residual);
                    $this->set('feedback', $residual);
                }
            // } else {
            //     $this->set('project_sum', $sourcelist);
            //     $this->set('feedback', $sourcelist);
            // }
        }
        } 
        $this->set('kemuStr', $kemuStr);




        $this->set('apply', $flag);
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }

    /**
     * 果树所领款单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_lingkuandan_print($main_id, $flag = '') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name . " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        //取项目信息
        $projecct_id = $attr_arr[0][$table_name]['project_id'];
        $subject = $main_arr['ApplyMain']['subject'];
        $feiyong = '';
        if ($projecct_id) {
            $project_arr = $this->ResearchProject->findById($projecct_id);
            $this->set('project_arr', $project_arr);
            //科研
            if (!empty($subject)) {
                $subject = json_decode($subject);
                foreach ($subject as $k=>$v) {
                    foreach (Configure::read('keyanlist') as $ky=>$vy) {
                        if (array_key_exists($k, $vy)) {
                            $feiyong = $vy[$k];
                            break;
                        }
                    }
                    break;
                }
                
            }
        } else {
            //行政
            if (!empty($subject)) {
                $subject = json_decode($subject);
                foreach ($subject as $k=>$v) {
                    foreach (Configure::read('xizhenglist') as $ky=>$vy) {
                        if (array_key_exists($k, $vy)) {
                            $feiyong = $vy[$k];
                            break;
                        }
                    }
                    break;
                }
                
            }
        }
        $this->set('feiyong', $feiyong);
        $source_id = $attr_arr[0][$table_name]['source_id'];
        $source_arr = $this->ResearchSource->findById($source_id);
        $this->set('source_arr', $source_arr);
        $kemuStr = '';
        if ($main_arr['ApplyMain']['type'] == 1 ) {
            $kemuArr = $this->ResearchProject->findById($main_arr['ApplyMain']['project_id']);
            $kemuStr = $kemuArr['ResearchProject']['name'];
            $kemuSourceArr = $this->ResearchSource->findById($main_arr['ApplyMain']['source_id']);
            $kemuStr .= ' |【' . $kemuSourceArr['ResearchSource']['source_channel'] . ' （' . $kemuSourceArr['ResearchSource']['file_number'] . '） ' . $kemuSourceArr['ResearchSource']['year'] . '】';

            if($main_arr['ApplyMain']['code'] < 10000 && $main_arr['ApplyMain']['code'] % 2 == 0 && $main_arr['ApplyMain']['is_calculation'] == 1){
            
            // 科研类费用 检查所申请金额是否超项目总额
            // $sourcelist = $this->residual_project_cost($main_arr, $kemuArr['ResearchProject']['amount']);
            // if ($sourcelist['code'] == 0) {
                // 科研类费用 检查所申请来源资金是否超额
                $residual = $this->residual_cost($main_arr, $attr_arr[0][$table_name]['source_id']);
                if ($residual['code'] == 0) {
                    //检查 单科目费用是否超过 科目总额
                    $is_subject_check_cost = $this->check_subject_cost($main_arr['ApplyMain']['project_id'], json_decode($main_arr['ApplyMain']['subject'], true));
                    $this->set('feedback', $is_subject_check_cost);
                } else {
                    $this->set('project_sum', $residual);
                    $this->set('feedback', $residual);
                }
            // } else {
            //     $this->set('project_sum', $sourcelist);
            //     $this->set('feedback', $sourcelist);
            // }
        }
        }

        // 审核记录
        $this->cwk_show_shenpi($main_arr);

        if ($main_arr['ApplyMain']['type'] == 2 ) { // 部门
            $kemuArr = $this->Department->findById($main_arr['ApplyMain']['department_id']);
            $kemuStr = $kemuArr['Department']['name'];

            if($main_arr['ApplyMain']['code'] < 10000 && $main_arr['ApplyMain']['code'] % 2 == 0 && $main_arr['ApplyMain']['is_calculation'] == 1){
            // 部门类型费用 检查申请单金额是否超总额
            //  $sourcelist = $this->residual_department_cost($main_arr);
            // if ($sourcelist['code'] == 0) {
                // 部门类费用 检查所申请来源资金是否超额
                $residual = $this->residual_department($main_arr, $attr_arr[0][$table_name]['source_id']);
                if ($residual['code'] == 1) {
                    $this->set('project_sum', $residual);
                    $this->set('feedback', $residual);
                }
            // } else {
            //     $this->set('project_sum', $sourcelist);
            //     $this->set('feedback', $sourcelist);
            // }
        }
        } 
        $this->set('kemuStr', $kemuStr);



        $this->set('apply', $flag);
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }

    /**
     * 果树所差旅费报销单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_chuchai_bxd_print($main_id, $flag = '') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name . " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        //取项目信息
        $projecct_id = $attr_arr[0][$table_name]['project_id'];
        if ($projecct_id) {
            $project_arr = $this->ResearchProject->findById($projecct_id);
            $this->set('project_arr', $project_arr);
            $source_id = $attr_arr[0][$table_name]['source_id'];
            $source_arr = $this->ResearchSource->findById($source_id);
            $this->set('source_arr', $source_arr);
        }

        $kemuStr = '';
        if ($main_arr['ApplyMain']['type'] == 1) {
            $kemuArr = $this->ResearchProject->findById($main_arr['ApplyMain']['project_id']);
            $kemuStr = $kemuArr['ResearchProject']['name'];
            $kemuSourceArr = $this->ResearchSource->findById($main_arr['ApplyMain']['source_id']);
            $kemuStr .= ' |【' . $kemuSourceArr['ResearchSource']['source_channel'] . ' （' . $kemuSourceArr['ResearchSource']['file_number'] . '） ' . $kemuSourceArr['ResearchSource']['year'] . '】';

            if($main_arr['ApplyMain']['code'] < 10000 && $main_arr['ApplyMain']['code'] % 2 == 0 && $main_arr['ApplyMain']['is_calculation'] == 1){
             
            // 科研类费用 检查所申请金额是否超项目总额   
            // $sourcelist = $this->residual_project_cost($main_arr, $kemuArr['ResearchProject']['amount']);
            // if ($sourcelist['code'] == 0) {
                // 科研类费用 检查所申请来源资金是否超额
                $residual = $this->residual_cost($main_arr, $attr_arr[0][$table_name]['source_id']);
                if ($residual['code'] == 0) {
                    //检查 单科目费用是否超过 科目总额
                    $is_subject_check_cost = $this->check_subject_cost($main_arr['ApplyMain']['project_id'], json_decode($main_arr['ApplyMain']['subject'], true));
                    $this->set('feedback', $is_subject_check_cost);
                } else {
                    $this->set('project_sum', $residual);
                    $this->set('feedback', $residual);
                }
            // } else {
            //     $this->set('project_sum', $sourcelist);
            //     $this->set('feedback', $sourcelist);
            // }
        }
        }


        if ($main_arr['ApplyMain']['type'] == 2 ) { // 部门
            $kemuArr = $this->Department->findById($main_arr['ApplyMain']['department_id']);
            $kemuStr = $kemuArr['Department']['name'];

            if($main_arr['ApplyMain']['code'] < 10000 && $main_arr['ApplyMain']['code'] % 2 == 0 && $main_arr['ApplyMain']['is_calculation'] == 1){
            // 部门类型费用 检查申请单金额是否超总额
            //  $sourcelist = $this->residual_department_cost($main_arr);
            // if ($sourcelist['code'] == 0) {
                // 部门类费用 检查所申请来源资金是否超额
                $residual = $this->residual_department($main_arr, $attr_arr[0][$table_name]['source_id']);
                if ($residual['code'] == 1) {
                    $this->set('project_sum', $residual);
                    $this->set('feedback', $residual);
                }
            // } else {
            //     $this->set('project_sum', $sourcelist);
            //     $this->set('feedback', $sourcelist);
            // }
        }
        } 
        $this->set('kemuStr', $kemuStr);

        // 审核记录
        $this->cwk_show_shenpi($main_arr);

        $this->set('apply', $flag);
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }

    /**
     * 财务科  4个单子的 审批信息
     * @param type $main_arr
     * 
     */
    public function cwk_show_shenpi($main_arr) {
        // 审核记录
        $applylist = $this->ApprovalInformation->find('all', array('conditions' => array('main_id' => $main_arr['ApplyMain']['id']), 'fields' => array('position_id', 'name', 'remarks', 'ctime')));
        // 获取部门负责人
        switch ($main_arr['ApplyMain']['type']) {
            case 2:
                $bmfzr = $this->User->query('select u.name,u.position_id from t_department d left join t_user u on d.user_id = u.id where d.id = ' . $main_arr['ApplyMain']['department_id'] . ' limit 1');
                break;
            case 1:
                $bmfzr = $this->User->query('select u.name,u.position_id from t_department d left join t_user u on d.user_id = u.id where d.id = 3 limit 1');
                break;
        }

        $applyArr = array();
        $apply_12 = array(); //科研 项目组负责人
        foreach ($applylist as $k => $v) {
            if (($main_arr['ApplyMain']['type'] == 1 && $v['ApprovalInformation']['position_id'] == $bmfzr[0]['u']['position_id']) || ($main_arr['ApplyMain']['type'] == 2 && $v['ApprovalInformation']['position_id'] == 15)) {
                $applyArr['ksfzr'] = $v['ApprovalInformation'];
            } else {
//                if ($v['ApprovalInformation']['position_id'] == 12) {
//                    //项目组负责人先保存起来
//                    $apply_12 = $v['ApprovalInformation'];
//                    continue;
//                }
                $applyArr[$v['ApprovalInformation']['position_id']] = $v['ApprovalInformation'];
            }
        }
        //如果是科研
//        if ($main_arr['ApplyMain']['type'] == 1) {
//            if (!empty($apply_12)) {
//                //把项目负责人的信息给项目负责人,用与打印显示
//                $applyArr[11] = $apply_12;
//            } else {
//                //判断next_approve_id是不12
//                if (!empty($applyArr[11])) {
//                    //看看 项目负责人在不
//                    //取出下一审核人的 next_approve_id
//                    if ($main_arr['ApplyMain']['next_approver_id'] == 12) {
//                        //如果是项目组负责人，那么就不显示项目负责人
//                        unset($applyArr[11]);
//                    }
//                }
//            }
//        }  
//        var_dump($applyArr);die;
        $this->set('applyArr', @$applyArr);
    }

    /**
     * 果树所请假单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_leave_print($main_id, $flag = '') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name . " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        //取项目信息
//        $projecct_id = $attr_arr[0][$table_name]['project_id'];
//        if ($projecct_id) {
//            $project_arr = $this->ResearchProject->findById($projecct_id);
//            $this->set('project_arr', $project_arr);
//            $source_id = $attr_arr[0][$table_name]['source_id'];
//            $source_arr = $this->ResearchSource->findById($source_id);
//            $this->set('source_arr', $source_arr);
//        }
        // 科研类费用 检查所申请来源资金是否超额
//        if($main_arr['ApplyMain']['type'] == 1){
//            $residual = $this->residual_cost($main_arr,$attr_arr[0][$table_name]['source_id']);         
//            $this->set('feedback',$residual);
//        }
        //账务科长 得填写审批金额
//        $caiwukezhang_flag = false;//默认不是账务科长
//        if ($main_arr['ApplyMain']['next_approver_id'] == 14) {
//            $caiwukezhang_flag = true;
//        }
//        $this->set('caiwukezhang_flag', $caiwukezhang_flag);
        // 审核记录
        $this->cwk_show_shenpi($main_arr);
        $dep_or_team_name = '';
        if ($main_arr['ApplyMain']['type'] == 3) {
            //取团队名称
            $team_id = $main_arr['ApplyMain']['team_id'];
            $team_arr = $this->Team->findById($team_id);
            $dep_or_team_name = $team_arr['Team']['name'];
        } else {
            //取部门
            $dep_or_team_name = $attr_arr[0][$table_name]['department_name'];
        }
        $this->set('dep_or_team_name', $dep_or_team_name);
        $this->set('apply', $flag);
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }

    /**
     * 请假申请审批
     */
    public function ajax_approve_leave() {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $main_id = $this->request->data('main_id');
            $remarks = $this->request->data('remarks');
            $status = $this->request->data('type');
            $approve_id = $this->userInfo->id;


//            if (!($main_arr = $this->ApplyMain->findById($main_id))) {
//                //有可能是不存在，也有可能是已经审批
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = '参数有误，请重新再试';
//                echo json_encode($this->ret_arr);
//                exit;
//            }
            //查看单子的 next_approve_id 是否和当前用户的职务Id一样，且有审批权限
//            $next_approve_id = $main_arr['ApplyMain']['next_approver_id'];
//            $position_id = $this->userInfo->position_id;
//            $can_approval = $this->userInfo->can_approval;
            //单子下个审批职务id与当前用户职务id不一样，不能审批
//            if ($next_approve_id != $position_id) {
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = '您暂时不能审批该单子，请刷新页面再试';
//                echo json_encode($this->ret_arr);
//                exit;
//            }
            //没有审批权限
//            if ($can_approval != 2) {
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = '您没有审批权限';
//                echo json_encode($this->ret_arr);
//                exit;
//            }

            $ret_arr = $this->ApplyLeave->apply_approve($main_id, (array) $this->userInfo, $status);

            if ($ret_arr == false) {
                //说明审批出错
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
                exit;
            }
//            $ret_arr = $this->get_apporve_approval_process_by_table_name($main_arr['ApplyMain']['table_name'], $main_arr['ApplyMain']['type'], $status, $main_arr['ApplyMain']['department_id']);
//            if ($ret_arr[$this->code] == 1) {
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = $ret_arr[$this->msg];
//                echo json_encode($this->ret_arr);
//                exit;
//            }
            //保存主表的数据
            $save_main = array(
                'code' => $ret_arr['code'],
                'next_approver_id' => $ret_arr['next_id'],
                'next_apprly_uid' => $ret_arr['next_uid']
            );
            //保存审批的数据
            $save_approve = array(
                'main_id' => $main_id,
                'approve_id' => $approve_id,
                'remarks' => !$remarks ? '' : $remarks,
                'name' => $this->userInfo->name,
                'ctime' => date('Y-m-d H:i:s', time()),
                'status' => $status
            );

            // 获取申请详情 取出审核前下一审核角色id
            $mainInfos = $this->ApplyMain->findById($main_id);
            $approve_position_id = $mainInfos['ApplyMain']['next_approver_id'];

            //开启事务
            $this->ApplyMain->begin();
            if ($this->ApplyMain->edit($main_id, $save_main)) {
//                if ($this->ApprovalInformation->add($save_approve)) {
                $this->ApplyMain->commit();

                //如果审批通过，且跳过下个则在表里记录一下
                if (isset($ret_arr['code_id'])) {
                    foreach ($ret_arr['code_id'] as $k => $v) {
                        if ($v == $this->userInfo->id) {
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $this->userInfo->id,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $this->userInfo->name,
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        } else {
                            //根据id取出当前用户的信息
                            $userinfo = $this->User->findById($v);
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $v,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $userinfo['User']['name'],
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        }
                    }
                    $this->ApprovalInformation->saveAll($save_approve_log);
                }
                //判断如果有审批金额则写到表里面
//                    if ($this->request->data('small_approval_amount')) {
//                        $small_approval_amount = $this->request->data('small_approval_amount');
//                        $big_approval_amount = $this->request->data('big_approval_amount');
//                        $attr_id = $mainInfos['ApplyMain']['attr_id'];
//                        $save_arr = array(
//                            'approve_money' => $small_approval_amount,
//                            'approve_money_capital' => $big_approval_amount
//                        );
//                        $attr_arr = $this->ApplyJiekuandan->edit($attr_id, $save_arr);
//                    }
                //成功
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '审批成功';
                echo json_encode($this->ret_arr);
                exit;
//                }
            }
            $this->ApplyMain->rollback();
            //失败
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '审批失败';
            echo json_encode($this->ret_arr);
            exit;
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            exit;
        }
    }

    public function apply_baogong_print($main_id, $flag) {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name . " where id=$attr_id");
        $team_arr = $this->ApplyMain->query("select *from t_team where id='{$attr_arr[0][$table_name]['team_id']}'");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);

        // 审核记录
        $this->cwk_show_shenpi($main_arr);
        $this->set('team_arr', $team_arr);
        $this->set('apply', $flag);
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }

    /**
     * 审批包工
     */
    public function ajax_approve_baogong() {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $main_id = $this->request->data('main_id');
            $remarks = $this->request->data('remarks');
            $status = $this->request->data('type');
            $approve_id = $this->userInfo->id;




            $ret_arr = $this->ApplyBaogong->apply_approve($main_id, (array) $this->userInfo, $status);

            if ($ret_arr == false) {
                //说明审批出错
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
                exit;
            }
//            $ret_arr = $this->get_apporve_approval_process_by_table_name($main_arr['ApplyMain']['table_name'], $main_arr['ApplyMain']['type'], $status, $main_arr['ApplyMain']['department_id']);
//            if ($ret_arr[$this->code] == 1) {
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = $ret_arr[$this->msg];
//                echo json_encode($this->ret_arr);
//                exit;
//            }
            //保存主表的数据
            $save_main = array(
                'code' => $ret_arr['code'],
                'next_approver_id' => $ret_arr['next_id'],
                'next_apprly_uid' => $ret_arr['next_uid']
            );
            //保存审批的数据
            $save_approve = array(
                'main_id' => $main_id,
                'approve_id' => $approve_id,
                'remarks' => !$remarks ? '' : $remarks,
                'name' => $this->userInfo->name,
                'ctime' => date('Y-m-d H:i:s', time()),
                'status' => $status
            );

            // 获取申请详情 取出审核前下一审核角色id
            $mainInfos = $this->ApplyMain->findById($main_id);
            $approve_position_id = $mainInfos['ApplyMain']['next_approver_id'];

            //开启事务
            $this->ApplyMain->begin();
            if ($this->ApplyMain->edit($main_id, $save_main)) {
//                if ($this->ApprovalInformation->add($save_approve)) {
                $this->ApplyMain->commit();

                //如果审批通过，且跳过下个则在表里记录一下
                if (isset($ret_arr['code_id'])) {
                    foreach ($ret_arr['code_id'] as $k => $v) {
                        if ($v == $this->userInfo->id) {
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $this->userInfo->id,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $this->userInfo->name,
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        } else {
                            //根据id取出当前用户的信息
                            $userinfo = $this->User->findById($v);
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $v,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $userinfo['User']['name'],
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        }
                    }
                    $this->ApprovalInformation->saveAll($save_approve_log);
                }
                //判断如果有审批金额则写到表里面
//                    if ($this->request->data('small_approval_amount')) {
//                        $small_approval_amount = $this->request->data('small_approval_amount');
//                        $big_approval_amount = $this->request->data('big_approval_amount');
//                        $attr_id = $mainInfos['ApplyMain']['attr_id'];
//                        $save_arr = array(
//                            'approve_money' => $small_approval_amount,
//                            'approve_money_capital' => $big_approval_amount
//                        );
//                        $attr_arr = $this->ApplyJiekuandan->edit($attr_id, $save_arr);
//                    }
                //成功
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '审批成功';
                echo json_encode($this->ret_arr);
                exit;
//                }
            }
            $this->ApplyMain->rollback();
            //失败
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '审批失败';
            echo json_encode($this->ret_arr);
            exit;
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            exit;
        }
    }

    /**
     * 果树所差旅审批单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_chuchai_print($main_id, $flag = '') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name . " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);

        // 审核记录
        $this->cwk_show_shenpi($main_arr);

        $this->set('apply', $flag);
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }

    /**
     * 差旅申请审批
     */
    public function ajax_approve_chuchai() {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $main_id = $this->request->data('main_id');
            $remarks = $this->request->data('remarks');
            $status = $this->request->data('type');
            $approve_id = $this->userInfo->id;

            $ret_arr = $this->ApplyChuchai->apply_approve($main_id, (array) $this->userInfo, $status);

            if ($ret_arr == false) {
                //说明审批出错
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
                exit;
            }

            //保存主表的数据
            $save_main = array(
                'code' => $ret_arr['code'],
                'next_approver_id' => $ret_arr['next_id'],
                'next_apprly_uid' => $ret_arr['next_uid']
            );
            //保存审批的数据
            $save_approve = array(
                'main_id' => $main_id,
                'approve_id' => $approve_id,
                'remarks' => !$remarks ? '' : $remarks,
                'name' => $this->userInfo->name,
                'ctime' => date('Y-m-d H:i:s', time()),
                'status' => $status
            );

            // 获取申请详情 取出审核前下一审核角色id
            $mainInfos = $this->ApplyMain->findById($main_id);
            $approve_position_id = $mainInfos['ApplyMain']['next_approver_id'];

            //开启事务
            $this->ApplyMain->begin();
            if ($this->ApplyMain->edit($main_id, $save_main)) {

                $this->ApplyMain->commit();

                //如果审批通过，且跳过下个则在表里记录一下
                if (isset($ret_arr['code_id'])) {
                    foreach ($ret_arr['code_id'] as $k => $v) {
                        if ($v == $this->userInfo->id) {
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $this->userInfo->id,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $this->userInfo->name,
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        } else {
                            //根据id取出当前用户的信息
                            $userinfo = $this->User->findById($v);
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $v,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $userinfo['User']['name'],
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        }
                    }
                    $this->ApprovalInformation->saveAll($save_approve_log);
                }
                //成功
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '审批成功';
                echo json_encode($this->ret_arr);
                exit;
            }
            $this->ApplyMain->rollback();
            //失败
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '审批失败';
            echo json_encode($this->ret_arr);
            exit;
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            exit;
        }
    }

    /**
     * 审批单 打印模板
     * @param type $main_id 主表id
     * @param type $flag 
     */
    private function apply_print_model($main_id, $flag = '') {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name . " where id=$attr_id");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);
        
        // 审核记录
        $this->cwk_show_shenpi($main_arr);
        
        $this->set('apply', $flag);
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
//        print_r($user_arr);die;
        $this->set('flag', $flag);
        $this->render();
    }

    /**
     * 审批单 审批模板
     */
    private function ajax_approve_model($tablename) {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $main_id = $this->request->data('main_id');
            $remarks = $this->request->data('remarks');
            $status = $this->request->data('type');
            $approve_id = $this->userInfo->id;

            $ret_arr = $this->$tablename->apply_approve($main_id, (array) $this->userInfo, $status);
            //print_r($tablename);die;
            if (!empty($ret_arr['msg'])) {
                //说明审批出错
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = $ret_arr['msg'];
                echo json_encode($this->ret_arr);
                exit;
            }
            if ($ret_arr == false) {
                //说明审批出错
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
                exit;
            }

            //保存主表的数据
            $save_main = array(
                'code' => $ret_arr['code'],
                'next_approver_id' => $ret_arr['next_id'],
                'next_apprly_uid' => $ret_arr['next_uid']
            );
            //保存审批的数据
            $save_approve = array(
                'main_id' => $main_id,
                'approve_id' => $approve_id,
                'remarks' => !$remarks ? '' : $remarks,
                'name' => $this->userInfo->name,
                'ctime' => date('Y-m-d H:i:s', time()),
                'status' => $status
            );

            // 获取申请详情 取出审核前下一审核角色id
            $mainInfos = $this->ApplyMain->findById($main_id);
            $approve_position_id = $mainInfos['ApplyMain']['next_approver_id'];

            //开启事务
            $this->ApplyMain->begin();
            if ($this->ApplyMain->edit($main_id, $save_main)) {

                $this->ApplyMain->commit();

                //如果审批通过，且跳过下个则在表里记录一下
                if (isset($ret_arr['code_id'])) {
                    foreach ($ret_arr['code_id'] as $k => $v) {
                        if ($v == $this->userInfo->id) {
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $this->userInfo->id,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $this->userInfo->name,
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        } else {
                            //根据id取出当前用户的信息
                            $userinfo = $this->User->findById($v);
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $v,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $userinfo['User']['name'],
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        }
                    }
                    $this->ApprovalInformation->saveAll($save_approve_log);
                }
                //成功
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '审批成功';
                echo json_encode($this->ret_arr);
                exit;
            }
            $this->ApplyMain->rollback();
            //失败
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '审批失败';
            echo json_encode($this->ret_arr);
            exit;
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            exit;
        }
    }

    /**
     * 果树所职工带薪年休假审批单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_paidleave_print($main_id, $flag = '') {
        $this->apply_print_model($main_id, $flag);
    }

    /**
     * 果树所职工带薪年休假审批单 审批
     */
    public function ajax_approve_paidleave() {
        $this->ajax_approve_model('ApplyPaidleave');
    }

    public function apply_endlessly_print($main_id, $flag) {
        //根据main_id取数据
        $main_arr = $this->ApplyMain->findById($main_id);
        $table_name = self::Table_fix . $main_arr['ApplyMain']['table_name'];
        $attr_id = $main_arr['ApplyMain']['attr_id'];
        //取附表
        $attr_arr = $this->ApplyMain->query("select *from " . $table_name . " where id=$attr_id");
        $team_arr = $this->ApplyMain->query("select *from t_team where id='{$attr_arr[0][$table_name]['team_id']}'");
        //取用户信息
        $user_arr = $this->User->findById($main_arr['ApplyMain']['user_id']);

        // 审核记录
        $this->cwk_show_shenpi($main_arr);
        $this->set('team_arr', $team_arr);
        $this->set('apply', $flag);
        $this->set('table_name', $table_name);
        $this->set('main_arr', $main_arr);
        $this->set('attr_arr', $attr_arr);
        $this->set('user_arr', $user_arr);
        $this->set('flag', $flag);
        $this->render();
    }

    /**
     * 职工因公不休或不全休带薪假审批表
     */
    public function ajax_approve_endlessly() {
        //code 1是审核通过，2是拒绝
        if ($this->request->is('ajax')) {
            $main_id = $this->request->data('main_id');
            $remarks = $this->request->data('remarks');
            $status = $this->request->data('type');
            $approve_id = $this->userInfo->id;

            $ret_arr = $this->ApplyEndlessly->apply_approve($main_id, (array) $this->userInfo, $status);

            if ($ret_arr == false) {
                //说明审批出错
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '审批失败';
                echo json_encode($this->ret_arr);
                exit;
            }
//            $ret_arr = $this->get_apporve_approval_process_by_table_name($main_arr['ApplyMain']['table_name'], $main_arr['ApplyMain']['type'], $status, $main_arr['ApplyMain']['department_id']);
//            if ($ret_arr[$this->code] == 1) {
//                $this->ret_arr['code'] = 1;
//                $this->ret_arr['msg'] = $ret_arr[$this->msg];
//                echo json_encode($this->ret_arr);
//                exit;
//            }
            //保存主表的数据
            $save_main = array(
                'code' => $ret_arr['code'],
                'next_approver_id' => $ret_arr['next_id'],
                'next_apprly_uid' => $ret_arr['next_uid']
            );
            //保存审批的数据
            $save_approve = array(
                'main_id' => $main_id,
                'approve_id' => $approve_id,
                'remarks' => !$remarks ? '' : $remarks,
                'name' => $this->userInfo->name,
                'ctime' => date('Y-m-d H:i:s', time()),
                'status' => $status
            );

            // 获取申请详情 取出审核前下一审核角色id
            $mainInfos = $this->ApplyMain->findById($main_id);
            $approve_position_id = $mainInfos['ApplyMain']['next_approver_id'];

            //开启事务
            $this->ApplyMain->begin();
            if ($this->ApplyMain->edit($main_id, $save_main)) {
//                if ($this->ApprovalInformation->add($save_approve)) {
                $this->ApplyMain->commit();

                //如果审批通过，且跳过下个则在表里记录一下
                if (isset($ret_arr['code_id'])) {
                    foreach ($ret_arr['code_id'] as $k => $v) {
                        if ($v == $this->userInfo->id) {
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $this->userInfo->id,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $this->userInfo->name,
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        } else {
                            //根据id取出当前用户的信息
                            $userinfo = $this->User->findById($v);
                            $save_approve_log[$k] = array(
                                'main_id' => $main_id,
                                'approve_id' => $v,
                                'remarks' => !$remarks ? '' : $remarks,
                                'position_id' => $approve_position_id,
                                'name' => $userinfo['User']['name'],
                                'ctime' => date('Y-m-d H:i:s', time()),
                                'status' => $status
                            );
                        }
                    }
                    $this->ApprovalInformation->saveAll($save_approve_log);
                }
                //判断如果有审批金额则写到表里面
//                    if ($this->request->data('small_approval_amount')) {
//                        $small_approval_amount = $this->request->data('small_approval_amount');
//                        $big_approval_amount = $this->request->data('big_approval_amount');
//                        $attr_id = $mainInfos['ApplyMain']['attr_id'];
//                        $save_arr = array(
//                            'approve_money' => $small_approval_amount,
//                            'approve_money_capital' => $big_approval_amount
//                        );
//                        $attr_arr = $this->ApplyJiekuandan->edit($attr_id, $save_arr);
//                    }
                //成功
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '审批成功';
                echo json_encode($this->ret_arr);
                exit;
//                }
            }
            $this->ApplyMain->rollback();
            //失败
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '审批失败';
            echo json_encode($this->ret_arr);
            exit;
        } else {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            exit;
        }
    }

    /**
     * 果树所 采购申请单 打印
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_caigou_print($main_id, $flag = '') {
        $this->apply_print_model($main_id, $flag);
    }

    /**
     * 果树所 采购申请单 审批
     */
    public function ajax_approve_caigou() {
        $this->ajax_approve_model('ApplyCaigou');
    }

    /**
     * 印信使用签批单
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_seal_print($main_id, $flag = '') {
        $this->apply_print_model($main_id, $flag);
    }

    /**
     * 印信使用签批单
     */
    public function ajax_approve_seal() {
        $this->ajax_approve_model('ApplySeal');
    }

    /**
     * 果树所来文处理单
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_received_print($main_id, $flag = '') {
        $this->apply_print_model($main_id, $flag);
    }

    /**
     * 果树所来文处理单 审核
     */
    public function ajax_approve_received() {
        $this->ajax_approve_model('ApplyReceived');
    }

    /**
     * 果树所发文处理单
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_dispatch_print($main_id, $flag = '') {
        $this->apply_print_model($main_id, $flag);
    }

    /**
     * 果树所发文处理单 审核
     */
    public function ajax_approve_dispatch() {
        $this->ajax_approve_model('ApplyDispatch');
    }

    /**
     * 档案借阅申请单
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_borrow_print($main_id, $flag = '') {
        $this->apply_print_model($main_id, $flag);
    }
    
    /**
     * 新闻签发卡
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_news_print($main_id, $flag = '') {
        $this->apply_print_model($main_id, $flag);
    }

    /**
     * 新闻签发卡
     * @param type $main_id 主表id
     * @param type $flag 
     */
    public function apply_request_report_print($main_id, $flag = '') {
        $this->apply_print_model($main_id, $flag);
    }
    
    /**
     * 档案借阅申请单  审核
     */
    public function ajax_approve_borrow() {
        $this->ajax_approve_model('ApplyBorrow');
    }
    
    /**
     * news
     */
    public function ajax_approve_news() {
        $this->ajax_approve_model('ApplyNews');
    }

     /**
     * news
     */
    public function ajax_approve_request_report() {
        $this->ajax_approve_model('ApplyRequestReport');
    }
    
    /**
     * 申请单 附件页面
     */
    public function file_print($fileurl) {
        $fileArr = json_decode(base64_decode($fileurl), true);
        $this->set('filearr', $fileArr);
        $this->render();
    }

    /**
     * 申请单 附件页面
     */
    public function test() {
        $project_id = 1;
        $subject = json_decode('{"data_fee":"6000","facility":"10000"}', true);
        var_dump($this->check_subject_cost($project_id, $subject));
        exit;
    }
    
    public function ajax_del_main() {
        if ($this->request->is('ajax')) {
            //确定是ajax
            $main_id = $this->request->data('main_id');
            $main_arr = $this->ApplyMain->query("select *from t_apply_main where id='{$main_id}'");
            if (empty($main_arr)) {
                $ret_arr = array(
                        'code' => 1,
                        'msg' => '此单子不存在，请刷新页面'
                    );
                echo json_encode($ret_arr);
                exit;
            }
            $creat_user_id = $main_arr[0]['t_apply_main']['user_id'];
            $table_name = 't_' . $main_arr[0]['t_apply_main']['table_name'];
            $attr_id = $main_arr[0]['t_apply_main']['attr_id'];
            $user_id = $this->userInfo->id;
            $code = $main_arr[0]['t_apply_main']['code'];
            if ($user_id != $creat_user_id) {
                $ret_arr = array(
                        'code' => 1,
                        'msg' => '您不是此单子的创建者'
                    );
                echo json_encode($ret_arr);
                exit;
            }
            if ($code != 0) {
                if ($code != 100 && !$this->has_approval_information($main_arr[0]['t_apply_main']['id'])) {
                    //跳过
                } else {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '此单子已经被操作，不能删除'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }  
            }
            //删除单子信息
            $this->ApplyMain->query("delete from t_apply_main where id='{$main_id}'");
            $this->ApplyMain->query("delete from  $table_name where id='{$attr_id}'");
            $ret_arr = array(
                        'code' => 0,
                        'msg' => '删除成功'
                    );
            echo json_encode($ret_arr);
            exit;
        }
    }
    
    //根据main id查看是否有过审批
    public function has_approval_information($main_id = 0) {
        return $this->User->query("select id from t_approval_information where main_id='{$main_id}' limit 1") ? true : false;
    }
}
