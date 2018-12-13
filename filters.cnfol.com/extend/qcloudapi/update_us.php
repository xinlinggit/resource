<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once 'src/QcloudApi/QcloudApi.php';


/**
 * 视频转码
 */
 function videocode($qq_field)
{
    $COMMON_PARAMS = array(
       'Nonce'=> rand(),
       'Timestamp'=>time(),
       'Action'=>'GetVideoInfo',
       'SecretId'=> 'AKIDsXSEnEDOjvxENVFrrNp8QXNH1cL9D5OY',
       'Region' =>'gz',
       'fileId'=>$qq_field,
       'infoFilter.0'=>'transcodeInfo',
    );
    $PRIVATE_PARAMS = array(
       
    );
    $rst=CreateRequest('vod.api.qcloud.com','GET',$COMMON_PARAMS,'FNQxIWfeVS2tqx43ucCoNo6GF6tbGxXy', $PRIVATE_PARAMS, true);
    return $rst;
} 

	
function CreateRequest($HttpUrl,$HttpMethod,$COMMON_PARAMS,$secretKey, $PRIVATE_PARAMS, $isHttps)
{
    $FullHttpUrl = $HttpUrl."/v2/index.php";

    /***************对请求参数 按参数名 做字典序升序排列，注意此排序区分大小写*************/
    $ReqParaArray = array_merge($COMMON_PARAMS, $PRIVATE_PARAMS);
    ksort($ReqParaArray);

    /**********************************生成签名原文**********************************
     * 将 请求方法, URI地址,及排序好的请求参数  按照下面格式  拼接在一起, 生成签名原文，此请求中的原文为
     * GETcvm.api.qcloud.com/v2/index.php?Action=DescribeInstances&Nonce=345122&Region=gz
     * &SecretId=AKIDz8krbsJ5yKBZQ    ·1pn74WFkmLPx3gnPhESA&Timestamp=1408704141
     * &instanceIds.0=qcvm12345&instanceIds.1=qcvm56789
     * ****************************************************************************/
    $SigTxt = $HttpMethod.$FullHttpUrl."?";

    $isFirst = true;
    foreach ($ReqParaArray as $key => $value)
    {
        if (!$isFirst)
        {
            $SigTxt = $SigTxt."&";
        }
        $isFirst= false;

        /*拼接签名原文时，如果参数名称中携带_，需要替换成.*/
        if(strpos($key, '_'))
        {
            $key = str_replace('_', '.', $key);
        }

        $SigTxt=$SigTxt.$key."=".$value;
    }

    /*********************根据签名原文字符串 $SigTxt，生成签名 Signature******************/
    $Signature = base64_encode(hash_hmac('sha1', $SigTxt, $secretKey, true));


    /***************拼接请求串,对于请求参数及签名，需要进行urlencode编码********************/
    $Req = "Signature=".urlencode($Signature);
    foreach ($ReqParaArray as $key => $value)
    {
        $Req=$Req."&".$key."=".urlencode($value);
    }

    /*********************************发送请求********************************/
    if($HttpMethod === 'GET')
    {
        if($isHttps === true)
        {
            $Req="https://".$FullHttpUrl."?".$Req;
        }
        else
        {
            $Req="http://".$FullHttpUrl."?".$Req;
        }
          // echo $Req;die;
        $Rsp = file_get_contents($Req);

    }
    else
    {
        if($isHttps === true)
        {
            $Rsp= SendPost("https://".$FullHttpUrl,$Req,$isHttps);
        }
        else
        {
            $Rsp= SendPost("http://".$FullHttpUrl,$Req,$isHttps);
        }
    }

    return  json_decode($Rsp,true);
}

function SendPost($FullHttpUrl,$Req,$isHttps)
{

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $Req);

        curl_setopt($ch, CURLOPT_URL, $FullHttpUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if ($isHttps === true) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,  false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,  false);
        }

        $result = curl_exec($ch);

        return $result;
}


function cpost($url,$params = array())
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , 1 );
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded"));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_TIMEOUT,5);
    if ($params) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($params));
    }
    $body=curl_exec($ch);
    if ($body === false) {
        echo "CURL Error: " . curl_error($body);
        return false;
    }else{
        curl_close($ch);
        return $body;
    }
}
