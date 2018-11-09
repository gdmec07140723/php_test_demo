<?php
namespace Frame\Libs;
use \Frame\Vendor\PDOWrapper;
//定义抽象类的基础模型类
abstract class BaseModel
{
	//私有的静态的保存模型类对象数组属性
	protected static $modelObjArr = array();
	//受保护的PDO对象属性
	protected $pdo = NULL;

	//构造方法：创建PDOWrapper对象
	public function __construct()
	{
		$this->pdo = new PDOWrapper();
	}
	
	//创建模型类对象的方法
	public static function getInstance()
	{
		//获取静态化调用方式的类名
		$className = get_called_class();
		//判断模型对象是否存在，如果不存在，则创建它
		if(!isset(self::$modelObjArr[$className])){
			self::$modelObjArr[$className] = new $className();
		}
		//返回模型类对象
		return self::$modelObjArr[$className];
	}

	//获取无限级分类数据
	public function categoryList($arrs,$level=0,$pid=0)
	{
		//静态变量保存结果数组
		static $categorys = array();
		
		//将$arrs中pid=0的数据先找出来
		foreach($arrs as $arr)
		{
			if($arr['pid']==$pid)
			{
				$arr['level'] = $level;
				$categorys[] = $arr;
				//递归调用：实现无限级分类
				$this->categoryList($arrs,$level+1,$arr['id']);
			}
		}
		return $categorys;
	}

	//获取一条记录
	public function fetchOne($where="2>1",$orderby='id desc')
	{
		$sql = "SELECT * FROM {$this->table} WHERE $where ORDER BY {$orderby} limit 1";
		return $this->pdo->fetchOne($sql);
	}

	//获取多行数据
	public function fetchAll($where='2>1',$orderby='id ASC')
	{
		$sql = "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderby}";
		return $this->pdo->fetchAll($sql);
	}

	//插入数据
	public function insert($data)
	{
		//构建字段列表和数据列表字符串
		$fields = "";
		$values = "";
		foreach($data as $key=>$value){
			$fields .= "$key,";
			$values .= "'$value',";
		}
		//去除左侧逗号
		$fields = rtrim($fields,",");
		$values = rtrim($values,",");
		//构建插入的SQL语句
		$sql = "INSERT INTO {$this->table}($fields) VALUES($values)";
		return $this->pdo->exec($sql);
	}

	//更新记录
	public function update($data,$id)
	{
		//构建更新的字段列表字符串
		$str = "";
		foreach($data as $key=>$value)
		{
			$str .= "$key='$value',";
		}
		//去掉字符串结尾的逗号
		$str = rtrim($str,",");
		//构建更新的SQL语句
		$sql = "UPDATE {$this->table} SET {$str} WHERE id={$id}";
		//执行SQL语句，并返回执行结果
		return $this->pdo->exec($sql);
	}

	//删除记录
	public function delete($id)
	{
		$sql = "DELETE FROM {$this->table} WHERE id=$id";
		return $this->pdo->exec($sql);
	}


	//获取记录数
	public function rowCount($where="2>1")
	{
		$sql = "SELECT * FROM {$this->table} WHERE {$where}";
		return $this->pdo->rowCount($sql);
	}


}



