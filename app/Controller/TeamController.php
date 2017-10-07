<?php

App::uses('TeamController', 'AppController');
/* 团队 */

class TeamController extends AppController {

    public $name = 'Team';
    public $uses = array('Team', 'TeamMember');
    public $layout = 'blank';
    public $components = array();
    private $ret_arr = array('code' => 1, 'msg' => '', 'class' => '');

    /**
     * 团队管理
     */
    public function index($pages = 1) {
        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 30;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $conditions = array(); //获取条件
        $total = $this->Team->find('count', array('conditions' => $conditions));

        $posiArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }

            $posiArr = array();
            $posiArr = $this->Team->query('select * from t_team as t order by t.id desc limit ' . (($pages - 1) * $limit) . ',' . $limit);

            $memArr = array();
            foreach($posiArr as $k => $v){
                $memArr[] = $v['t']['id'];
            }
            $memberArr = $this->TeamMember->find('list',array('conditions' => array('team_id'=>$memArr,'code'=>array(1,2)),'fields'=>array('team_id','name','code')));
        }
        $this->set('posiArr', $posiArr);
        $this->set('memberArr', $memberArr);

        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 团队编辑
     */
    public function add($id = 0) {
        if ($id && is_numeric($id)) {
            $teamArr = $this->Team->findById($id);
            $this->set('teamArr', $teamArr);
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
                if ($this->Team->edit($id, $delArr)) {
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
                    'msg' => '团队名为空',
                    'class' => '.name'
                );
                echo json_encode($ret_arr);
                exit;
            }

            if ($id < 1 || !is_numeric($id)) {
                ADD:
                //add
                //先查看团队名是否被占用
                if ($this->Team->findByName($name)) {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '团队名被占用',
                        'class' => '.name'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                //save
                if ($this->Team->add($save_arr)) {
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
                if (!($posi_arr = $this->Team->findById($id))) {
                    //如果找不到此团队就让他添加
                    goto ADD;
                }
                //先查看团队名是否被占用
                $name_user_arr = $this->Team->findByName($name);
                if (count($name_user_arr) > 1) {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '该团队已添加',
                        'class' => '.name'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }

                if ($this->Team->edit($id, $save_arr)) {
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

    /**
     * 添加 添加团队成员列表
     */
    public function add_member($tid = 0) {
        if (empty($tid)) {
            header("Location:/homes/index");
            die;
        }

        # 非团队内成员
        $notInMember = $this->User->not_team_member($tid);
        $this->set('notInMember', $notInMember);

        #团队内成员
        $teamMember = $this->TeamMember->getList($tid);
        $this->set('teamMember', $teamMember);
        $this->set('tid', $tid);
        $this->render();
    }

    /**
     * 添加 添加团队成员
     */
    public function member_operation() {
        if (empty($_POST['tid']) || (empty($_POST['member']) && empty($_POST['mid'])) || (empty($_POST['code']) && $_POST['code'] != 0)) {
            $this->ret_arr['msg'] = '参数有误';
        } else {
            $editArr = $addArr = array();
            switch ($_POST['type']) {
                case 'add' :
                    $memberInfo = $this->User->findById($_POST['member']);
                    if (!$memberInfo)
                        exit(json_encode($this->ret_arr));

                    $isAdd = $this->TeamMember->getmember($_POST['tid'], $_POST['member']);
                    if ($isAdd) {
                        $this->ret_arr['msg'] = '该用户已是团队成员';
                        exit(json_encode($this->ret_arr));
                    }

                    $addArr['user_id'] = $_POST['member'];
                    $addArr['team_id'] = $_POST['tid'];
                    $addArr['code'] = $_POST['code'];
                    $addArr['name'] = $memberInfo['User']['name'];
                    $addArr['create_time'] = date('Y-m-d H:i:s');
                    $memberId = $this->TeamMember->add($addArr);
                    break;
                case 'edit':
                    $editArr['code'] = $_POST['code'];
                    $memberId = $this->TeamMember->edit($_POST['mid'], $editArr);
                    break;
                case 'del':
                    $memberId = $this->TeamMember->del($_POST['tid'], $_POST['mid']);
                    break;
            }

             // 团队表修改负责人、所领导
            if (($_POST['type'] == 'add' || $_POST['type'] == 'edit') && $memberId ) {
                switch ($_POST['code']) {
                    case 1:
                        $memberId = $this->Team->edit($_POST['tid'], array('fzr' => $_POST['mid']));
                        break;
                    case 2:
                        $memberId = $this->Team->edit($_POST['tid'], array('sld' => $_POST['mid']));
                        break;
                }
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

}
