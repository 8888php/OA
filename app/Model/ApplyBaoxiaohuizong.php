<?php

/* *
 *  项目 出入库
 */

App::uses('ApplyBaoxiaohuizong', 'AppModel');

class ApplyBaoxiaohuizong extends AppModel {

    public $name = 'ApplyBaoxiaohuizong';
    public $useTable = 'apply_baoxiaohuizong';
    public $components = array('Session');

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function add($data) {
        $this->setDataSource('write');
        $this->create();
        $this->save($data);
        return $this->id;
    }

    /**
     * 修改数据
     * @param type $id
     * @param type $data
     * @return type
     */
    public function edit($id, $data) {
        $this->setDataSource('write');
        $fieldList = array('spec','nums','amount','abstract','del');
        $this->id = $id;
        return $this->save($data, true, $fieldList);
    }

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function del($sid) {
        $this->setDataSource('write');
        return $this->deleteAll(array('id' => $sid));
    }

    # 获取项目全部 出入库

    public function getList($pid) {
        $fields = array();
        return $this->find('all', array('conditions' => array('project_id' => $pid, 'del' => 0), 'fields' => $fields));
    }
    



}
