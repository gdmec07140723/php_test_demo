<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CategoryModel;
//定义最终的CategoryController类
final class CategoryController extends BaseController
{
	//显示列表
	public function index()
	{
		$this->denyAccess();
		//创建模型类对象
		$modelObj = CategoryModel::getInstance();
		//获取多行数据
		$arrs = $modelObj->fetchAll("2>1","id ASC");
		//获取无限级分类数据
		$categorys = $modelObj->categoryList($arrs);
		//向模板赋值，并显示视图文件
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->display("Category/index.html");
	}

	//添加分类
	public function add()
	{
		$this->denyAccess();
		//创建模型类对象
		$modelObj = CategoryModel::getInstance();
		//获取分类数据
		$arrs = $modelObj->fetchAll();
		//获取无限级分类数据
		$categorys = $modelObj->categoryList($arrs);
		//向模板赋值
		$this->smarty->assign("categorys",$categorys);
		//显示视图文件
		$this->smarty->display("Category/add.html");
	}
	
	//插入分类
	public function insert()
	{
		$this->denyAccess();
		//获取表单数据
		$data['classname']	= $_POST['classname'];
		$data['orderby']	= $_POST['orderby'];
		$data['pid']		= $_POST['pid'];
		//创建模型类对象
		$modelObj = CategoryModel::getInstance();
		//调用模型类对象方法写入数据
		$modelObj->insert($data);
		//跳转到列表页
		$this->jump("分类添加成功！","?c=Category");
	}

	//编辑分类
	public function edit()
	{
		$this->denyAccess();
		$id = $_GET['id'];
		$modelObj = CategoryModel::getInstance();
		//获取一行数据
		$arr = $modelObj->fetchOne($id);
		//获取无限级分类数据
		$categorys = $modelObj->fetchAll();
		$categorys = $modelObj->categoryList($categorys);
		//向模板赋值，并显示视图文件
		$this->smarty->assign(array(
			'arr'		=> $arr,
			'categorys'	=> $categorys,
		));
		$this->smarty->display("Category/edit.html");
	}

	//更新分类
	public function update()
	{
		$this->denyAccess();
		//获取表单数据
		$id					= $_POST['id'];
		$data['classname']	= $_POST['classname'];
		$data['orderby']	= $_POST['orderby'];
		$data['pid']		= $_POST['pid'];
		//调用模型类对象更新数据
		CategoryModel::getInstance()->update($data,$id);
		//跳转到列表页
		$this->jump("id={$id}的分类更新成功！","?c=Category&a=index");
	}

	//删除分类
	public function delete()
	{
		$this->denyAccess();
		$id = $_GET['id'];
		CategoryModel::getInstance()->delete($id);
		$this->jump("id={$id}的分类删除成功！","?c=Category");
	}


}