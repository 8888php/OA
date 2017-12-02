<?php

App::uses('AppController', 'Controller');
/* 汇总报表 */

class ReportformsController extends AppController {

    public $name = 'Reportforms';
    var $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'ApplyMain', 'Department');

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
        $fromArr = array(1 => array(), 2 => array(), 'one' => array('amount' => 0, 'pay' => 0), 'two' => array('amount' => 0, 'pay' => 0));
        if (isset($this->appdata['applyList'][1])) {
            foreach ($this->appdata['applyList'][1] as $k => $v) {
                $fromArr[1][$k]['amount'] = isset($startAmount[$k]) ? $startAmount[$k] : 0;
                $fromArr[1][$k]['pay'] = isset($payTotalArr[$k]) ? $payTotalArr[$k] : 0;
                $fromArr['one']['amount'] += $fromArr[1][$k]['amount'];
                $fromArr['one']['pay'] += $fromArr[1][$k]['pay'];
            }
        }

        if (isset($this->appdata['applyList'][2])) {
            foreach ($this->appdata['applyList'][2] as $k => $v) {
                $fromArr[2][$k]['amount'] = isset($startAmount[$k]) ? $startAmount[$k] : 0;
                $fromArr[2][$k]['pay'] = isset($payTotalArr[$k]) ? $payTotalArr[$k] : 0;
                $fromArr['two']['amount'] += $fromArr[2][$k]['amount'];
                $fromArr['two']['pay'] += $fromArr[2][$k]['pay'];
            }
        }
        $this->set('fromArr', $fromArr);

        if ($export) {
            return $fromArr;
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
       $xls_name = '项目汇总报表-'.date("Y-m-d H:i:s");
       $xls_suffix = 'xls';
       header("Content-Type:application/vnd.ms-excel");
       header("Content-Disposition:attachment;filename=$xls_name.$xls_suffix");

        $export_xls_head = array('title'=>'项目汇总报表', 'cols'=>array('资金类型', '项目', '期初数', '支出累计', '期末数'));
       $this->set('xls_head',$export_xls_head);
       $dataArr = $this->index(true);
       
       $this->render();
    }

   //部门汇总报表 导出
    function dep_export() {
       $this->layout = 'blank';
       $xls_name = '部门汇总报表-'.date("Y-m-d H:i:s");
       $xls_suffix = 'xls';
       header("Content-Type:application/vnd.ms-excel");
       header("Content-Disposition:attachment;filename=$xls_name.$xls_suffix");
       
       $export_xls_head = array('title'=>'部门汇总报表', 'cols'=>array('部门', '文号', '期初数', '支出累计', '期末数'));
       $this->set('xls_head',$export_xls_head);
       $dataArr = $this->department(true);
       
       $this->render();
    }
    
    
}
