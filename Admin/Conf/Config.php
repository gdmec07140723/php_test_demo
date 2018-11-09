<?php
//定义Admin应用配置信息数组
return array(
	/*数据库配置*/
	'db_type'	=> 'mysql',
	'db_host'	=> 'localhost',
	'db_port'	=> '3306',
	'db_user'	=> 'root',
	'db_pass'	=> 'root',
	'db_name'	=> 'Blog',
	'charset'	=> 'utf8',

	//Home默认配置信息
	'default_platform'		=> 'Admin',	//应用名称、平台名称
	'default_controller'	=> 'Index',	//控制器名称
	'default_action'		=> 'index',	//动作名称、方法名称
);



?>