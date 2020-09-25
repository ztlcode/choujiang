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
/**
 * @package SlightPHP
 */
abstract class SRestServer{
	private $_allowMethods=array("PUT","POST","GET","HEAD","DELETE","OPTIONS");
	private $_method="GET";
	public function __call($name,$arguments){
		if(!empty($_SERVER['REQUEST_METHOD']) && in_array($_SERVER['REQUEST_METHOD'],$this->_allowMethods)){
			$this->_method = $_SERVER['REQUEST_METHOD'];
		}
		$method=$this->_method.$arguments[0][2];
		if(method_exists($this,$method)){
			return call_user_func(array($this,$method),$arguments[0]);
		}else{
			return false;
		}
	}
}
