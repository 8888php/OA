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

    const SUOZHANG_ID = 6;//所长的职务id

    private function dep_create($type, $data, $user_info) {
       
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0,
            $this->err_msg = ''
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
            $ret_arr[$this->err_msg] = '部门不存在不存在';
            return $ret_arr;
        }
        if ($dem_arr[0]['t_department']['sld'] == $user_info['id']) {
            //说明他是分管领导
            //取人事领导信息
            $sql_renshi = "select *from t_user u left join t_department d on u.department_id=d.id where u.department_id=4 and d.sld=u.id";
            $renshi_arr = $this->query($sql_renshi);
            if (empty($renshi_arr)) {
                $ret_arr[$this->err_msg] = '分管人事领导不存在';
                return $ret_arr;
            }
            $ret_arr[$this->next_id] = 22;
            $ret_arr[$this->next_uid] = $renshi_arr[0]['u']['id'];
            return $ret_arr;
        }
        if ($dem_arr[0]['t_department']['user_id'] == $user_info['id']) {
            //说明他是部门负责人
            //分管所领导信息
            $sql_fenguan = "select *from t_user u left join t_department d on u.department_id=d.id where u.department_id='{$user_info['department_id']}' and d.sld=u.id"; 
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
        $sql_fenze = "select *from t_user u left join t_department d on u.department_id=d.id where u.department_id='{$user_info['department_id']}' and d.user_id=u.id";
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
        $sum_days = $data['sum_days'];//天数
        $ret_arr = array(
            $this->next_id => 0,
            $this->next_uid => 0,
            $this->code => 0
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
            $ret_arr[$this->next_id] = self::SUOZHANG_ID;
            $ret_arr[$this->next_uid] = $suo_zhang[0]['t_user']['id'];
            return $ret_arr;
        }
        $sql_1 = "select *from t_department where id='{$user_info['department_id']}' and del=0";
        $dem_arr = $this->query($sql_1);
        if ($dem_arr[0]['t_department']['sld'] == $user_info['id']) {
            //说明他是分管领导
            //取人事领导信息
            $sql_renshi = "select *from t_user u left join t_department d on u.department_id=d.id where u.department_id=4 and d.sld=u.id";
            $renshi_arr = $this->query($sql_renshi);
            $ret_arr[$this->next_id] = 22;
            $ret_arr[$this->next_uid] = $renshi_arr[0]['u']['id'];
            return $ret_arr;
        }
        if ($dem_arr[0]['t_department']['user_id'] == $user_info['id']) {
            //说明他是部门负责人
            //分管所领导信息
            $sql_fenguan = "select *from t_user u left join t_department d on u.department_id=d.id where u.department_id='{$user_info['department_id']}' and d.sld=u.id"; 
            $fenguan_arr = $this->query($sql_fenguan);
            $ret_arr[$this->next_id] = 5;
            $ret_arr[$this->next_uid] = $fenguan_arr[0]['u']['id'];
            return $ret_arr;
        }
        //这里是职员
        $sql_fenze = "select *from t_user u left join t_department d on u.department_id=d.id where u.department_id='{$user_info['department_id']}' and d.user_id=u.id";
        $fuze_arr = $this->query($sql_fenze);
        $ret_arr[$this->next_id] = 15;
        $ret_arr[$this->next_uid] = $fuze_arr[0]['u']['id'];
        return $ret_arr;
    }
        /**
     * 审批时间 获取审批信息
     * $id 所审批的id
     * $user_info 审批人信息
     */
    public function apply_approve($id, $user_info) {
        
    }
}
