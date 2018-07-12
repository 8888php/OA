<?php

/**
 * Application model for CakePHP.
 *
 * This file is application-wide model file. You can put all
 * application-wide model-related methods here.
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
 * @package       app.Model
 * @since         CakePHP(tm) v 0.2.9
 * @license       http://www.opensource.org/licenses/mit-license.php MIT License
 */
App::uses('User', 'AppModel');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends AppModel {

    var $name = 'User';
    var $useTable = 'user';
    var $is_del = 1; //删除

    const SESSINO_OA_NAME = 'OA'; //oa session name

    public $components = array('Session');

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
    }

    /**
     * 判断用户名与密码
     * @param type $user
     * @param type $pwd
     * @return number or array()
     */
    public function check_user_pwd($user, $pwd) {

        if (empty($user) || empty($pwd)) {
            //帐号密码为空
            return -1;
        }
        $user_arr = $this->findByUser($user);

        if (empty($user_arr)) {
            //用户不存在
            return -2;
        }
        if ($user_arr[$this->name]['password'] != $pwd) {
            //密码不对
            return -3;
        }
        if ($user_arr[$this->name]['del'] == $this->is_del || $user_arr[$this->name]['status'] == $this->is_del) {
            //已删除
            return -3;
        }
        return $user_arr[$this->name];
    }

    /**
     * 记录session
     * @param type $user_arr
     */
    public function save_session_oa($user_arr) {
        unset($user_arr['password']);
        CakeSession::write(SESSINO_OA_NAME, base64_encode(json_encode($user_arr)));
    }

    /**
     * 检测session是否存在
     * @return boolean
     */
    public function get_session_oa() {
        return CakeSession::read(SESSINO_OA_NAME);
    }

    /**
     * 检测session是否存在
     * @return boolean
     */
    public function del_session_oa() {
        CakeSession::delete(SESSINO_OA_NAME);
    }

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function add($data) {
        $this->setDataSource('write');
        $this->create();
        return $this->save($data);
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
        return $this->save($data);
    }

    # 获取全部数据

    public function getAlluser($page = 0, $num = 20, $conditions = array()) {
        $userArr = $fields = array();

        $fields = array('id', 'user', 'department_id', 'position_id', 'name', 'tel','email', 'ctime', 'status');
        $userArr = $this->find('all', array('conditions' => $conditions, 'fields' => $fields, 'limit' => $num, 'page' => $page));

        return $userArr;
    }

    # 获取全部负责人

    public function getAllfuzeren($conditions = array()) {
        $userArr = $fields = array();
        $conditions = array('del' => 0, 'status' => 0, 'position_id >' => 0);
        $fields = array('id', 'position_id', 'name');
        $userArr = $this->find('all', array('conditions' => $conditions, 'fields' => $fields));
        return $userArr;
    }
    
    
     # 获取全部非项目内成员

    public function not_project_member($pid) {
        $userArr = array();
        $sql = 'SELECT u.id,u.name FROM t_user u WHERE u.id NOT IN(SELECT m.user_id FROM t_project_member m WHERE m.project_id = '.$pid.' ) AND u.del = 0 AND u.status = 0  ';
        $userArr = $this->query($sql);

        return $userArr;
    }
    /**
     * 获取所有用户的id,name，以id为下标，name为值的一维数组返回
     * @return array()
     */
    public function get_all_user_id_name() {
        $table_name = 'User';
        $all_user_arr = array();
        $sql = "select id,name from t_user {$table_name} where id > 1 and del = 0  ";
        $tmp_arr = $this->query($sql);
        if (!$tmp_arr) {
            return $all_user_arr;
        }
        foreach ($tmp_arr as $t) {
            $all_user_arr[$t[$table_name]['id']] = $t[$table_name]['name'];
        }
        return $all_user_arr;
    }

    
    
    
     
     # 获取全部非团队内成员

    public function not_team_member($tid) {
        $userArr = array();
        $sql = 'SELECT u.id,u.name FROM t_user u WHERE u.id NOT IN(SELECT m.user_id FROM t_team_member m WHERE m.team_id = '.$tid.' ) AND u.del = 0 AND u.status = 0  ';
        $userArr = $this->query($sql);

        return $userArr;
    }   
    
    
}
