<?php
class index_language{
	function pageEntry($inPath){
		SLanguage::setLanguageDir(SlightPHP::$appDir."/../locale");
		SLanguage::setLocale("en");
		echo SLanguage::tr("name","main");
		SLanguage::setLocale("zh-cn");
		echo SLanguage::tr("name","main");
	}
}
?>
