<?php

/**
 * Static content controller.
 *
 * This file will render views from views/pages/
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
 * Static content controller
 *
 * Override this controller by placing a copy in controllers directory of an application
 *
 * @package       app.Controller
 * @link http://book.cakephp.org/2.0/en/controllers/pages-controller.html
 */
class UserController extends AppController {

    /**
     * This controller does not use a model
     *
     * @var array
     */
    public $uses = array('User');
    public $layout = 'blank';

    /**
     * 列表页
     */
    public function index() {
        $this->render();
        $userArr = array();
        $pages = empty($_GET['page']) ? 0 : $_GET['page'];
        $userArr = $this->User->getAlluser($pages,20);
        $this->set('userArr',$userArr);
    }

    /**
     * 添加/修改页面
     */
    public function edit($id=0) {
        if ($id > 0 && is_numeric($id)) {
            $user_arr = $this->User->findById($id);
            $this->set('user', $user_arr['User']);
        }
            
        $this->render('add');
    }

    /**
     * ajax 保存添加/修改
     */
    public function ajax_edit() {
        $ret_arr = array();
        if ($this->request->is('ajax')) {
            $id = $this->request->data('user_id');
            $user = $this->request->data('username');
            $password = $this->request->data('password');
            $name = $this->request->data('name');
            $pid = $this->request->data('pid');
            $position = $this->request->data('position');
            $tel = $this->request->data('tel');
            $sex = $this->request->data('sex');
            $email = $this->request->data('email');
            $del = $this->request->data('del');
            $save_arr = array(
                'user' => $user,
                'password' => md5($password),
                'department_id' => $pid,
                'name' => $name,
                'position_id' => $position,
                'tel' => $tel ? $tel : '',
                'sex' => $sex ? (in_array($sex, array(1,2)) ? $sex : 1) : 1,
                'email' => $email ? $email : '',
                'del' => $del ? (in_array($del, array(0,1)) ? $del : 1) : 1
            );
            if (empty($user)) {
                $ret_arr = array(
                    'code' => 1,
                    'msg' => '用户名为空',
                    'class' => '.username'
                );
                echo json_encode($ret_arr);
                exit;
            }
            if (empty($password)) {
                $ret_arr = array(
                    'code' => 1,
                    'msg' => '密码为空',
                    'class' => '.pwd'
                );
                echo json_encode($ret_arr);
                exit;
            }
            if (empty($name)) {
                $ret_arr = array(
                    'code' => 1,
                    'msg' => '呢称为空',
                    'class' => '.nname'
                );
                echo json_encode($ret_arr);
                exit;
            }
            if ($id < 1 || !is_numeric($id)) {
                ADD:
                //add
                //先查看用户是否被占用
                if ($this->User->findByUser($user)) {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '用户名被战占用',
                        'class' => '.username'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                //save
                if ($this->User->add($save_arr)) {
                    $ret_arr = array(
                        'code' => 0,
                        'msg' => '添加成功',
                        'class' => ''
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                //保存失败
                $ret_arr = array(
                    'code' => 2,
                    'msg' => '添加失败',
                    'class' => ''
                );
                echo json_encode($ret_arr);
                exit;
            } else {
                //edit
                if (!($user_arr = $this->User->findById($id))) {
                    //如果找不到此用户就让他添加
                    goto ADD;
                }
                //先查看用户是否被占用
                $name_user_arr = $this->User->findByUser($user);
                if ($name_user_arr['User']['id'] != $user_arr['User']['id']) {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '用户名被好占用',
                        'class' => '.username'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                if ($user_arr['User']['password'] == $password) {
                    unset($save_arr['password']);
                } 
                if ($this->User->edit($id,$save_arr)) {
                    $ret_arr = array(
                        'code' => 0,
                        'msg' => '修改成功',
                        'class' => ''
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                //失败
                $ret_arr = array(
                    'code' => 2,
                    'msg' => '修改失败',
                    'class' => ''
                );
                echo json_encode($ret_arr);
                exit;
            }
        } else {
            $ret_arr = array(
                'code' => 1,
                'msg' => '参数有误',
                'class' => ''
            );
        }
        echo json_encode($ret_arr);
        exit;
    }

    /**
     * ajax 启用/停用
     */
    public function ajax_del() {
        $ret_arr = array();
        if ($this->request->is('ajax')) {
            $id = $this->request->data('id');
            if ($id < 1 || !is_numeric($id)) {
                //参数有误
                $ret_arr = array(
                    'code' => 1,
                    'msg' => '参数有误'
                );
            } else {
                //edit
            }
        } else {
            $ret_arr = array(
                'code' => 1,
                'msg' => '参数有误'
            );
        }
        echo json_encode($ret_arr);
        exit;
    }

}
