<?php

App::uses('AppController', 'Controller');
/* 汇总报表 */

class ReportformsController extends AppController {

    public $name = 'Reportforms';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'ApplyMain', 'Department');
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
        foreach ($this->appdata['deplist'][1] as $k => $v) {
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
        //header("Location:" . $_SERVER['HTTP_REFERER']);
        $this->render();
    }
    
   
    //人事报表  请假单
    private function leave() {
        $this->layout = 'blank';
        $export_xls_head = array('title' => '请假申请单-汇总报表', 'cols' => array('ID', '申请日期', '请假人', '单位或部门', '开始日期', '结束日期', '共计天数', '事由', '请假类型', '单位负责人', '医务室', '分管领导', '分管人事领导', '所长', '审批状态'));
        $this->set('xls_head', $export_xls_head);
        $this->set('colscount', count($export_xls_head['cols']));
        
        $wherestr = '';
        if( $_POST['startdate'] && $_POST['enddate'] ){
            $wherestr = " where s.ctime between '".$_POST['startdate']."' and '".$_POST['enddate']."'";
        }
        
        $sheetArr = $this->ApplyMain->query("select m.id,m.code,s.* from t_apply_leave s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_leave' $wherestr");
        
        $leavetype = Configure::read('apply_leave_type');
        $applytype = Configure::read('new_appprove_code_arr');
        $sheetList = array();
        foreach($sheetArr as $k => $v){
            $sheetList[$v['m']['id']][] = $v['m']['id'];
            $sheetList[$v['m']['id']][] = $v['s']['ctime'];
            $sheetList[$v['m']['id']][] = $v['s']['applyname'];
            $sheetList[$v['m']['id']][] = $v['s']['department_name'];
            $sheetList[$v['m']['id']][] = $v['s']['start_time'];
            $sheetList[$v['m']['id']][] = $v['s']['end_time'];
            $sheetList[$v['m']['id']][] = $v['s']['total_days'];
            $sheetList[$v['m']['id']][] = $v['s']['about'];
            $sheetList[$v['m']['id']][] = $leavetype[$v['s']['type_id']];
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = $applytype[$v['m']['code']];
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
        if( $_POST['startdate'] && $_POST['enddate'] ){
            $wherestr = " where s.ctime between '".$_POST['startdate']."' and '".$_POST['enddate']."'";
        }
        $sheetArr = $this->ApplyMain->query("select m.id,m.code,s.*,u.name from t_apply_chuchai s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_chuchai' left join t_user u on s.user_id = u.id $wherestr ");
        
        $applytype = Configure::read('new_appprove_code_arr');
        $sheetList = array();
        foreach($sheetArr as $k => $v){
            $sheetList[$v['m']['id']][] = $v['m']['id'];
            $sheetList[$v['m']['id']][] = $v['s']['ctime'];
            $sheetList[$v['m']['id']][] = $v['u']['name'];
            $sheetList[$v['m']['id']][] = $v['s']['personnel'];
            $sheetList[$v['m']['id']][] = $v['s']['department_name'];
            $sheetList[$v['m']['id']][] = $v['s']['reason'];
            $sheetList[$v['m']['id']][] = $v['s']['start_date'];
            $sheetList[$v['m']['id']][] = $v['s']['end_date'];
            $sheetList[$v['m']['id']][] = $v['s']['days'];
            $sheetList[$v['m']['id']][] = $v['s']['place'];
            $sheetList[$v['m']['id']][] = $v['s']['mode_route'];
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = $applytype[$v['m']['code']];
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
        if( $_POST['startdate'] && $_POST['enddate'] ){
            $wherestr = " where s.create_time between '".$_POST['startdate']."' and '".$_POST['enddate']."'";
        } 
        $sheetArr = $this->ApplyMain->query("select m.id,m.code,s.*,u.name from t_apply_baogong s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_baogong' left join t_user u on s.user_id = u.id $wherestr ");
        
        $applytype = Configure::read('new_appprove_code_arr');
        $sheetList = array();
        foreach($sheetArr as $k => $v){
            $sheetList[$v['m']['id']][] = $v['m']['id'];
            $sheetList[$v['m']['id']][] = $v['s']['create_time'];
            $sheetList[$v['m']['id']][] = $v['u']['name'];
            $sheetList[$v['m']['id']][] = "`".$v['s']['number'] ;
            $sheetList[$v['m']['id']][] = $v['s']['department_name'];
            $sheetList[$v['m']['id']][] = $v['s']['personnel'];
            $sheetList[$v['m']['id']][] = $v['s']['time_address'];
            $sheetList[$v['m']['id']][] = $v['s']['content'];
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = $applytype[$v['m']['code']];
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
        if( $_POST['startdate'] && $_POST['enddate'] ){
            $wherestr = " where s.create_time between '".$_POST['startdate']."' and '".$_POST['enddate']."'";
        } 
        $sheetArr = $this->ApplyMain->query("select m.id,m.code,s.* from t_apply_paidleave s left join t_apply_main m on m.attr_id = s.id and m.table_name = 'apply_paidleave' $wherestr ");
        
        $applytype = Configure::read('new_appprove_code_arr');
        $sheetList = array();
        foreach($sheetArr as $k => $v){
            $sheetList[$v['m']['id']][] = $v['m']['id'];
            $sheetList[$v['m']['id']][] = $v['s']['create_time'];
            $sheetList[$v['m']['id']][] = $v['s']['user_name'];
            $sheetList[$v['m']['id']][] = $v['s']['department_name'];
            $sheetList[$v['m']['id']][] = $v['s']['start_work'];
            $sheetList[$v['m']['id']][] = $v['s']['years'];
            $sheetList[$v['m']['id']][] = $v['s']['vacation_days'];
            $sheetList[$v['m']['id']][] = $v['s']['yx_vacation_days'];
            $sheetList[$v['m']['id']][] = $v['s']['start_time'];
            $sheetList[$v['m']['id']][] = $v['s']['end_time'];
            $sheetList[$v['m']['id']][] = $v['s']['total_days'];
            $sheetList[$v['m']['id']][] = $v['s']['grsq'];
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = '';
            $sheetList[$v['m']['id']][] = $applytype[$v['m']['code']];
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
        $sheetname = array('leave'=>'请假申请单','chuchai'=>'果树所差旅审批单','baogong'=>'田间作业包工申请表','paidleave'=>'果树所职工带薪年休假审批单');
        
        $xls_name = $sheetname[$_POST['stype']].'-汇总报表-' . date("Y-m-d H:i:s");
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
            default:
                $fromdata = false;
        }

        $this->render();
    }

}
