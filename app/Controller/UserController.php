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
    }

    /**
     * 添加/修改页面
     */
    public function edit() {
        $this->render();
    }

    /**
     * ajax 保存添加/修改
     */
    public function ajax_edit() {
        $ret_arr = array();
        if ($this->request->is('ajax')) {
            $id = $this->request->data('id');
            if ($id < 1 || !is_numeric($id)) {
                //add
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
