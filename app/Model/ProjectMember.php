<?php

/* *
 *  项目成员
 */

App::uses('ProjectMember','AppModel');

class ProjectMember extends AppModel{
    
    public $name = 'ProjectMember';
    public $useTable = 'project_member';
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
        $fieldList = array('remark');
        $this->id = $id;
        return  $this->save($data,true,$fieldList);
    }

    /**
     * 添加数据
     * @param type $data
     * @return type
     */
    public function del($pid,$mid) {
        $this->setDataSource('write');
        //$this->query("delete from t_project_member where project_id = $pid and id = $mid ");
       return $this->deleteAll(array('project_id'=>$pid,'id'=>$mid));
    }
    
    
    # 获取全部项目成员
    public function getList($pid){
        $fields = array('id','name','user_name','tel','type','ctime','remark');
        return  $this->find('all',array('conditions' => array('project_id'=>$pid),'fields'=>$fields));
    }
    
      # 获取某个项目成员
    public function getmember($pid,$uid){
        $fields = array('id','name','user_name','tel','type','ctime','remark');
        return  $this->find('all',array('conditions' => array('project_id'=>$pid,'user_id'=>$uid),'fields'=>$fields));
    }
    
    
    
}