<?php
namespace Admin\Model;
use \Frame\Libs\BaseModel;
//定义UserModel类
final class UserModel extends BaseModel
{
	//受保护的数据表名称
	protected $table = "user";

	//登录更新
	public function loginUpdate($data,$id)
	{
		//构建更新的字符串
		$str = "";
		foreach($data as $key=>$value){
			$str .= "$key='$value',";
		}
		//更新登录次数
		$str .= "login_times=login_times+1";
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET {$str} WHERE id={$id}";
		return $this->pdo->exec($sql);
	}

}