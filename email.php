<?php
php email
需要开启php.ini里的openssl
	/**
     * 邮件发送
     * @param $to    接收人
     * @param string $subject   邮件标题
     * @param string $content   邮件内容(html模板渲染后的内容)
     * @throws Exception
     * @throws phpmailerException
     */

    function sendEmail($to, $title, $content) { 
        Vendor('PHPMailer.PHPMailerAutoload');     
        $mail = new \PHPMailer(); //实例化
        $mail->IsSMTP(); // 启用SMTP
        $mail->Host=C('MAIL_HOST'); //smtp服务器的名称（这里以QQ邮箱为例）
        $mail->SMTPAuth = C('MAIL_SMTPAUTH'); //启用smtp认证
        $mail->Username = C('MAIL_USERNAME'); //你的邮箱名
        $mail->Password = C('MAIL_PASSWORD') ; //邮箱密码
        $mail->From = C('MAIL_FROM'); //发件人地址（也就是你的邮箱地址）
        $mail->FromName = C('MAIL_FROMNAME'); //发件人姓名
        $mail->AddAddress($to,"尊敬的客户");
        $mail->WordWrap = 50; //设置每行字符长度
        $mail->IsHTML(C('MAIL_ISHTML')); // 是否HTML格式邮件
        $mail->CharSet=C('MAIL_CHARSET'); //设置邮件编码
        $mail->Subject =$title; //邮件主题
        $mail->Body = $content; //邮件内容
        $mail->AltBody = "这是一个纯文本的身体在非营利的HTML电子邮件客户端"; //邮件正文不支持HTML的备用显示
        return($mail->Send());
    }


配置文件
    //邮件配置
    'MAIL_HOST' =>'smtp.qq.com', //smtp服务器的名称
    'MAIL_SMTPAUTH' =>TRUE,     //启用smtp认证
    'MAIL_USERNAME' =>'382244473@qq.com',   //你的邮箱名
    'MAIL_FROM' =>'382244473@qq.com',   //发件人地址
    'MAIL_FROMNAME'=>'xinling',  //发件人姓名
    'MAIL_PASSWORD' =>'xgkcueusvybdbgfg', //邮箱密码
    'MAIL_CHARSET' =>'utf-8',   //设置邮件编码
    'MAIL_ISHTML' =>TRUE,       // 是否HTML格式邮件