<?php

/* *
 *  项目 出入库
 */

App::uses('ApplyMain', 'AppModel');

class ApplyMain extends AppModel {

    public $name = 'ApplyMain';
    public $useTable = 'apply_main';
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
        $fieldList = array('code','project_leader_id','head_department_id','charge_id','director_id','charge_finance_id','financial_officer_id');
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
