<?php
namespace Frame\Libs;
//定义一个最终的单例的数据库操作类
final class Db{
	//私有的静态的保存对象的属性
	private static $obj = NULL;
	//数据库配置信息
	private $db_host;
	private $db_user;
	private $db_pass;
	private $db_name;
	private $charset;

	//私有的构造方法：阻止类外new对象
	private function __construct()
	{
		$this->db_host = $GLOBALS['config']['db_host'];
		$this->db_user = $GLOBALS['config']['db_user'];
		$this->db_pass = $GLOBALS['config']['db_pass'];
		$this->db_name = $GLOBALS['config']['db_name'];
		$this->charset = $GLOBALS['config']['charset'];
		$this->connMySQL(); //连接数据库
		$this->selectDb();  //选择数据库
		$this->setCharacter(); //设置字符集
	}

	//私有的克隆方法：阻止类外clone对象
	private function __clone(){}

	//公共的静态的创建对象的方法
	public static function getInstance()
	{
		//如果对象存在，直接返回
		if(!self::$obj instanceof self)
		{
			//如果对象不存在，创建对象
			self::$obj = new self();
		}
		return self::$obj;//返回对象
	}

	//私有的连接数据库的方法
	private function connMySQL()
	{
		if(!$link = @mysql_connect($this->db_host,$this->db_user,$this->db_pass))
		{
			exit("PHP连接MySQL服务器失败！");
		}
	}

	//私有的选择数据库的方法
	private function selectDb()
	{
		if(!mysql_select_db($this->db_name))
		{
			exit("选择数据库{$this->db_name}失败！");
		}
	}

	//私有的设置数据库字符集
	private function setCharacter()
	{
		$this->exec("set names {$this->charset}");
	}

	//公共的执行SQL语句的方法：insert、update、delete、set
	public function exec($sql = NULL)
	{
		//转成全小写
		$sql = strtolower($sql);
		//如果是SELECT语句，则中止
		if(substr($sql,0,6)=="select")
		{
			exit("SELECT语句请调用其它方法！");
		}
		//返回执行的结果
		return mysql_query($sql);
	}

	//私有的执行SQL语句的方法：select
	private function query($sql = NULL)
	{
		//转成全小写
		$sql = strtolower($sql);
		//如果不是SELECT语句，则中止
		if(substr($sql,0,6)!="select")
		{
			exit("非SELECT语句请调用其它方法！");
		}
		//返回执行的结果
		return mysql_query($sql);
	}

	//公共的返回多行记录
	public function fetchAll($sql,$type=3)
	{
		//定义常量数组
		$types = array(
			1 => MYSQL_NUM,
			2 => MYSQL_BOTH,
			3 => MYSQL_ASSOC
		);

		//执行SQL语句，返回结果集
		$result = $this->query($sql);

		//循环取出结果集中的多行记录，并构建二维数组
		while($row = mysql_fetch_array($result,$types[$type]))
		{
			$arr[] = $row;
		}

		return $arr; //返回二维数组
	}

	//公共的获取一条记录的方法
	public function fetchOne($sql,$type=3)
	{
		//定义常量数组
		$types = array(
			1 => MYSQL_NUM,
			2 => MYSQL_BOTH,
			3 => MYSQL_ASSOC
		);
		
		//执行SQL语句，返回结果集
		$result = $this->query($sql);
		
		//返回结果(一维数组)
		return mysql_fetch_array($result,$types[$type]);
	}

	//获取总记录数
	public function getRecords($sql)
	{
		$result = $this->query($sql);
		$arr = mysql_fetch_row($result);
		return $arr[0];
	}

	//析构方法
	public function __destruct()
	{
		//关闭数据库连接
		mysql_close();
	}
}

?>