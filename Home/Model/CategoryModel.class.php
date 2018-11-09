<?php
namespace Home\Model;
use \Frame\Libs\BaseModel;
//定义最终的CategoryModel类
final class CategoryModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "category";

	//获取连表查询的数据
	public function fetchAllWithJoin()
	{
		//构建查询的SQL语句
		$sql = "SELECT category.*,count(article.id) AS article_count FROM category ";
		$sql .= "LEFT JOIN article ON category.id=article.category_id ";
		$sql .= "GROUP BY category.id";
		//执行SQL语句，并返回结果
		return $this->pdo->fetchAll($sql);
	}
}