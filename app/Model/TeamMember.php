<?php

/* *
 *  团队成员
 */

App::uses('TeamMember','AppModel');

class TeamMember extends AppModel{
    
    public $name = 'TeamMember';
    public $useTable = 'team_member';
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
     * 删除数据
     * @param type $data
     * @return type
     */
    public function del($tid,$mid) {
        $this->setDataSource('write');
       return $this->deleteAll(array('team_id'=>$tid,'id'=>$mid));
    }
    
    
    # 获取全部团队成员
    public function getList($tid){
        $fields = array();
        return  $this->find('all',array('conditions' => array('team_id'=>$tid),'fields'=>$fields));
    }
    
      # 获取某个团队成员
    public function getmember($tid,$uid){
        $fields = array();
        return  $this->find('all',array('conditions' => array('team_id'=>$tid,'user_id'=>$uid),'fields'=>$fields));
    }
    
    
    
}