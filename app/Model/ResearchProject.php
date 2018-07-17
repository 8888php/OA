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
    
  
    # 获取全部项目 按资金性质分类

    public function getApplyList($conditions = array()) {
        $userArr = $fields = array();
        $conditions['del'] = 0;
        $fields = array('id', 'name','type');
        return $this->find('list', array('conditions' => $conditions, 'fields' => $fields));
    }
    
    # 获取全部项目  按项目组分类

    public function getApplyLisTeam($conditions = array()) {
        $userArr = $fields = array();
        $conditions['del'] = 0;
        $fields = array('id', 'name','project_team_id');
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

     
    // 获取所有科研项目 汇总报表
    public function summary(){
        // 1、取当前所有 未关闭、未删除状态下的 科研项目
        
        // 2、取符合条件的所有科研项目的 总额、科目总额
        
        // 3、取符合条件的所有科研项目的 申请单（已申请通过）的总额、科目总额
        
        // 4、计算科目的支出剩余总金额、科目剩余总额；支出进度；
        $conditions = array();
        $conditions['del'] = 0;
        $fields = array('id', 'name','project_team_id');
        $this->find('list',array('conditions' =>$conditions, 'fields' => $fields ));
    } 

    
    // 获取当前所有 未关闭、未删除状态下的 科研项目
    public function summary_pro(){
        $conditions = array();
        $conditions['code'] = 4;
        $conditions['del'] = 0;
        $fields = array('id');
        return $this->find('list',array('conditions' =>$conditions, 'fields' => $fields ));
    } 
  
    // 获取所有科研项目 科目费用总金额
    public function summary_ky($proArr){
        $conditions = $proArr ? implode(',', $proArr) : 1 ; 
        $fields = 'SUM(data_fee) data_fee,SUM(collection) collection,SUM(facility) facility,SUM(material) material,SUM(assay) assay,SUM(elding) elding,SUM(publish) publish,SUM(property_right) property_right,SUM(office) office,SUM(vehicle) vehicle,SUM(travel) travel,SUM(meeting) meeting,SUM(international) international,SUM(cooperation) cooperation,SUM(labour) labour,SUM(consult) consult,SUM(indirect_manage) indirect_manage,SUM(indirect_performance) indirect_performance,SUM(indirect_other) indirect_other,SUM(other) other,SUM(other2) other2,SUM(other3) other3,SUM(total) total,count(id) nums';
       $sqlstr = "SELECT $fields FROM t_research_cost where project_id in( $conditions ) ";
       return  $this->query($sqlstr);
    } 
    

}
