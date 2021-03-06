<?php
/*{{{LICENSE
+-----------------------------------------------------------------------+
| SlightPHP Framework                                                   |
+-----------------------------------------------------------------------+
| This program is free software; you can redistribute it and/or modify  |
| it under the terms of the GNU General Public License as published by  |
| the Free Software Foundation. You should have received a copy of the  |
| GNU General Public License along with this program.  If not, see      |
| http://www.gnu.org/licenses/.                                         |
| Copyright (C) 2008-2009. All Rights Reserved.                         |
+-----------------------------------------------------------------------+
| Supports: http://www.slightphp.com                                    |
+-----------------------------------------------------------------------+
}}}*/

if(!defined("SLIGHTPHP_PLUGINS_DIR"))define("SLIGHTPHP_PLUGINS_DIR",dirname(__FILE__));
require_once(SLIGHTPHP_PLUGINS_DIR."/tpl/Tpl.php");
/**
 * @package SlightPHP
 */
class STpl extends SlightPHP\Tpl{
	/**
	 * 设置强制编译
	 */
	public static function setForceCompile($force_compile=false){
		parent::$force_compile=$force_compile;
	}
	/**
	 * 安全模式，禁止编译php代码
	 */
	public static function setSafeMode($safe_mode=true){
		parent::$safe_mode=$safe_mode;
	}
	/**
	 * 设置模板代码的左右分割符号
	 */
	public static function setDelimter($left="{", $right="}"){
		parent::$left_delimiter=$left;
		parent::$right_delimiter=$right;
	}
	/**
	 * 设置模板编译路径
	 */
	public static function setCompileDir($path=""){
		if($path==""){
			parent::$compile_dir = SlightPHP::$appDir.DIRECTORY_SEPARATOR."templates_c";
		}else{
			parent::$compile_dir = $path;
		}
	}
	/**
	 * 设置模板文件路径
	 */
	public static function setTemplateDir($path=""){
		if($path==""){
			parent::$template_dir= SlightPHP::$appDir.DIRECTORY_SEPARATOR."templates";
		}else{
			parent::$template_dir= $path;
		}
	}
	/**
	 * 渲染模板
	 */
	public function render($tpl,$parames=array()){
		if(parent::$template_dir==""){
			self::setTemplateDir();
		}
		if(parent::$compile_dir==""){
			self::setCompileDir();
		}
		if(parent::$left_delimiter=="" || parent::$right_delimiter==""){
			self::setDelimter();
		}
		parent::assign($parames);
		return parent::fetch("$tpl");
	}

	/**
	 * 别名 render
	 */
	public function display($tpl,$parames=array()){
		return $this->render($tpl, $parames);
	}
	/**
	 * 302 redirect
	 */
	public function redirect($url) {
		header('Location:'.$url);
		exit;
	}
}
?>
