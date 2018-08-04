<?php

/* *
 *  档案借阅
 */

App::uses('ApplyNews', 'AppModel');

class ApplyNews extends AppModel {

    public $name = 'ApplyNews';
    public $useTable = 'apply_news';
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
        //目前只有部门的，$type=2
        return $this->dep_create($type, $data, $user_info);
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
        $pos_id = $user_info['position_id'];
        $user_id = $user_info['id'];
        $dep_id = $user_info['department_id'];
        $team_id = 0;
        $shenpi_arr = explode(',', $this->get_shengpin_arr($type));
        if (empty($shenpi_arr)) {
            $ret_arr[$this->err_msg] = '定义审批流异常';
            return $ret_arr;
        }
        $shenpi_arr = array_reverse($shenpi_arr);
        $arr_tmp = array();//定义空数组存放所有职务信息
        $flag = false;//标志是否有相同的
        foreach ($shenpi_arr as $k=>$v) {
            $arr_get = $this->get_by_pos_dep($v, $dep_id, $team_id);
            $arr_tmp[$k] = $arr_get;//放进去
            if ($arr_get[$this->next_uid] == 0) {
                //存在错误信息
                $ret_arr[$this->err_msg] = $this->get_error_msg($v, $type);
                return $ret_arr;
            }
            if ($v == 36) {
                if ($pos_id == $v && $user_id == $arr_get[$this->next_uid]) {
                    $ret_arr[$this->code] = 10000;
                    return $ret_arr;
                } else {
                    continue;
                }
            } else {
                if ($user_id == $arr_get[$this->next_uid]) {
                    $flag = true;
                    break;
                }
            }
        }
        $index = !$flag ? $k : $k -1;
        $ret_arr[$this->next_id] = $arr_tmp[$index][$this->next_id];
        $ret_arr[$this->next_uid] = $arr_tmp[$index][$this->next_uid];
        return $ret_arr; //这里结束
      
    }
    //团队
    private function team_create($type, $data, $user_info) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => ''
        );
        $pos_id = $user_info['position_id'];
        $user_id = $user_info['id'];
        $dep_id = $user_info['department_id'];
        $team_id = $data['dep_pro'];//团队id
        $shenpi_arr = explode(',', $this->get_shengpin_arr($type));
        if (empty($shenpi_arr)) {
            $ret_arr[$this->err_msg] = '定义审批流异常';
            return $ret_arr;
        }
        $shenpi_arr = array_reverse($shenpi_arr);
        $arr_tmp = array();//定义空数组存放所有职务信息
        $flag = false;//标志是否有相同的
        foreach ($shenpi_arr as $k=>$v) {
            $arr_get = $this->get_by_pos_dep($v, $dep_id, $team_id);
            $arr_tmp[$k] = $arr_get;//放进去
            if ($arr_get[$this->next_uid] == 0) {
                //存在错误信息
                $ret_arr[$this->err_msg] = $this->get_error_msg($v, $type);
                return $ret_arr;
            }
            if ($v == 26) {
                //档案室经办人
                if ($pos_id == $v && $user_id == $arr_get[$this->next_uid]) {
                    $ret_arr[$this->code] = 10000;
                    return $ret_arr;
                } else {
                    continue;
                }
            } else {
                if ($user_id == $arr_get[$this->next_uid]) {
                    $flag = true;
                    break;
                }
            }
        }
        $index = !$flag ? $k : $k -1;
        $ret_arr[$this->next_id] = $arr_tmp[$index][$this->next_id];
        $ret_arr[$this->next_uid] = $arr_tmp[$index][$this->next_uid];
        
        return $ret_arr; //这里结束
    }
    
    //返回错误信息
    private function get_error_msg($pos_id = 0, $type = 2)  {
        $msg = '审批参数有误';
        $not_found = '不存在';
        /**
           2 => '15,28,6,26',   // 档案借阅 部门
        3 => '20,28,6,26',   // 档案借阅 团队
         */
        switch ($pos_id) {
            case 15:
                $msg = '部门负责人' . $not_found;
                break;
            case 20:
                $msg = '团队负责人' . $not_found;
                break;
            case 28:
                $msg = '所长办室负责人' . $not_found;
                break;
            case 6:
                $msg = '所长' . $not_found;
                break;
            case 26:
                $msg = '档案室经办人' . $not_found;
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
        $type = $main_arr[0]['t_apply_main']['type'];
        //dep
        return $this->dep_approve($main_arr, $user_info, $status);
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
        $listarr = Configure::read('approval_process');
        return $listarr['apply_news'][$type];
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
        $sql_qingjia = "select *from t_apply_news where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $qingjia_arr = $this->query($sql_qingjia);
        if (empty($qingjia_arr)) {
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        $user_id = $user_info['id'];
        $pos_id = $user_info['position_id'];
        $team_id = 0;
        $type = $main_arr[0]['t_apply_main']['type'];
        $dep_id = $main_arr[0]['t_apply_main']['department_id'];
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $shengpin_arr = explode(',', $this->get_shengpin_arr($main_arr[0]['t_apply_main']['type']));
        if (empty($shengpin_arr)) {
            $ret_arr[$this->err_msg] = '定义审批流异常';
            return $ret_arr;
        }
        foreach ($shengpin_arr as $k=>$v) {
            if ($v != $next_approver_id) {
                continue;
            }
            $arr_get = $this->get_by_pos_dep($v, $dep_id, $team_id);
            
            if ($arr_get[$this->next_uid] == 0) {
                //说明有问题
                $ret_arr[$this->err_msg] = $this->get_error_msg($v, $type);
                return $ret_arr;
            }
            if ($v == 36) {
                if ($arr_get[$this->next_uid] == $user_id) {
                    $ret_arr[$this->code] = 10000;
                    $ret_arr[$this->code_id][] = $user_id;
                    return $ret_arr;
                } else {
                    continue;
                }
                
            } else {
                
                if ($arr_get[$this->next_uid] == $user_id) {
                    $arr_get = $this->get_by_pos_dep($shengpin_arr[$k+1], $dep_id, $team_id);//下一职务
                    if ($arr_get[$this->next_uid] == 0) {
                        //说明有问题
                        $ret_arr[$this->err_msg] = $this->get_error_msg($shenpi_arr[$k+1], $type);
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
    //根据职务和部门取出用户信息get_by_pos_dep 行政
    private function get_by_pos_dep($pos_id, $dep_id, $team_id=0) {
       //15,5,35,28,36
        //流程：申请人（拟稿人）—科室负责人（签发人）—分管领导—党委办公室主任（审核人） 
        //      所长办公室主任—贺晋瑜（个人）   后面两个节点不需要审批意见
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0
        );
        if ($pos_id == 15) {
            $sql_15 = "select *from t_department where id='{$dep_id}' and del=0";
            $arr_15 = $this->query($sql_15);
            $ret_arr[$this->next_uid] = empty($arr_15[0]['t_department']['user_id']) ? 0 : $arr_15[0]['t_department']['user_id'];
            $ret_arr[$this->next_id] = $pos_id;
        }  elseif ($pos_id == 5) {
            $sql_5 = "select *from t_department where id='{$dep_id}' and del=0";
            $arr_5 = $this->query($sql_5);
            $ret_arr[$this->next_uid] = empty($arr_5[0]['t_department']['sld']) ? 0 : $arr_5[0]['t_department']['sld'];
            $ret_arr[$this->next_id] = $pos_id;
        } elseif ($pos_id == 35) {
            $sql_35 = "select *from t_department where id=2 and del=0";
            $arr_35 = $this->query($sql_35);
            $ret_arr[$this->next_uid] = empty($arr_35[0]['t_department']['user_id']) ? 0 : $arr_35[0]['t_department']['user_id'];
            $ret_arr[$this->next_id] = $pos_id;
        } elseif ($pos_id == 28) {
            $sql_28 = "select *from t_department where id=1 and del=0";
            $arr_28 = $this->query($sql_28);
            $ret_arr[$this->next_uid] = empty($arr_28[0]['t_department']['user_id']) ? 0 : $arr_28[0]['t_department']['user_id'];
            $ret_arr[$this->next_id] = $pos_id;
        } elseif ($pos_id == 36) {
            //贺晋瑜 id:252  因住院 暂时改为 孟庆仙  id:256
			// 最新已改回 贺晋瑜
            $sql_36 = "select *from t_user where id='252' and del=0";
            $arr_36 = $this->query($sql_36);
            $ret_arr[$this->next_uid] = empty($arr_36[0]['t_user']['id']) ? 0 : $arr_36[0]['t_user']['id'];
            $ret_arr[$this->next_id] = $pos_id;
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
        $sql_qingjia = "select *from t_apply_borrow where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $qingjia_arr = $this->query($sql_qingjia);
        if (empty($qingjia_arr)) {
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        $user_id = $user_info['id'];
        $pos_id = $user_info['position_id'];
        $team_id = $main_arr[0]['t_apply_main']['team_id'];
        $type = $main_arr[0]['t_apply_main']['type'];
        $dep_id = $main_arr[0]['t_apply_main']['department_id'];
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $shengpin_arr = explode(',', $this->get_shengpin_arr($main_arr[0]['t_apply_main']['type']));
        if (empty($shengpin_arr)) {
            $ret_arr[$this->err_msg] = '定义审批流异常';
            return $ret_arr;
        }
        foreach ($shengpin_arr as $k=>$v) {
            if ($v != $next_approver_id) {
                continue;
            }
            $arr_get = $this->get_by_pos_dep($v, $dep_id, $team_id);
            
            if ($arr_get[$this->next_uid] == 0) {
                //说明有问题
                $ret_arr[$this->err_msg] = $this->get_error_msg($v, $type);
                return $ret_arr;
            }
            if ($v == 26) {
                if ($arr_get[$this->next_uid] == $user_id) {
                    $ret_arr[$this->code] = 10000;
                    $ret_arr[$this->code_id][] = $user_id;
                    return $ret_arr;
                } else {
                    continue;
                }
                
            } else {
                
                if ($arr_get[$this->next_uid] == $user_id) {
                    $arr_get = $this->get_by_pos_dep($shengpin_arr[$k+1], $dep_id, $team_id);//下一职务
                    if ($arr_get[$this->next_uid] == 0) {
                        //说明有问题
                        $ret_arr[$this->err_msg] = $this->get_error_msg($shenpi_arr[$k+1], $type);
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
