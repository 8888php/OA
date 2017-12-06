<?php

App::uses('Team', 'AppModel');

/**
 * 团队
 */
class Team extends AppModel {

    var $name = 'Team';
    var $useTable = 'team';
    var $is_del = 1; //删除

    const SESSINO_OA_NAME = 'OA'; //oa session name

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


    
    # 获取全部团队
    public function getList(){
        $userArr = $fields = array();
        $fields = array('id','name');
        return  $this->find('list',array('conditions' => array('del'=>0),'fields'=>$fields));
    }
    
     
    # 获取指定团队
    public function getListId($conditions = array()){
        $conditions['del'] = 0;
        return  $this->find('list',array('conditions' => $conditions,'fields'=>array('id','name')));
    }
    
    
}
