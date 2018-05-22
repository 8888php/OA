<?php

App::uses('AddLots', 'AppModel');

/**
 *  加签
 */
class AddLots extends AppModel {

    var $name = 'AddLots';
    var $useTable = 'add_lots';

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
     * 同一申请单同一节点单人仅添加一次
     * @param type $data
     * @return type
     */
    public function findAdd($data) {
        $conditions = array('main_id' => $data['main_id'] , 'position_id' => $data['position_id'] , 'user_id' => $data['user_id']);
        return $this->find('first',array('conditions' => $conditions , 'fields' => array('id'))) ;
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
     * 删除
     * @param array $mid
     * @return array
     */
    public function del($id) {
        $this->setDataSource('write');
        return $this->delete($id);
    }

}
