<?php
namespace Home\Controller;
use \Home\Model\IndexModel;
use \Frame\Libs\BaseController;
use \Home\Model\CategoryModel;
use \Home\Model\LinksModel;
use \Home\Model\ArticleModel;
use \Frame\Vendor\Pager;
use \Home\Model\CommentModel;
//定义IndexController控制器
final class IndexController extends BaseController
{
	//网站首页
	public function index()
	{
		//1.获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAllWithJoin()
		);

		//2.获取友情链接数据
		$links = LinksModel::getInstance()->fetchAll();	
		
		//3.获取文章归档数据
		$dates = ArticleModel::getInstance()->fetchAllWithCount();

		//3.构建查询条件
		$where = "2>1";
		//判断文章分类是否存在
		if(!empty($_GET['category_id'])){
			$where .= " AND category_id=".$_GET['category_id'];
		}
		//判断查询文章标题关键字是否存在
		if(!empty($_POST['title'])){
			$where .= " AND title LIKE '%".$_POST['title']."%'";
		}

		//4.分页参数
		$pagesize	= 5;
		$page		= isset($_GET['page']) ? $_GET['page'] : 1;
		$startrow	= ($page-1)*$pagesize;
		$records	= ArticleModel::getInstance()->rowCount($where);
		
		//5.创建分页类对象
		$params		= array('c'=>'Index','a'=>'index');
		if(isset($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
		if(isset($_POST['title'])) $params['title'] = $_POST['title'];
		$pageObj	= new Pager($records,$pagesize,$page,$params);
		$pagestring	= $pageObj->showPage();

		//6.获取文章数据
		$orderby = "id DESC";
		$articles = ArticleModel::getInstance()->fetchAllWithJoin($where,$orderby,$startrow,$pagesize);

		//7.向模板赋值，并显示视图文件
		$this->smarty->assign(array(
			'categorys'	=> $categorys,
			'links'		=> $links,
			'articles'	=> $articles,
			'pagestring'=> $pagestring,
			'dates'		=> $dates,
		));
		$this->smarty->display("Index/index.html");
	}

	//文章列表显示
	public function showList()
	{
		//1.获取无限级分类数据
		$categorys = CategoryModel::getInstance()->categoryList(
			CategoryModel::getInstance()->fetchAllWithJoin()
		);

		//2.获取友情链接数据
		$links = LinksModel::getInstance()->fetchAll();
		
		//3.获取文章归档数据
		$dates = ArticleModel::getInstance()->fetchAllWithCount();
		
		//3.构建查询条件
		$where = "2>1";
		//判断文章分类是否存在
		if(!empty($_GET['category_id'])){
			$where .= " AND category_id=".$_GET['category_id'];
		}
		//判断查询文章标题关键字是否存在
		if(!empty($_POST['title'])){
			$where .= " AND title LIKE '%".$_POST['title']."%'";
		}

		//4.分页参数
		$pagesize	= 30;
		$page		= isset($_GET['page']) ? $_GET['page'] : 1;
		$startrow	= ($page-1)*$pagesize;
		$records	= ArticleModel::getInstance()->rowCount($where);
		
		//5.创建分页类对象
		$params		= array('c'=>'Index','a'=>'showList');
		if(isset($_GET['category_id'])) $params['category_id'] = $_GET['category_id'];
		if(isset($_POST['title'])) $params['title'] = $_POST['title'];
		$pageObj	= new Pager($records,$pagesize,$page,$params);
		$pagestring	= $pageObj->showPage();

		//6.获取文章数据
		$orderby = "id DESC";
		$articles = ArticleModel::getInstance()->fetchAllWithJoin($where,$orderby,$startrow,$pagesize);

		//7.向模板赋值，并调用视图显示
		$this->smarty->assign(array(
			'categorys'	=> $categorys,
			'links'		=> $links,
			'articles'	=> $articles,
			'pagestring'=> $pagestring,
			'dates'		=> $dates,
		));
		$this->smarty->display("Index/list.html");
	}

	//文章内容显示
	public function detail()
	{
		//1.更新文章阅读数
		$id = $_GET['id'];
		ArticleModel::getInstance()->updateRead($id);
		
		//2.获取一条记录
		$article = ArticleModel::getInstance()->fetchAllWithJoinById($id);

		//3.获取上一篇和下一篇
		$pageArr[] = ArticleModel::getInstance()->fetchOne("id>$id","id asc");//后一篇
		$pageArr[] = ArticleModel::getInstance()->fetchOne("id<$id");//前一篇

		//4.显示评论数据
		$comments = CommentModel::getInstance()->commentList(
			CommentModel::getInstance()->fetchAllWithJoin("article_id=$id")
		);
		
		//5.向模板赋值，并调用视图显示
		$this->smarty->assign(array(
			'article'	=> $article,
			'pageArr'	=> $pageArr,
			'comments'	=> $comments,
		));
		$this->smarty->display("Index/content.html");
	}

	//点赞方法
	public function praise()
	{
		$id = $_GET['id'];
		if(empty($_SESSION['praise']))
		{
			ArticleModel::getInstance()->updatePraise($id);
			$_SESSION['praise'] = 1;
			$this->jump("id={$id}的文章点赞成功！","?c=Index&a=detail&id={$id}");
		}else
		{
			$this->jump("id={$id}的文章已经点赞过啦，不能再进行点赞！","?c=Index&a=detail&id={$id}");
		}
	}

	//发布评论
	public function send()
	{
		//获取表单数据
		$data['article_id']	= $_POST['article_id'];
		$data['pid']		= $_POST['pid'];
		$data['user_id']	= $_SESSION['uid'];
		$data['content']	= $_POST['content'];
		$data['addate']		= time();
		//写入评论数据
		CommentModel::getInstance()->insert($data);

		//文章评论数加1
		ArticleModel::getInstance()->updateCommentCount($data['article_id']);

		//网页跳转
		$this->jump("评论发布成功！","?c=Index&a=detail&id=".$data['article_id']);
	}
}

