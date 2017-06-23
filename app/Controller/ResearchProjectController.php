<?php

App::uses('ResearchProjectController', 'AppController');
/* 科研项目 */

class ResearchProjectController extends AppController {

    public $name = 'ResearchProject';
    public $uses = array('ResearchProject', 'User', 'ResearchCost', 'ResearchSource', 'ProjectMember', 'Fixedassets');
    public $layout = 'blank';
    public $components = array('Cookie');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

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
            //  header("Location:/homes/index");die;
        }
        $this->set('pid', $pid);

        $cost = $this->ResearchCost->findByProjectId($pid);
        $cost = @$cost['ResearchCost'];

        $this->set('cost', $cost);
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
            //  header("Location:/homes/index");die;
        }
        $this->set('pid', $pid);


        $this->render();
    }

    /**
     * 详情 报表
     */
    public function report_form($pid = 0) {
        if (empty($pid)) {
            //  header("Location:/homes/index");die;
        }
        $this->set('pid', $pid);


        $this->render();
    }

    /**
     * 详情 档案
     */
    public function archives($pid = 0) {
        if (empty($pid)) {
            // header("Location:/homes/index");die;
        }
        $this->set('pid', $pid);


        $this->render();
    }

    /**
     * 详情 出入库
     */
    public function storage($pid = 0) {
        if (empty($pid)) {
            //  header("Location:/homes/index");die;
        }
        $this->set('pid', $pid);


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

        $list = array(
            array('data_fee' => '资料费', 'facility1' => '设备费1'),
            array('facility2' => '设备费2', 'facility3' => '设备费3'),
            array('material1' => '材料费1', 'material2' => '材料费2'),
            array('material3' => '材料费3', 'material4' => '材料费4'),
            array('assay' => '测试化验费', 'elding' => '燃料动力费'),
            array('publish' => '印刷、出版费', 'property_right' => '知识产权费'),
            array('travel' => '差旅费', 'meeting' => '会议、会务费'),
            array('cooperation' => '国内协作费', 'labour' => '劳务费'),
            array('consult' => '专家咨询费', 'other' => '其他费用'),
            array('indirect' => '间接费', 'train' => '培训费'),
            array('vehicle' => '车辆使用费', 'collection' => '采集费'),
        );
        $this->set('list', $list);

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
            !empty($_POST['facility1']) && $saveArr['facility1'] = $_POST['facility1'];
            !empty($_POST['facility2']) && $saveArr['facility2'] = $_POST['facility2'];
            !empty($_POST['facility3']) && $saveArr['facility3'] = $_POST['facility3'];
            !empty($_POST['material1']) && $saveArr['material1'] = $_POST['material1'];
            !empty($_POST['material2']) && $saveArr['material2'] = $_POST['material2'];
            !empty($_POST['material3']) && $saveArr['material3'] = $_POST['material3'];
            !empty($_POST['material4']) && $saveArr['material4'] = $_POST['material4'];
            !empty($_POST['assay']) && $saveArr['assay'] = $_POST['assay'];
            !empty($_POST['elding']) && $saveArr['elding'] = $_POST['elding'];
            !empty($_POST['publish']) && $saveArr['publish'] = $_POST['publish'];
            !empty($_POST['property_right']) && $saveArr['property_right'] = $_POST['property_right'];
            !empty($_POST['travel']) && $saveArr['travel'] = $_POST['travel'];
            !empty($_POST['meeting']) && $saveArr['meeting'] = $_POST['meeting'];
            !empty($_POST['cooperation']) && $saveArr['cooperation'] = $_POST['cooperation'];
            !empty($_POST['labour']) && $saveArr['labour'] = $_POST['labour'];
            !empty($_POST['consult']) && $saveArr['consult'] = $_POST['consult'];
            !empty($_POST['other']) && $saveArr['other'] = $_POST['other'];
            !empty($_POST['indirect']) && $saveArr['indirect'] = $_POST['indirect'];
            !empty($_POST['train']) && $saveArr['train'] = $_POST['train'];
            !empty($_POST['vehicle']) && $saveArr['vehicle'] = $_POST['vehicle'];
            !empty($_POST['collection']) && $saveArr['collection'] = $_POST['collection'];

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
    public function add_storage($pid = 0) {
        if (empty($pid)) {
            header("Location:/home/index");
        }

        #项目详情
        $pinfos = $this->ResearchProject->findById($pid);
        $pinfos = @$pinfos['ResearchProject'];
        $this->set('pinfos', $pinfos);
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
         /*   $editArr = array();
            $editArr['user_id'] = $_POST['member'];
            $editArr['project_id'] = $_POST['pid'];
            $editArr['user_name'] = $memberInfo['User']['user'];
            $editArr['name'] = $memberInfo['User']['name'];
            $editArr['tel'] = $memberInfo['User']['tel'];
            $editArr['type'] = $_POST['types'];
            $editArr['ctime'] = date('Y-m-d');
            $editArr['remark'] = $_POST['remark'];
            $memberId = $this->ProjectMember->add($editArr);


            if ($memberId) {
                $this->ret_arr['code'] = 0;
            } else {
                $this->ret_arr['msg'] = '操作失败';
            }*/
        }

        echo json_encode($this->ret_arr);
        exit;
    }

}
