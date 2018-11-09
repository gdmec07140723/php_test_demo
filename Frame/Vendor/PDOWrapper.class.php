<?php
/******************************封装PDO类****************************/
namespace Frame\Vendor;
use \PDO;
use \PDOException;
//定义一个PDOWrapper类
final class PDOWrapper{
	//数据库配置信息属性
	private $db_type; //数据库类型
	private $db_host; //主机
	private $db_port; //端口号
	private $db_user; //用户名
	private $db_pass; //密码
	private $db_name; //数据库名
	private $charset; //字符集
	private $pdo; //保存PDO对象
	//构造方法：初始化数据库连接等信息
	public function __construct(){
		//数据库基本数据初始化
		$this->db_type = $GLOBALS['config']['db_type'];
		$this->db_host = $GLOBALS['config']['db_host'];
		$this->db_port = $GLOBALS['config']['db_port'];
		$this->db_user = $GLOBALS['config']['db_user'];
		$this->db_pass = $GLOBALS['config']['db_pass'];
		$this->db_name = $GLOBALS['config']['db_name'];
		$this->charset = $GLOBALS['config']['charset'];
		$this->connectDb(); //连接数据库
		$this->setErrMode(); //PDO错误报告模式
	}
	//连接数据库方法
	private function connectDb(){
		try{
			//PDO认证信息
			$dsn = "{$this->db_type}:host={$this->db_host};port={$this->db_port};";
			$dsn .= "dbname={$this->db_name};charset={$this->charset}";
			//创建PDO对象
			$this->pdo = new PDO($dsn,$this->db_user,$this->db_pass);
		}catch(PDOException $e){
			echo "<h2>数据库连接失败！</h2>";
			echo "错误编号：".$e->getCode();
			echo "<br />错误行号：".$e->getLine();
			echo "<br />错误文件：".$e->getFile();
			echo "<br />错误信息：".$e->getMessage();
			exit();
		}
	}
	//设置PDO错误报告模式为异常模式
	private function setErrMode(){
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	//执行SQL语句：增删改
	public function exec($sql){
		try{
			return $this->pdo->exec($sql);
		}catch(PDOException $e){
			$this->showError($e);
		}
	}
	//执行SQL语句：查询一条数据
	public function fetchOne($sql){
		try{
			$PDOStatement = $this->pdo->query($sql);
			return $PDOStatement->fetch(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			$this->showError($e);
		}
	}

	//执行SQL语句：查询多条数据
	public function fetchAll($sql){
		try{
			$PDOStatement = $this->pdo->query($sql);
			return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
		}catch(PDOException $e){
			$this->showError($e);
		}
	}
	//获取查询的记录数
	public function rowCount($sql){
		try{
			$PDOStatement = $this->pdo->query($sql);
			return $PDOStatement->rowCount();
		}catch(PDOException $e){
			$this->showError($e);
		}
	}
	//获取最后插入成功记录的id
	public function lastInsertId(){
		return $this->pdo->lastInsertId();
	}
	//错误处理方法
	private function showError($e){
		echo "<h2>SQL语句出错！</h2>";
		echo "错误编号：".$e->getCode();
		echo "<br />错误行号：".$e->getLine();
		echo "<br />错误文件：".$e->getFile();
		echo "<br />错误信息：".$e->getMessage();
		exit();
	}
}

?>