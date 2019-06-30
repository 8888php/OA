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
        $this->id = $id;
        return $this->save($data);
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
        return $this->find('all', array('conditions' => array('project_id' => $pid, 'code' => 10000), 'fields' => $fields));
    }

    # 获取项目全部 费用信息

    public function getSubject($pid) {
        $fields = array('id', 'subject');
        return $this->find('list', array('conditions' => array('project_id' => $pid, 'code' => 10000), 'fields' => $fields));
    }

    # 获取项目全部 费用信息 

    public function getSubjectTwo($pid) {
        $fields = array('id', 'subject', 'table_name', 'attr_id');
        return $this->find('all', array('conditions' => array('project_id' => $pid, 'code' => 10000, 'is_calculation' => 1), 'fields' => $fields));
    }

    # 获取资金来源 剩余金额    只统计已审批
    # $data : array  资金来源id，金额

    public function getSurplus($data) {
        $fields = array('id', 'total', 'source_id');
        $surplusArr = $this->find('list', array('conditions' => array('source_id' => $data['sourceId'], 'code' => 10000, 'is_calculation' => 1), 'fields' => $fields));
        $surplus = $data['amount'];
        if ($surplusArr) {
            foreach ($surplusArr as $key => $value) {
                foreach ($value as $k => $v) {
                    $surplus[$key] = sprintf("%0.2f", $surplus[$key] - $v);
                }
            }
        }
        return $surplus;
    }

    # 获取资金来源 剩余金额  已审批和未审批都统计
    # $data : array  资金来源id，金额

    public function getSurplusnew($data) {
        $fields = array('id', 'total', 'source_id');
        $sourceId = implode(',', $data['sourceId']);
        $sql = 'select id,total,source_id from t_apply_main where source_id in( ' . $sourceId . ') and is_calculation = 1 and code % 2 = 0';
        $surplusArr = $this->query($sql);

        if ($surplusArr) {
            $surplus = $data['amount'];
            foreach ($surplusArr as $key => $val) {
                $surpkey = $val['t_apply_main']['source_id'];
                $surplus[$surpkey] = sprintf("%0.2f", $surplus[$surpkey] - $val['t_apply_main']['total']);
            }
        }
        return $surplus;
    }
    
   
    # 获取资金来源 剩余金额  已审批和未审批都统计
    # $sourceid : array  资金来源id，
    # $source_amount 资金来源总金额
    # $pro_amount 申请单金额
    public function getSourceTotal($sourceid, $source_amount, $pro_amount) {
        $sql = 'select sum(`total`) sums from t_apply_main where source_id = ' . $sourceid . ' and is_calculation = 1 and code % 2 = 0';
        $surplusArr = $this->query($sql);
        if ($surplusArr) {
           $surplus = sprintf("%0.2f", $source_amount - $surplusArr[0][0]['sums'] - $pro_amount);
        }else{
            $surplus = sprintf("%0.2f", $source_amount - $pro_amount);
        }
        return $surplus;
    }
   

    /**
     * 修改加签人
     * @param type $id
     * @param type $add_uid 加签人id
     * @param type $data
     * @param type $type add:添加加签人  del:删除加签人
     * @return type
     */
    public function addLots($id, $add_uid, $type = 'add', $data = '') {
        $this->setDataSource('write');
        $this->id = $id;
        // 取 当前申请单中加签人
        if (empty($data)) {
            $infos = $this->findById($id);
            $save_add_lots = $infos['ApplyMain']['add_lots'];
        } else {
            $save_add_lots = $data;
        }

        switch ($type) {
            case 'add':
                $save_add_lots .= ',' . $add_uid;
                break;
            case 'del':
                $lotStr = str_replace(",$add_uid,", ',', $save_add_lots, $count);
                $count == 0 && $lotStr = str_replace(",$add_uid", '', $save_add_lots, $nums);
                $save_add_lots = $lotStr;
                break;
        }
        return $this->saveField('add_lots', $save_add_lots);
    }

    // 取符合条件的所有科研项目的 申请单（已申请通过）的总额、科目总额
    public function summary_keyan_pro_bak($proArr) {
        $conditions = array();
        $conditions['project_id'] = $proArr ? $proArr : 1;
        $conditions['type'] = 1;
        $conditions['code'] = 10000;
        $conditions['is_calculation'] = 1;
        $conditions['table_name'] = ['apply_chuchai_bxd', 'apply_lingkuandan', 'apply_baoxiaohuizong', 'apply_jiekuandan'];
        $fields = array('id', 'subject', 'total');
        $summaryArr = $this->find('all', array('conditions' => $conditions, 'fields' => $fields));
        if (empty($summaryArr)) {
            return [];
        }
        $summary = ['total' => 0];
        foreach ($summaryArr as $k => $v) {
            $summary['total'] += $v['ApplyMain']['total'];
            foreach (json_decode($v['ApplyMain']['subject'], true) as $key => $val) {
                !isset($summary[$key]) && $summary[$key] = 0;
                $summary[$key] += $val;
            }
        }
        return $summary;
    }

    // 取符合条件的所有科研项目的 申请单（已申请通过）的总额、科目总额
    public function summary_keyan_pro($proArr) {
        $conditions = array();
        $conditions['ApplyMain.project_id'] = $proArr ? $proArr : 1;
        $conditions['ApplyMain.type'] = 1;
        $conditions['ApplyMain.code'] = 10000;
        $conditions['ApplyMain.is_calculation'] = 1;
        $conditions['p.is_finish'] = 0;
        $conditions['ApplyMain.table_name'] = ['apply_chuchai_bxd', 'apply_lingkuandan', 'apply_baoxiaohuizong', 'apply_jiekuandan'];
        $fields = array('id', 'subject', 'total', 'p.project_team_id');
        $summaryArr = $this->find('all', array('conditions' => $conditions, 'fields' => $fields, 'alias' => 'm', 'joins' => array(
                array(
                    'alias' => 'p',
                    'table' => 't_research_project',
                    'type' => 'LEFT',
                    'conditions' => ' project_id = p.id ',
                ),
        )));

        if (empty($summaryArr)) {
            return [];
        }
        $summary = [];
        foreach ($summaryArr as $k => $v) {
            !isset($summary[$v['p']['project_team_id']]['total']) && $summary[$v['p']['project_team_id']]['total'] = 0;
            $summary[$v['p']['project_team_id']]['total'] += $v['ApplyMain']['total'];
            foreach (json_decode($v['ApplyMain']['subject'], true) as $key => $val) {
                !isset($summary[$v['p']['project_team_id']][$key]) && $summary[$v['p']['project_team_id']][$key] = 0;
                $summary[$v['p']['project_team_id']][$key] += $val;
            }
        }
        return $summary;
    }

}
