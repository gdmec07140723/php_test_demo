<?php
namespace Admin\Controller;
use \Admin\Model\UserModel;
use \Frame\Libs\BaseController;
use \Frame\Vendor\Captcha;
//定义UserController类
final class UserController extends BaseController
{
	//用户列表
	public function index()
	{
		$this->denyAccess();
		$modelObj = UserModel::getInstance();
		$users = $modelObj->fetchAll("2>1","id ASC");
		$this->smarty->assign(array(
			'users' => $users,	
		));
		$this->smarty->display('User/index.html');
	}

    //添加用户表单(普通用户注册)
    public function register()
    {
        $this->smarty->display("User/register.html");
    }

    //插入用户(普通用户注册)
    public function insert_register()
    {
        //获取表单数据
        $data['username']	= $_POST['username'];
        $data['password']	= md5($_POST['password']);
        $data['name']		= $_POST['name'];
        $data['tel']		= $_POST['tel'];
        $data['status']		= $_POST['status'];
        $data['role']		= $_POST['role'];
        $data['addate']		= time();

        //创建模型类对象
        $modelObj = UserModel::getInstance();

        //查询用户是已经存在
        $records = $modelObj->rowCount("username='".$data['username']."'");
        if($records){
            $this->jump("用户{$data['username']}已经被注册了！","?c=User");
        }

        //调用模型对象的插入数据方法
        $modelObj->insert($data);
        //跳转到用户首页
        $this->jump("用户{$data['username']}注册成功！","?c=User");
    }

	//添加用户表单(后台添加)
	public function add()
	{
		$this->denyAccess();
		$this->smarty->display("User/add.html");
	}

	//插入用户(后台添加)
	public function insert()
	{
		$this->denyAccess();
		//获取表单数据
		$data['username']	= $_POST['username'];
		$data['password']	= md5($_POST['password']);
		$data['name']		= $_POST['name'];
		$data['tel']		= $_POST['tel'];
		$data['status']		= $_POST['status'];
		$data['role']		= $_POST['role'];
		$data['addate']		= time();

		//创建模型类对象
		$modelObj = UserModel::getInstance();

		//查询用户是已经存在
		$records = $modelObj->rowCount("username='".$data['username']."'");
		if($records){
			$this->jump("用户{$data['username']}已经被注册了！","?c=User");
		}

		//调用模型对象的插入数据方法
		$modelObj->insert($data);
		//跳转到用户首页
		$this->jumping("用户{$data['username']}添加成功！","?c=index");
	}

	//编辑用户
	public function edit()
	{
		$this->denyAccess();
		//获取用户id号
		$id = $_GET['id'];
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//调用模型类对象的查询数据方法
		$user = $modelObj->fetchOne("id=$id");
		//向模板赋值，并调用视图
		$this->smarty->assign("user",$user);
		$this->smarty->display("User/edit.html");
	}
	
	//更新用户
	public function update()
	{
		$this->denyAccess();
		//获取表单提交值
		$id	= $_POST['id'];

		//如果密码为空，则保留原始密码
		if(!empty($_POST['password'])){
			$data['password']	= md5($_POST['password']);
		}
		$data['name']		= $_POST['name'];
		$data['tel']		= $_POST['tel'];
		$data['status']		= $_POST['status'];
		$data['role']		= $_POST['role'];

		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//调用模型类对象的更新数据方法
		$modelObj->update($data,$id);
		//跳转到用户列表
		$this->jump("id={$id}的记录更新成功！","?c=User");
	}

	//删除用户
	public function delete()
	{
		$this->denyAccess();
		//获取用户id号
		$id = $_GET['id'];
		//创建模型类对象
		$modelObj = UserModel::getInstance();
		//调用模型类对象的删除数据方法
		$modelObj->delete($id);
		//跳转到用户首页
		$this->jump("id={$id}的用户删除成功！","?c=User");
	}
	
	//用户登录表单
	public function login()
	{
		$this->smarty->display("User/login.html");
	}

	//用户登录检测
	public function loginCheck()
	{
		//1.获取表单提交值
		$username = $_POST['username'];
		$password = md5($_POST['password']);
//		$verify   = $_POST['verify'];
		$data['last_login_ip']		= $_SERVER['REMOTE_ADDR'];
		$data['last_login_time']	= time();
		
		//2.检测验证码是否正确
//		if($verify != $_SESSION['captcha'])
//		{
//			$this->jump("验证码输入有误！","?c=User&a=login");
//		}
		//3.检测用户是否存在
		$modelObj = UserModel::getInstance();
		$user = $modelObj->fetchOne("username='$username' and password='$password'");
		if(empty($user)){
			$this->jump("用户名或密码不正确！","?c=User&a=login");
		}
		//4.更新用户资料
		$modelObj->loginUpdate($data,$user['id']);
		//5.保存用户信息到SESSION
		$_SESSION['uid'] = $user['id'];
		$_SESSION['username'] = $username;
		//6.跳转到后台管理首页
		$this->jump("用户{$username}登录成功，即将进入后台管理！");
	}

	//用户登录
	public function logout()
	{
		unset($_SESSION['username']);
		unset($_SESSION['uid']);
		session_destroy();
		$this->jump("退出成功！","?c=User&a=login");
	}

	//获取验证码
	public function captcha()
	{
		//生成验证码图片
		$c = new Captcha();
		//将验证码字符串，保存到SESSION中
		$_SESSION['captcha'] = $c->getCode();
	}
}