<?php

App::uses('AppController', 'Controller');

class HomesController extends AppController {

    var $name = 'Homes';
    //var $uses=array('SysMenus'); 
    /* 左 */
    function left() {
        $this->layout = 'blank';

        //	$menu = $this->SysMenus->get_sys_menu(0);
        $menu = array(
            '权限管理' => array('users' => '管理员管理', 'roles' => '角色管理', 'permissions' => '权限管理'),
            '商城配置管理' => array('users/shop_site' => '商城配置', 'users/tongbu_site' => '同步Api配置', 'sys_menus' => '系统菜单'),
            '资源管理' => array('e_classes/site' => '资源配置管理', 'e_classes' => '资源分类管理', 'e_pictures' => '资源管理'),
            '商品管理' => array('gcattributes' => '商品属性管理', 'gagroups' => '属性分组管理', 'goods_classes' => '商品分类管理', 'goods_brands' => '商品品牌管理', 'goods_suppliers' => '商品供应商管理', 'goods' => '商品管理', 'goods_colors' => '商品颜色管理'),
            '订单管理' => array('orders/order_site' => '订单配置管理', 'orders' => '订单列表', 'orders/index/1' => '超时订单', 'orders/index/2' => '未确认订单', 'orders/show_refund_list' => '退货单列表', 'orders/show_barter_list' => '换货单列表', 'orders/show_notcollect_list' => '拒收单列表', 'order_third_abnormals' => '异常订单管理', 'orders/order_import' => '团购订单导入'),
            '发票管理' => array('order_invoices' => '发票列表'),
            '会员管理' => array('members' => '会员列表', 'members/add' => '添加会员', 'members/detail' => '余额明细', 'member_comments' => '评论/留言', 'member_points' => '用户积分管理', 'bonus/search' => '红包查询', 'members/search_codes' => '兑换码查询', 'member_groups' => '会员分组管理'),
            '文章管理' => array('cmsclasses' => '文章分类', 'cmscontents' => '文章列表'),
            '团购管理' => array('tuans' => '企业团购'),
            '专题管理' => array('specials' => '专题列表', 'specials/add' => '添加专题'),
            '活动管理' => array('ploy_favourables' => '特惠', 'ploy_code_changes' => '购物兑换码', 'bonus' => '红包管理', 'member_cash_cards' => '礼品卡管理', 'ploys' => '活动管理', 'ploy_rebates' => '满减满折', 'ployfreights' => '免运费', 'ploymarkups' => '加价购', 'ploycombinations' => '组和返', 'ployspikes' => '秒杀', 'ploygroupbuys' => '团购', 'ployemails' => '邮箱折扣', 'ploypoints' => '积分', 'ploycustoms' => '自定义礼包', 'ploy_promotions' => '商品促销信息'),
            '支付管理' => array('payments' => '支付管理列表', 'payments/add' => '添加支付'),
            '物流管理' => array('shippings' => '物流管理列表', 'shippings/add' => '添加物流'),
            '位置管理' => array('positionmanages/index/2' => '模板位置', 'positionmanages/index/1' => '广告位置', 'positionmanages/timer' => '定时位置列表'),
            '邮件/短信管理' => array('email_temps/email_site' => '邮件/短信设置', 'email_temps' => '邮件/短信列表'),
            '第三方登录管理' => array('third_lands' => '第三方登录列表'),
            '定时脚本管理' => array('shell_sites' => '定时脚本列表'),
            '在线客服管理' => array('online_services' => '在线客服列表'),
            '分享设置管理' => array('share_settings' => '分享设置列表'),
            '统计管理' => array('tongji' => '订单汇总', 'tongji/summary' => '网站概况', 'tongji/specials' => '专题分析', 'tongji/goods_top/price' => '商品销售销售额top', 'tongji/goods_top/count' => '商品销售销量top'),
        );
        /* 	//'ploypoints'=>'积分',,'member_points'=>'用户积分管理'
          //获得用户的权限
          $permission = $this->Session->read('User.permission');
          if($this->Session->read('User.id')!=1)
          {
          foreach($menu as $key=>$val)
          {
          foreach($val as $key2=>$val2)
          {
          if(!strpos('.'.$permission,$key2)) unset($menu[$key][$key2]);
          }
          if(!count($menu[$key])) unset($menu[$key]);

          }
          }

          $uid = $this->Session->read('User.id');
          if($uid == 7){
          $menu['车资源']['c_resources/sendyworder'] = '销售订单推送记录';
          }
         */
        $this->set('menu', $menu);
    }

    /* 右 */

    function index() {
        
    }

    /* 上 */

    function head() {
        $this->layout = 'blank';
    }

}
