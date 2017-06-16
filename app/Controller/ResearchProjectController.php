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
        //var_dump(CookieDecode($this->Cookie->read('research_project')));
        
        $this->render();
    }

    /**
     * 添加 项目详情
     */
    public function step1() {
        $this->set('user_id', $this->userInfo->id);
        $this->render();
    }

    /**
     * 添加 任务书
     */
    public function step2() {
        $this->render();
    }

    /**
     * 添加 项目费用
     */
    public function step3() {
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
     * 把数据存入到cookie里
     */
    public function ajax_cookie() {
        $saveArr = array();
        if ($this->request->is('ajax')) {
            if ($this->request->data('upstep') == 'step1') {
                $saveArr['user_id'] = $this->request->data('user_id');
                $saveArr['name'] = $this->request->data('name');
                $saveArr['alias'] = $this->request->data('alias');
                $saveArr['amount'] = $this->request->data('amount');
                $saveArr['start_date'] = $this->request->data('start_date');
                $saveArr['end_date'] = $this->request->data('end_date');
                $saveArr['overview'] = $this->request->data('overview');
                $saveArr['remark'] = $this->request->data('remark');

                $saveArr['source_channel'] = $this->request->data('source_channel');
                $saveArr['file_number'] = $this->request->data('file_number');
                $saveArr['amount'] = $this->request->data('amount');
                $saveArr['year'] = $this->request->data('year');
                $cookiewrite = $this->Cookie->write('research_project', CookieEncode($saveArr), true, '7 day');
            }

            if ($this->request->data('upstep') == 'step2') {
                $saveArr['filename'] = $this->request->data('filename');
                $cookiewrite = $this->Cookie->write('research_file', CookieEncode($saveArr), true, '7 day');
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
                $cookiewrite = $this->Cookie->write('research_cost', CookieEncode($saveArr), true, '7 day');
            }


            if ($cookiewrite) {
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = $cookiewrite;
            } else {
                $this->ret_arr['msg'] = $cookiewrite;
            }
            echo json_encode($cookiewrite);
            die;
        }
    }

}
