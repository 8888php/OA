<?php

App::uses('AppNotLoginController', 'Controller');

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
class LoginController extends AppNotLoginController {
    public $layout = 'default';
    public $uses = array('User');
     
    public function signin() {
        $this->render();
    }
}
