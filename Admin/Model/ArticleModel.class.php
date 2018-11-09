<?php
namespace Admin\Model;
use \Frame\Libs\BaseModel;
//定义ArticleModel模型类
final class ArticleModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "article";

	//文章表连接分类表
	public function fetchAllWithJoin($where="2>1",$orderby="id desc",$startrow=0,$pagesize=10)
	{
		//构建连表查询的SQL语句
		$sql = "SELECT article.*,category.classname,user.username FROM article ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY {$orderby} LIMIT {$startrow},{$pagesize}";
		//执行SQL语句，并返回结果
		return $this->pdo->fetchAll($sql);
	}
}