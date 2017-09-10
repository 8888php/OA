<?php

/**
 * 科研项目—— 资金来源
 */
App::uses('ResearchSource', 'AppModel');

class ResearchSource extends AppModel {

    public $name = 'ResearchSource';
    public $useTable = 'research_source';
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
        $fields = array('id','project_id');
        return  $this->find('list',array('conditions' => array('del'=>0),'fields'=>$fields));
    }
 
    
    
       # 获取相关来源
    public function getAll($pid){
        return  $this->find('all',array('conditions' => array('project_id'=>$pid)));
    }
     
         # 获取部门相关来源
    public function getDepAll($depid){
        return  $this->find('all',array('conditions' => array('department_id'=>$depid)));
    }  
    
    
    
}
