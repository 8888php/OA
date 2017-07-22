<?php

App::uses('ResearchProjectController', 'AppController');
/* 科研项目 */

class ResearchProjectController extends AppController {

    public $name = 'ResearchProject';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'Fixedassets', 'Storage', 'ApplyBaoxiaohuizong', 'ApplyMain', 'Department','TeamProject', 'ApprovalInformation');
    public $layout = 'blank';
    public $components = array('Cookie', 'Approval');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

    public function beforeFilter() {
        parent::beforeFilter();
        if ($this->request->is('get')) {
            //获取参数
            $action = $this->request->params['action'];
            $left_use_show_actions = array(
                '', //默认index
                'index',
                'archives',
                'assets',
                'budget',
                'report_form',
                'storage',
                'declares'
            );
            //左侧栏 科研项目显示
            if (in_array($action, $left_use_show_actions) || in_array(strtolower(substr($action, 0, 1)) . substr($action, 1), $left_use_show_actions)) {
                $pid = isset($this->request->params['pass'][0]) ? $this->request->params['pass'][0] : 0;
                if ($pid > 0) {
                    $left_show_arr = $this->ResearchProject->query("select * from t_research_project ResearchProject where id='$pid' and code=4 limit 1");
                    $this->set('left_show_arr', $left_show_arr);
                }
            }
        }
    }

    /**
     * 详情
     */
    public function index($pid = 0) {
        if (empty($pid)) {
            // header("Location:/homes/index");die;
        }
        $this->set('pid', $pid);

        $pinfos = $this->ResearchProject->findById($pid);
        $pinfos = @$pinfos['ResearchProject'];
        $source = $this->ResearchSource->getAll($pid);

        $members = $this->ProjectMember->getList($pid);

        $this->set('pinfos', $pinfos);
        $this->set('members', $members);
        $this->set('source', $source);
        $this->render();
    }

    /**
     * 详情 预算
     */
    public function budget($pid = 0) {
        if (empty($pid)) {
              header("Location:/homes/index");die;
        }
        $this->set('costList', Configure::read('keyanlist'));
        $this->set('pid', $pid);

        $cost = $this->ResearchCost->findByProjectId($pid);
        $cost = @$cost['ResearchCost'];
        
        $minus = array();
        if(!empty($cost)){
            $overplus = $this->ApplyMain->getSubject($pid);
            foreach($overplus as $k => $v){
                $units = json_decode($v,true);
                foreach($units as $uk => $uv){
                   !isset($minus[$uk]) && $minus[$uk] = 0;
                   $minus[$uk]  += $uv;
                }
            }
        }
        $this->set('cost', $cost);
        $this->set('minus',$minus);
        $this->render();
    }

    /**
     * 详情 项目资产
     */
    public function assets($pid = 0) {
        if (empty($pid)) {
            //  header("Location:/homes/index");die;
        }
        //根据项目id取出他的固定资产列表
        $fixedassets = $this->Fixedassets->query('select Fixedassets.*,project.code,project.name from t_fixed_assets Fixedassets left join t_research_project project  on Fixedassets.project_id=project.id  where project_id=' . "$pid");
        $this->set('fixedassets', $fixedassets);
        $this->set('pid', $pid);


        $this->render();
    }

    /**
     * 详情 费用申报
     */
    public function declares($pid = 0) {
        if (empty($pid)) {
            header("Location:/homes/index");die;
        }
        //费用申报的内容
        $declares_arr = $this->ResearchSource->query("SELECT m.*,b.page_number,b.id,b.subject,b.rmb_capital,b.amount,b.description,u.name,s.* FROM t_apply_main m LEFT JOIN t_apply_baoxiaohuizong b ON m.attr_id = b.id  LEFT JOIN t_user u ON m.user_id = u.id LEFT JOIN t_research_source s ON b.source_id = s.id  WHERE m.project_id =  '$pid'");
        $this->set('keyanlist', Configure::read('keyanlist'));
        $this->set('declares_arr', $declares_arr);
        $this->set('pid', $pid);

        $this->render();
    }

    /**
     * 添加 费用申报
     */
    public function add_declares($pid = 0) {
        if (empty($pid)) {
            //  header("Location:/homes/index");die;
        }
        $this->set('pid', $pid);

        $projectInfo = $this->ResearchProject->findById($pid);
        $source = $this->ResearchSource->getAll($pid);
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $this->set('department_arr', $department_arr);
        $this->set('is_department', !empty($department_arr) ? $department_arr['Department']['type'] : 2);
        $this->set('xizhenglist', Configure::read('xizhenglist'));
        $this->set('keyanlist', Configure::read('keyanlist'));
        $this->set('projectInfo', $projectInfo['ResearchProject']);
        $this->set('source', $source);
        $this->render();
    }

    /**
     * 添加 费用报销单
     */
    public function sub_declares() {
        if (!$this->request->is('ajax')) {
            $this->ret_arr['msg'] = '请求不合法';
            exit(json_encode($this->ret_arr));
        }
        
        if (empty($_POST['ctime']) || empty($_POST['page_number']) || empty($_POST['subject']) || empty($_POST['rmb_capital']) || empty($_POST['amount'])) {
            $this->ret_arr['msg'] = '参数有误';
            exit(json_encode($this->ret_arr));
        }
        $table_name = 'apply_baoxiaohuizong';

        $type = Configure::read('type_number');//行政费用
        
        
        //获取审批流id
//        $p_id = Configure::read('approval_process');
        $p_id = 2;
        if ($_POST['projectname'] == 0) {
            $project_id = 0;//让他为0
            $type = $type[0];
        }else {
            //项目
            $project_id = $_POST['projectname'];
            $type = $type[1];
            $p_id = 2;
        }
        $ret_arr = $this->Approval->apply_create($p_id, $this->userInfo);
        
//        $ret_arr = $this->get_create_approval_process_by_table_name($table_name,$type, $this->userInfo->department_id);
//
//        if ($ret_arr[$this->code] == 1) {
//            $this->ret_arr['msg'] = $ret_arr[$this->msg];
//            exit(json_encode($this->ret_arr));
//        }
        #附表入库
        //是部门，取当前用户的部门信息
        $department_id = $this->userInfo->department_id;
        $department_arr = $this->Department->findById($department_id);
        $department_name = !empty($department_arr) ? $department_arr['Department']['name'] : '';
        
        $attrArr = array();
        $attrArr['ctime'] = $_POST['ctime'];
        $attrArr['page_number'] = $_POST['page_number'];

        $attrArr['department_id'] = $department_id;
        $attrArr['department_name'] = $department_name;
        $attrArr['project_id'] = $project_id;
        $attrArr['source_id'] = $_POST['filenumber'];
        $attrArr['subject'] = json_encode($_POST['subject']);
        $attrArr['rmb_capital'] = $_POST['rmb_capital'];
        $attrArr['amount'] = $_POST['amount'];
        $attrArr['description'] = $_POST['description'];
        $attrArr['user_id'] = $this->userInfo->id;
                
        # 开始入库
        $this->ApplyBaoxiaohuizong->begin();
        $attrId = $this->ApplyBaoxiaohuizong->add($attrArr);
        
        # 主表入库
        $mainArr = array();
        $mainArr['next_approver_id'] = $ret_arr['next_id'];//下一个审批职务的id
        $mainArr['code'] = $ret_arr['code'];//当前单子审批的状态码
        $mainArr['approval_process_id'] = $p_id; //审批流程id
        $mainArr['type'] = $type; 
        $mainArr['attachment'] = $_POST['attachment']; 
        $mainArr['name'] = $_POST['declarename'];
        $mainArr['project_id'] = $project_id;
        $mainArr['department_id'] = $department_id;        
        $mainArr['table_name'] = $table_name;
        $mainArr['user_id'] = $this->userInfo->id;
        $mainArr['total'] = $_POST['amount'];
        $mainArr['attr_id'] = $attrId;
        $mainArr['ctime'] = $_POST['ctime'];
        $mainArr['subject'] = json_encode($_POST['subject']);
        if ($attrId) {
            $mainId = $this->ApplyMain->add($mainArr);
        } else {
            $this->ApplyBaoxiaohuizong->rollback();
        }
        $mainId ? $commitId = $this->ApplyBaoxiaohuizong->rollback() : $commitId = $this->ApplyBaoxiaohuizong->commit();


        if ($commitId) {
            //如果审批通过，且跳过下个则在表里记录一下
            if (isset($ret_arr['code_id']) && $ret_arr['code_id'] == $this->userInfo->position_id) {
                //说明这个审批人是他自己
                //保存审批的数据
                $save_approve = array(
                    'main_id' => $mainId,
                    'approve_id' => $this->userInfo->id,
                    'remarks' => '',
                    'name' => $this->userInfo->name,
                    'ctime' => date('Y-m-d H:i:s', time()),
                    'status' => 1
                );
                $this->ApprovalInformation->add($save_approve);
            } else {
                //其他审批人 暂时不处理
            }
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '申请成功';
        } else {
            $this->ret_arr['msg'] = '申请失败';
        }


        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 详情 报表
     */
    public function report_form($pid = 0) {
        if (empty($pid)) {
             header("Location:/homes/index");die;
        }
         $declares_arr = $this->ResearchSource->query("SELECT m.*,b.page_number,b.id,b.subject,b.rmb_capital,b.amount,b.description,u.name,s.* FROM t_apply_main m LEFT JOIN t_apply_baoxiaohuizong b ON m.attr_id = b.id  LEFT JOIN t_user u ON m.user_id = u.id LEFT JOIN t_research_source s ON b.source_id = s.id  WHERE m.project_id =  '$pid' and code = 10000 ");
     
        $this->set('keyanlist', Configure::read('keyanlist'));
        $this->set('declares_arr', $declares_arr);
        $this->set('pid', $pid);
        
        $pcost = $this->ResearchCost->findByProjectId($pid);
        $pcost = $pcost['ResearchCost'];
        $this->set('pcost', $pcost);  // 预算费用
        
        $expent = array();  // 支出总计费用
        foreach($declares_arr as $k => $v){
            $zhichu = json_decode($v['b']['subject'],true);
            foreach($zhichu as $zk => $zv){ 
                $expent[$zk] = isset($expent[$zk]) ? $expent[$zk]+$zv : $zv;
            }
        }
        $this->set('expent', $expent);  // 支出总计费用
        
        
        $this->render();
    }

    /**
     * 详情 档案
     */
    public function archives($pid = 0) {
        if (empty($pid)) {
             header("Location:/homes/index");die;
        }
        $this->set('pid', $pid);
        
        $sourcelist = $this->ResearchSource->getAll($pid);
        
        $this->render();
    }

    /**
     * 详情 出入库
     */
    public function storage($pid = 0) {
        if (empty($pid)) {
            header("Location:/homes/index");
            die;
        }
        $this->set('pid', $pid);

        $storagelist = $this->Storage->getList($pid);
        $this->set('storagelist', $storagelist);

        $this->render();
    }

    /**
     * 添加 添加项目成员列表
     */
    public function add_member($pid = 0) {
        if (empty($pid)) {
            header("Location:/homes/index");
            die;
        }

        # 非项目内成员
        $notInMember = $this->User->not_project_member($pid);
        $this->set('notInMember', $notInMember);

        #项目内成员
        $projectMember = $this->ProjectMember->getList($pid);
        $this->set('projectMember', $projectMember);
        $this->set('pid', $pid);
        $this->render();
    }

    /**
     * 添加 添加项目成员
     */
    public function member_operation() {
        if (empty($_POST['pid']) || (empty($_POST['member']) && empty($_POST['mid'])) || empty($_POST['type'])) {
            $this->ret_arr['msg'] = '参数有误';
        } else {
            $editArr = array();
            switch ($_POST['type']) {
                case 'add' :
                    $memberInfo = $this->User->findById($_POST['member']);
                    if (!$memberInfo)
                        exit(json_encode($this->ret_arr));

                    $isAdd = $this->ProjectMember->getmember($_POST['pid'], $_POST['member']);
                    if ($isAdd) {
                        $this->ret_arr['msg'] = '该用户已是项目成员';
                        exit(json_encode($this->ret_arr));
                    }

                    $editArr['user_id'] = $_POST['member'];
                    $editArr['project_id'] = $_POST['pid'];
                    $editArr['user_name'] = $memberInfo['User']['user'];
                    $editArr['name'] = $memberInfo['User']['name'];
                    $editArr['tel'] = $memberInfo['User']['tel'];
                    $editArr['type'] = $_POST['types'];
                    $editArr['ctime'] = date('Y-m-d');
                    $editArr['remark'] = $_POST['remark'];
                    $memberId = $this->ProjectMember->add($editArr);
                    break;
                case 'edit':
                    $editArr['remark'] = $_POST['remark'];
                    $memberId = $this->ProjectMember->edit($_POST['mid'], $editArr);
                    break;
                case 'del':
                    $memberId = $this->ProjectMember->del($_POST['pid'], $_POST['mid']);
                    break;
            }

            if ($memberId) {
                $this->ret_arr['code'] = 0;
            } else {
                $this->ret_arr['msg'] = '操作失败';
            }
        }

        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 添加 添加项目
     */
    public function step1() {
        
        $team = $this->TeamProject->find('list',array('conditions'=>array('del'=>0),'fields'=>array('id','name')));
        $this->set('team',$team);
        $this->render();
    }

    /**
     * 添加 任务书
     */
    public function step2() {
        if ($this->request->isPost() && $this->request->data('step1') != 'step1') {
            header('Location:/ResearchProject/index');
        }

        $saveArr = array();
        $saveArr['user_id'] = $this->userInfo->id;
        $saveArr['name'] = $this->request->data('name');
        $saveArr['alias'] = $this->request->data('alias');
        $saveArr['amount'] = $this->request->data('amount');
        $saveArr['start_date'] = $this->request->data('start_date');
        $saveArr['end_date'] = $this->request->data('end_date');
        $saveArr['overview'] = $this->request->data('overview');
        $saveArr['remark'] = $this->request->data('remark');
        $saveArr['source'] = $this->request->data('source');
        $saveArr['project_team_id'] = $this->request->data('project_team_id');//所属项目组id
        $saveArr['type'] = $this->request->data('type');
        
        $saveArr['qdly'] = $this->request->data('qdly'); //这里放的是数组
        $this->Cookie->write('research_project' . $this->userInfo->id, CookieEncode($saveArr), false, '7 day');
        $this->render();
    }

    /**
     * 添加 项目费用
     */
    public function step3() {
        if ($this->request->isPost() && $this->request->data('step2') != 'step2') {
            header('Location:/ResearchProject/index');
        }
        $savefiles = $this->request->data('file_upload');
        $this->Cookie->write('research_file' . $this->userInfo->id, CookieEncode($savefiles), false, '7 day');

        $this->set('list', Configure::read('keyanlist'));

        $this->render();
    }

    /**
     * 添加 项目 入库
     */
    public function substep3() {
        $project = $this->Cookie->read('research_project' . $this->userInfo->id);
        $files = $this->Cookie->read('research_file' . $this->userInfo->id);
        if (empty($project) || empty($files)) {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '数据不完整';
            echo json_encode($this->ret_arr);
            die;
        }

        $saveArr = array();
        if ($this->request->is('ajax') && $this->request->data('upstep') == 'step3') {
            !empty($_POST['data_fee']) && $saveArr['data_fee'] = $_POST['data_fee'];
            !empty($_POST['collection']) && $saveArr['collection'] = $_POST['collection'];
            !empty($_POST['facility']) && $saveArr['facility'] = $_POST['facility'];
            !empty($_POST['material']) && $saveArr['material'] = $_POST['material'];
            !empty($_POST['assay']) && $saveArr['assay'] = $_POST['assay'];
            !empty($_POST['elding']) && $saveArr['elding'] = $_POST['elding'];
            !empty($_POST['publish']) && $saveArr['publish'] = $_POST['publish'];
            !empty($_POST['property_right']) && $saveArr['property_right'] = $_POST['property_right'];
            !empty($_POST['office']) && $saveArr['office'] = $_POST['office'];
            !empty($_POST['vehicle']) && $saveArr['vehicle'] = $_POST['vehicle'];
            !empty($_POST['travel']) && $saveArr['travel'] = $_POST['travel'];
            !empty($_POST['meeting']) && $saveArr['meeting'] = $_POST['meeting'];
            !empty($_POST['travel']) && $saveArr['travel'] = $_POST['travel'];
            !empty($_POST['international']) && $saveArr['international'] = $_POST['international'];
            !empty($_POST['cooperation']) && $saveArr['cooperation'] = $_POST['cooperation'];
            !empty($_POST['labour']) && $saveArr['labour'] = $_POST['labour'];
            !empty($_POST['consult']) && $saveArr['consult'] = $_POST['consult'];
            !empty($_POST['indirect_manage']) && $saveArr['indirect_manage'] = $_POST['indirect_manage'];
            !empty($_POST['indirect_performance']) && $saveArr['indirect_performance'] = $_POST['indirect_performance'];
            !empty($_POST['indirect_other']) && $saveArr['indirect_other'] = $_POST['indirect_other'];
            !empty($_POST['other']) && $saveArr['other'] = $_POST['other'];
            !empty($_POST['other2']) && $saveArr['other2'] = $_POST['other2'];
            !empty($_POST['other3']) && $saveArr['other3'] = $_POST['other3'];

            $saveArr['total'] = array_sum($saveArr);  // 总额
            !empty($_POST['remarks']) && $saveArr['remarks'] = $_POST['remarks'];

            $projectArr = CookieDecode($project);
            $sourceArr = $projectArr['source'];
            unset($projectArr['source']);
            $filesArr = CookieDecode($files);
            $projectArr['filename'] = $filesArr[0];
            $projectArr['ctime'] = date("Y-m-d H:i:s");

            # 开始入库
            $this->ResearchProject->begin();
            $this->ResearchProject->add($projectArr);

            if ($porijectId = $this->ResearchProject->id) {
                $saveSourceArr = array();
                $key = 0; //定义一个下标
                foreach ($sourceArr['file_number'] as $k => $v) {
                    if (!$v) {
                        continue;
                    }

                    $saveSourceArr[$key]['project_id'] = $porijectId;
                    $saveSourceArr[$key]['source_channel'] = $sourceArr['source_channel'][$k];
                    $saveSourceArr[$key]['year'] = $sourceArr['year'][$k];
                    $saveSourceArr[$key]['file_number'] = $sourceArr['file_number'][$k];
                    $saveSourceArr[$key]['amount'] = $sourceArr['amount'][$k];
                    $this->ResearchSource->add($saveSourceArr[$key++]);
                }
            } else {
                $this->ResearchProject->rollback();
            }

            $saveArr['project_id'] = $porijectId;
            !$this->ResearchSource->id ? $this->ResearchProject->rollback() : $costId = $this->ResearchCost->add($saveArr);

            !$this->ResearchCost->id ? $this->ResearchProject->rollback() : $commitId = $this->ResearchProject->commit();

            if ($costId) {
                //添加成功，把自己写出到项目成员表里面
                    $editArr['user_id'] = $this->userInfo->id;
                    $editArr['project_id'] = $porijectId;
                    $editArr['user_name'] = $this->userInfo->user;
                    $editArr['name'] = $this->userInfo->name;
                    $editArr['tel'] =  $this->userInfo->tel;
                    $editArr['type'] = 1;//项目负责人
                    $editArr['ctime'] = date('Y-m-d');
                    $editArr['remark'] = '';
                    $memberId = $this->ProjectMember->add($editArr);
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = $commitId . '添加项目成功！请等待审核';
            } else {
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '添加项目失败！';
            }
        } else {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '参数有误';
        }

        echo json_encode($this->ret_arr);
        exit;
        $this->render();
    }

    /**
     * 把数据存入到cookie里
     */
    public function ajax_cookie() {
        $saveArr = array();

        if ($this->request->is('ajax')) {
            if ($this->request->data('upstep') == 'step1') {
                $saveArr['user_id'] = $this->userInfo->id;
                $saveArr['name'] = $this->request->data('name');
                $saveArr['alias'] = $this->request->data('alias');
                $saveArr['amount'] = $this->request->data('amount');
                $saveArr['start_date'] = $this->request->data('start_date');
                $saveArr['end_date'] = $this->request->data('end_date');
                $saveArr['overview'] = $this->request->data('overview');
                $saveArr['remark'] = $this->request->data('remark');
                $saveArr['project_team_id'] = $this->request->data('project_team_id');//所属项目组id
                $saveArr['type'] = $this->request->data('type');
                $saveArr['qdly'] = $this->request->data('qdly'); //这里放的是数组
//                $saveArr['source_channel'] = $this->request->data('source_channel');
//                $saveArr['file_number'] = $this->request->data('file_number');
//                $saveArr['amount'] = $this->request->data('amount');
//                $saveArr['year'] = $this->request->data('year');
                //这里取出用户的  id，把id也存入到cookie里面

                $this->Cookie->write('research_project' . $this->userInfo->id, CookieEncode($saveArr), false, '7 day');
                
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = json_encode($saveArr);

                echo json_encode($this->ret_arr);
                die;
            }

            if ($this->request->data('upstep') == 'step2') {
                $saveArr['filename'] = $this->request->data('filename');
                $this->Cookie->write('research_file' . $this->userInfo->id, CookieEncode($saveArr), false, '7 day');
            }

            if ($this->request->data('upstep') == 'step3') {
                $saveArr['data_fee'] = $this->request->data('data_fee');
                $saveArr['facility1'] = $this->request->data('facility1');
                $saveArr['facility2'] = $this->request->data('facility2');
                $saveArr['facility3'] = $this->request->data('facility3');
                $saveArr['material1'] = $this->request->data('material1');
                $saveArr['material2'] = $this->request->data('material2');
                $saveArr['material3'] = $this->request->data('material3');
                $saveArr['material4'] = $this->request->data('material4');
                $saveArr['assay'] = $this->request->data('assay');
                $saveArr['elding'] = $this->request->data('elding');
                $saveArr['publish'] = $this->request->data('publish');
                $saveArr['property_right'] = $this->request->data('property_right');
                $saveArr['travel'] = $this->request->data('travel');
                $saveArr['meeting'] = $this->request->data('meeting');
                $saveArr['cooperation'] = $this->request->data('cooperation');
                $saveArr['labour'] = $this->request->data('labour');
                $saveArr['consult'] = $this->request->data('consult');
                $saveArr['other'] = $this->request->data('other');
                $saveArr['indirect'] = $this->request->data('indirect');
                $saveArr['train'] = $this->request->data('train');
                $saveArr['vehicle'] = $this->request->data('vehicle');
                $saveArr['collection'] = $this->request->data('collection');

                $saveArr['total'] = array_sum($saveArr);  // 总额
                $saveArr['remarks'] = $this->request->data('remarks');
                $this->Cookie->write('research_cost' . $this->userInfo->id, CookieEncode($saveArr), false, '7 day');
            }


            //cookie write没有返回值
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '存入成功';

            echo json_encode($this->ret_arr);
            die;
        } else {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '参数有误';
            echo json_encode($this->ret_arr);
            die;
        }
    }

    public function upload_file() {
        $file_arr = $_FILES['file'];
        $name = $file_arr['name'];
        $tmp_name = $file_arr['tmp_name'];
        $error = $file_arr['error'];
        $size = $file_arr['size'];

        $new_file_name = WWW_ROOT . DS . 'files' . DS . $name;
        //判断这个文件没有存在，如果存在就不上传
        if (file_exists($new_file_name)) {
            $this->ret_arr['code'] = 1;
            $this->ret_arr['msg'] = '文件名重复';
            echo json_encode($this->ret_arr);
            exit;
        }
        if (move_uploaded_file($tmp_name, $new_file_name)) {
            $this->ret_arr['code'] = 0;
            $this->ret_arr['msg'] = '上传成功';
        } else {
            $this->ret_arr['code'] = 2;
            $this->ret_arr['msg'] = '上传失败';
        }
        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 添加 出入库
     */
    public function add_storage($pid = 0, $sid = 0) {
        if (empty($pid)) {
            header("Location:/home/index");
        }

        #项目详情
        $pinfos = $this->ResearchProject->findById($pid);
        $pinfos = @$pinfos['ResearchProject'];
        $this->set('pinfos', $pinfos);

        $storageInfo = array();
        if (!empty($sid) && $sid > 0) {
            $storageInfo = $this->Storage->findById($sid);
            $storageInfo = $storageInfo['Storage'];
        }
        $this->set('storageInfo', $storageInfo);
        $this->set('pid', $pid);
        $this->render();
    }

    /**
     * 提交 出入库
     */
    public function sub_storage() {
        if (empty($_POST['pid']) || (empty($_POST['spec']) && empty($_POST['nums'])) || empty($_POST['amount'])) {
            $this->ret_arr['msg'] = '参数有误';
        } else {
            $saveArr = array();
            $saveArr['project_id'] = $_POST['pid'];
            $saveArr['project_name'] = $_POST['pname'];
            $saveArr['spec'] = $_POST['spec'];
            $saveArr['nums'] = $_POST['nums'];
            $saveArr['amount'] = $_POST['amount'];
            $saveArr['abstract'] = $_POST['abstract'];
            $saveArr['ctime'] = date('Y-m-d');
            if ($_POST['sid']) {
                $storageId = $this->Storage->edit($_POST['sid'], $saveArr);
            } else {
                $storageId = $this->Storage->add($saveArr);
            }


            if ($storageId) {
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '添加成功';
                $_POST['sid'] && $this->ret_arr['class'] = 'edit';
            } else {
                $this->ret_arr['msg'] = '操作失败';
            }
        }

        echo json_encode($this->ret_arr);
        exit;
    }

    /**
     * 删除 出入库
     */
    public function edit_storage() {
        if (empty($_POST['sid'])) {
            $this->ret_arr['msg'] = '参数有误';
        } else {
            $saveArr = array();
            $saveArr['del'] = 1;
            $storageId = $this->Storage->edit($_POST['sid'], $saveArr);

            if ($storageId) {
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '删除成功';
            } else {
                $this->ret_arr['msg'] = '删除失败';
            }
        }

        echo json_encode($this->ret_arr);
        exit;
    }
    
    /**
     * 
     * 根据部门或者项目id取出对用的费用
     * @param type $type 类型 1为部门， 2为科研项目
     * @param type $project_id 项目id
     */
    public function add_declares_department_project($type,$project_id=0) {
        if ($type == 1) {
            //部门费用
            $feiyong  = Configure::read('xizhenglist');
        } else {
            //科研费用
            $feiyong  = Configure::read('keyanlist');
        }
        $this->set('feiyong', $feiyong);
        $this->render();
    }
    
    /**
     * 预览 打印，费用申报列表的打印
     */
    public function budget_print($main_id = 0) {
        if (empty($main_id)) {
              header("Location:/homes/index");die;
        }
        $this->set('costList', Configure::read('keyanlist'));
        $main_arr = $this->ApplyMain->findById($main_id);
        
        $subject = array();
        if (!empty($main_arr['ApplyMain']['subject'])) {
            $subject = json_decode($main_arr['ApplyMain']['subject'], true);
        }
        $this->set('subject', $subject);
        $this->render();
    }
}
