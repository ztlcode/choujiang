<?php 
class utility_weixin
{
    public static function decryptData( $appid, $sessionKey, $encryptedData, $iv, &$data )
    {
        if (strlen($sessionKey) != 24) {
            return -41001;
        }
        $aesKey = base64_decode($sessionKey);

        if (strlen($iv) != 24) {
            return -41002;
        }
        $aesIV = base64_decode($iv);
        $aesCipher = base64_decode($encryptedData);
        $result = openssl_decrypt( $aesCipher, "AES-128-CBC", $aesKey, 1, $aesIV);
        $dataObj=json_decode( $result );
        if( $dataObj  == NULL ){
            return -41003;
        }
        if( $dataObj->watermark->appid != $appid ){
            return -41004;
        }
        $data = json_decode($result, true);

        return true;
    }

    public static function xmlToArray($xml)
    {

        $data = json_decode(json_encode(simplexml_load_string($xml, 'SimpleXMLElement', LIBXML_NOCDATA)), true);
	return $data;
    }

    public static function arrayToXml($arr){
	    $xml = "<xml>";
	    foreach ($arr as $key=>$val)
	    {
		    if (is_numeric($val))
		    {
			    $xml.="<".$key.">".$val."</".$key.">";

		    }else{
			    $xml.="<".$key."><![CDATA[".$val."]]></".$key.">";
		    }
	    }
	    $xml.="</xml>";
	    return $xml;
    }



    public static function createNoncestr($length=32)
    {
        $chars = "abcdefghijklmnopqrstuvwxyz0123456789";
        $str = "";
        for ($i = 0; $i < $length; $i++) {
            $str .= substr($chars, mt_rand(0, strlen($chars) - 1), 1);
        }
        return $str;
    }

    public static function formatBizQueryParaMap($paraMap, $urlencode) 
    {
        $buff = "";
        ksort($paraMap);
        foreach ($paraMap as $k => $v) {
            if ($urlencode) {
                $v = urlencode($v);
            }
            $buff .= $k . "=" . $v . "&";
        }
        
        if (strlen($buff) > 0) {
            $reqPar = substr($buff, 0, strlen($buff) - 1);
        }

        return $reqPar;
    }
}
