<?php

/* *
 *  请示报告卡片
 */

App::uses('ApplyRequestReport', 'AppModel');

class ApplyRequestReport extends AppModel {

    public $name = 'ApplyRequestReport';
    public $useTable = 'apply_request_report';
    public $components = array('Session');

    private $resultArr = ['state' => 0 , 'next_id' => 0 , 'next_uid' => 0 , 'code' => 0 , 'err_msg' => '' , 'code_id' => '' ];
    const SUOZHANG_ID = 6;//所长的职务id
    public $next_id = 'next_id';
    public $next_uid = 'next_uid';
    public $code = 'code';
    public $err_msg = 'msg';
    public $code_id = 'code_id';

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function add($data) {
        $this->setDataSource('write');
        $this->create();
        $this->save($data);
        return $this->id;
    }

    /**
     * 修改数据
     * @param type $id
     * @param type $data
     * @return type
     */
    public function edit($id, $data) {
        $this->setDataSource('write');
        $this->id = $id;
        $this->save($data, true, $fieldList);
        return $this->id;
    }

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function del($sid) {
        $this->setDataSource('write');
        $this->deleteAll(array('id' => $sid));
        return $this->id;
    }
   
    
    
    /**
     * 创建时获取审批信息
     * 创建信息 $data
     * 创建者信息 $user_info
     * 类型 $type 1是科研项目 2是部门 3是团队，目前只有这两种
     */
    public function apply_create($type, $data, $user_info) { 
        if($type != 1 && $type != 2){
                return false;
        }
        return $this->auditing($type, $data, $user_info);
    }


    
    // 11 验证项目组负责人
    public function apply_11($data,$user_info) {
        $resultArr = $this->resultArr ;

        // 验证通过返回下一审批角色和审批人id，不通过返回当前审批进度角色和当前进度审批人id
        if ($data['user_id'] != $user_info['id']) {
            $resultArr['err_msg'] = '非项目负责人，审核失败' ;
            $resultArr['next_id'] = 11 ;
            $resultArr['next_uid'] = $data['user_id'] ; 
            return $resultArr ;
        }

        //1.2 当前用户是项目负责人
        $resultArr['state'] = 200 ;
        $resultArr['code_id'] = $data['user_id'] ;
        $resultArr['code'] = 22 ;

        //2.1 如果该项目组负责人存在，则验证项目组负责人
        if( isset($data['xmz_uid']) ){
            $result = $this->apply_2($data,$user_info);

            //3.1 如果验证项目组负责人是当前用户，则直接返回项目组验证结果
            if( $result['state'] == 200 ){
                return $result ;
            }else{
                //3.2 如果该项目组验证失败，则返回项目组为下已审批
                $resultArr['next_id'] = $result['next_id'] ;
                $resultArr['next_uid'] = $result['next_uid'] ;  
            }
        }else{
        //2.2 如果该项目无项目组负责人，则下一审批不需要验证项目组
            $resultArr['next_id'] = 5 ;
            $resultArr['next_uid'] = $data['approval_sld'] ; 
        }
        
        return $resultArr;
        
    }


    // 2 验证项目组负责人
    public function apply_2($data,$user_info) {
        $resultArr = $this->resultArr ;

        // 验证通过返回下一审批角色和审批人id，不通过返回当前审批进度角色和当前进度审批人id
        if ( $data['xmz_uid'] != $user_info['id'] ) {
            $resultArr['err_msg'] = '非项目组负责人，审核失败' ;
            $resultArr['next_id'] = 2 ;
            $resultArr['next_uid'] = $data['xmz_uid'] ;
        } else {
            /*
            $resultArr['state'] = 200 ;
            $resultArr['code_id'] = $data['xmz_uid'] ;
            $resultArr['code'] = 4 ;
            $resultArr['next_id'] = 5 ;
            $resultArr['next_uid'] = $data['approval_sld'] ;
             */
            // 如果是团队负责人申请/审核 则直接审核结束
            $resultArr['state'] = 200 ;
            $resultArr['code_id'] = $data['xmz_uid'] ;
            $resultArr['code'] = 10000 ;
            $resultArr['next_id'] = 5 ;
            $resultArr['next_uid'] = $data['approval_sld'] ;
        }
        return $resultArr;
    }

    // 5 验证项目分管所领导
    public function apply_5($data,$user_info) {
        $resultArr = $this->resultArr ;

        // 行政部门类申请单 ，则取对应部门所领导,更改approval_sld
        if($data['type'] == 2){
            $dep = $this->query("SELECT d.user_id,d.sld FROM t_department d WHERE d.id={$data['dep_id']} limit 1 ");
            $data['approval_sld'] = $dep[0]['d']['sld'] ;
        }

        // 验证通过返回下一审批角色和审批人id，不通过返回当前审批进度角色和当前进度审批人id
        if ($data['approval_sld'] != $user_info['id']) {
            $resultArr['err_msg'] = '非项目分管所领导，审核失败' ;
            $resultArr['next_id'] = 5 ;
            $resultArr['next_uid'] = $data['approval_sld'] ;
        } else {
            $resultArr['state'] = 200 ;
            $resultArr['code_id'] = $data['approval_sld'] ;
            $resultArr['code'] = 10 ;
            $resultArr['next_id'] = 6 ;

            $suo_zhang = $this->query("select id from t_user where position_id='".self::SUOZHANG_ID."' and del=0 limit 1 ");
            $resultArr['next_uid'] = $suo_zhang[0]['t_user']['id'] ;
        }
        return $resultArr;
    }

    // 6 验证所长审批
    public function apply_6($data,$user_info) {
        $resultArr = $this->resultArr ;
        $sz_pid = self::SUOZHANG_ID;

        // 验证通过返回下一审批角色和审批人id，不通过返回当前审批进度角色和当前进度审批人id
        if ($user_info['position_id'] != $sz_pid) {
            $dep = $this->query("SELECT u.id FROM t_user u WHERE u.position_id = {$sz_pid} and u.del = 0 limit 1 ");
            $resultArr['err_msg'] = '非所长，审核失败' ;
            $resultArr['next_id'] = self::SUOZHANG_ID ;
            $resultArr['next_uid'] = $dep[0]['u']['id'] ;
        } else {
            $resultArr['state'] = 200 ;
            $resultArr['code'] = 10000 ;
            $resultArr['code_id'] = $user_info['id'] ;
        }
        return $resultArr;
    }


    // 15 验证部门负责人审批
    public function apply_15($data,$user_info) {
        $resultArr = $this->resultArr ;

        $dep = $this->query("SELECT d.user_id,d.sld FROM t_department d WHERE d.id={$data['dep_id']} limit 1 ");
 //       var_dump($dep,$user_info);
        // 验证通过返回下一审批角色和审批人id，不通过返回当前审批进度角色和当前进度审批人id
        if ($user_info['id'] != $dep[0]['d']['user_id']) {
            $resultArr['err_msg'] = '非该部门负责人，审核失败' ;
            $resultArr['next_id'] = 15 ;
            $resultArr['next_uid'] = $dep[0]['d']['user_id'] ; 
        } else {
            /*
            $resultArr['state'] = 200 ;
            $resultArr['code_id'] = $dep[0]['d']['user_id'] ;
            $resultArr['code'] = 30 ;
            $resultArr['next_id'] = 5 ;
            $resultArr['next_uid'] = $dep[0]['d']['sld'] ; 
             */
            // 如果是部门负责人申请/审核 则直接审核结束
            $resultArr['state'] = 200 ;
            $resultArr['code_id'] = $dep[0]['d']['user_id'] ;
            $resultArr['code'] = 10000 ;
            $resultArr['next_id'] = 15 ;
            $resultArr['next_uid'] = $dep[0]['d']['user_id'] ; 
        }
        return $resultArr ;
    }



    /** 
     * 行政部门：申请人—所在部门负责人—分管所领导—所长
     * 科研项目：申请人—所在项目负责人—项目所属分管所领导（赵旗峰/李登科）—所长
     *
     * 申请单 审核验证
     * @param   $type  申请单类型
     * @param   $data   ['dep_pro'] ['sum_day'] ['department_id']
     * @param   $user_info  当前用户信息
     * @param   $is_apply 为false时是创建申请单审批验证，为true时是审批验证
     * @return   $ret_arr
     */
    private function auditing($type,$data, $user_info, $is_apply = false) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => '',
            $this->code_id=>array(),
        );

        $proArr = [];
        $pro_team_id = 0 ;

        // 如果type为1是科研项目，则获取项目负责人、所领导、项目组负责人
        if($type == 1){
            $pro_infos = $this->query("select p.user_id,p.approval_sld,p.project_team_id from t_research_project p where p.id = {$data['dep_pro']} limit 1 ");
            // 项目负责人不存在 直接返回
            if( $pro_infos[0]['p']['user_id'] <= 0 ){
                $ret_arr[$this->err_msg] = '该科研项目负责人不存在';
                return $ret_arr;
            }
            $proArr = $pro_infos[0]['p'];
            $pro_team_id = $proArr['project_team_id'];
        }
        $proArr['type'] = $type;
        $proArr['dep_id'] = ( $is_apply == true ) ? $data['department_id'] : $user_info['department_id']; //项目所属部门 

        // 如果项目属于项目组  则获取项目组负责人
        if( $pro_team_id > 0 ){
            $team_infos = $this->query("select m.user_id from t_team t left join t_team_member m on t.fzr = m.id where t.id = {$pro_team_id} limit 1 ");
            $proArr['xmz_uid'] = $team_infos[0]['m']['user_id'];
        }

        $liu = explode(',', $this->get_shengpin_arr($type)); // 获取审批流

        // 如果是审批验证，移动审批到当前审批位置
        if($is_apply == true && in_array($data['next_id'], $liu)){
            foreach($liu as $ak => $av){
                if( $av != $data['next_id'] )
                     array_shift($liu);
                else
                    break;
            }
        }

        // 如果当前审批角色为2，则在审批流中增加2
        if($is_apply == true && $data['next_id'] == 2){
            array_shift($liu); 
            array_unshift($liu , 2);
        }

        $previousInfo = $applyInfo = [];  
        foreach($liu as $lk => $lv){
            $apply_name = 'apply_'.$lv;
//print_r($apply_name);
            // 11 项目负责人 项目id、user_id
            // 2 项目组负责人  项目id、user_id 
            // 5 科研项目分管领导 项目分管所领导id、user_id
            // 6 所长  user_position_id

            $applyInfo = $this->$apply_name($proArr,$user_info);
//print_r($applyInfo);
            // 验证状态为0则验证未通过，或code为10000审核完成，跳出循环验证，返回上一验证结果，如果为初次验证则返回初次验证结果  
            if( $applyInfo['state'] == 0 || $applyInfo['code'] == 10000 ){
               $applyInfo = ($lk == 0) ? $applyInfo : $previousInfo ;
                break ;
            }
            // 暂存上一验证结果
            $previousInfo = $applyInfo ;

        }
//print_r($applyInfo);
        if(empty($applyInfo)){
            return false;
        }

        $ret_arr[$this->code] = $applyInfo['code'] ;
        !empty($applyInfo['code_id']) && $ret_arr[$this->code_id][] = $applyInfo['code_id'];
        $ret_arr[$this->next_uid] = $applyInfo['next_uid'];
        $ret_arr[$this->next_id] = $applyInfo['next_id'];
        //如果是审批验证 不返回提示信息
        if($is_apply == true){
            $ret_arr[$this->err_msg] = $applyInfo['err_msg'];
        }
//var_dump($ret_arr);die;
        return $ret_arr;

    }


 /**
     * 审核
     * @param type $main_id
     * @param type $user_info
     * @param type $status
     * @return type
     */
    public function apply_approve($main_id, $user_info, $status) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => '',
            $this->code_id=>array()
        );
        //根据main_id取出信息
        $main_arr = $this->query("select m.id,m.type,m.project_id,m.department_id,m.code,m.next_apprly_uid,m.next_approver_id,m.add_lots,m.table_name,m.subject from t_apply_main m where m.id='{$main_id}' limit 1 ");
        $main_arr = $main_arr[0];
        if (empty($main_arr)) {
            $ret_arr[$this->err_msg] = '申请单不存在';
            return $ret_arr;
        }
        
        // 加签审核
        // 先判断当前节点是否有加签审批人，如果有走加签审批流程
        if ($main_arr['m']['add_lots'] != '0') {
            require_once('AddLots.php');
            $AddLots = new AddLots();
            $reserve = $AddLots->addLotsApply($user_info, $main_arr['m']) ; 
            exit( json_encode($reserve) );
        }
        
        
        
        $code = $main_arr['m']['code'];
        $next_id = $main_arr['m']['next_apprly_uid'];
        $next_approver_id = $main_arr['m']['next_approver_id'];
        if ($code == 10000) {
            $ret_arr[$this->err_msg] = '申请单已经审批通过了';
            return $ret_arr;
        }
        if ($code%2 !=0) {
            $ret_arr[$this->err_msg] = '申请单已经被拒绝';
            return $ret_arr;
        }
        if ($next_id != $user_info['id']) {
            $ret_arr[$this->err_msg] = '您无权审批此申请单';
            return $ret_arr;
        }
        //拒绝直接返回
        if ($status == 2) {
            //拒绝
            $ret_arr[$this->code] = $next_approver_id *2 -1;
            $ret_arr[$this->code_id][] = $user_info['id'];
            return $ret_arr;
        }
        $type = $main_arr['m']['type'];

        $data = array();
        $data['dep_pro'] = $main_arr['m']['project_id'];
        $data['department_id'] = $main_arr['m']['department_id'];
        $data['next_id'] = $main_arr['m']['next_approver_id'];

        return $this->auditing($type, $data, $user_info, true) ;

    }


    
    /**
     * 获取出差审批单 审批流
     * @param type $type 1科研项目，2行政
     * @return array()
     */
    private function get_shengpin_arr ($type = 2) {
        return Configure::read('approval_process')['apply_request_report'][$type];
    }

    
    
    
}
