<?php

App::uses('ResearchProjectController', 'AppController');
/* 科研项目 */

class ResearchProjectController extends AppController {

    public $name = 'ResearchProject';
    public $uses = array('ResearchProject');
    public $layout = 'blank';
    public $components = array('Cookie');  

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
        $this->render();
    }
    
    /**
     * 添加 项目费用
     */
    public function step3() {
        $list = array(
            array('data_fee'=>'资料费','facility1'=>'设备费1'),
            array('facility2'=>'设备费2','facility3'=>'设备费3'),
            array('material1'=>'材料费1','material2'=>'材料费2'),
            array('material3'=>'材料费3','material4'=>'材料费4'),
            array('assay'=>'测试化验费','elding'=>'燃料动力费'),
            array('publish'=>'印刷、出版费','property_right'=>'知识产权费'),
            array('travel'=>'差旅费','meeting'=>'会议、会务费'),
            array('cooperation'=>'国内协作费','labour'=>'劳务费'),
            array('consult'=>'专家咨询费','other'=>'其他费用'),
            array('indirect'=>'间接费','train'=>'培训费'),   
            array('vehicle'=>'车辆使用费','collection'=>'采集费'),   
            );
        $this->set('list',$list);
        $this->render();
    }
    
    
    /**
     * 把数据存入到cookie里
     */
    public function ajax_cookie() {
        // CookieEncode($array = array())  加密
        // CookieDecode($str = '') 解密
    }
    
    
    
    
    
    
    
    
    
    
}
