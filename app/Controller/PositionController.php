<?php

App::uses('PositionController', 'AppController');
/* 科研项目 */

class PositionController extends AppController {

    public $name = 'Position';
    public $uses = array('Position');
    public $layout = 'blank';
    public $components = array('Approval');
    /* 左 */
    
    /**
     * 职务管理
     */
    public function index($pages = 1) {
var_dump($this->Approval->apply(3,$this->userInfo,2));die;
      //  var_dump($_SERVER);
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 20;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $conditions = array(); //获取条件
        $total = $this->Position->find('count',array('conditions'=>$conditions));
        
        $posiArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }

            $posiArr = array();
            $posiArr = $this->Position->query('select * from t_position as pos order by id desc limit ' . (($pages - 1) * $limit) . ',' . $limit);
        }
        $this->set('posiArr', $posiArr);

        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 职务编辑
     */
    public function add($id = 0) {
        if($id && is_numeric($id)){
           $posiArr = $this->Position->findById($id);
           $this->set('posiArr',$posiArr);
        }

        $this->render();
    }
    
   /**
     * ajax 启用/停用
     */
    public function ajax_del() {
        $ret_arr = array();
        if ($this->request->is('ajax')) {
            $id = $this->request->data('did');
            $del = $this->request->data('status');
            if ($id < 1 || !is_numeric($id)) {
                //参数有误
                $ret_arr = array(
                    'code' => 1,
                    'msg' => $id
                );
            } else {
                $delArr['del'] = ($del == 'del') ? 1 : 0;
                if ($this->Position->edit($id, $delArr)) {
                    $ret_arr = array(
                        'code' => 0,
                        'msg' => '删除成功'
                    );
                } else {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '删除失败'
                    );
                }
            }
        } else {
            $ret_arr = array(
                'code' => 1,
                'msg' => $this->request->is('ajax')
            );
        }
        echo json_encode($ret_arr);
        exit;
    }

   
    /**
     * ajax 保存添加/修改
     */
    public function ajax_edit() {
        $ret_arr = array();
        if ($this->request->is('ajax')) {
            $id = $this->request->data('id');
            $name = $this->request->data('name');
            $desc = $this->request->data('desc');
            $save_arr = array(
                'name' => $name,
                'description' => $desc,
                'ctime' => time(),
            );
            if (empty($name)) {
                $ret_arr = array(
                    'code' => 1,
                    'msg' => '职务名为空',
                    'class' => '.name'
                );
                echo json_encode($ret_arr);
                exit;
            }

            if ($id < 1 || !is_numeric($id)) {
                ADD:
                //add
                //先查看职务名是否被占用
                if ($this->Position->findByName($name)) {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '职务名被占用',
                        'class' => '.name'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                //save
                if ($this->Position->add($save_arr)) {
                    $ret_arr = array(
                        'code' => 0,
                        'msg' => '添加成功',
                        'class' => ''
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                //保存失败
                $ret_arr = array(
                    'code' => 2,
                    'msg' => '添加失败',
                    'class' => ''
                );
                echo json_encode($ret_arr);
                exit;
            } else {
                //edit
                if (!($posi_arr = $this->Position->findById($id))) {
                    //如果找不到此职务就让他添加
                    goto ADD;
                }
                //先查看职务名是否被占用
                $name_user_arr = $this->Position->findByName($name);
                if (count($name_user_arr) > 1) {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '该职务已添加',
                        'class' => '.name'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }

                if ($this->Position->edit($id, $save_arr)) {
                    $ret_arr = array(
                        'code' => 0,
                        'msg' => '修改成功',
                        'class' => ''
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                //失败
                $ret_arr = array(
                    'code' => 2,
                    'msg' => '修改失败',
                    'class' => ''
                );
                echo json_encode($ret_arr);
                exit;
            }
        } else {
            $ret_arr = array(
                'code' => 1,
                'msg' => '参数有误',
                'class' => ''
            );
        }
        echo json_encode($ret_arr);
        exit;
    }


}
