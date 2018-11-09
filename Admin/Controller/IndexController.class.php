<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
//定义IndexController控制器类
final class IndexController extends BaseController
{
	//框架首页
	public function index()
	{
		$this->denyAccess();
		$this->smarty->display("Index/index.html");
	}

	//顶部框架页
	public function top()
	{
		$this->denyAccess();
		$this->smarty->display("Index/top.html");
	}

	//左侧框架页
	public function left()
	{
		$this->denyAccess();
		$this->smarty->display("Index/left.html");
	}

	//中部框架页
	public function center()
	{
		$this->denyAccess();
		$this->smarty->display("Index/center.html");
	}

	//主框架页
	public function main()
	{
		$this->denyAccess();
		$this->smarty->display("Index/main.html");
	}
}

