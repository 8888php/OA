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
    public function getList(){
        $userArr = $fields = array();
        $fields = array('id','name');
        return  $this->find('list',array('conditions' => array('del'=>0),'fields'=>$fields));
    }

    
}
