<?php

class ApprovalComponent extends Component{
    public $controller = true;
    public $component = array('Cookie');
    


 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *  2、审批人是否申请人对应部门 办公室主任、副所长
    *
    *   项目负责人审核
    *  @params: $pid 申请所属项目id; $uid 申请人id
    *  @response:
    */

    public function apply_fzr($pid = 0,$uid = 0){
        if(!empty($pid)){ 
            require_once('../Model/ResearchProject.php');
            $Project = new ResearchProject();
            $pinfo = $Project->findById($pid);
            $pinfo = $pinfo['ResearchProject']; 
        }

        if($uid == $pinfo['user_id']){
            return true;
        }else{
            return false;
        }

    }


 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *  2、审批人是否申请人对应部门 办公室主任、副所长
    *  3、申请项目是否属于项目组下
    *
    *   项目组负责人审核
    *  @params: $pid 申请所属项目id; $uid 申请人id
    *  @response:
    */

    public function apply_xmzfzr($pid = 0,$uid = 0){
        if(!empty($pid)){ 
            require_once('../Model/ResearchProject.php');
            $Project = new ResearchProject();
            $pinfo = $Project->findById($pid);
            $pinfo = $pinfo['ResearchProject']; 
        }

        // 如果项目属于项目组则取找项目组负责人
        // 否则 直接返回 
        if($pinfo['project_team_id']){
            echo '去取项目组负责人';
        }else{
            return true;
        }

var_dump($pinfo);
        // 验证uid 是否 项目组负责人 project_team_uid 项目组负责人id
        if($uid == $project_team_uid ){
            return true;
        }else{
            return false;
        }

        
    }

 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *  2、审批人是否申请人对应部门 办公室主任、副所长
    *  3、
    *
    *   对应办公室主任（科研办公室主任）
    *  @params: $apply_id 申请费用id; $uid 申请人id
    *  @response:
    */

    public function apply_bgszr($apply_id = 0,$uid = 0){
        if(!empty($apply_id)){ 
            require_once('../Model/ApplyMain.php');
            $applyInfo = new ApplyMain();
            $info = $applyInfo->findById($apply_id);
            $info = $info['ApplyMain']; 
        }

        // 根据$info['type'] 为1 去查项目所属 科研主任(department_id:3)
        // 为2 则去查 对应行政部门 办公室主任(position_id:4)
        switch($info['type']){
            case 1: 
            // 找科研主任
                $Uinfo = new User();
                $zhuren = $Uinfo->find('list',array('conditions'=>array('department_id' => 3,'position_id' => 4),'fields'=>array('id')));
            break;
            case 2:
            // 找对应行政部门 办公室主任
                $Uinfo = new User();
                $zhuren = $Uinfo->find('list',array('conditions'=>array('department_id' => $info['department_id'],'position_id' => 4),'fields'=>array('id')));
            break;
            default:
                return false;
        }

        if(in_array($uid, $zhuren)){
            return true;
        }else{
            return false;
        }

    }


 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *  2、审批人是否申请人对应部门 办公室主任、副所长
    *  3、
    *
    *   对应副所长（科研副所长）
    *  @params: $pid 申请所属项目id; $uid 申请人id
    *  @response:
    */

    public function apply_fsz(){

    }


 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *  2、申报金额低于2W 所长直接通过
    *
    *    所长
    *  @params: $pid 申请所属项目id; $uid 申请人id
    *  @response:
    */

    public function apply_sz(){

    }


 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *
    *   财务副所长
    *  @params: $pid 申请所属项目id; $uid 申请人id
    *  @response:
    */

    public function apply_cwfsz(){

    }

 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *
    *   财务办公室主任
    *  @params: $pid 申请所属项目id; $uid 申请人id
    *  @response:
    */

    public function apply_cwbgszr(){

    }



 /*
     * 创建表时 根据表名获取审批流，信息
     * @param type $table_name 表名 $type 费用类型 $department_id 部门id
     * @return array();
     */
   /* public function get_create_approval_process_by_table_name($table_name, $type, $department_id) {
        //code = 0;正常，1异常 msg正常或者错误信息，res返回数据
        $ret_arr = array(
            $this->code => 0,
            $this->msg => '',
            $this->res => array()
        );
        //获取审批流id
        $p_id = Configure::read('approval_process');
        $p_id = $p_id[$table_name];
        $approval_process_arr = $this->ResearchProject->query("select * from t_approval_process approval_process where id='$p_id' limit 1");
        //如果未找到则返回空
        if (!$approval_process_arr)
        {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流有问题，请联系管理员';
            return $ret_arr;
        }
        
        $approve_ids = $approval_process_arr[0]['approval_process']['approve_ids'];
        if (!$approve_ids) {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流未设置审批人，请联系管理员';
            return $ret_arr;
        }
        $approve_ids_arr = explode(',', $approve_ids);
        //取出创建人的用户id和角色id和是否有审批权限
        $user_id = $this->userInfo->id;
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;//是否有审批权限 1,无审批权限，2是有
        $user_department_id = $this->userInfo->department_id;//用户的部门id
        //创建的时间，自己的部门id,就是单子的部门id 所以说，创建时先不用做处理
        
        //下一位审批人的职务id
        $next_approve_id = $approve_ids_arr[0];
        $approve_code = 0;//第一次
        foreach ($approve_ids_arr as $k=>$v) {
            if ($position_id == $v && $can_approval == 2) {
                //先判断有没有下一个审批人，如果没有则说明审批成功,也就说他自己创建了一个不需要审批的单子
                if (!isset($approve_ids_arr[$k+1])) {
                    $next_approve_id = $user_id;//审批
                    $approve_code = $this->succ_code;
                    break;
                } else {
                    $next_approve_id = $approve_ids_arr[$k+1];
                    $approve_code = $v * 2;
                }
            } else {
                //如果不是则
                break;
            }
        }
       $ret_arr[$this->res]['next_approver_id'] = $next_approve_id;
       $ret_arr[$this->res]['approve_code'] = $approve_code;
       $ret_arr[$this->res]['approval_process_id'] = $p_id;
               
       return $ret_arr; 
    }
  */  
     /**
     * 审批时 根据表名获取审批流，信息
     * @param type $table_name 表名  $type 费用类型  $is_approve 1同意 2拒绝 $department_id单子的部门id
     * @return array();
     */
  /*  public function get_apporve_approval_process_by_table_name($table_name, $type, $is_approve, $department_id) {
        //code = 0;正常，1异常 msg正常或者错误信息，res返回数据
        $ret_arr = array(
            $this->code => 0,
            $this->msg => '',
            $this->res => array()
        );
        //获取审批流id
        $p_id = Configure::read('approval_process');
        $p_id = $p_id[$table_name];
        $approval_process_arr = $this->ResearchProject->query("select * from t_approval_process approval_process where id='$p_id' limit 1");
        //如果未找到则返回空
        if (!$approval_process_arr)
        {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流有问题，请联系管理员';
            return $ret_arr;
        }
        
        $approve_ids = $approval_process_arr[0]['approval_process']['approve_ids'];
        if (!$approve_ids) {
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流未设置审批人，请联系管理员';
            return $ret_arr;
        }
        //取出创建人的用户id和角色id和是否有审批权限
        $user_id = $this->userInfo->id;
        $position_id = $this->userInfo->position_id;
        $can_approval = $this->userInfo->can_approval;//是否有审批权限 1,无审批权限，2是有
        $user_department_id = $this->userInfo->department_id;//用户部门id
        //判断审批人的部门与单子的部门是否一样，不一样返回错误，但是除去像所长，账务科长的特殊职务
        if (!in_array($position_id, $this->not_department_arr)) {
            //不是特殊职务
            if ($department_id != $user_department_id) {
                //用户的职务与单子的职务不一样，不能审批
                $ret_arr[$this->code] = 1;
                $ret_arr[$this->msg] = '您不能审批其它部门的审批单';
                return $ret_arr;
            }
        } 
        
        $approve_ids_arr = explode(',', $approve_ids);
        if (($index = array_search($position_id, $approve_ids_arr)) === false) {
            //如果不存在，那么说明审批流发生变化
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '审批流发生变动，请联系管理员';
            return $ret_arr;
        }
        
        if ($can_approval != 2) {
            //如果不存在，那么说明审批流发生变化
            $ret_arr[$this->code] = 1;
            $ret_arr[$this->msg] = '您没有审批权限，请联系管理员';
            return $ret_arr;
        }
        if ($is_approve == 1) {
            //审核同意
            if (isset($approve_ids_arr[$index + 1])) {
                //说明还有人要审批
                $next_approve_id = $approve_ids_arr[$index + 1];
                $approve_code = $position_id * 2;
            } else {
                //已经完事了
                $next_approve_id = $user_id; //审批
                $approve_code = $this->succ_code;
            }
        } else {
            //拒绝
            $next_approve_id = $approve_ids_arr[$index];
            $approve_code = $position_id * 2 -1;
        }
        
       $ret_arr[$this->res]['next_approver_id'] = $next_approve_id;
       $ret_arr[$this->res]['approve_code'] = $approve_code;
       $ret_arr[$this->res]['approval_process_id'] = $p_id;
       $ret_arr[$this->res]['status'] = $is_approve == 1 ? $is_approve : 2;
               
       return $ret_arr; 
    }
*/





    
}
