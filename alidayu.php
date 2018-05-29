<?php
阿里大于
	/**
     * 发送验证码    
     * @param $sender 接收人
     * @param $type 发送类型
     * @return json
     */
    public function send_code(){
    	if(IS_AJAX){
    		$time = time();
	    	$mobile = $this->_post('mobile','trim');
	    	//$mobile = "18062111459";
	    	$sms_exists = M('sms')->where(array('mobile'=>$mobile))->order('add_time desc')->getField('add_time');
	    	$time_out = $time - $sms_exists;
	    	if($time_out < 10){
	    		$this->ajaxReturn(-2,"5分钟内请不要连续发送！");
	    	}
	    	$type = $this->_post('type','trim');
	    	//$type = "注册验证";
	    	$code =  mt_rand(100000,999999);
	    	$res = M('sms')->add(array('mobile'=>$mobile,'code'=>$code,'add_time'=>$time));
	    	//$send = $this->sendSMS($mobile,$code,$type);
	    	$send = '1';
	    	if(!empty($res) && !empty($send)){
	    		$this->ajaxReturn(1,"验证码发送成功!");
	    	}else{
	    		$this->ajaxReturn(-1,"验证码发送失败,请稍后重试!");
	    	}
    	}
    	
    }

    function sendSMS($mobile, $code, $type){
    	if($type == "注册验证"){
    		$setSmsFreeSignName = $type;
    		$setSmsTemplateCode = 'SMS_65035132';
    	}   
        //时区设置：亚洲/上海
        date_default_timezone_set('Asia/Shanghai');
        //这个是你下面实例化的类
        Vendor('Alidayu.TopClient');
        //这个是topClient 里面需要实例化一个类所以我们也要加载 不然会报错
        Vendor('Alidayu.ResultSet');
        //这个是成功后返回的信息文件
        Vendor('Alidayu.RequestCheckUtil');
        //这个是错误信息返回的一个php文件
        Vendor('Alidayu.TopLogger');
        //这个也是你下面示例的类
        Vendor('Alidayu.AlibabaAliqinFcSmsNumSendRequest');

        $c = new \TopClient;
        //$config = F('sms','',TEMP_PATH);
        //短信内容：公司名/名牌名/产品名
        $product = '无忧文案';//$config['sms_product'];
        //App Key的值 这个在开发者控制台的应用管理点击你添加过的应用就有了
        $c->appkey = '23798022';//$config['sms_appkey'];
        //App Secret的值也是在哪里一起的 你点击查看就有了
        $c->secretKey = '0554750e074cc0d62682d70ff3f2fb04';//$config['sms_secretKey'];
        //这个是用户名记录那个用户操作
        $req = new \AlibabaAliqinFcSmsNumSendRequest;
        //代理人编号 可选
        $req->setExtend("123456");
        //短信类型 此处默认 不用修改
        $req->setSmsType("normal");
        //短信签名 必须
        $req->setSmsFreeSignName($setSmsFreeSignName);
        //短信模板 必须
        $req->setSmsParam("{\"code\":\"$code\",\"product\":\"$product\"}");
        //短信接收号码 支持单个或多个手机号码，传入号码为11位手机号码，不能加0或+86。群发短信需传入多个号码，以英文逗号分隔，
        $req->setRecNum("$mobile");
        //短信模板ID，传入的模板必须是在阿里大鱼“管理中心-短信模板管理”中的可用模板。
        $req->setSmsTemplateCode($setSmsTemplateCode); // templateCode
        
        $c->format='json'; 
        //发送短信
        $resp = $c->execute($req);
        //短信发送成功返回True，失败返回false
        //if (!$resp) 
        if ($resp){
            return true;        
        }else{
            return false;
        }
    } 

