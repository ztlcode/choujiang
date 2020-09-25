<?php
require_once("global.php");

SlightPHP::setDebug(true);
SlightPHP::setAppDir(ROOT_APP);
SlightPHP::setDefaultZone("index");
SlightPHP::setDefaultPage("main");
SlightPHP::setDefaultEntry("entry");
SlightPHP::setSplitFlag("-_.");

SDb::setConfigFile(ROOT_CONFIG."/db.conf");
if(($r=SlightPHP::run())===false){
	echo("404 error");
}elseif(is_object($r) || is_array($r)){
	echo SJson::encode($r);
}else{
	echo($r);
}
