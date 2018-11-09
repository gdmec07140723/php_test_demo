<?php
namespace Home\Model;
use \Frame\Libs\BaseModel;
//定义最终的ArticleModel类
final class ArticleModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "article";

	//获取多条连表查询的数据
	public function fetchAllWithJoin($where="2>1",$orderby="id desc",$startrow=0,$pagesize=10)
	{
		//构建连表查询的SQL语句
		$sql = "SELECT article.*,category.classname,user.name FROM {$this->table} ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "WHERE {$where} ";
		$sql .= "ORDER BY {$orderby} ";
		$sql .= "LIMIT {$startrow},{$pagesize}";
		//执行SQL语句，并返回结果
		return $this->pdo->fetchAll($sql);
	}

	//获取一条连表查询的数据
	public function fetchAllWithJoinById($id)
	{
		//构建连表查询的SQL语句
		$sql = "SELECT article.*,category.classname,user.name FROM {$this->table} ";
		$sql .= "LEFT JOIN category ON article.category_id=category.id ";
		$sql .= "LEFT JOIN user ON article.user_id=user.id ";
		$sql .= "WHERE article.id={$id}";
		//执行SQL语句，并返回结果
		return $this->pdo->fetchOne($sql);
	}


	//获取按时间分组的统计数据
	public function fetchAllWithCount()
	{
		//构建查询的SQL语句
		$sql = "SELECT date_format(from_unixtime(addate),'%Y年%m月') AS months,count(id) AS count FROM article GROUP BY months ORDER BY months DESC";
		//执行SQL语句，并返回结果
		return $this->pdo->fetchAll($sql);
	}

	//更新阅读数
	public function updateRead($id)
	{
		$sql = "UPDATE {$this->table} SET `read`=`read`+1 WHERE id={$id}";
		return $this->pdo->exec($sql);
	}

	//更新点赞数
	public function updatePraise($id)
	{
		$sql = "UPDATE {$this->table} SET `praise`=`praise`+1 WHERE id={$id}";
		return $this->pdo->exec($sql);
	}

	//更新文章评论数
	public function updateCommentCount($id)
	{
		$sql = "UPDATE {$this->table} SET `comment_count`=`comment_count`+1 WHERE id={$id}";
		return $this->pdo->exec($sql);
	}
}