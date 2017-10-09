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
        $sql_1 = "select *from t_department where id='{$user_info['department_id']}' and del=0";
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
            $ret_arr[$this->next_id] = 5;
            $ret_arr[$this->next_uid] = $fenguan_arr[0]['u']['id'];
            $ret_arr[$this->code_id][] = $user_info['id'];
            $ret_arr[$this->code] = $is_apply ? 11*2 : 0;
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
        if ($type == 2) {
            //部门
            //return $this->dep_approve($main_arr, $user_info, $status);
           return $this->dep_create($type, $data, $user_info, true) ;
        } else {
            //科研
            //return $this->pro_approve($main_arr, $user_info, $status);
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
            
    
    private function dep_approve($main_arr, $user_info, $status) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => '',
            $this->code_id=>array()
        );
        //获取请假天数
        $sql_chuchai = "select * from t_apply_chuchai where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $chuchai_arr = $this->query($sql_chuchai);
        if (empty($chuchai_arr)) {
            $ret_arr[$this->err_msg] = '申请单信息不存在';
            return $ret_arr;
        }
        $sum_day = $chuchai_arr[0]['t_apply_chuchai']['days'];
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $shengpin_arr = explode(',', $this->get_shengpin_arr($main_arr[0]['t_apply_main']['type']));
        
        if ($sum_day < 3) {
            //只要下一个审批就可以
            foreach ($shengpin_arr as $k=>$v) {
                if ($v == $next_approver_id) {
                    if ($next_approver_id == 6) {
                        $ret_arr[$this->code] = 10000;
                        $ret_arr[$this->code_id][] = $user_info['id'];
                    } else {
                        //15,5,22,6
                        if ($next_approver_id == 15) {
                            //取出5所对应该的信息
                            $sql_5 = "select *from t_department where id='{$chuchai_arr[0]['t_apply_chuchai']['department_id']}' and del=0";
                            $arr_5 = $this->query($sql_5);
                            $ret_arr[$this->code] = 10000;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_5[0]['t_department']['sld']) ? 0 : $arr_5[0]['t_department']['sld'];
                            $ret_arr[$this->next_id] = 5;
                            
                        } else if ($next_approver_id == 5) {
                            //取出 22
                            $sql_22 = "select *from t_department where id=4 and del=0";
                            $arr_22 = $this->query($sql_22);
                            $ret_arr[$this->code] = 10000;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_22[0]['t_department']['sld']) ? 0 : $arr_22[0]['t_department']['sld'];
                            $ret_arr[$this->next_id] = 22;
                            
                        } else if ($next_approver_id == 22) {
                            //取出 6
                            $sql_6 = "select *from t_user where position_id=6 and del=0";
                            $arr_6 = $this->query($sql_6);
                            $ret_arr[$this->code] = 10000;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_6[0]['t_department']['sld']) ? 0 : $arr_6[0]['t_department']['sld'];
                            $ret_arr[$this->next_id] = 6;
                            
                        }
                        
                    }
                   
                }
                return $ret_arr; 
            }
        } else {
            //走完整的审批
            foreach ($shengpin_arr as $k=>$v) {
                //根据$v取出他下一个审批的id
                if ($v == $next_approver_id) {
                    $arr_get = $this->get_by_pos_dep($v, $chuchai_arr[0]['t_apply_chuchai']['department_id']);
                    if ($next_approver_id == 6 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                        $ret_arr[$this->code] = 10000;
                        $ret_arr[$this->code_id][] = $user_info['id'];
                    } else {
                        //15,5,22,6
                        if ($next_approver_id == 15 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出5所对应该的信息
//                            $sql_5 = "select *from t_department where id='{$chuchai_arr[0]['t_apply_chuchai']['department_id']}' and del=0";
                            $arr_5 = $this->get_by_pos_dep(5, $chuchai_arr[0]['t_apply_chuchai']['department_id']);
                            
                            $next_approver_id = 5;
                            $ret_arr[$this->code] = 15 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_5['next_uid']) ? 0 : $arr_5['next_uid'];
                            $ret_arr[$this->next_id] = 5;
                            
                        } else if ($next_approver_id == 5 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出 22
//                            $sql_22 = "select *from t_department where id=4 and del=0";
                            $arr_22 = $this->get_by_pos_dep(22, $chuchai_arr[0]['t_apply_chuchai']['department_id']);
                            $next_approver_id = 22;
                            $ret_arr[$this->code] = 5 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_22['next_uid']) ? 0 : $arr_22['next_uid'];
                            $ret_arr[$this->next_id] = 22;
                            
                        } else if ($next_approver_id == 22 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出 6
//                            $sql_6 = "select *from t_user where position_id=6 and del=0";
                            $arr_6 = $this->get_by_pos_dep(6, $chuchai_arr[0]['t_apply_chuchai']['department_id']);
                            $next_approver_id = 6;
                            $ret_arr[$this->code] = 22 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_6['next_uid']) ? 0 : $arr_6['next_uid'];
                            $ret_arr[$this->next_id] = 6;
                            
                        }
                    }
                    
                }
            }
            return $ret_arr;
        }
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
    // 科研
    private function pro_approve($main_arr, $user_info, $status) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => '',
            $this->code_id=>array()
        );
        //获取请假天数
        $sql_chuchai = "select *from t_apply_chuchai where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $chuchai_arr = $this->query($sql_chuchai);
        if (empty($chuchai_arr)) {
            $ret_arr[$this->err_msg] = '申请单信息不存在';
            return $ret_arr;
        }
        $sum_day = $chuchai_arr[0]['t_apply_chuchai']['total_days'];
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $shengpin_arr = explode(',', $this->get_shengpin_arr($main_arr[0]['t_apply_main']['type']));
        $dep_id = $chuchai_arr[0]['t_apply_chuchai']['department_id'];
        $team_id = $chuchai_arr[0]['t_apply_chuchai']['team_id'];
        if ($sum_day < 3) {
            //只要下一个审批就可以
            foreach ($shengpin_arr as $k=>$v) {
                if ($v == $next_approver_id) {
                    if ($next_approver_id == 6) {
                        $ret_arr[$this->code] = 10000;
                        $ret_arr[$this->code_id][] = $user_info['id'];
                    } else {
                        //20,21,22,6
                        
                        if ($next_approver_id == 20) {
                            //取出21所对应该的信息
                            $arr_21 = $this->get_by_pos_dep(21, $dep_id, $team_id);
                            $ret_arr[$this->code] = 10000;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_21[$this->next_uid]) ? 0 : $arr_21[$this->next_uid];
                            $ret_arr[$this->next_id] = 21;
                            
                        } else if ($next_approver_id == 21) {
                            //取出 22
                            $sql_22 = "select *from t_department where id=4 and del=0";
                            $arr_22 = $this->query($sql_22);
                            $ret_arr[$this->code] = 10000;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_22[0]['t_department']['sld']) ? 0 : $arr_22[0]['t_department']['sld'];
                            $ret_arr[$this->next_id] = 22;
                            
                        } else if ($next_approver_id == 22) {
                            //取出 6
                            $sql_6 = "select *from t_user where position_id=6 and del=0";
                            $arr_6 = $this->query($sql_6);
                            $ret_arr[$this->code] = 10000;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_6[0]['t_department']['sld']) ? 0 : $arr_6[0]['t_department']['sld'];
                            $ret_arr[$this->next_id] = 6;
                            
                        }
                        
                    }
                   
                }
                return $ret_arr; 
            }
        } else {
            //走完整的审批
            foreach ($shengpin_arr as $k=>$v) {
                //根据$v取出他下一个审批的id
                if ($v == $next_approver_id) {
                    $arr_get = $this->get_by_pos_dep($v, $dep_id, $team_id);
                    if ($next_approver_id == 6 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                        $ret_arr[$this->code] = 10000;
                        $ret_arr[$this->code_id][] = $user_info['id'];
                    } else {
                        //20,21,22,6
                        if ($next_approver_id == 20 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            $arr_20 = $this->get_by_pos_dep(21, $dep_id, $team_id);
                            $next_approver_id = 22;
                            $ret_arr[$this->code] = 20 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_20[$this->next_uid]) ? 0 : $arr_20[$this->next_uid];
                            $ret_arr[$this->next_id] = 21;
                        } else if ($next_approver_id == 21 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出 22
//                            $sql_22 = "select *from t_department where id=4 and del=0";
                            $arr_22 = $this->get_by_pos_dep(22, $chuchai_arr[0]['t_apply_chuchai']['department_id']);
                            $next_approver_id = 22;
                            $ret_arr[$this->code] = 21 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_22['next_uid']) ? 0 : $arr_22['next_uid'];
                            $ret_arr[$this->next_id] = 22;
                            
                        } else if ($next_approver_id == 22 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出 6
//                            $sql_6 = "select *from t_user where position_id=6 and del=0";
                            $arr_6 = $this->get_by_pos_dep(6, $chuchai_arr[0]['t_apply_chuchai']['department_id']);
                            $next_approver_id = 6;
                            $ret_arr[$this->code] = 22 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_6['next_uid']) ? 0 : $arr_6['next_uid'];
                            $ret_arr[$this->next_id] = 6;
                            
                        }
                    }
                    
                }
            }
            return $ret_arr;
        }
    } 
    
    
    
    
}
