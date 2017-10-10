<?php

/* *
 *  项目 出入库
 */

App::uses('ApplyBaogong', 'AppModel');

class ApplyBaogong extends AppModel {

    public $name = 'ApplyBaogong';
    public $useTable = 'apply_baogong';
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
        return $this->team_create($type, $data, $user_info);
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

  
    //团队
    private function team_create($type, $data, $user_info) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => ''
        );
        //判断是不是科研主任
        $sql_4 = "select *from t_department where id=3 and del=0";
        $arr_4 = $this->query($sql_4);
        if (empty($arr_4)) {
            $ret_arr[$this->err_msg] = '科研主任不存在';
            return $ret_arr;
        }
        if ($arr_4[0]['t_department']['user_id'] == $user_info['id']) {
            $ret_arr[$this->code] = 10000;
            return $ret_arr;
        }
        $team_id = $data['dep_pro'];
        $sql_20 = "select *from t_team  t left join t_team_member m on  m.team_id=t.id and t.fzr=m.id where t.id='{$team_id}'";
        $arr_20 = $this->query($sql_20);
        if (empty($arr_20)) {
            $ret_arr[$this->err_msg] = '团队负责人不存在';
            return $ret_arr;
        }
        if ($arr_20[0]['m']['user_id'] == $user_info['id']) {
            $ret_arr[$this->next_id] = 4;
            $ret_arr[$this->next_uid] = $arr_4[0]['t_department']['user_id'];
            $ret_arr[$this->code] = 0;
            return $ret_arr;
        }
        
        $ret_arr[$this->next_id] = 20;
        $ret_arr[$this->next_uid] = $arr_20[0]['m']['user_id'];
        $ret_arr[$this->code] = 0;
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
        //团队
        return $this->team_approve($main_arr, $user_info, $status);
       
    }
    
    /**
    获取审批流
     */
    private function get_shengpin_arr () {
        return Configure::read('approval_process')['apply_baogong'];
    }
            
    
    //根据职务和部门取出用户信息 行政
    private function get_by_pos_dep($pos_id, $dep_id, $team_id=0) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0
        );
        if ($pos_id == 20) {
            //团队负责人
            $sql_20 = "select *from t_team t left join t_team_member m on m.team_id=t.id and t.fzr=m.id where t.id='{$team_id}' ";
            $arr_20 = $this->query($sql_20);
            $ret_arr[$this->next_uid] = empty($arr_20[0]['m']['user_id']) ? 0 : $arr_20[0]['m']['user_id'];
            $ret_arr[$this->next_id] = 20;
        } elseif ($pos_id == 4) {
            //科研主任
            $sql_4 = "select *from t_department where id=3 and del=0";
            $arr_4 = $this->query($sql_4);
            $ret_arr[$this->next_uid] = empty($arr_4[0]['t_department']['user_id']) ? 0 : $arr_4[0]['t_department']['user_id'];
            $ret_arr[$this->next_id] = 4;
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
        $attr_id = $main_arr[0]['t_apply_main']['attr_id'];
        //取出单子信息
        $sql_baogong = "select *from t_apply_baogong where id='{$attr_id}'";
        $baogong_arr = $this->query($sql_baogong);
        if (empty($baogong_arr)) {
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $shengpin_arr = explode(',', $this->get_shengpin_arr());
        $dep_id = $baogong_arr[0]['t_apply_baogong']['department_id'];
        $team_id = $baogong_arr[0]['t_apply_baogong']['team_id'];
       {
            //走完整的审批
            foreach ($shengpin_arr as $k=>$v) {
                //根据$v取出他下一个审批的id
                if ($v == $next_approver_id) {
                    $arr_get = $this->get_by_pos_dep($v, $dep_id, $team_id);
                    if ($next_approver_id == 4 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                        $ret_arr[$this->code] = 10000;
                        $ret_arr[$this->code_id][] = $user_info['id'];
                    } else {
                        //20,4
                        if ($next_approver_id == 20 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            $arr_20 = $this->get_by_pos_dep(4, $dep_id, $team_id);
                            $next_approver_id = 4;
                            $ret_arr[$this->code] = 20 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_20[$this->next_uid]) ? 0 : $arr_20[$this->next_uid];
                            $ret_arr[$this->next_id] = 4;
                        }
                    }
                    
                }
            }
            return $ret_arr;
        }
    }
}
