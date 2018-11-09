<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CategoryModel;
use \Admin\Model\ArticleModel;
use \Frame\Vendor\Pager;
//定义ArticleController控制器类
final class ArticleController extends BaseController
{
	//显示文章列表
	public function index()
	{
		$this->denyAccess();
		//获取文章分类数据
		$categoryModelObj = CategoryModel::getInstance();
		$categorys = $categoryModelObj->fetchAll();
		$categorys = $categoryModelObj->categoryList($categorys);
		//查询条件
		$where = "2>1";
		if(!empty($_POST['category_id']))
			$where .= " AND category_id=".$_POST['category_id'];
		if(!empty($_POST['keyword']))
			$where .= " AND title like '%".$_POST['keyword']."%'";

		//分页参数
		$pagesize = 10;
		$page = isset($_GET['page']) ? $_GET['page'] : 1;
		$startrow = ($page-1)*$pagesize;
		$records = ArticleModel::getInstance()->rowCount($where);

		//获取分页代码
		$params = array(
			'c'	=> 'Article',
			'a'	=> 'index',
		);
		$pageObj = new Pager($records,$pagesize,$page,$params);
		$pageStr = $pageObj->showPage();

		//获取分页文章数据
		$articleModelObj = ArticleModel::getInstance();
		$orderby = "id desc";
		$articles = $articleModelObj->fetchAllWithJoin($where,$orderby,$startrow,$pagesize);

		//向模板赋值，并调用视图显示
		$this->smarty->assign(array(
			'categorys'	=> $categorys,
			'articles'	=> $articles,
			'pageStr'	=> $pageStr,
		));
		$this->smarty->display("Article/index.html");
	}

	//添加文章
	public function add()
	{
		$this->denyAccess();
		//获取文章分类数据
		$categoryModelObj = CategoryModel::getInstance();
		$categorys = $categoryModelObj->fetchAll();
		$categorys = $categoryModelObj->categoryList($categorys);
		//向模板赋值，并调用视图显示
		$this->smarty->assign("categorys",$categorys);
		$this->smarty->display("Article/add.html");
	}

	//插入文章
	public function insert()
	{
		//获取表单数据
		$data['category_id']	= $_POST['category_id'];
		$data['user_id']		= $_SESSION['uid'];
		$data['title']			= $_POST['title'];
		$data['orderby']		= $_POST['orderby'];
		if(isset($_POST['top'])){
			$data['top'] = 1;
		}else{
			$data['top'] = 0;
		}
		$data['content']		= $_POST['content'];
		$data['addate']			= time();
		//调用模型类对象写入数据
		ArticleModel::getInstance()->insert($data);
		//跳转到列表页
		$this->jump("文章添加成功！","?c=Article");
	}

	//编辑文章
	public function edit()
	{
		$this->denyAccess();
		//获取一行记录
		$id = $_GET['id'];
		$article = ArticleModel::getInstance()->fetchOne("id={$id}");
		//获取文章分类数据
		$categoryModelObj = CategoryModel::getInstance();
		$categorys = $categoryModelObj->fetchAll();
		$categorys = $categoryModelObj->categoryList($categorys);
		//向模板赋值，并调用视图显示
		$this->smarty->assign(array(
			'article'   => $article,
			"categorys" => $categorys,
		));
		$this->smarty->display("Article/edit.html");
	}

	//更新文章
	public function update()
	{
		$this->denyAccess();
		//获取表单数据
		$id = $_POST['id'];
		$data['category_id']	= $_POST['category_id'];
		$data['title']			= $_POST['title'];
		$data['orderby']		= $_POST['orderby'];
		if(isset($_POST['top'])){
			$data['top'] = 1;
		}else{
			$data['top'] = 0;
		}
		$data['content']		= $_POST['content'];
		//调用模型类对象的更新方法
		ArticleModel::getInstance()->update($data,$id);
		//跳转到列表页
		$this->jump("id={$id}的记录更新成功！","?c=Article");
	}

//删除文章
public function delete()
{
	$id = $_GET['id'];
	ArticleModel::getInstance()->delete($id);
	$this->jump("id={$id}的记录删除成功！","?c=Article");
}
}