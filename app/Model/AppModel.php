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

App::uses('AppModel', 'Model');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class AppModel extends Model {


	// 请假单、差旅审批单  
	// 判断是否  6 吕英忠、7 赵旗峰、9 李登科、8 李全、5 乔永胜，是则直接交给所长审批 
    public function teshuApply($uid){
    	$ret_arr = array(
            'next_id' => 0,
            'next_uid' => 0,
            'code' => 0,
            'msg' => '',
        );
        $teshu_uid = [5, 6, 7, 8, 9];
        
        if(in_array($uid, $teshu_uid)){
            $suo_zhang = $this->query("select id from t_user where position_id=6 and del=0 limit 1 ");
            if (empty($suo_zhang)) {
                $ret_arr['msg'] = '所长不存在';
                return $ret_arr;
            }
            $ret_arr['code'] = 10 ;
            $ret_arr['next_uid'] = $suo_zhang[0]['t_user']['id'];
            $ret_arr['next_id'] = 6;

            return $ret_arr;
        }else{
        	return false;
        }

    }


}
