<?php

App::uses('AppController', 'Controller');
/* 行政办公 */

class RequestNoteController extends AppController {

    public $name = 'RequestNote';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource','ProjectMember', 'ApplyMain', 'ApplyBaoxiaohuizong', 'ApprovalInformation','Department');

    public $layout = 'blank';
    public $components = array('Cookie');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

   /**
     * 公共方法
     */  
    
    // 项目下所属资源文件
    public function getsource(){
        if(empty($_POST['pd'])){
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $sourcelist = $sourceArr = array();
        $sourcelist =  $this->ResearchSource->getAll($_POST['pd']);
        foreach($sourcelist as $k => $v){
            $sourceArr[$v['ResearchSource']['id']] = $v['ResearchSource'];
        }
       
        if(empty($sourceArr)){
            $this->ret_arr['msg'] = '无文件数据';
            exit(json_encode($this->ret_arr));
        }else{
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = $sourceArr;
            exit(json_encode($this->ret_arr));
        }
        
    }
    
    
    
    /**
     * 科研项目费用报销
     */
    
    //汇总报销申批单
    public function huizongbaoxiao() {
        
        if ($this->request->is('ajax')) {
            $this->sub_declares($_POST);
        }else{
        //当前用户所属项目
        $conditions = array('user_id'=>$this->userInfo->id);
        $projectArr = $this->Department->getlist($conditions);

        $this->set('projectArr', $projectArr[1]);
        $this->set('list', Configure::read('keyanlist'));
        
        $this->render();
        }
        
    }

     // 添加 汇总报销申批单
    private function sub_declares($datas) {
        if (empty($datas['ctime']) || empty($datas['page_number']) || empty($datas['projectname']) || empty($datas['filenumber']) || empty($datas['subject']) || empty($datas['rmb_capital']) || empty($datas['amount'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_baoxiaohuizong';

        $type = Configure::read('type_number');//行政费用
        $type = $type[0];
        $ret_arr = $this->get_create_approval_process_by_table_name($table_name,$type, $this->userInfo->department_id);

        if ($ret_arr[$this->code] == 1) {
            $this->ret_arr['msg'] = $ret_arr[$this->msg];
            exit(json_encode($this->ret_arr));
        }
        #附表入库
        $attrArr = array();
        $attrArr['ctime'] = $datas['ctime'];
        $attrArr['page_number'] = $datas['page_number'];
        $attrArr['department_id'] = $datas['page_number'];
        $attrArr['department_name'] = $datas['page_number'];
        $attrArr['project_id'] = $datas['projectname'];
        $attrArr['subject'] = $datas['subject'];
        $attrArr['rmb_capital'] = $datas['rmb_capital'];
        $attrArr['amount'] = $datas['amount'];
        $attrArr['description'] = $datas['description'];
        $attrArr['user_id'] = $this->userInfo->id;

        # 开始入库
        $this->ApplyBaoxiaohuizong->begin();
        $attrId = $this->ApplyBaoxiaohuizong->add($attrArr);

        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr[$this->res]['next_approver_id'];//下一个审批职务的id
        $mainArr['code'] = $ret_arr[$this->res]['approve_code'];//当前单子审批的状态码
        $mainArr['approval_process_id'] = $ret_arr[$this->res]['approval_process_id']; //审批流程id
        $mainArr['type'] = $type; 
        $mainArr['name'] = $datas['declarename'];
        $mainArr['project_id'] = $datas['projectname'];
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['attr_id'] = $attrId;
        $mainArr['ctime'] = $datas['ctime'];
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyBaoxiaohuizong->rollback();
        }
        $mainId ? $commitId = $this->ApplyBaoxiaohuizong->rollback() : $commitId = $this->ApplyBaoxiaohuizong->commit();


        if ($commitId) {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '申请成功';
        } else {
            $this->ret_arr['msg'] = '申请失败';
        }


        echo json_encode($this->ret_arr);
        exit;
    }
  
    
    
    /**
     * 行政部门费用报销
     */
   
    // 畜牧所出差审批单
     public function xms_evection() {
         
        if ($this->request->is('ajax')) {
            $this->sub_declares($_POST);
        }else{
        //当前用户所属项目
        $projectArr = $this->Department->findById($this->userInfo->department_id);
        $projectArr =  $projectArr['Department'];

        $this->set('projectArr', $projectArr);
        $this->set('list', Configure::read('xizhenglist'));
        
        $this->render();
        }
    }

    
 

}
