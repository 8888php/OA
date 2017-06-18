<?php

App::uses('ResearchProjectController', 'AppController');
/* 科研项目 */

class ResearchProjectController extends AppController {

    public $name = 'ResearchProject';
    public $uses = array('ResearchProject', 'User');
    public $layout = 'blank';
    public $components = array('Cookie');
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

    /**
     * 详情
     */
    public function index() {
        $this->render();
    }

    /**
     * 添加 项目详情
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
    public function step4() {
        
        
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
//                var_dump($this->Cookie->read('research_project'. $user_info->id));die;
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

}
