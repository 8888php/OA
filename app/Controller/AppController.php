<?php

/**
 * Application level Controller
 *
 * This file is application-wide controller file. You can put all
 * application-wide controller-related methods here.
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (http://cakefoundation.org)
 * @link          http://cakephp.org CakePHP(tm) Project
 * @package       app.Controller
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('AppController', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		app.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
class AppController extends Controller {

    public $uses = array('User','Department', 'ResearchProject','ProjectMember');
    public $userInfo = array();
    public $appdata = array();
    public $code = 'code';//返回的状态
    public $msg = 'msg';//返回信息
    public $res = 'res';//返回数据
    public $succ_code = '10000';//审批成功的状态
    //定义不用部门的职务id,如所长，账务科长
    public $not_department_arr = array(
        6,//所长
        11,//账务科长
    );
    public function beforeFilter() {
        parent::beforeFilter();

        if (!$this->User->get_session_oa()) {
            //ajax
            if ($this->request->is('ajax')) {
                echo json_encode(array(
                    $this->code => -1,
                    $this->msg => 'Please login in'
                ));
                exit;
            }
            //普通请求
            $this->redirect(array('controller' => 'login', 'action' => 'signin'));
        }

        $this->userInfo = json_decode(base64_decode($this->User->get_session_oa()));
        $this->set('userInfo',$this->userInfo);
        
        # 部门列表
        $this->appdata['deplist'] = $this->Department->deplist();
        $this->set('deplist',$this->appdata['deplist']);
        
        #当前用户所属项目
        
        $projectId = $this->ProjectMember->find('list',array('conditions'=>array('user_id'=>$this->userInfo->id),'fields'=>array('project_id')));
        $projectId = empty($projectId) ? array(-1) : array_values($projectId);   //当前用户所属项目
        $this->appdata['projectId'] = $projectId;

       $applyList =  $this->ResearchProject->getApplyList(array('code'=>4,'id'=>$projectId));
       $this->set('applyList',$applyList);
  
       
    }

    /**
     * 退出登录
     */
    public function logout() {
        $this->User->del_session_oa();
        $this->redirect(array('controller' => 'login', 'action' => 'signin'));
    }
    
    
    
    /**
     * 当前用户身份权限
     */
    public function is_who(){
         //判断当前用户是 科研办公室 主任3 4、财务科 科长5 11
            if ($this->userInfo->department_id == 3 && $this->userInfo->position_id == 4) {
                // 科研办公室 主任
                return  'keyanzhuren';
            } else if ($this->userInfo->department_id == 5 && $this->userInfo->position_id == 14) {
                // 财务科 科长
                return 'caiwukezhang';
            }
    }

    /**
     * 创建表时 根据表名获取审批流，信息
     * @param type $table_name 表名 $type 费用类型 $department_id 部门id
     * @return array();
     */
    public function get_create_approval_process_by_table_name($table_name, $type, $department_id) {
        //code = 0;正常，1异常 msg正常或者错误信息，res返回数据
        $ret_arr = array(
            $this->code => 0,
            $this->msg => '',
            $this->res => array()
        );
        //获取审批流id
        $p_id = Configure::read('approval_process');
        $p_id = $p_id[$table_name];
        $approval_process_arr = $this->ResearchProject->query("select * from t_approval_process approval_process where id='$p_id' limit 1");
        //如果未找到则返回空
        if (!$approval_process_arr)
        {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流有问题，请联系管理员';
            return $ret_arr;
        }
        
        $approve_ids = $approval_process_arr[0]['approval_process']['approve_ids'];
        if (!$approve_ids) {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流未设置审批人，请联系管理员';
            return $ret_arr;
        }
        $approve_ids_arr = explode(',', $approve_ids);
        //取出创建人的用户id和角色id和是否有审批权限
        $user_id = $this->userInfo->id;
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;//是否有审批权限 1,无审批权限，2是有
        $user_department_id = $this->userInfo->department_id;//用户的部门id
        //创建的时间，自己的部门id,就是单子的部门id 所以说，创建时先不用做处理
        
        //下一位审批人的职务id
        $next_approve_id = $approve_ids_arr[0];
        $approve_code = 0;//第一次
        foreach ($approve_ids_arr as $k=>$v) {
            if ($position_id == $v && $can_approval == 2) {
                //先判断有没有下一个审批人，如果没有则说明审批成功,也就说他自己创建了一个不需要审批的单子
                if (!isset($approve_ids_arr[$k+1])) {
                    $next_approve_id = $user_id;//审批
                    $approve_code = $this->succ_code;
                    break;
                } else {
                    $next_approve_id = $approve_ids_arr[$k+1];
                    $approve_code = $v * 2;
                }
            } else {
                //如果不是则
                break;
            }
        }
       $ret_arr[$this->res]['next_approver_id'] = $next_approve_id;
       $ret_arr[$this->res]['approve_code'] = $approve_code;
       $ret_arr[$this->res]['approval_process_id'] = $p_id;
               
       return $ret_arr; 
    }
    
     /**
     * 审批时 根据表名获取审批流，信息
     * @param type $table_name 表名  $type 费用类型  $is_approve 1同意 2拒绝 $department_id单子的部门id
     * @return array();
     */
    public function get_apporve_approval_process_by_table_name($table_name, $type, $is_approve, $department_id) {
        //code = 0;正常，1异常 msg正常或者错误信息，res返回数据
        $ret_arr = array(
            $this->code => 0,
            $this->msg => '',
            $this->res => array()
        );
        //获取审批流id
        $p_id = Configure::read('approval_process');
        $p_id = $p_id[$table_name];
        $approval_process_arr = $this->ResearchProject->query("select * from t_approval_process approval_process where id='$p_id' limit 1");
        //如果未找到则返回空
        if (!$approval_process_arr)
        {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流有问题，请联系管理员';
            return $ret_arr;
        }
        
        $approve_ids = $approval_process_arr[0]['approval_process']['approve_ids'];
        if (!$approve_ids) {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流未设置审批人，请联系管理员';
            return $ret_arr;
        }
        //取出创建人的用户id和角色id和是否有审批权限
        $user_id = $this->userInfo->id;
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;//是否有审批权限 1,无审批权限，2是有
        $user_department_id = $this->userInfo->department_id;//用户部门id
        //判断审批人的部门与单子的部门是否一样，不一样返回错误，但是除去像所长，账务科长的特殊职务
        if (!in_array($position_id, $this->not_department_arr)) {
            //不是特殊职务
            if ($department_id != $user_department_id) {
                //用户的职务与单子的职务不一样，不能审批
                $ret_arr[$this->code] = 1;
                $ret_arr[$this->msg] = '您不能审批其它部门的审批单';
                return $ret_arr;
            }
        } 
        
        $approve_ids_arr = explode(',', $approve_ids);
        if (($index = array_search($position_id, $approve_ids_arr)) === false) {
            //如果不存在，那么说明审批流发生变化
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流发生变动，请联系管理员';
            return $ret_arr;
        }
        
        if ($can_approval != 2) {
            //如果不存在，那么说明审批流发生变化
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '您没有审批权限，请联系管理员';
            return $ret_arr;
        }
        if ($is_approve == 1) {
            //审核同意
            if (isset($approve_ids_arr[$index + 1])) {
                //说明还有人要审批
                $next_approve_id = $approve_ids_arr[$index + 1];
                $approve_code = $position_id * 2;
            } else {
                //已经完事了
                $next_approve_id = $user_id; //审批
                $approve_code = $this->succ_code;
            }
        } else {
            //拒绝
            $next_approve_id = $approve_ids_arr[$index];
            $approve_code = $position_id * 2 -1;
        }
        
       $ret_arr[$this->res]['next_approver_id'] = $next_approve_id;
       $ret_arr[$this->res]['approve_code'] = $approve_code;
       $ret_arr[$this->res]['approval_process_id'] = $p_id;
       $ret_arr[$this->res]['status'] = $is_approve == 1 ? $is_approve : 2;
               
       return $ret_arr; 
    }

}
