<?php

App::uses('AppController', 'Controller');
/* 汇总报表 */

class ReportformsController extends AppController {

    public $name = 'Reportforms';
    var $uses=array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'ApplyMain', 'Department'); 
    /* 左 */
    
    public function index() {
        $this->layout = 'blank';
       //var_dump( $this->appdata['applyList'] );die;
        //项目对应总金额
        $totalArr = $this->ResearchSource->query('select project_id,sum(amount) sum_amount from t_research_source group by project_id ');
        $startAmount= array();
        foreach($totalArr as $v){
           $startAmount[$v['t_research_source']['project_id']] = $v[0]['sum_amount'];
        }
        //print_r($startAmount);
        
        // 各项目已支出累计金额
        $payTotal = $this->ApplyMain->query('SELECT project_id,SUM(total) sum_amount FROM t_apply_main WHERE TYPE = 1 AND is_calculation = 1 AND CODE = 10000 GROUP BY project_id ');
        $payTotalArr= array();
        foreach($payTotal as $v){
           $payTotalArr[$v['t_apply_main']['project_id']] = $v[0]['sum_amount'];
        }
       // print_r($payTotalArr);
        
        // 合并数据
        $fromArr = array(1=>array(),2=>array(),'one'=>array('amount'=>0,'pay'=>0),'two'=>array('amount'=>0,'pay'=>0));
        if(isset($this->appdata['applyList'][1])){
            foreach($this->appdata['applyList'][1] as $k => $v){
               $fromArr[1][$k]['amount'] = isset($startAmount[$k]) ? $startAmount[$k] : 0  ;
               $fromArr[1][$k]['pay']= isset($payTotalArr[$k]) ? $payTotalArr[$k] : 0 ;
               $fromArr['one']['amount'] +=  $fromArr[1][$k]['amount'];
               $fromArr['one']['pay'] +=  $fromArr[1][$k]['pay'];
            }   
        }
        
        if(isset($this->appdata['applyList'][2])){
            foreach($this->appdata['applyList'][2] as $k => $v){
               $fromArr[2][$k]['amount'] = isset($startAmount[$k]) ? $startAmount[$k] : 0  ;
               $fromArr[2][$k]['pay']= isset($payTotalArr[$k]) ? $payTotalArr[$k] : 0 ;
               $fromArr['two']['amount'] +=  $fromArr[2][$k]['amount'];
               $fromArr['two']['pay'] +=  $fromArr[2][$k]['pay'];
            }
        }
       // print_r($fromArr);
        $this->set('fromArr',$fromArr);
        
        $this->render();
 
    }

   

}
