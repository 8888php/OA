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

   //  初始化mysqli
    private function mysqli_start(){
         $dbsource = $this->getdatasource() ; 
         $dbconfig = $dbsource->config ;
         $mysqli  = new  mysqli( $dbconfig['host'] , $dbconfig['login'] , $dbconfig['password'] , $dbconfig['database'] );
         $mysqli->set_charset('utf8');
         return $mysqli ;
    }
     
    
    /**
     *  加签审批人审批
     *  @params:  $uinfo 审批人信息;$applyinfo 审批单信息 
     *  @response:
     */
    public function addLotsApply($uinfo, $applyinfo) { 
        $ret_arr = array('code' => 2, 'msg' => '加签审批失败');

        $add_lotsArr = explode(',', $applyinfo['add_lots']);
        $uinfo = (array)$uinfo;
        //是加签审批人
        if (in_array($uinfo['id'], $add_lotsArr)) {
            $uid = $uinfo['id'];
            $mid = $applyinfo['id']; 
            // main表 加签人id移除
            switch($uinfo['app_status']){
                case 1 :
                    $lotStr = str_replace(",$uid,",',', $applyinfo['add_lots'], $nums); 
                    $nums == 0 && $lotStr = str_replace(",$uid", '',$applyinfo['add_lots'], $nums);

                    //  更新apply_main 中加签人id；更新add_lots 中该申请单的所属加签人为已审核状态is_apply = 1
                    $upSql = "update t_apply_main m left join t_add_lots l on m.id = l.main_id and l.user_id = $uid  set m.add_lots = '$lotStr' ,l.is_apply = 1 where m.id = $mid ";
                    break;
                case 2 :
                    $lotStr = '0' ;
                    $fail_code = $applyinfo['next_approver_id'] * 2 - 1; 
                    $upSql = "update t_apply_main m left join t_add_lots l on m.id = l.main_id and l.user_id = $uid  set m.add_lots = '$lotStr' ,m.code = $fail_code ,l.is_apply = 1 where m.id = $mid ";
                    break;
                default :
                    $ret_arr['msg'] = '加签审批数据有误！';
                    return $ret_arr;
            }
    
            $mysqli = $this->mysqli_start();
            $mysqli->autocommit(false) ;
            if(!$mysqli->query($upSql)) {
                // 更新失败 直接返回json
               // $ret_arr['msg'] = $upSql ;
               return $ret_arr;
            }

            // 添加审批日志
            $saveStr = " insert into t_approval_information(main_id,approve_id,remarks,position_id,name,ctime,status,type) values(%d ,%d , '%s' ,%d ,'%s' ,'%s' , %d,1) " ;
            
            $remarks = !$uinfo['app_remarks'] ? '' : $uinfo['app_remarks'] ;
            $ctime = date('Y-m-d H:i:s', time()) ;
            $uname = $uinfo['name'];
            $saveSql = sprintf($saveStr , $mid ,$uid , $remarks ,$applyinfo['next_approver_id'] ,$uname ,$ctime , $uinfo['app_status'] ); 
            if( !$mysqli->query($saveSql) ){
                // 修改失败 直接返回json
                $mysqli->rollback(); 
               // $ret_arr['msg'] = $saveSql ;
                return $ret_arr;
            }
      
            //判断如果有审批金额则写到表里面
                if ($uinfo['app_small'] && $applyinfo['table_name'] == 'apply_jiekuandan') {
                    $total = $uinfo['app_small'];
                    $main_subject = json_decode($applyinfo['subject'],true);
                    foreach($main_subject as $mk => $mv){
                        $main_subject[$mk] = $uinfo['app_small'];
                    }
                    $subject = json_encode($main_subject);
                    
                    $big_total = $uinfo['app_big'];
                    
                    $jiekuanSql = "update t_apply_main m left join t_apply_jiekuandan j on m.attr_id = j.id set m.total = $total , m.subject = $subject ,j.approve_money = $total , j.approve_money_capital = $big_total  where m.id = $mid ";
                    if( !$mysqli->query($jiekuanSql)){
                        // 修改失败 直接返回json
                        $mysqli->rollback();
                      //  $ret_arr['msg'] = $jiekuanSql ;
                        return $ret_arr;
                    }
                }
                
            if( $mysqli->commit() ){
                $ret_arr['code'] = 0;
                $ret_arr['msg'] = '加签审批成功';
            }
            $mysqli->autocommit(true) ;
           // var_dump($mysqli->error);
            $mysqli->close();
        }else{
            $ret_arr['msg'] = '请等待加签人审批结束后再审批';
        }
        
        return $ret_arr;
        
    }
    
    
    
    
    
    
    
    
    
}
