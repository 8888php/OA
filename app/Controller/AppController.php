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

    public $uses = array('User','Department', 'ResearchProject');
    public $userInfo = array();
    public $appdata = array();

    public function beforeFilter() {
        parent::beforeFilter();

        if (!$this->User->get_session_oa()) {
            //ajax
            if ($this->request->is('ajax')) {
                echo json_encode(array(
                    'code' => -1,
                    'msg' => 'Please login in'
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
        
        #所属项目
       $applyList =  $this->ResearchProject->getApplyList(array('code'=>4));
       $this->set('applyList',$applyList);

    }

    /**
     * 退出登录
     */
    public function logout() {
        $this->User->del_session_oa();
        $this->redirect(array('controller' => 'login', 'action' => 'signin'));
    }



}
