<?php

App::uses('AppController', 'Controller');
/* 汇总报表 */

class ReportformsController extends AppController {

    public $name = 'Reportforms';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'ApplyMain', 'Department', 'ApprovalInformation','Team');
    private $ret_arr = array('code' => 0, 'msg' => '', 'class' => '');

    public function index($export = false) {
        $this->layout = 'blank';
        //项目对应总金额
        $totalArr = $this->ResearchSource->query('select project_id,sum(amount) sum_amount from t_research_source where department_id = 0 group by project_id ');
        $startAmount = array();
        foreach ($totalArr as $v) {
            $startAmount[$v['t_research_source']['project_id']] = $v[0]['sum_amount'];
        }

        // 各项目已支出累计金额
        $payTotal = $this->ApplyMain->query("SELECT project_id,SUM(total) sum_amount FROM t_apply_main WHERE TYPE = 1 AND is_calculation = 1 AND CODE = 10000  and  table_name in('apply_baoxiaohuizong','apply_jiekuandan','apply_lingkuandan','apply_chuchai_bxd')  GROUP BY project_id ");
        $payTotalArr = array();
        foreach ($payTotal as $v) {
            $payTotalArr[$v['t_apply_main']['project_id']] = $v[0]['sum_amount'];
        }

        // 合并数据
        $fromArr = $sumArr = $totalArr = array();
        foreach ($this->appdata['applyList'] as $k => $v) {
            foreach ($v as $kv => $vv) {
                $fromArr[$k][$kv]['amount'] = isset($startAmount[$kv]) ? $startAmount[$kv] : 0;
                $fromArr[$k][$kv]['pay'] = isset($payTotalArr[$kv]) ? $payTotalArr[$kv] : 0;
                $sumArr[$k]['amount'] += $fromArr[$k][$kv]['amount'];
                $sumArr[$k]['pay'] += $fromArr[$k][$kv]['pay'];
            }
        }

        foreach ($sumArr as $k => $v) {
            $totalArr['amount'] += $v['amount'];
            $totalArr['pay'] += $v['pay'];
        }

        $this->set('fromArr', $fromArr);
        $this->set('sumArr', $sumArr);
        $this->set('totalArr', $totalArr);

        if ($export) {
            return true;
        }

        $this->render();
    }

    //部门汇总报表
    public function department($export = false) {
        $this->layout = 'blank';

        // 验证是否有查看权限  财务科成员、所长、分管财务所长可查看
        if ($this->userInfo->department_id != 5 && $this->userInfo->position_id != 6) {
            header("Location:" . $_SERVER['HTTP_REFERER']);
            exit;
        }
        //部门对应总金额
        $totalArr = $this->ResearchSource->query('select id,department_id,amount,file_number from t_research_source where project_id = 0  ');
        $startAmount = array();
        foreach ($totalArr as $v) {
            $startAmount[$v['t_research_source']['department_id']][$v['t_research_source']['id']] = $v['t_research_source'];
        }

        // 各项目已支出累计金额
        $payTotal = $this->ApplyMain->query("SELECT department_id,source_id,SUM(total) sum_amount FROM t_apply_main WHERE TYPE = 2 AND is_calculation = 1 AND CODE = 10000  and  table_name in('apply_baoxiaohuizong','apply_jiekuandan','apply_lingkuandan','apply_chuchai_bxd')  GROUP BY source_id ");
        $payTotalArr = array();
        foreach ($payTotal as $v) {
            $payTotalArr[$v['t_apply_main']['department_id']][$v['t_apply_main']['source_id']] = $v[0]['sum_amount'];
        }

        // 合并数据
        $fromArr = array();
        $total = array('amount' => 0, 'pay' => 0);
        // 取行政、科研部门
        $depArr = $this->appdata['deplist'][1] + $this->appdata['deplist'][2];
        foreach ($depArr as $k => $v) {
            if (isset($startAmount[$k])) {
                $fromArr[$k]['amount'] = $fromArr[$k]['pay'] = 0;
                foreach ($startAmount[$k] as $dk => $dv) {
                    $fromArr[$k]['amount'] += isset($dv['amount']) ? $dv['amount'] : 0;
                    $startAmount[$k][$dk]['pay'] = isset($payTotalArr[$k][$dk]) ? $payTotalArr[$k][$dk] : 0;
                    $fromArr[$k]['pay'] += $startAmount[$k][$dk]['pay'];
                }
            } else {
                $fromArr[$k]['amount'] = 0;
                $fromArr[$k]['pay'] = 0;
            }
            $total['amount'] += $fromArr[$k]['amount'];
            $total['pay'] += $fromArr[$k]['pay'];
        }

        $this->set('fromArr', $fromArr);
        $this->set('total', $total);
        $this->set('startAmount', $startAmount);
        $this->set('depArr', $depArr);

        if ($export) {
            return array('fromArr' => $fromArr, 'total' => $total, 'startAmount' => $startAmount);
        }

        $this->render();
    }

    //项目汇总报表 导出
    function pro_export() {
        $this->layout = 'blank';
        $xls_name = '项目汇总报表-' . date("Y-m-d H:i:s");
        $xls_suffix = 'xls';
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=$xls_name.$xls_suffix");

        $export_xls_head = array('title' => '项目汇总报表', 'cols' => array('资金类型', '项目', '期初数', '支出累计', '期末数'));
        $this->set('xls_head', $export_xls_head);
        $dataArr = $this->index(true);

        $this->render();
    }

    //部门汇总报表 导出
    function dep_export() {
        $this->layout = 'blank';
        $xls_name = '部门汇总报表-' . date("Y-m-d H:i:s");
        $xls_suffix = 'xls';
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=$xls_name.$xls_suffix");

        $export_xls_head = array('title' => '部门汇总报表', 'cols' => array('部门', '文号', '期初数', '支出累计', '期末数'));
        $this->set('xls_head', $export_xls_head);
        $dataArr = $this->department(true);

        $this->render();
    }

    // 人事汇总报表
    function report_form() {
        $this->layout = 'blank';

        // 验证是否有查看权限  可查看成员：所长室科室成员、财务分管领导、人事教育科科长（杨明霞）、侯东梅、物资采购中心科长（杨兆亮）、采购核对员（王海松） 杨萍（1月18加的 36）
        if ($this->userInfo->department_id != 27 && ($this->userInfo->position_id != 13 || $this->userInfo->department_id != 5) && !in_array($this->userInfo->id, array(33, 35, 84, 85, 36))) {
            header("Location:" . $_SERVER['HTTP_REFERER']);
            exit;
        }

        $this->render();
    }

    //人事报表  请假单
    private function leave() {
        $export_xls_head = array('title' => '请假申请单-汇总报表', 'cols' => array('ID', '申请日期', '请假人', '单位或部门', '开始日期', '结束日期', '共计天数', '事由', '请假类型', '单位负责人', '医务室', '分管领导', '分管人事领导', '所长', '审批状态'));
        $this->set('xls_head', $export_xls_head);
        $this->set('colscount', count($export_xls_head['cols']));

        $wherestr = '';
        if ($_POST['startdate'] && $_POST['enddate']) {
            $wherestr = " where s.ctime between '" . $_POST['startdate'] . "' and '" . $_POST['enddate'] . "'";
        }

        $sheetArr = $this->ApplyMain->query("select m.id,m.code,s.* from t_apply_leave s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_leave' $wherestr");

        //获取审批信息
        $mainIdArr = $applyList = array();
        foreach ($sheetArr as $v) {
            $mainIdArr[] = $v['m']['id'];
        }
        if (count($mainIdArr) > 0) {
            $applyList = $this->ApprovalInformation->approveList($mainIdArr);
        }

        $leavetype = Configure::read('apply_leave_type');
        $applytype = Configure::read('new_appprove_code_arr');
        $sheetList = array();
        foreach ($sheetArr as $k => $v) {
            $m_id = $v['m']['id'];
            $sheetList[$m_id][] = $m_id;
            $sheetList[$m_id][] = $v['s']['ctime'];
            $sheetList[$m_id][] = $v['s']['applyname'];
            $sheetList[$m_id][] = $v['s']['department_name'];
            $sheetList[$m_id][] = $v['s']['start_time'];
            $sheetList[$m_id][] = $v['s']['end_time'];
            $sheetList[$m_id][] = $v['s']['total_days'];
            $sheetList[$m_id][] = $v['s']['about'];
            $sheetList[$m_id][] = $leavetype[$v['s']['type_id']];
            if ($v['s']['team_id'] == 0) {
                $sheetList[$m_id][] = isset($applyList[$m_id][15]) ? $applyList[$m_id][15] : '';
                $sheetList[$m_id][] = '';
                $sheetList[$m_id][] = isset($applyList[$m_id][5]) ? $applyList[$m_id][5] : '';
            } else {
                $sheetList[$m_id][] = isset($applyList[$m_id][20]) ? $applyList[$m_id][20] : '';
                $sheetList[$m_id][] = '';
                $sheetList[$m_id][] = isset($applyList[$m_id][21]) ? $applyList[$m_id][21] : '';
            }
            $sheetList[$m_id][] = isset($applyList[$m_id][22]) ? $applyList[$m_id][22] : '';
            $sheetList[$m_id][] = isset($applyList[$m_id][6]) ? $applyList[$m_id][6] : '';
            $sheetList[$m_id][] = $applytype[$v['m']['code']];
        }

        $this->set('sheetList', $sheetList);

        return true;
    }

    //人事报表  出差审批单
    private function chuchai() {
        $this->layout = 'blank';
        $export_xls_head = array('title' => '果树所差旅审批单-汇总报表', 'cols' => array('ID', '申请日期', '申请人', '出差人员', '单位或部门', '出差事由', '开始时间', '结束时间', '出差天数', '出差地点', '交通方式及路线', '部门负责人', '分管领导', '所长', '审批状态'));
        $this->set('xls_head', $export_xls_head);
        $this->set('colscount', count($export_xls_head['cols']));

        $wherestr = '';
        if ($_POST['startdate'] && $_POST['enddate']) {
            $wherestr = " where s.ctime between '" . $_POST['startdate'] . "' and '" . $_POST['enddate'] . "'";
        }
        $sheetArr = $this->ApplyMain->query("select m.id,m.code,s.*,u.name from t_apply_chuchai s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_chuchai' left join t_user u on s.user_id = u.id $wherestr ");

        //获取审批信息
        $mainIdArr = $applyList = array();
        foreach ($sheetArr as $v) {
            $mainIdArr[] = $v['m']['id'];
        }
        if (count($mainIdArr) > 0) {
            $applyList = $this->ApprovalInformation->approveList($mainIdArr);
        }

        $applytype = Configure::read('new_appprove_code_arr');
        $sheetList = array();
        foreach ($sheetArr as $k => $v) {
            $m_id = $v['m']['id'];
            $sheetList[$m_id][] = $m_id;
            $sheetList[$m_id][] = $v['s']['ctime'];
            $sheetList[$m_id][] = $v['u']['name'];
            $sheetList[$m_id][] = $v['s']['personnel'];
            $sheetList[$m_id][] = $v['s']['department_name'];
            $sheetList[$m_id][] = $v['s']['reason'];
            $sheetList[$m_id][] = $v['s']['start_date'];
            $sheetList[$m_id][] = $v['s']['end_date'];
            $sheetList[$m_id][] = $v['s']['days'];
            $sheetList[$m_id][] = $v['s']['place'];
            $sheetList[$m_id][] = $v['s']['mode_route'];
            if ($v['s']['reason'] == 0) {
                $sheetList[$m_id][] = isset($applyList[$m_id][15]) ? $applyList[$m_id][15] : '';
            } else {
                $sheetList[$m_id][] = isset($applyList[$m_id][11]) ? $applyList[$m_id][11] : '';
            }
            $sheetList[$m_id][] = isset($applyList[$m_id][5]) ? $applyList[$m_id][5] : '';
            $sheetList[$m_id][] = isset($applyList[$m_id][6]) ? $applyList[$m_id][6] : '';
            $sheetList[$m_id][] = $applytype[$v['m']['code']];
        }
        $this->set('sheetList', $sheetList);

        return true;
    }

    //人事报表  田间作业包工单
    private function baogong() {
        $this->layout = 'blank';
        $export_xls_head = array('title' => '田间作业包工申请表-汇总报表', 'cols' => array('ID', '申请日期', '申请人', '编号', '部门', '包工人员', '包工时间地点', '包工内容及工作量', '部门负责人', '科研办公室', '审批状态'));
        $this->set('xls_head', $export_xls_head);
        $this->set('colscount', count($export_xls_head['cols']));

        $wherestr = '';
        if ($_POST['startdate'] && $_POST['enddate']) {
            $wherestr = " where s.create_time between '" . $_POST['startdate'] . "' and '" . $_POST['enddate'] . "'";
        }
        $sheetArr = $this->ApplyMain->query("select m.id,m.code,s.*,u.name from t_apply_baogong s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_baogong' left join t_user u on s.user_id = u.id $wherestr ");

        //获取审批信息
        $mainIdArr = $applyList = array();
        foreach ($sheetArr as $v) {
            $mainIdArr[] = $v['m']['id'];
        }
        if (count($mainIdArr) > 0) {
            $applyList = $this->ApprovalInformation->approveList($mainIdArr);
        }

        $applytype = Configure::read('new_appprove_code_arr');
        $sheetList = array();
        foreach ($sheetArr as $k => $v) {
            $m_id = $v['m']['id'];
            $sheetList[$m_id][] = $m_id;
            $sheetList[$m_id][] = $v['s']['create_time'];
            $sheetList[$m_id][] = $v['u']['name'];
            $sheetList[$m_id][] = "`" . $v['s']['number'];
            $sheetList[$m_id][] = $v['s']['department_name'];
            $sheetList[$m_id][] = $v['s']['personnel'];
            $sheetList[$m_id][] = $v['s']['time_address'];
            $sheetList[$m_id][] = $v['s']['content'];
            $sheetList[$m_id][] = isset($applyList[$m_id][20]) ? $applyList[$m_id][20] : '';
            $sheetList[$m_id][] = isset($applyList[$m_id][4]) ? $applyList[$m_id][4] : '';
            $sheetList[$m_id][] = $applytype[$v['m']['code']];
        }
        $this->set('sheetList', $sheetList);

        return true;
    }

    //人事报表 职工带薪年休假申请单
    private function paidleave() {
        $this->layout = 'blank';
        $export_xls_head = array('title' => '果树所职工带薪年休假审批单-汇总报表', 'cols' => array('ID', '申请日期', '申请人', '所在单位', '参加工作时间', '工作年限', '按规定享受年假天数', '本年度已休年假天数', '开始时间', '结束时间', '共几天', '个人申请', '所在单位负责人意见', '分管领导意见', '主管人事领导意见', '审批状态'));
        $this->set('xls_head', $export_xls_head);
        $this->set('colscount', count($export_xls_head['cols']));

        $wherestr = '';
        if ($_POST['startdate'] && $_POST['enddate']) {
            $wherestr = " where s.create_time between '" . $_POST['startdate'] . "' and '" . $_POST['enddate'] . "'";
        }
        $sheetArr = $this->ApplyMain->query("select m.id,m.type,m.code,s.* from t_apply_paidleave s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_paidleave' $wherestr ");

        //获取审批信息
        $mainIdArr = $applyList = array();
        foreach ($sheetArr as $v) {
            $mainIdArr[] = $v['m']['id'];
        }
        if (count($mainIdArr) > 0) {
            $applyList = $this->ApprovalInformation->approveList($mainIdArr);
        }

        $applytype = Configure::read('new_appprove_code_arr');
        $sheetList = array();
        foreach ($sheetArr as $k => $v) {
            $m_id = $v['m']['id'];
            $sheetList[$m_id][] = $m_id;
            $sheetList[$m_id][] = $v['s']['create_time'];
            $sheetList[$m_id][] = $v['s']['user_name'];
            $sheetList[$m_id][] = $v['s']['department_name'];
            $sheetList[$m_id][] = $v['s']['start_work'];
            $sheetList[$m_id][] = $v['s']['years'];
            $sheetList[$m_id][] = $v['s']['vacation_days'];
            $sheetList[$m_id][] = $v['s']['yx_vacation_days'];
            $sheetList[$m_id][] = $v['s']['start_time'];
            $sheetList[$m_id][] = $v['s']['end_time'];
            $sheetList[$m_id][] = $v['s']['total_days'];
            $sheetList[$m_id][] = $v['s']['grsq'];
            if ($v['m']['type'] == 3) {
                $sheetList[$m_id][] = isset($applyList[$m_id][20]) ? $applyList[$m_id][20] : '';
                $sheetList[$m_id][] = isset($applyList[$m_id][21]) ? $applyList[$m_id][21] : '';
            } else {
                $sheetList[$m_id][] = isset($applyList[$m_id][15]) ? $applyList[$m_id][15] : '';
                $sheetList[$m_id][] = isset($applyList[$m_id][5]) ? $applyList[$m_id][5] : '';
            }
            $sheetList[$m_id][] = isset($applyList[$m_id][22]) ? $applyList[$m_id][22] : '';
            $sheetList[$m_id][] = $applytype[$v['m']['code']];
        }
        $this->set('sheetList', $sheetList);

        return true;
    }

    //人事报表 采购申请单
    private function caigou() {
        $this->layout = 'blank';
        $export_xls_head = array('title' => '果树所采购申请单-汇总报表', 'cols' => array('ID', '申报部门', '支出项目', '申报时间', '经办人', '预算指标文号', '资金性质', '采购物资名称', '单位', '数量', '单价', '合计金额', '采购理由', '需求部门负责人审核', '需求团队负责人审核', '需求部门分管所领导审核', '财务科审核', '采购内容核对', '采购中心审核', '财务及采购分管领导审核', '所长审核', '审核状态', '采购完成时间', '支出金额'));
        $this->set('xls_head', $export_xls_head);
        $this->set('colscount', count($export_xls_head['cols']));

        $wherestr = '';
        if ($_POST['startdate'] && $_POST['enddate']) {
            $wherestr = " where s.create_time between '" . $_POST['startdate'] . "' and '" . $_POST['enddate'] . "'";
        }
        $sheetArr = $this->ApplyMain->query("select m.id,m.type,m.code,m.user_id,u.name,s.* from t_apply_caigou s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_caigou' left join t_user u on s.user_id = u.id $wherestr ");

        //获取审批信息
        $mainIdArr = $applyList = array();
        foreach ($sheetArr as $v) {
            $mainIdArr[] = $v['m']['id'];
        }
        if (count($mainIdArr) > 0) {
            $applyList = $this->ApprovalInformation->approveList($mainIdArr);
        }

        $applytype = Configure::read('new_appprove_code_arr');
        $channelType = Configure::read('apply_caigou_type');
        $sheetList = array();
        foreach ($sheetArr as $k => $v) {
            $m_id = $v['m']['id'];
            $sheetList[$m_id][] = $m_id;
            $sheetList[$m_id][] = $v['s']['project'];
            $sheetList[$m_id][] = $v['s']['team_name'];
            $sheetList[$m_id][] = $v['s']['ctime'];
            $sheetList[$m_id][] = $v['u']['name'];
            $sheetList[$m_id][] = $v['s']['file_number'];
            $sheetList[$m_id][] = $channelType[$v['s']['channel_id']];
            $sheetList[$m_id][] = $v['s']['purchase_name'];
            $sheetList[$m_id][] = $v['s']['company'];
            $sheetList[$m_id][] = $v['s']['number'];
            $sheetList[$m_id][] = $v['s']['price'];
            $sheetList[$m_id][] = $v['s']['amount'];
            $sheetList[$m_id][] = $v['s']['reason'];
            //1 => '11,20,5,14,23,24,13,6'
            //2 => '15,5,14,23,24,13,6'

            $sheetList[$m_id][] = ($v['s']['type'] == 1) ? $applyList[$m_id][11] : $applyList[$m_id][15];
            $sheetList[$m_id][] = ($v['s']['type'] == 1) ? $applyList[$m_id][20] : '';
            $sheetList[$m_id][] = isset($applyList[$m_id][5]) ? $applyList[$m_id][5] : '';
            $sheetList[$m_id][] = isset($applyList[$m_id][14]) ? $applyList[$m_id][14] : '';
            $sheetList[$m_id][] = isset($applyList[$m_id][23]) ? $applyList[$m_id][23] : '';

            $sheetList[$m_id][] = isset($applyList[$m_id][24]) ? $applyList[$m_id][24] : '';
            $sheetList[$m_id][] = isset($applyList[$m_id][13]) ? $applyList[$m_id][13] : '';
            $sheetList[$m_id][] = isset($applyList[$m_id][6]) ? $applyList[$m_id][6] : '';

            $sheetList[$m_id][] = $applytype[$v['m']['code']];
        }
        $this->set('sheetList', $sheetList);

        return true;
    }

    //人事报表 汇总 导出
    function personnel_export() {
        $this->layout = 'blank';
        $msg = $this->ret_arr;
        if (!$_POST['stype'] || !$_POST['startdate'] || !$_POST['enddate']) {
            $msg['msg'] = '参数有误';
            echo json_encode($msg);
            die;
        }
        $sheetname = array('leave' => '请假申请单', 'chuchai' => '果树所差旅审批单', 'baogong' => '田间作业包工申请表', 'paidleave' => '果树所职工带薪年休假审批单', 'caigou' => '果树所采购申请单');

        $xls_name = $sheetname[$_POST['stype']] . '-汇总报表-' . date("Y-m-d H:i:s");
        $xls_suffix = 'xls';
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=$xls_name.$xls_suffix");

        $fromdata = '';
        switch ($_POST['stype']) {
            case 'leave':
                $fromdata = $this->leave();
                $this->set('fromtype', 'leave');
                break;
            case 'chuchai':
                $fromdata = $this->chuchai();
                $this->set('fromtype', 'chuchai');
                break;
            case 'baogong':
                $fromdata = $this->baogong();
                $this->set('fromtype', 'baogong');
                break;
            case 'paidleave':
                $fromdata = $this->paidleave();
                $this->set('fromtype', 'paidleave');
                break;
            case 'caigou':
                $fromdata = $this->caigou();
                $this->set('fromtype', 'caigou');
                break;
            default:
                $fromdata = false;
        }

        $this->render();
    }

    protected function keyankemu() {
        $keyanlist = ['key' => [], 'val' => []];
        foreach (Configure::read('keyanlist') as $tdv) {
            foreach ($tdv as $lk => $lv) {
                $keyanlist['key'][] = $lk;
                $keyanlist['val'][] = $lv;
            }
        }
        return $keyanlist;
    }
    
    protected function keyankemuArr() {
        $keyanlist = [];
        foreach (Configure::read('keyanlist') as $tdv) {
            foreach ($tdv as $lk => $lv) {
                $keyanlist[$lk] = $lv;
            }
        }
        return $keyanlist;
    }
    
    public function summary_bak($export = false) {
        $this->layout = 'blank';
        // 验证是否有查看权限  财务科成员、所长、分管财务所长可查看
        if($this->userInfo->department_id != 5 && $this->userInfo->position_id != 6){
            header("Location:" . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // 1、取当前所有 未关闭、未删除状态下的 科研项目
        $proArr = $this->ResearchProject->summary_pro();
        // 2、取符合条件的所有科研项目的 总额、科目总额
        $proCountSum = $this->ResearchProject->summary_ky_bak($proArr);
        $proCountSum = $proCountSum[0][0];

        // 3、取符合条件的所有科研项目的 申请单（已申请通过）的总额、科目总额
        $expendSum = $this->ApplyMain->summary_keyan_pro_bak($proArr);
        
        // 4、计算科目的支出剩余总金额、科目剩余总额；支出进度；
        $surplusSum = $percentage = [];  // 结余总额、支出百分比
        $keyanlist = $this->keyankemu();
        $keyanlist['key'][] = 'total'; 
        $keyanlist['val'][] = '总计'; 
        
        foreach($keyanlist['key'] as $v){
            $surplusSum[$v] = $proCountSum[$v] - $expendSum[$v]; 
            $percentage[$v] = round($expendSum[$v] / $proCountSum[$v] * 100 , 2) ;
        }

        $this->set('proCountSum', $proCountSum);
        $this->set('expendSum', $expendSum);
        $this->set('surplusSum', $surplusSum);
        $this->set('percentage', $percentage);
        $this->set('keyanlist', $keyanlist);

        if ($export) {
            return array('proCountSum' => $proCountSum, 'expendSum' => $expendSum, 'surplusSum' => $surplusSum, 'percentage' => $percentage, 'keyanlist' => $keyanlist);
        }
        $this->render();
    }

    //人事报表 汇总 导出
    function sum_export_bak() {
        $this->layout = 'blank';
        $msg = $this->ret_arr;
        // 验证是否有查看权限  财务科成员、所长、分管财务所长可查看
      if($this->userInfo->department_id != 5 && $this->userInfo->position_id != 6){
         header("Location:" . $_SERVER['HTTP_REFERER']);
         exit;
      }

        //$export_xls_head = array('title' => '科研项目汇总报表', 'cols' => array('ID', '申请日期', '申请人', '出差人员', '单位或部门', '出差事由', '开始时间', '结束时间', '出差天数', '出差地点', '交通方式及路线', '部门负责人', '分管领导', '所长', '审批状态'));
        $this->set('title', '科研项目汇总报表');
        $xls_name = '科研项目汇总报表-' . date("Y-m-d H:i:s");
        $xls_suffix = 'xls';
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=$xls_name.$xls_suffix");

        $fromdata = $this->summary();

        $this->render();
    }


 public function summary($export = false) {
        $this->layout = 'blank';
        // 验证是否有查看权限  财务科成员、所长、分管财务所长可查看
        if($this->userInfo->department_id != 5 && $this->userInfo->position_id != 6){
            header("Location:" . $_SERVER['HTTP_REFERER']);
            exit;
        }

        // 1、取当前所有 未关闭、未删除状态下的 科研项目
        $proArr = $this->ResearchProject->summary_pro();
        // 2、取符合条件的所有科研项目的 总额、科目总额
        $proCountSum = $this->ResearchProject->summary_ky($proArr); 
        //var_dump($proCountSum);die;

        // 3、取符合条件的所有科研项目的 申请单（已申请通过）的总额、科目总额
        $expendSum = $this->ApplyMain->summary_keyan_pro($proArr);  
        //var_dump($expendSum);die;
        
        // 4、计算科目的支出剩余总金额、科目剩余总额；支出进度；
        $surplusSum = $percentage = [];  // 结余总额、支出百分比
        $keyanlist = $this->keyankemuArr();
        $keyanlist = ['total' => '合计'] + $keyanlist;  
       
        foreach($proCountSum as $key => $val){
            foreach($val as $k => $v){
                if(isset($expendSum[$key][$k])){
                    $surplusSum[$key][$k] = $v - $expendSum[$key][$k] ; 
                    $percentage[$key][$k] = round($expendSum[$key][$k] / $v * 100 , 2) ;
                }else if( $v > 0 ){
                    $surplusSum[$key][$k] = $v ; 
                    $percentage[$key][$k] = 100 ;
                }else{
                    $surplusSum[$key][$k] = 0 ; 
                    $percentage[$key][$k] = 0 ;
                }
            }
        }
//var_dump($surplusSum);die;
//var_dump($percentage);die;
        $teamlist = $this->Team->getList();
        // $teamlist = $teamlist + [0 => '单个项目'];
        $this->set('teamlist', $teamlist);
        $this->set('proCountSum', $proCountSum);
        $this->set('expendSum', $expendSum);
        $this->set('surplusSum', $surplusSum);
        $this->set('percentage', $percentage);
        $this->set('keyanlist', $keyanlist);

        if ($export) {
            return array('teamlist' => $teamlist, 'proCountSum' => $proCountSum, 'expendSum' => $expendSum, 'surplusSum' => $surplusSum, 'percentage' => $percentage, 'keyanlist' => $keyanlist);
        }
        $this->render();
    }

    //人事报表 汇总 导出
    function sum_export() {
        $this->layout = 'blank';
        $msg = $this->ret_arr;
        // 验证是否有查看权限  财务科成员、所长、分管财务所长可查看
      if($this->userInfo->department_id != 5 && $this->userInfo->position_id != 6){
         header("Location:" . $_SERVER['HTTP_REFERER']);
         exit;
      }

      $fromdata = $this->summary();
      
      $export_xls_head = array('title' => '团队进度表', 'cols' => $fromdata['keyanlist']);
      $this->set('xls_head', $export_xls_head);
        
        $xls_name = '团队进度表-' . date("Y-m-d-Hi");
        $xls_suffix = 'xls';
        header("Content-Type:application/vnd.ms-excel");
        header("Content-Disposition:attachment;filename=$xls_name.$xls_suffix");

        $this->render();
    }    
    
    
    
    
}
