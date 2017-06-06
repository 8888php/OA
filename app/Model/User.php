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
App::uses('UserModel', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class User extends Model {

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
        if ($user_arr[$this->name]['del'] == $this->is_del) {
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
    public function getAlluser($page = 0 ,$num = 20 , $conditions = array()){
        $userArr = $fields = array();
        $fields = array('id','user','department_id','position_id','name','tel','ctime','status');
        $userArr = $this->find('all',array('conditions'=>$conditions,'fields'=>$fields,'limit'=>$num,'page'=>$page));
        return $userArr;
    }
    
    
}
