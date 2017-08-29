<?php

App::uses('DepartmentController', 'AppController');
/* 党政部门 */

class DepartmentController extends AppController {

    public $name = 'Department';
    public $uses = array('Department', 'User', 'Position', 'DepartmentCost');
    public $layout = 'blank';

    /* 左 */

    /**
     * 部门管理
     */
    public function index($pages = 1) {

        if ((int) $pages < 1) {
            $pages = 1;
        }
        $limit = 20;
        $total = 0;
        $curpage = 0;
        $all_page = 0;
        $conditions = array(); //获取条件
        $total = $this->Department->find('count', array('conditions' => $conditions));

        $depArr = array();
        if ($total > 0) {
            $all_page = ceil($total / $limit);
            //如果大于最大页数，就让他等于最大页
            if ($pages > $all_page) {
                $pages = $all_page;
            }

            $depArr = array();
            $depArr = $this->Department->query('select dep.*,u.name,tu.name from t_department as dep left join t_user u on dep.user_id = u.id left join t_user tu on dep.sld = tu.id order by dep.id desc limit ' . (($pages - 1) * $limit) . ',' . $limit);
        }
        $this->set('depArr', $depArr);

        $this->set('limit', $limit);       //limit      每页显示的条数
        $this->set('total', $total);      //total      总条数       
        $this->set('curpage', $pages);      //curpage    当前页
        $this->set('all_page', $all_page);
        $this->render();
    }

    /**
     * 部门详情
     */
    public function infos($id = 0) {

        if (!$id && !is_numeric($id)) {
            header("Location:/homes/index");
        }

        $depInfo = $this->Department->findById($id);
        $this->set('depInfo', $depInfo);
        $this->set('pid', $id);

        # 该部门所属成员
        $conditions = array('or' => array('and' => array('del' => 0, 'department_id' => $id), 'id' => array($depInfo['Department']['user_id'], $depInfo['Department']['sld'])));
        $depMember = $this->User->getAlluser(0, 100, $conditions);
        $this->set('depMember', $depMember);
//var_dump($depMember);        
        # 职务
        $posArr = $this->Position->getList();
        $this->set('d_id', $id);
        $this->set('posArr', $posArr);

        // 预算
        /*  暂时取消
          $this->set('costList', Configure::read('xizhenglist'));
          $cost = $this->DepartmentCost->findByDepartmentId($id);
          $cost = @$cost['DepartmentCost'];
          $this->set('cost', $cost);
         */
        // 费用申报
        if ($depInfo['Department']['type'] == 1 && (in_array($this->userInfo->position_id,array(6,13,14)) || $this->userInfo->department_id == $id) ) {
            $declares_arr = $this->DepartmentCost->query("SELECT m.*,u.name FROM t_apply_main m LEFT JOIN t_user u ON m.user_id = u.id WHERE m.department_id = '$id' and m.project_id=0 and m.code = 10000 ");


            $mainArr = array();
            foreach ($declares_arr as $k => $v) {
                $mainArr[$v['m']['table_name']][$v['m']['id']] = $v['m']['attr_id'];
            }

            //取各分表内容
            $attrArr = array();
            foreach ($mainArr as $k => $v) {
                $attrid = implode(',', $v);
                switch ($k) {
                    case 'apply_baoxiaohuizong':  // 报销汇总单
                        $attrinfo = $this->DepartmentCost->query("SELECT b.id,b.subject,b.amount,b.description,s.* FROM t_apply_baoxiaohuizong b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                        break;
                    case 'apply_chuchai_bxd':  // 差旅费报销单
                        $attrinfo = $this->DepartmentCost->query("SELECT b.id,b.reason description,s.* FROM t_apply_chuchai_bxd b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                        break;
                    case 'apply_lingkuandan':  // 领款单
                        $attrinfo = $this->DepartmentCost->query("SELECT b.id,s.* FROM t_apply_lingkuandan b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                        break;
                    case 'apply_jiekuandan':  // 借款单
                        $attrinfo = $this->DepartmentCost->query("SELECT b.id,b.reason description,s.* FROM t_apply_jiekuandan b left join t_research_source s ON b.source_id = s.id  WHERE b.id in($attrid)  ");
                        break;
                }
                if (count($attrinfo) > 0) {
                    foreach ($attrinfo as $atk => $atv) {
                        $attrinfo[$atv['b']['id']] = $atv;
                    }
                    foreach ($v as $atk => $atv) {
                        $attrArr[$atk] = $attrinfo[$atv];
                    }
                }
            }

            //var_dump($mainArr,$attrArr);        
            $this->set('declares_arr', $declares_arr);
            $this->set('attr_arr', $attrArr);
        }
        $this->set('xizhenglist', Configure::read('xizhenglist'));
        $this->set('id', $id);

        $this->render();
    }

    /**
     * 部门编辑
     */
    public function add($id = 0) {

        $conditions = array('del' => 0, 'department_id' => 0);
        if ($id && is_numeric($id)) {
            $depArr = $this->Department->findById($id);
            $this->set('depArr', $depArr);
            #  未指定部门成员
            $members = $this->User->find('list', array('conditions' => $conditions, 'fileds' => array('id', 'name')));
            $this->set('members', $members);

            # 该部门id
            $conditions['department_id'] = $id;
        }
        # 该部门所属成员
        if (!empty($depArr)) {
            //$conditions['position_id'] = array(1,4);//职员，科室主任
            $fuzeren = $this->User->find('all', array('conditions' => $conditions, 'fileds' => array('id', 'name', 'position_id')));

            $this->set('fuzeren', $fuzeren);
        }

        # 分管所领导
        $sld_conditions = array('del' => 0, 'position_id' => array(5, 6,13));
        $suolingdao = $this->User->find('list', array('conditions' => $sld_conditions, 'fileds' => array('id', 'name')));
        $this->set('suolingdao', $suolingdao);
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
                if ($this->Department->edit($id, $delArr)) {
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
            $type = $this->request->data('type');
            $fzr = $this->request->data('fzr');
            $sld = $this->request->data('sld');
            $save_arr = array(
                'name' => $name,
                'description' => $desc,
                'type' => $type,
                'user_id' => $fzr,
                'sld' => $sld,
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
                //先查看部门名是否被占用
                if ($this->Department->findByName($name)) {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '部门名被占用',
                        'class' => '.name'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }
                //save
                if ($this->Department->add($save_arr)) {
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
                if (!($posi_arr = $this->Department->findById($id))) {
                    //如果找不到此部门就让他添加
                    goto ADD;
                }
                //先查看部门名是否被占用
                $name_user_arr = $this->Department->findByName($name);
                if (count($name_user_arr) > 1) {
                    $ret_arr = array(
                        'code' => 1,
                        'msg' => '该部门已添加',
                        'class' => '.name'
                    );
                    echo json_encode($ret_arr);
                    exit;
                }

                if ($this->Department->edit($id, $save_arr)) {
                    /*
                      //修改成功，把本部门的负责人变成科室主任，把之前的科室主任变成职员
                      $this->User->query("update t_user set position_id=1 where department_id='$id' and position_id=4");
                      if (!empty($fzr)) {
                      //部门负责人非空时，把所选的，变为科室主任
                      $this->User->query("update t_user set position_id=4 where id='$fzr'");
                      }

                     */
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
     * ajax 保存添加/修改
     */
    public function ajax_member() {
        $ret_arr = array();
        if ($this->request->is('ajax')) {
            $id = $this->request->data('id');
            $member = $this->request->data('member');
            $save_arr = array(
                'department_id' => $id
            );
            if ($id < 1 || !is_numeric($id)) {
                $ret_arr = array(
                    'code' => 1,
                    'msg' => '参数有误',
                    'class' => ''
                );
                echo json_encode($ret_arr);
                exit;
            }

            if (empty($member)) {
                $ret_arr = array(
                    'code' => 1,
                    'msg' => '请选择成员',
                    'class' => '.members'
                );
                echo json_encode($ret_arr);
                exit;
            }

            if ($this->User->edit($member, $save_arr)) {
                $ret_arr = array(
                    'code' => 0,
                    'msg' => '添加成功',
                    'class' => ''
                );
                echo json_encode($ret_arr);
                exit;
            }
            //失败
            $ret_arr = array(
                'code' => 2,
                'msg' => '添加失败',
                'class' => ''
            );
            echo json_encode($ret_arr);
            exit;
        }

        echo json_encode($ret_arr);
        exit;
    }

}
