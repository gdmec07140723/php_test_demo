<?php
namespace Home\Model;
use \Frame\Libs\BaseModel;
//定义最终的CommentModel类
final class CommentModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "comment";

	//获取连表查询的评论数据
	public function fetchAllWithJoin($where="2>1")
	{
		$sql = "SELECT comment.*,user.username FROM comment ";
		$sql .= "LEFT JOIN user ON comment.user_id=user.id ";
		$sql .= "WHERE {$where} ORDER BY id DESC";
		return $this->pdo->fetchAll($sql);
	}

	//获取评论的无限级分类数据
	public function commentList($arrs,$pid=0)
	{
		$comments = array();
		foreach($arrs as $arr)
		{
			//先查找顶级评论
			if($arr['pid']==$pid)
			{
				$arr['son'] = $this->commentList($arrs,$arr['id']);
				$comments[] = $arr;
			}
		}
		return $comments;
	}
}