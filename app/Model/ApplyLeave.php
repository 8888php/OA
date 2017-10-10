<?php

/* *
 *  项目 出入库
 */

App::uses('ApplyLeave', 'AppModel');

class ApplyLeave extends AppModel {

    public $name = 'ApplyLeave';
    public $useTable = 'apply_leave';
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
        $sum_days = $data['sum_days'];//天数
        if ($user_info['position_id'] == self::SUOZHANG_ID) {
            //说明他是所长
            $ret_arr[$this->code] = 10000;
            return $ret_arr;
        }
        $sql = "select *from t_department where id=4 and sld='{$user_info['id']}' and del=0";
        if ($this->query($sql)) {
            //说明他是人事领导
            //取出所长信息
            $sql_suozhang = "select *from t_user where position_id='".self::SUOZHANG_ID."' and del=0";
            $suo_zhang = $this->query($sql_suozhang);
            if (empty($suo_zhang)) {
                $ret_arr[$this->err_msg] = '所长不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = self::SUOZHANG_ID;
            $ret_arr[$this->next_uid] = $suo_zhang[0]['t_user']['id'];
            return $ret_arr;
        }
        $sql_1 = "select *from t_department where id='{$user_info['department_id']}' and del=0";
        $dem_arr = $this->query($sql_1);
        if (empty($dem_arr)) {
            $ret_arr[$this->err_msg] = '部门不存在';
            return $ret_arr;
        }
        if ($dem_arr[0]['t_department']['sld'] == $user_info['id']) {
            //说明他是分管领导
            //取人事领导信息
            $sql_renshi = 'SELECT *FROM t_user u LEFT JOIN t_department d ON d.sld=u.id WHERE d.id=4 ';
           // $sql_renshi = "select *from t_user u left join t_department d on d.sld=u.id where u.department_id=4";
            $renshi_arr = $this->query($sql_renshi);
            if (empty($renshi_arr)) {
                $ret_arr[$this->err_msg] = $sql_renshi.'分管人事领导不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = 22;
            $ret_arr[$this->next_uid] = $renshi_arr[0]['u']['id'];
            return $ret_arr;
        }
        if ($dem_arr[0]['t_department']['user_id'] == $user_info['id']) {
            //说明他是部门负责人
            //分管所领导信息
            //$sql_fenguan = "select *from t_user u left join t_department d on u.department_id=d.id where u.department_id='{$user_info['department_id']}' and d.sld=u.id"; 
            $sql_fenguan = "SELECT *FROM t_user u LEFT JOIN t_department d ON d.sld=u.id WHERE d.id={$user_info['department_id']} ";
            $fenguan_arr = $this->query($sql_fenguan);
            if (empty($fenguan_arr)) {
                $ret_arr[$this->err_msg] = '分管所领导不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = 5;
            $ret_arr[$this->next_uid] = $fenguan_arr[0]['u']['id'];
            return $ret_arr;
        }
        //这里是职员
       // $sql_fenze = "select *from t_user u left join t_department d on u.department_id=d.id where u.department_id='{$user_info['department_id']}' and d.user_id=u.id";
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
    //团队
    private function team_create($type, $data, $user_info) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => ''
        );
        $sum_days = $data['sum_days'];//天数
        if ($user_info['position_id'] == self::SUOZHANG_ID) {
            //说明他是所长
            $ret_arr[$this->code] = 10000;
            return $ret_arr;
        }
        $sql = "select *from t_department where id=4 and sld='{$user_info['id']}' and del=0";
        if ($this->query($sql)) {
            //说明他是人事领导
            //取出所长信息
            $sql_suozhang = "select *from t_user where position_id='".self::SUOZHANG_ID."' and del=0";
            $suo_zhang = $this->query($sql_suozhang);
            if (empty($suo_zhang)) {
                $ret_arr[$this->err_msg] = '所长不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = self::SUOZHANG_ID;
            $ret_arr[$this->next_uid] = $suo_zhang[0]['t_user']['id'];
            return $ret_arr;
        }
        //取出团队成员
        $sql_team_member = "select *from t_team_member where user_id='{$user_info['id']}'";
        $tmam_member_arr = $this->query($sql_team_member);
        if (empty($tmam_member_arr)) {
            $ret_arr[$this->err_msg] = '所对应的团队成员不存在';
            return $ret_arr;
        }
        $sql_1 = "select *from t_team where id='{$data['dep_pro']}' and del=0";
        $dem_arr = $this->query($sql_1);
        if (empty($dem_arr)) {
            $ret_arr[$this->err_msg] = '团队不存在';
            return $ret_arr;
        }
        $sld_member = $this->query("select *from t_team_member where id='{$dem_arr[0]['t_team']['sld']}'");
        if (empty($sld_member[0]['t_team_member']['user_id'])) {
            $ret_arr[$this->err_msg] = '分管领导不存在';
            return $ret_arr;
        }
        if ($sld_member[0]['t_team_member']['user_id'] == $user_info['id']) {
            //说明他是分管领导
            //取人事领导信息
            $sql_renshi = "select *from t_user u left join t_department d on d.sld=u.id where d.id=4";
            $renshi_arr = $this->query($sql_renshi);
            if (empty($renshi_arr)) {
                $ret_arr[$this->err_msg] = '分管人事领导不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = 22;
            $ret_arr[$this->next_uid] = $renshi_arr[0]['u']['id'];
            return $ret_arr;
        }
        //团队负责人
        $fzr_member[0]['t_team_member']['user_id'] = $this->query("select *from t_team_member where id='{$dem_arr[0]['t_team']['fzr']}'");
        if (empty($fzr_member[0]['t_team_member']['user_id'])) {
            $ret_arr[$this->err_msg] = '团队负责人不存在';
            return $ret_arr;
        }
        if ($fzr_member == $user_info['id']) {
            //说明他是部门负责人
            //分管所领导信息
            $sql_fenguan = "select *from t_team_member m left join t_team t on m.team_id=t.id where t.id='{$data['dep_pro']}' and t.sld=m.id"; 
            $fenguan_arr = $this->query($sql_fenguan);
            if (empty($fenguan_arr)) {
                $ret_arr[$this->err_msg] = '团队分管所领导不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = 21;
            $ret_arr[$this->next_uid] = $fenguan_arr[0]['m']['user_id'];
            return $ret_arr;
        }
        //这里是职员
        $sql_fenze = "select *from t_team_member m left join t_team t on m.team_id=t.id where t.id='{$data['dep_pro']}' and t.fzr=m.id";
        
        $fuze_arr = $this->query($sql_fenze);
        if (empty($fuze_arr)) {
            $ret_arr[$this->err_msg] = '团队部门负责人不存在';
            return $ret_arr;
        }
        $ret_arr[$this->next_id] = 20;
        $ret_arr[$this->next_uid] = $fuze_arr[0]['m']['user_id'];
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
        return Configure::read('approval_process')['apply_leave'][$type];
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
        $sql_qingjia = "select *from t_apply_leave where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $qingjia_arr = $this->query($sql_qingjia);
        if (empty($qingjia_arr)) {
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        $sum_day = $qingjia_arr[0]['t_apply_leave']['total_days'];
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
                            $sql_5 = "select *from t_department where id='{$qingjia_arr[0]['t_apply_leave']['department_id']}' and del=0";
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
                    $arr_get = $this->get_by_pos_dep($v, $qingjia_arr[0]['t_apply_leave']['department_id']);
                    if ($next_approver_id == 6 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                        $ret_arr[$this->code] = 10000;
                        $ret_arr[$this->code_id][] = $user_info['id'];
                    } else {
                        //15,5,22,6
                        if ($next_approver_id == 15 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出5所对应该的信息
//                            $sql_5 = "select *from t_department where id='{$qingjia_arr[0]['t_apply_leave']['department_id']}' and del=0";
                            $arr_5 = $this->get_by_pos_dep(5, $qingjia_arr[0]['t_apply_leave']['department_id']);
                            
                            $next_approver_id = 5;
                            $ret_arr[$this->code] = 15 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_5['next_uid']) ? 0 : $arr_5['next_uid'];
                            $ret_arr[$this->next_id] = 5;
                            
                        } else if ($next_approver_id == 5 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出 22
//                            $sql_22 = "select *from t_department where id=4 and del=0";
                            $arr_22 = $this->get_by_pos_dep(22, $qingjia_arr[0]['t_apply_leave']['department_id']);
                            $next_approver_id = 22;
                            $ret_arr[$this->code] = 5 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_22['next_uid']) ? 0 : $arr_22['next_uid'];
                            $ret_arr[$this->next_id] = 22;
                            
                        } else if ($next_approver_id == 22 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出 6
//                            $sql_6 = "select *from t_user where position_id=6 and del=0";
                            $arr_6 = $this->get_by_pos_dep(6, $qingjia_arr[0]['t_apply_leave']['department_id']);
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
    private function team_approve($main_arr, $user_info, $status) {
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg => '',
            $this->code_id=>array()
        );
        //获取请假天数
        $sql_qingjia = "select *from t_apply_leave where id='{$main_arr[0]['t_apply_main']['attr_id']}'";
        $qingjia_arr = $this->query($sql_qingjia);
        if (empty($qingjia_arr)) {
            $ret_arr[$this->err_msg] = '单子信息不存在';
            return $ret_arr;
        }
        $sum_day = $qingjia_arr[0]['t_apply_leave']['total_days'];
        $code = $main_arr[0]['t_apply_main']['code'];
        $next_id = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $next_approver_id = $main_arr[0]['t_apply_main']['next_approver_id'];
        $next_apprly_uid = $main_arr[0]['t_apply_main']['next_apprly_uid'];
        $shengpin_arr = explode(',', $this->get_shengpin_arr($main_arr[0]['t_apply_main']['type']));
        $dep_id = $qingjia_arr[0]['t_apply_leave']['department_id'];
        $team_id = $qingjia_arr[0]['t_apply_leave']['team_id'];
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
                            $next_approver_id = 21;
                            $ret_arr[$this->code] = 20 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_20[$this->next_uid]) ? 0 : $arr_20[$this->next_uid];
                            $ret_arr[$this->next_id] = 21;
                        } else if ($next_approver_id == 21 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出 22
//                            $sql_22 = "select *from t_department where id=4 and del=0";
                            $arr_22 = $this->get_by_pos_dep(22, $qingjia_arr[0]['t_apply_leave']['department_id']);
                            $next_approver_id = 22;
                            $ret_arr[$this->code] = 21 * 2;
                            $ret_arr[$this->code_id][] = $user_info['id'];
                            $ret_arr[$this->next_uid] = empty($arr_22['next_uid']) ? 0 : $arr_22['next_uid'];
                            $ret_arr[$this->next_id] = 22;
                            
                        } else if ($next_approver_id == 22 && $arr_get[$this->next_uid] == $next_apprly_uid) {
                            //取出 6
//                            $sql_6 = "select *from t_user where position_id=6 and del=0";
                            $arr_6 = $this->get_by_pos_dep(6, $qingjia_arr[0]['t_apply_leave']['department_id']);
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
