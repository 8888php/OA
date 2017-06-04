<?php

App::uses('Controller', 'Controller');

/**
 * Application Controller
 *
 * Add your application-wide methods in the class below, your controllers
 * will inherit them.
 *
 * @package		AppNotLogin.Controller
 * @link		http://book.cakephp.org/2.0/en/controllers.html#the-app-controller
 */
//此class不用登录就可以用  例如 登录页面
class AppNotLoginController extends Controller {
    public $uses = array('User');
    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->User->get_session_oa()) {
            $this->redirect(array('controller'=>'homes', 'action'=>'index'));
        }
    }
}
