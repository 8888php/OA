<?php

/* *
 *  项目 出入库
 */

App::uses('ApplyCaigou', 'AppModel');

class ApplyCaigou extends AppModel {

    public $name = 'ApplyCaigou';
    public $useTable = 'apply_caigou';
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
     * 类型 $type 2是部门 1是科研科目，目前只有这两种
     */
    public function apply_create($type, $data, $user_info) {
        return $this->team_create($type, $data, $user_info);
    }
    //部门
    /**
     *  项目：申请人-项目负责人-团队负责人—部门分管领导（科研分管领导赵旗峰）-财务科主任-采购员（王海松）-采购中心主任（杨兆亮）-财务及采购分管领导（吕英忠）-所长
        部门：申请人-行政部门负责人-行政部门分管领导-财务科主任-采购员（王海松）-采购中心主任（杨兆亮）-财务及采购分管领导（吕英忠）-所长
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
        $dep_id = $user_info['department_id'];
        // 项目 或 部门 id  $data['team'] == 0 时为 部门类型申请
        $dep_pro_id = $data['team']; // 
        $user_id = $user_info['id'];        
        $pos_id = $user_info['position_id'];
        $shenpi_arr = $this->get_shengpin_arr();
        $shenpi_arr = array_reverse(explode(',', $shenpi_arr[$type]));//反转数组
//print_r($shenpi_arr);
        if (empty($shenpi_arr)) {
            $ret_arr[$this->err_msg] = '定义审批流异常';
            return $ret_arr;
        }
        $arr_tmp = array();//放所有的审批角色信息
        $flag = false;//标志是否有相同的
        foreach ($shenpi_arr as $k=>$v) {
            $arr_get = $this->get_by_pos_dep($v, $dep_id, $dep_pro_id);
//            var_dump($v, $dep_id, $dep_pro_id,$arr_get);
            $arr_tmp[$k] = $arr_get;//把所有的都记录下来
            if ($arr_get[$this->next_uid] == 0) {
                //说明有问题
                $ret_arr[$this->err_msg] = $this->get_error_msg($v);
                return $ret_arr;
            }
            if ($pos_id == 6) {
                //所长
                $ret_arr[$this->code] = 10000;
                return $ret_arr;
            } else {
                if ($arr_get[$this->next_uid] == $user_id) {
                    $flag = true;
                    break;
                }
            }
        }
        $index = !$flag ? $k : $k -1;
        $ret_arr[$this->next_id] = $arr_tmp[$index][$this->next_id];
        $ret_arr[$this->next_uid] = $arr_tmp[$index][$this->next_uid];
//        print_r($ret_arr);die;
        return $ret_arr; //这里结束
        //判断是不是所长  6
        $arr_6 = $this->get_by_pos_dep(6, $dep_id, $dep_pro_id);
        
        //财务副所长  13
        $arr_13 = $this->get_by_pos_dep(13, $dep_id, $dep_pro_id);
        
        //采购中心这个部门的负责人  24
        $arr_24 = $this->get_by_pos_dep(24, $dep_id, $dep_pro_id);
        
        //采购内容核对员 23
        $arr_23 = $this->get_by_pos_dep(23, $dep_id, $dep_pro_id);
        
        //财务科长 14
        $arr_14 = $this->get_by_pos_dep(14, $dep_id, $dep_pro_id);
        
        //部门分管领导 5
        $arr_5 = $this->get_by_pos_dep(5, $dep_id, $dep_pro_id);
    
        //团队负责人 20
        $arr_20 = $this->get_by_pos_dep(20, $dep_id, $dep_pro_id);

        //项目负责人 11
        $arr_11 = $this->get_by_pos_dep(11, $dep_id, $dep_pro_id);

        //或  部门负责人 15
        $arr_15 = $this->get_by_pos_dep(15, $dep_id, $dep_pro_id);

    }
    //返回错误信息
    private function get_error_msg($pos_id = 0)  {
        $msg = '审批参数有误';
        //20,5,14,23,24,13,6
        switch ($pos_id) {
            case 6:
                $msg = '所长不存在';
                break;
            case 13:
                $msg = '财务副所长不存在';
                break;
            case 24:
                $msg = '采购中心负责人不存在';
                break;
            case 23:
                $msg = '采购内容核对员不存在';
                break;
            case 14:
                $msg = '财务科长不存在';
                break;
            case 5:
                $msg = '部门分管领导不存在';
                break;
            case 20:
                $msg = '团队负责人不存在';
                break;
            case 11:
                $msg = '项目负责人不存在';
                break;
            case 15:
                $msg = '部门负责人不存在';
                break;
            default :
                break;
        }
        return $msg;
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
        $listarr = Configure::read('approval_process');
        return  $listarr['apply_caigou'];
    }
            
    
    //根据职务和部门取出用户信息 行政
    private function get_by_pos_dep($pos_id, $dep_id, $dep_pro_id=0) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0
        );
        
        switch($pos_id) {
        //判断是不是所长  6
            case 6 :  
            $sql_6 = "select *from t_user where position_id='{$pos_id}' and del=0";
            $arr_6 = $this->query($sql_6);
            $ret_arr[$this->next_id] = 6;
            $ret_arr[$this->next_uid] = empty($arr_6[0]['t_user']['id']) ? 0 : $arr_6[0]['t_user']['id'];
            break;
        //财务副所长  13
            case 13 :
            $sql_13 = "select *from t_user where position_id='{$pos_id}' and del=0";
            $arr_13 = $this->query($sql_13);
            $ret_arr[$this->next_id] = 13;
            $ret_arr[$this->next_uid] = empty($arr_13[0]['t_user']['id']) ? 0 : $arr_13[0]['t_user']['id'];
            break;
        //采购中心这个部门的负责人  24
            case 24 : 
            $sql_24 = "select *from t_department where id=12 and del=0";
            $arr_24 = $this->query($sql_24);
            $ret_arr[$this->next_id] = 24;
            $ret_arr[$this->next_uid] = empty($arr_24[0]['t_department']['user_id']) ? 0 : $arr_24[0]['t_department']['user_id'];
            break;
        //采购内容核对员 23
            case 23 :
            $sql_23 = "select *from t_user where position_id='{$pos_id}' and del=0";
            $arr_23 = $this->query($sql_23);
            $ret_arr[$this->next_id] = 23;
            $ret_arr[$this->next_uid] = empty($arr_23[0]['t_user']['id']) ? 0 : $arr_23[0]['t_user']['id'];
            break;
        //财务科长 14
            case 14 :
            $sql_14 = "select *from t_user where position_id='{$pos_id}' and del=0";
            $arr_14 = $this->query($sql_14);
            $ret_arr[$this->next_id] = 14;
            $ret_arr[$this->next_uid] = empty($arr_14[0]['t_user']['id']) ? 0 : $arr_14[0]['t_user']['id'];
            break;
        //部门分管领导 5
            case 5 :
            //当 dep_pro_id 不为0时，为项目申请，分管领导取科研部门所领导
            $dep_id != 0 && $dep_id = 3;
            $sql_5 = "select *from t_department where id='{$dep_id}' and del=0";
            $arr_5 = $this->query($sql_5);
            $ret_arr[$this->next_uid] = empty($arr_5[0]['t_department']['sld']) ? 0 : $arr_5[0]['t_department']['sld'];
            $ret_arr[$this->next_id] = 5;
            break;
        //团队负责人 20   可看作项目所属 项目组
            case 20 :
            $sql_20 = "select p.project_team_id,p.user_id,m.user_id from t_research_project p left join t_team t on p.project_team_id = t.id left join t_team_member m on m.team_id=t.id and t.fzr=m.id where p.id='{$dep_pro_id}' ";
            $arr_20 = $this->query($sql_20);
            // 若是单个项目 返回项目负责人id
            if( $arr_20[0]['p']['project_team_id'] == 0 ){  
                $ret_arr[$this->next_uid] = $arr_20[0]['p']['user_id'] ;
            }else{
                $ret_arr[$this->next_uid] = empty($arr_20[0]['m']['user_id']) ? 0 : $arr_20[0]['m']['user_id'];
            }
            $ret_arr[$this->next_id] = 20;
            break;
        //项目负责人 11
            case 11 :
            $sql_11 = "select id,user_id from t_research_project where id='{$dep_pro_id}' and del=0";
            $arr_11 = $this->query($sql_11);
            $ret_arr[$this->next_uid] = empty($arr_11[0]['t_research_project']['user_id']) ? 0 : $arr_11[0]['t_research_project']['user_id'];
            $ret_arr[$this->next_id] = 11;
            break;
        //部门负责人 15
            case 15 :
            $sql_15 = "select *from t_department where id='{$dep_id}' and del=0";
            $arr_15 = $this->query($sql_15);
            $ret_arr[$this->next_uid] = empty($arr_15[0]['t_department']['user_id']) ? 0 : $arr_15[0]['t_department']['user_id'];
            $ret_arr[$this->next_id] = 15;
            break;
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
        $sql_baogong = "select *from t_apply_caigou where id='{$attr_id}'";
        $baogong_arr = $this->query($sql_baogong);
        if (empty($baogong_arr)) {
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        $user_id = $user_info['id'];
        $pos_id = $user_info['position_id'];
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];

        $type = $main_arr[0]['t_apply_main']['type'] ; // 申请单类型：1项目，2部门
        $shenpi_arr = $this->get_shengpin_arr();
        $shenpi_arr = explode(',', $shenpi_arr[$type]);
        
        if (empty($shenpi_arr)) {
            $ret_arr[$this->err_msg] = '定义审批流异常';
            return $ret_arr;
        }
        $dep_id = $main_arr[0]['t_apply_main']['department_id'];
        if($type == 1){
            $dep_pro_id = $main_arr[0]['t_apply_main']['project_id'];
        }else{
            $dep_pro_id = $dep_id;
        }
       
        foreach ($shenpi_arr as $k=>$v) {
            if ($v != $next_approver_id) {
                continue;
            }
            $arr_get = $this->get_by_pos_dep($v, $dep_id, $dep_pro_id);
            
            if ($arr_get[$this->next_uid] == 0) {
                //说明有问题
                $ret_arr[$this->err_msg] = $this->get_error_msg($v);
                return $ret_arr;
            }
            if ($v == 6 && $pos_id == $v) {
                //所长
                $ret_arr[$this->code] = 10000;
                $ret_arr[$this->code_id][] = $user_id;
                return $ret_arr;
            } else {
                
                if ($arr_get[$this->next_uid] == $user_id) {
                    $arr_get = $this->get_by_pos_dep($shenpi_arr[$k+1], $dep_id, $dep_pro_id);//下一职务
                    if ($arr_get[$this->next_uid] == 0) {
                        //说明有问题
                        $ret_arr[$this->err_msg] = $this->get_error_msg($shenpi_arr[$k+1]);
                        return $ret_arr;
                    }
                    $next_approver_id = $arr_get[$this->next_id];
                    $ret_arr[$this->next_id] = $arr_get[$this->next_id];
                    $ret_arr[$this->next_uid] = $arr_get[$this->next_uid];
                    $ret_arr[$this->code] = $v * 2;
                    $ret_arr[$this->code_id][] = $user_id;
                }
            }
        } 
       if ($ret_arr[$this->code] == 0 && $ret_arr[$this->err_msg] == '') {
           $ret_arr[$this->err_msg] = '审批失败';
       }
       return $ret_arr;
    }

}
