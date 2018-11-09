<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\LinksModel;
//定义LinksController类
final class LinksController extends BaseController
{
	//显示列表
	public function index()
	{
		$links = LinksModel::getInstance()->fetchAll();
		$this->smarty->assign("links",$links);
		$this->smarty->display("Links/index.html");
	}

	//添加链接
	public function add()
	{
		$this->smarty->display("Links/add.html");
	}

	//插入链接
	public function insert()
	{
		$data['domain']		= $_POST['domain'];
		$data['url']		= $_POST['url'];
		$data['orderby']	= $_POST['orderby'];
		LinksModel::getInstance()->insert($data);
		$this->jump("友情链接添加成功！","?c=Links");
	}

	//编辑链接
	public function edit()
	{
		$id = $_GET['id'];
		$link = LinksModel::getInstance()->fetchOne("id=$id");
		$this->smarty->assign("link",$link);
		$this->smarty->display("Links/edit.html");
	}

	//更新链接
	public function update()
	{
		$id = $_POST['id'];
		$data['domain']		= $_POST['domain'];
		$data['url']		= $_POST['url'];
		$data['orderby']	= $_POST['orderby'];
		LinksModel::getInstance()->update($data,$id);
		$this->jump("id={$id}的记录修改成功！","?c=Links");
	}

	//删除链接
	public function delete()
	{
		$id = $_GET['id'];
		LinksModel::getInstance()->delete($id);
		$this->jump("id={$id}的记录删除成功！","?c=Links");
	}
}