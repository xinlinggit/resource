<?php
//引入类
require './PHPMailerAutoload.php';
//创建一个PHPMailer实例
$mail = new PHPMailer;
// 使用SMTP方式发送
$mail->IsSMTP();
//设置编码，否则发送中文乱码
$mail->CharSet ="UTF-8";
//需要发送邮件的主机IP，以下为QQ主机服务器
$mail->Host = "smtp.qq.com";
// 启用SMTP验证功能
$mail->SMTPAuth = true;
$mail->Port     = 25;
//发件人邮箱账号
$mail->Username = "1356162970@qq.com";
//发件人邮箱密码
$mail->Password = "zlyhtaqscinbgjjf";
//设置发送人信息(参数1：发送人邮箱，参数2：发送人名称)
$mail->setFrom('1356162970@qq.com', '罗传');
//收件人邮箱----注意如果是群发，改点for循环添加收件人邮箱
$mail->addAddress('2625414042@qq.com', '谈心');
//邮件主题，即标题
$mail->Subject = '国际电缆';
//邮件内容
$mail->Body = '您的验证码为: 12345';
//邮件附件信息，可以省略
$mail->AltBody = '请注意查收';
//换行，每行超过多少字符自动换行
$mail->WordWrap = 50;
//是否发送HTML
//$mail->isHTML(true);
//发送邮件

//var_dump($mail->send());exit;
if (!$mail->send()) {
    echo "Mailer Error: " . $mail->ErrorInfo;
} else {
    echo "发送成功";
}


