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
App::uses('ApprovalInformation', 'AppModel');

/**
 * Application model for Cake.
 *
 * Add your application-wide methods in the class below, your models
 * will inherit them.
 *
 * @package       app.Model
 */
class ApprovalInformation extends AppModel {

    //记录审批的附表
    var $name = 'ApprovalInformation';
    var $useTable = 'approval_information';

    public function __construct($id = false, $table = null, $ds = null) {
        parent::__construct($id, $table, $ds);
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

    /**
     * 汇总报表 获取审批信息
     * @param array $mid
     * @return array
     */
    public function approveList($mid) {
        $applyList = $this->find('all', array('conditions' => array('main_id' => $mid), 'fields' => array('main_id', 'position_id', 'name', 'remarks', 'ctime')));

        $approveListArr = array();
        if (count($applyList) > 0) {
            foreach ($applyList as $v) {
                $approveListArr[$v['ApprovalInformation']['main_id']][$v['ApprovalInformation']['position_id']] = $v['ApprovalInformation']['name'] . ' ' . $v['ApprovalInformation']['ctime'] . ' ' . $v['ApprovalInformation']['remarks'];
            }
        }
        return $approveListArr;
    }

}
