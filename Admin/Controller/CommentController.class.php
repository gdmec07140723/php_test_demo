<?php
namespace Admin\Controller;
use \Frame\Libs\BaseController;
use \Admin\Model\CommentModel;
//定义最终的CommentController类
final class CommentController extends BaseController
{
	//显示评论
	public function index()
	{
		//获所有的评论数据
		$comments = CommentModel::getInstance()->fetchAllWithJoin();
		//向模板赋值，并调用视图显示
		$this->smarty->assign("comments",$comments);
		$this->smarty->display("Comment/index.html");
	}

	//删除评论
	public function delete()
	{
		$id = $_GET['id'];
		CommentModel::getInstance()->delete($id);
		$this->jump("id={$id}的评论删除成功！","?c=Comment");
	}
}