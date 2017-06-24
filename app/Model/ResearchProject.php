<?php

/**
 * 科研项目
 */
App::uses('ResearchProject', 'AppModel');

class ResearchProject extends AppModel {

    public $name = 'ResearchProject';
    public $useTable = 'research_project';
    public $is_del = 1; //删除
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
        $this->id = $id;
        return $this->save($data);
    }

    # 获取全部项目

    public function getList() {
        $userArr = $fields = array();
        $fields = array('id', 'name');
        return $this->find('list', array('conditions' => array('del' => 0), 'fields' => $fields));
    }
    
  
    # 获取全部项目

    public function getApplyList($conditions = array()) {
        $userArr = $fields = array();
        $conditions['del'] = 0;
        $fields = array('id', 'name','type');
        return $this->find('list', array('conditions' => $conditions, 'fields' => $fields));
    }
    

    # 获取全部项目

    public function getAll($conditions = array(), $limit = 0, $page = 0) {
        $screen = array();
        $conditions['del'] = 0;
        $screen['conditions'] = $conditions;
        $limit && $screen['limit'] = $limit;
        $page && $screen['page'] = $page;
        return $this->find('all', $screen);
    }


}
