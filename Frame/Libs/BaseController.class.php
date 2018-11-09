<?php
namespace Frame\Libs;
use \Frame\Vendor\Smarty;
//定义基础控制器类
abstract class BaseController
{
	//受保护的Smarty对象属性
	protected $smarty = null;
	//构造方法：初始化Smarty对象
	public function __construct()
	{
		$this->initSmarty();
	}
	//初始化Smarty对象
	protected function initSmarty()
	{
		//创建Smarty对象，并进行配置
		$smarty = new Smarty();
		$smarty->left_delimiter = "<{";
		$smarty->right_delimiter = "}>";
		$smarty->setTemplateDir(VIEW_PATH);
		$smarty->setCompileDir(sys_get_temp_dir().DS."view_c");
		//给$smarty属性赋值
		$this->smarty = $smarty;
	}

	//页面跳转方法
	protected function jump($message,$url='?',$time=3)
	{
		$this->smarty->assign(array(
			'message'	=> $message,
			'url'		=> $url,
			'time'		=> $time,
		));
		$this->smarty->display("public/jump.html");
		exit();
	}

	//用户访问权限
	public function denyAccess()
	{
		//如果用户没有登录，则跳转到登录页面
		if(!isset($_SESSION['username']))
			$this->jump("你还没有登录，请先登录！","?c=User&a=login");
	}
}