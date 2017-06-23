<?php

App::uses('AppController', 'Controller');
/* 固定资产 */

class FixedassetsController extends AppController {

    public $name = 'Fixedassets';
    var $uses=array('Fixedassets', 'ResearchProject'); 
    /* 左 */
    public $layout = 'blank';
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');
    public function index() {
        
    }
    public function add() {
        //取出项目 未审核的，且未删除的
        $project = $this->ResearchProject->query('select id,name from t_research_project ResearchProject where code=0 and del=0');
        $this->set('project', $project);
        $this->render();
    }
    /**
     * ajax 保存
     */
    public function ajax_add() {
        if ($this->request->is('ajax')) {
            $save_arr['project_id'] = $this->request->data('pid');
            $save_arr['asset_name'] = $this->request->data('asset_name');
            $save_arr['category'] = $this->request->data('category');
            $save_arr['purchase_date'] = $this->request->data('purchase_date');
            $save_arr['code'] = $this->request->data('code');
            $save_arr['international_classification'] = $this->request->data('international_classification');
            $save_arr['model'] = $this->request->data('model');
            $save_arr['number'] = $this->request->data('number');
            $save_arr['company'] = $this->request->data('company');
            $save_arr['price'] = $this->request->data('price');
            $save_arr['amount'] = $save_arr['price'] * $save_arr['number'];
            $save_arr['is_government'] = $this->request->data('is_government');
            $save_arr['approval_number'] = $this->request->data('approval_number');
            $save_arr['current_situation'] = $this->request->data('current_situation');
            $save_arr['remarks'] = $this->request->data('remarks');
            
            if ($this->Fixedassets->add($save_arr)) {
                //成功
                $this->ret_arr['code'] = 0;
                $this->ret_arr['msg'] = '添加成功';
            } else {
                //失败
                $this->ret_arr['code'] = 1;
                $this->ret_arr['msg'] = '添加失败';
            }
            echo json_encode($this->ret_arr);
            exit;
        }
    }

}
