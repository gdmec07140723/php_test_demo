<?php
namespace Frame\Vendor;
//定义图片验证码类
final class Captcha
{
	//成员属性
	private $code;			//验证码字符串
	private $codelen;		//验证码长度
	private $width;			//宽度
	private $height;		//高度
	private $img;			//图像资源句柄
	private $fontfile;		//字体文件
	private $fontsize;		//字体大小
	private $fontcolor;		//字体颜色

	//构造方法
	public function __construct($codelen=4,$width=85,$height=40,$fontsize=20)
	{
		$this->codelen	= $codelen; //验证码字符串长度
		$this->width	= $width; //图像宽度
		$this->height	= $height; //图像高度
		$this->fontfile = "./Public/Admin/Images/msyh.ttf"; //字体文件
		$this->fontsize = $fontsize;
        $this->createCode(); //创建验证码字符串
		$this->createImg(); //创建图像资源
        $this->createBg(); //创建背景颜色
        $this->createFont(); //绘制文本
        $this->outPut(); //输出图像
	}

	//创建图像资源
	private function createImg()
	{
		$this->img = imagecreatetruecolor($this->width,$this->height);
	}

	//生成验证码
	private function createCode()
	{
		$str = "";
		$arr = array_merge(range("a","z"),range("A","Z"),range(0,9));
		shuffle($arr);
		shuffle($arr);
		$arr_index = array_rand($arr,4);
		shuffle($arr_index);
		//生成随机字符串
		foreach($arr_index as $i)
		{
			$str .= $arr[$i];
		}
		//将字符串赋给$code属性
		$this->code = $str;
	}

	//生成背景
	private function createBg()
	{
		//随机背景色
		$color = imagecolorallocate($this->img, mt_rand(157,255), mt_rand(157,255), mt_rand(157,255));
		//绘制矩形
        imagefilledrectangle($this->img,0,0,$this->width,$this->height,$color);
	}

	//创建文字
	private function createFont()
	{
		$this->fontcolor = imagecolorallocate($this->img,mt_rand(0,156),mt_rand(0,156),mt_rand(0,156));
		imagettftext($this->img,$this->fontsize,0,5,30,$this->fontcolor,$this->fontfile,$this->code);
	}
	//输出
	private function outPut()
	{
		header('Content-type:image/png');
		imagepng($this->img);
		imagedestroy($this->img);
	}
	//获取验证码
	public function getCode()
	{
		return strtolower($this->code);
	}
}