<?php

class ApprovalComponent extends Component{
    public $controller = true;
    public $component = array('Cookie');
 
 
 /**
    *  以下所用方法 都需先获取 申请费用信息
    *
    *   获取申请项目信息
    *  @params: $apply_id 申请费用id; 
    *  @response:
    */

    public function apply_info($apply_id = 0){
        $info = array();
        if(!empty($apply_id)){ 
            require_once('../Model/ApplyMain.php');
            $applyInfo = new ApplyMain();
            $info = $applyInfo->findById($apply_id);
            $info = $info['ApplyMain']; 
        }
        return $info;

    }

/**
    *  以下所用方法 都需先获取 申请审批流
    *
    *   获取申请审批流
    *  @params: $process_id 申请流id; 
    *  @response:
    */

    public function apply_process($process_id = 0){
        $info = array();
        if(!empty($apply_id)){ 
            require_once('../Model/ApprovalProcess.php');
            $Process = new ApprovalProcess();
            $info = $Process->findById($process_id);
            $info = $info['ApprovalProcess']; 
        }
        return $info;

    }

 


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
    *
    *   对应办公室主任（科研办公室主任）
    *  @params: $department_id 部门id; $type 申请类型：1科研、2行政; $uid 申请人id
    *  @response:
    *   $info = $this->apply_info($apply_id); // 先获取审报费用信息
    */

    public function apply_bgszr($department_id = 0,$type = 0,$uid = 0){

        // 根据$type 为1 去查项目所属 科研主任(department_id:3)
        // 为2 则去查 对应行政部门 办公室主任(position_id:4)
        switch($type){
            case 1: 
            // 找科研主任
                $Uinfo = new User();
                $zhuren = $Uinfo->find('list',array('conditions'=>array('department_id' => 3,'position_id' => 4),'fields'=>array('id')));
            break;
            case 2:
            // 找对应行政部门 办公室主任
                $Uinfo = new User();
                $zhuren = $Uinfo->find('list',array('conditions'=>array('department_id' => $department_id,'position_id' => 4),'fields'=>array('id')));
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
    *  @params: $department_id 部门id;$type 申请类型：1科研、2行政; $uid 申请人id
    *  @response:
    */

    public function apply_fsz($department_id = 0,$type = 0,$uid = 0){

        // 根据$type 为1 去查项目所属 科研主任(department_id:3)
        // 为2 则去查 对应行政部门 办公室主任(position_id:4)
        switch($type){
            case 1: 
            // 找科研副所长
                $Uinfo = new User();
                $fusuozhang = $Uinfo->find('list',array('conditions'=>array('department_id' => 3,'position_id' => 5),'fields'=>array('id')));
                if(in_array($uid, $fusuozhang)){
                    return true;
                }else{
                    return false;
                }
            break;
            case 2:
            // 找对应行政部门 分管领导 副所长
                if(in_array(department_id,array(3,5))){ //如果是科研部门、财务部门 则直接跳过
                    return false;
                }

                //部门分管副所长
                $Department = new Department();
                $fusuozhang = $Department->findById($department_id);
                $fusuozhang = $fusuozhang['Department']; 
                if($uid == $fusuozhang['sld']){
                    return true;
                }else{
                    return false;
                }
            break;
            default:
                return false;
        }

        

    }


 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *  2、申报金额低于2W 所长直接通过
    *
    *    所长
    *  @params: $total 申请总费用; $uid 申请人id
    *  @response:
    */

    public function apply_sz($total = 0,$uid = 0){
        if($total < 20000){  //小于2W
            return true;
        }

        require_once('../Model/User.php');
        $Uinfo = new User();
        $userinfo = $Uinfo ->findByPositionId(6);
        $userinfo = $userinfo['User']; 
        
        if($uid == $userinfo['id']){
            return true;
        }else{
            return false;
        }


    }


 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *
    *   财务副所长
    *  @params:  $uid 申请人id
    *  @response:
    */

    public function apply_cwfsz($uid = 0){

        require_once('../Model/User.php');
        $Uinfo = new User();
        $userinfo = $Uinfo ->find('list',array('conditions'=>array('department_id' => 5,'position_id' => 5),'fields'=>array('id')));  
        $userinfo = $userinfo['User']; 
        

        if(in_array($uid, $userinfo)){
            return true;
        }else{
            return false;
        }

    }

 /**
    *  1、审批人是否项目申请人，是：直接跳过
    *
    *   财务办公室主任
    *  @params:  $uid 申请人id
    *  @response:
    */

    public function apply_cwbgszr($uid = 0){

        require_once('../Model/User.php');
        $Uinfo = new User();
        $userinfo = $Uinfo ->find('list',array('conditions'=>array('department_id' => 5,'position_id' => 4),'fields'=>array('id')));  
        $userinfo = $userinfo['User']; 
        
        if(in_array($uid, $userinfo)){
            return true;
        }else{
            return false;
        }

    }



 /*
     *  1、获取审批流
     *  2、获取当前审请 进度
     *  2、验证当前审批人是否 有权限
     *  3、如有 验证下一审批人是否为当前用户
     *  4、 如是 继续验证下一审批角色
     *  5、审批信息入库 
     *
     */
   

    
}
