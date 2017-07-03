<?php

/**
 * 科研项目—— 费用
 */
App::uses('DepartmentCost', 'AppModel');

class DepartmentCost extends AppModel {

    public $name = 'DepartmentCost';
    public $useTable = 'department_cost';
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


    
    # 获取项目全部
    public function getList(){
        $userArr = $fields = array();
        $fields = array('id','department_id');
        return  $this->find('list',array('fields'=>$fields));
    }
    
    
}
