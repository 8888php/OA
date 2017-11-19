<?php

/* *
 *  差旅审批单
 */

App::uses('ApplyChuchai', 'AppModel');

class ApplyChuchai extends AppModel {

    public $name = 'ApplyChuchai';
    public $useTable = 'apply_chuchai';
    public $components = array('Session');

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
     * 类型 $type 2是部门 3是团队，目前只有这两种
     */
    public function apply_create($type, $data, $user_info) { 
        switch($type){
            case 2:  //行政
                return $this->dep_create($type, $data, $user_info);
            break;
            case 1:  // 科研
                return $this->pro_create($type, $data, $user_info);
            break;
            default:
                return false;
        }
    }
    //部门
    /**
     * 行政部门：申请人—所在部门负责人—分管所领导—所长
          科研 ：申请人—所在项目负责人—分管所领导（赵旗峰）—所长
     */
    public $next_id = 'next_id';
    public $next_uid = 'next_uid';
    public $code = 'code';
    public $err_msg = 'msg';
    public $code_id = 'code_id';

    const SUOZHANG_ID = 6;//所长的职务id

    // 部门类型申请单
    private function dep_create($type, $data, $user_info, $is_apply = false) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => '',
            $this->code_id=>array(),
        );

        $sum_days = $data['sum_day'];//天数
        // 是否所长申请
        if ($user_info['position_id'] == self::SUOZHANG_ID) {
            //说明他是所长
            $ret_arr[$this->code] = 10000;
            $ret_arr[$this->code_id][] = $user_info['id'];
            $ret_arr[$this->next_uid] = 0;
            $ret_arr[$this->next_id] = 0;
            return $ret_arr;
        }
       
        // 是否部门所领导申请
        if ($is_apply) {
            //审批时间取创建单子的 部门id
            $dep_id = $data['dep_id'];
        } else {
            $dep_id = $user_info['department_id'];
        }
        $sql_1 = "select *from t_department where id='{$dep_id}' and del=0";
        $dem_arr = $this->query($sql_1);
        if (empty($dem_arr)) {
            $ret_arr[$this->err_msg] = '部门不存在';
            return $ret_arr;
        }
        if ($dem_arr[0]['t_department']['sld'] == $user_info['id']) {
            //说明他是分管领导  取出所长信息
            $sql_suozhang = "select *from t_user where position_id='".self::SUOZHANG_ID."' and del=0";
            $suo_zhang = $this->query($sql_suozhang);
            if (empty($suo_zhang)) {
                $ret_arr[$this->err_msg] = '所长不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = self::SUOZHANG_ID;
            $ret_arr[$this->next_uid] = $suo_zhang[0]['t_user']['id'];
            $ret_arr[$this->code_id][] = $user_info['id'];
            // 3天以内 所领导审，3天以上 需所长最终审批
            if($sum_days <= 3){ 
                $ret_arr[$this->code] = $is_apply ? 10000 : 0;
            }else{
                $ret_arr[$this->code] = $is_apply ? 5*2 : 0;
            }
            return $ret_arr;
        }
        
        // 是否部门负责人申请
        if ($dem_arr[0]['t_department']['user_id'] == $user_info['id']) {
            //说明他是部门负责人  取分管所领导信息
            $sql_fenguan = "SELECT *FROM t_user u LEFT JOIN t_department d ON d.sld=u.id WHERE d.id={$user_info['department_id']} ";
            $fenguan_arr = $this->query($sql_fenguan);
            if (empty($fenguan_arr)) {
                $ret_arr[$this->err_msg] = '分管所领导不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = 5;
            $ret_arr[$this->next_uid] = $fenguan_arr[0]['u']['id'];
            $ret_arr[$this->code_id][] = $user_info['id'];
            $ret_arr[$this->code] = $is_apply ? 15*2 : 0;
            return $ret_arr;
        }
        
        if($is_apply === false){
            // 职员申请
            $sql_fenze = "SELECT *FROM t_user u LEFT JOIN t_department d ON d.user_id=u.id WHERE d.id={$user_info['department_id']} ";
            $fuze_arr = $this->query($sql_fenze);
            if (empty($fuze_arr)) {
                $ret_arr[$this->err_msg] = '部门负责人不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = 15;
            $ret_arr[$this->next_uid] = $fuze_arr[0]['u']['id'];
            return $ret_arr;
        }
        return false;
    }
    
    //科研类型申请单
    private function pro_create($type, $data, $user_info, $is_apply = false) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => '',
            $this->code_id=>array(),
        );
        $sum_days = $data['sum_day'];//天数
        // 是否所长申请
        if ($user_info['position_id'] == self::SUOZHANG_ID) {
            //说明他是所长
            $ret_arr[$this->code] = 10000;
            $ret_arr[$this->code_id][] = $user_info['id'];
            $ret_arr[$this->next_uid] = 0;
            $ret_arr[$this->next_id] = 0;
            return $ret_arr;
        }
        
        // 是否科研办公室所领导申请
        $sql = "select * from t_department where id=3 and del=0";
        $pro_arr = $this->query($sql);
        if (empty($pro_arr[0]['t_department']['sld'])) {
            $ret_arr[$this->err_msg] = '科研办公室所领导不存在';
            return $ret_arr;
        }
        if ($pro_arr[0]['t_department']['sld'] == $user_info['id']) {
            //说明他是科研办公室所领导   取出所长信息
            $sql_suozhang = "select *from t_user where position_id='".self::SUOZHANG_ID."' and del=0";
            $suo_zhang = $this->query($sql_suozhang);
            if (empty($suo_zhang)) {
                $ret_arr[$this->err_msg] = '所长不存在';
                return $ret_arr;
            }

            $ret_arr[$this->code_id][] = $user_info['id'];
            // 3天以内 所领导审，3天以上 需所长最终审批
            if($sum_days <= 3){ 
                $ret_arr[$this->code] = $is_apply ? 10000 : 10000;
            }else{
                $ret_arr[$this->next_id] = self::SUOZHANG_ID;
                $ret_arr[$this->next_uid] = $suo_zhang[0]['t_user']['id'];
                $ret_arr[$this->code] = $is_apply ? 5*2 : 0;
            }
            return $ret_arr;
        }
        //课题组负责人  2 
        $xmz_flag = false;//是否 项目组负责人
        $por_id = $data['dep_pro'];
        $xmz_sql = "select *from t_research_project t_p left join t_team_project t_t on t_p.project_team_id=t_t.id where t_t.id !=1 and t_t.del=0 and t_t.team_user_id > 0 and t_p.id='{$por_id}'";
        $xmz_arr = $this->query($xmz_sql);
        if (!empty($xmz_arr)) {
            $xmz_flag = true;//有项目组负责人
            if ($xmz_arr[0]['t_t']['team_user_id'] < 1) {
                //项目负责人不存在
                $ret_arr[$this->err_msg] = '项目组负责人不存在';
                return $ret_arr;
            }
            //项目组负责人
            $sql_fenguan = "SELECT *FROM t_user u WHERE u.id={$pro_arr[0]['t_department']['sld']} ";
            $fenguan_arr = $this->query($sql_fenguan);
            if ($xmz_arr[0]['t_t']['team_user_id'] == $user_info['id']) {
                $ret_arr[$this->next_id] = 5;
                $ret_arr[$this->next_uid] = $fenguan_arr[0]['u']['id'];
                $ret_arr[$this->code_id][] = $user_info['id'];
                $ret_arr[$this->code] = $is_apply ? 2*2 : 0;
                return $ret_arr;
            }
            
        }
        
        
        // 是否项目负责人申请
        $pro_fzr = "SELECT * FROM t_user u LEFT JOIN t_research_project p ON p.user_id = u.id WHERE p.id={$data['dep_pro']} ";
        $profzr_arr = $this->query($pro_fzr);
        if (empty($profzr_arr)) {
            $ret_arr[$this->err_msg] = '项目负责人不存在';
            return $ret_arr;
        }
        
        if ($profzr_arr[0]['p']['user_id'] == $user_info['id']) {
            //说明他是项目负责人  取科研办公室所领导信息
            $sql_fenguan = "SELECT *FROM t_user u WHERE u.id={$pro_arr[0]['t_department']['sld']} ";
            $fenguan_arr = $this->query($sql_fenguan);
            if (empty($fenguan_arr)) {
                $ret_arr[$this->err_msg] = '科研办公室所领导不存在';
                return $ret_arr;
            }
            if ($xmz_flag) {
                //有项目组负责人
                $ret_arr[$this->next_id] = 2;
                $ret_arr[$this->next_uid] = $xmz_arr[0]['t_t']['team_user_id'];
                $ret_arr[$this->code_id][] = $user_info['id'];
                $ret_arr[$this->code] = $is_apply ? 11*2 : 0;
            } else {
                $ret_arr[$this->next_id] = 5;
                $ret_arr[$this->next_uid] = $fenguan_arr[0]['u']['id'];
                $ret_arr[$this->code_id][] = $user_info['id'];
                $ret_arr[$this->code] = $is_apply ? 11*2 : 0;
            }
            return $ret_arr;
        }
        
        if($is_apply === false){
            // 项目所属职员申请
            $ret_arr[$this->next_id] = 15;
            $ret_arr[$this->next_uid] = $profzr_arr[0]['u']['id'];
            return $ret_arr;
        }
        return false;
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
        $main_sql = "select *from t_apply_main where id='{$main_id}'";
        $main_arr = $this->query($main_sql);
        if (empty($main_arr)) {
            $ret_arr[$this->err_msg] = '申请单信息不存在';
            return $ret_arr;
        }
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
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
        $type = $main_arr[0]['t_apply_main']['type'];
        
        $chuchai_sql = "select * from t_apply_chuchai where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $chuchai_arr = $this->query($chuchai_sql);
        $data = array();
        $data['sum_day'] = $chuchai_arr[0]['t_apply_chuchai']['days'];
        $data['dep_pro'] = $chuchai_arr[0]['t_apply_chuchai']['project_id'];
        $data['dep_id'] = $chuchai_arr[0]['t_apply_chuchai']['department_id'];
        if ($type == 2) {
            //部门
           return $this->dep_create($type, $data, $user_info, true) ;
        } else {
            //科研
            return $this->pro_create($type, $data, $user_info, true) ;
        }
    }
    
    /**
     * 
     * @param type $type 1科研项目，2行政
     * @return array()
     */
    private function get_shengpin_arr ($type = 2) {
        return Configure::read('approval_process')['apply_chuchai'][$type];
    }
            
    
   
    //根据职务和部门取出用户信息 行政
    private function get_by_pos_dep($pos_id, $dep_id, $team_id=0) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0
        );
        if ($pos_id == 15) {
            $sql_15 = "select *from t_department where id='{$dep_id}' and del=0";
            $arr_15 = $this->query($sql_15);
            $ret_arr[$this->next_uid] = empty($arr_15[0]['t_department']['user_id']) ? 0 : $arr_15[0]['t_department']['user_id'];
            $ret_arr[$this->next_id] = 15;
        } elseif ($pos_id == 5) {
            $sql_5 = "select *from t_department where id='{$dep_id}' and del=0";
            $arr_5 = $this->query($sql_5);
            $ret_arr[$this->next_uid] = empty($arr_5[0]['t_department']['sld']) ? 0 : $arr_5[0]['t_department']['sld'];
            $ret_arr[$this->next_id] = 5;
        } elseif ($pos_id == 22) {
            $sql_22 = "select *from t_department where id=4 and del=0";
            $arr_22 = $this->query($sql_22);
            $ret_arr[$this->next_uid] = empty($arr_22[0]['t_department']['sld']) ? 0 : $arr_22[0]['t_department']['sld'];
            $ret_arr[$this->next_id] = 22;
        } elseif ($pos_id == 6) {
            $sql_6 = "select *from t_user where position_id=6 and del=0";
            $arr_6 = $this->query($sql_6);
            $ret_arr[$this->next_uid] = empty($arr_6[0]['t_user']['id']) ? 0 : $arr_6[0]['t_user']['id'];
            $ret_arr[$this->next_id] = 6;
        } elseif ($pos_id == 20) {
            $sql_20 = "select *from t_team t left join t_team_member m on m.team_id=t.id and m.id=t.fzr where t.id='{$team_id}'  and del=0";//团队负责人
            
            $arr_20 = $this->query($sql_20);
            $ret_arr[$this->next_uid] = empty($arr_20[0]['m']['user_id']) ? 0 : $arr_20[0]['m']['user_id'];
            $ret_arr[$this->next_id] = 20;
        } elseif ($pos_id == 21) {
            $sql_21 = "select *from t_team t left join t_team_member m on m.team_id=t.id and m.id=t.sld where t.id='{$team_id}'  and del=0";//团队所领导
            $arr_21 = $this->query($sql_21);
            $ret_arr[$this->next_uid] = empty($arr_21[0]['m']['user_id']) ? 0 : $arr_21[0]['m']['user_id'];
            $ret_arr[$this->next_id] = 21;
        }
        return $ret_arr;
    }
  
    
    
    
    
}
