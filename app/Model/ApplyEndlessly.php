<?php

/* *
 *  项目 出入库
 */

App::uses('ApplyEndlessly', 'AppModel');

class ApplyEndlessly extends AppModel {

    public $name = 'ApplyEndlessly';
    public $useTable = 'apply_endlessly';
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
        return $this->save($data, true, $fieldList);
    }

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function del($sid) {
        $this->setDataSource('write');
        return $this->deleteAll(array('id' => $sid));
    }
    
    /**
     * 创建时获取审批信息
     * 创建信息 $data
     * 创建者信息 $user_info
     * 类型 $type 2是部门 3是团队，目前只有这两种
     */
    public function apply_create($type, $data, $user_info) {
                
        if ($type == 2) {
            //部门
            return $this->dep_create($type, $data, $user_info);
        } else {
            //团队
            return $this->team_create($type, $data, $user_info);
        }
    }
    //部门
    /**
     * 行政部门：申请人—所在单位负责人—分管领导—分管人事领导（乔永胜）—所长
          团队    ：申请人—所在团队负责人—分管领导（赵旗峰）—分管人事领导—所长
     */
    public $next_id = 'next_id';
    public $next_uid = 'next_uid';
    public $code = 'code';
    public $err_msg = 'msg';
    public $code_id = 'code_id';

    const SUOZHANG_ID = 6;//所长的职务id

    private function dep_create($type, $data, $user_info) {
       
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => ''
        );
        $dep_id = $user_info['department_id'];
        $team_id = $data['dep_pro'];
        $user_id = $user_info['id'];
        //取出 职务为5
        $arr_5 = $this->get_by_pos_dep(5, $dep_id, $team_id);
        if (empty($arr_5['next_uid'])) {
            $ret_arr[$this->err_msg] = '部门分管领导不存在';
            return $ret_arr;
        }
        if ($arr_5[$this->next_uid] == $user_id) {
            //说明是部门分管领导的单子
            $ret_arr[$this->code] = 10000;
            return $ret_arr;
        }
        //取出 职务15
        $arr_15 = $this->get_by_pos_dep(15, $dep_id, $team_id);
        if (empty($arr_15['next_uid'])) {
            $ret_arr[$this->err_msg] = '部门负责人不存在';
            return $ret_arr;
        }
        if ($arr_15[$this->next_uid] == $user_id) {
            //说明是部门负责人，取分管领导
            $ret_arr[$this->next_id] = 5;
            $ret_arr[$this->next_uid] = $arr_5[$this->next_uid];
            return $ret_arr;
        }
        //这里是职员
        $ret_arr[$this->next_id] = 15;
        $ret_arr[$this->next_uid] = $arr_15[$this->next_uid];
        return $ret_arr;
    }
    //团队
    private function team_create($type, $data, $user_info) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => ''
        );
        $dep_id = $user_info['department_id'];
        $team_id = $data['dep_pro'];
        $user_id = $user_info['id'];
        //取出 职务为21
        $arr_21 = $this->get_by_pos_dep(21, $dep_id, $team_id);
        if (empty($arr_21[$this->next_uid])) {
            $ret_arr[$this->err_msg] = '部门分管领导不存在';
            return $ret_arr;
        }
        if ($arr_21[$this->next_uid] == $user_id) {
            //说明是部门分管领导的单子
            $ret_arr[$this->code] = 10000;
            return $ret_arr;
        }
        //取出 职务20
        $arr_20 = $this->get_by_pos_dep(20, $dep_id, $team_id);
        if (empty($arr_20[$this->next_uid])) {
            $ret_arr[$this->err_msg] = '部门负责人不存在';
            return $ret_arr;
        }
        if ($arr_20[$this->next_uid] == $user_id) {
            //说明是部门负责人，取分管领导
            $ret_arr[$this->next_id] = 21;
            $ret_arr[$this->next_uid] = $arr_21[$this->next_uid];
            return $ret_arr;
        }
        //这里是职员
        $ret_arr[$this->next_id] = 20;
        $ret_arr[$this->next_uid] = $arr_20[$this->next_uid];
        return $ret_arr;
    }
       
    /**
     * 
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
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        if ($code == 10000) {
            $ret_arr[$this->err_msg] = '单子已经审批通过了';
            return $ret_arr;
        }
        if ($code%2 !=0) {
            $ret_arr[$this->err_msg] = '单子已经被拒绝';
            return $ret_arr;
        }
        if ($next_id != $user_info['id']) {
            $ret_arr[$this->err_msg] = '您无权审批此单子';
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
        
        if ($type == 2) {
            //部门
            return $this->dep_approve($main_arr, $user_info, $status);
        } else {
            //团队
            return $this->team_approve($main_arr, $user_info, $status);
        }
    }
    
    /**
     * 
     * @param type $type 2行政，3团队
     * @return array()
     */
    private function get_shengpin_arr ($type = 2) {
        return Configure::read('approval_process')['apply_endlessly'][$type];
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
        $sql_qingjia = "select *from t_apply_endlessly where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $qingjia_arr = $this->query($sql_qingjia);
        if (empty($qingjia_arr)) {
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        $user_id = $user_info['id'];
        $dep_id = $user_info['department_id'];
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $shengpin_arr = explode(',', $this->get_shengpin_arr($main_arr[0]['t_apply_main']['type']));
        
        foreach ($shengpin_arr as $k=>$v) {
            if ($v == $next_approver_id) {
                $arr_get = $this->get_by_pos_dep($v, $dep_id);
                if ($next_approver_id == 5 && $arr_get[$this->next_uid] == $user_id) {
                    $ret_arr[$this->code] = 10000;
                    $ret_arr[$this->code_id][] = $user_id;
                } else {
                    if ($next_approver_id == 15 && $arr_get[$this->next_uid] == $user_id) {
                        $arr_get = $this->get_by_pos_dep(5, $dep_id);
                        $next_approver_id = 5;
                        $ret_arr[$this->code] = 15 * 2;
                        $ret_arr[$this->code_id][] = $user_id;
                        $ret_arr[$this->next_uid] = empty($arr_get['next_uid']) ? 0 : $arr_get['next_uid'];
                        $ret_arr[$this->next_id] = 5;
                    }
                }
            }
        }
        
        return $ret_arr;
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
    private function team_approve($main_arr, $user_info, $status) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => '',
            $this->code_id=>array()
        );
        //获取请假天数
        $sql_qingjia = "select *from t_apply_endlessly where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $qingjia_arr = $this->query($sql_qingjia);
        if (empty($qingjia_arr)) {
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $shengpin_arr = explode(',', $this->get_shengpin_arr($main_arr[0]['t_apply_main']['type']));
        
        $dep_id = $qingjia_arr[0]['t_apply_endlessly']['department_id'];
        $team_id = $qingjia_arr[0]['t_apply_endlessly']['team_id'];
        $user_id = $user_info['id'];
        foreach ($shengpin_arr as $k=>$v) {
            if ($v == $next_approver_id) {
                $arr_get = $this->get_by_pos_dep($v, $dep_id, $team_id);
                if ($next_approver_id == 21 && $arr_get[$this->next_uid] == $user_id) {
                    $ret_arr[$this->code] = 10000;
                    $ret_arr[$this->code_id][] = $user_id;
                } else {
                    if ($next_approver_id == 20 && $arr_get[$this->next_uid] == $user_id) {
                        $arr_get = $this->get_by_pos_dep(21, $dep_id, $team_id);
                        $next_approver_id = 21;
                        $ret_arr[$this->code] = 20 * 2;
                        $ret_arr[$this->code_id][] = $user_id;
                        $ret_arr[$this->next_uid] = empty($arr_get[$this->next_uid]) ? 0 : $arr_get[$this->next_uid];
                        $ret_arr[$this->next_id] =21;
                    }
                }
            }
        }
        
        return $ret_arr;
    }
}
