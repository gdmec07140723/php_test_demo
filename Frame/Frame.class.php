<?php
namespace Frame;
//定义最终的核心框架类
final class Frame
{
	//公共的静态的初始化框架的方法
	public static function run()
	{
		self::initCharset(); //初始化字符集
		self::initPhpErr(); //初始化错误显示方式
		self::initConfig(); //初始化配置文件
		self::initRoute(); //解析URL路由参数
		self::initConst(); //初始化目录常量
		self::initAutoLoad(); //注册类的自动加载
		self::initDispatch(); //请求分发
	}

	//私有的静态的设置字符集
	private static function initCharset()
	{
		header("content-type:text/html;charset=utf-8");
		//开启SESSION会话
		session_start();
	}

	//私有的静态的设置PHP错误显示方式
	private static function initPhpErr()
	{
		//修改PHP的脚本级配置：是否显示错误
		ini_set("display_errors","on");
		//修改PHP的脚本配置：显示的错误等级
		ini_set("error_reporting",E_ALL | E_STRICT);
	}

	//私有的静态的初始化配置文件
	private static function initConfig()
	{
		$GLOBALS['config'] = require_once(APP_PATH."Conf".DS."Config.php");
	}

	//私有的静态的解析URL路由参数
	private static function initRoute()
	{
		$p = $GLOBALS['config']['default_platform'];
		$c = isset($_GET['c']) ? $_GET['c'] : $GLOBALS['config']['default_controller'];
		$a = isset($_GET['a']) ? $_GET['a'] : $GLOBALS['config']['default_action'];
		define("PLAT",$p);
		define("CONTROLLER",$c);
		define("ACTION",$a);
	}

	//私有的静态的常量设置
	private static function initConst()
	{
		define("FRAME_PATH",ROOT_PATH."Frame".DS); //Frame目录
		define("VIEW_PATH",APP_PATH."View".DS); //View目录
	}

	//私有的静态的类的自动加载
	private static function initAutoLoad()
	{
		spl_autoload_register(function($className){
			$filename = ROOT_PATH.str_replace("\\",DS,$className).".class.php";
			//判断类文件是否存在，如果存在，则加载
			if(file_exists($filename)) require_once($filename);
		});
	}

	//私有的静态的分发路由
	private static function initDispatch()
	{
		//构建类的完全路径
		$c = "\\".PLAT."\\Controller\\".CONTROLLER."Controller";
		//创建控制器对象
		$controllerObj = new $c();
		//根据用户不同的动作，调用不同的方法
		$a = ACTION;
		$controllerObj->$a();
	}
}