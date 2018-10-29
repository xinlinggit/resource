PHPÒì²½ÊµÏÖ£º
curl
fsockopen
popen

curl 
curl curl 本身没有异步功能，是阻塞模式运行，只是我们可以通过设置一个等待时间来 控制请求的响应速度。即设置CURLOPT_TIMEOUT（0为无限等待）。

使用curl异步访问：
$ch = curl_init();
        $data = [
            'sql' =>1,
            'where'=>2
        ];
        curl_setopt($ch, CURLOPT_URL, "http://ci.dev/index.php/welcome/sync1?sql=123");
        //ÉèÖÃÍ·ÎÄ¼þµÄÐÅÏ¢×÷ÎªÊý¾ÝÁ÷Êä³ö
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT,1);
        curl_setopt($ch, CURLOPT_POST,TRUE);//TRUE Ê±»á·¢ËÍ POST ÇëÇó
        //ÉèÖÃpostÊý¾Ý
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data);//
        //ÉèÖÃ»ñÈ¡µÄÐÅÏ¢ÒÔÎÄ¼þÁ÷µÄÐÎÊ½·µ»Ø£¬¶ø²»ÊÇÖ±½ÓÊä³ö¡£
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //Ö´ÐÐÃüÁî
        curl_exec($ch);
        //¹Ø±Õ¾ä±ú
        curl_close($ch);
--------------------- 

fsockopen 
fsockopen fsockopen 与 curl 本身没什么区别，最大不同fsockopen 要自己拼接头。默认情况下将以阻塞模式开启套接字连接。当然你可以通过stream_set_blocking()将它转换到非阻塞模式。

使用fsocketopen()异步：
public  function syncbySocket(){
        $len = strlen('123');
        $host= "www.ci.dev";
        $path='/index.php/welcome/syncwrite';
        $fp = fsockopen($host, 80, $errno, $errstr, 1);//×îºóÒ»¸öÊÇÁ¬½Ó³¬Ê±
        if($fp){
            $header =  "POST $path HTTP/1.1\r\n";
            $header .= "Host: $host\r\n";
            $header .= "Content-type: application/x-www-form-urlencoded\r\n";
            $header .= "Content-Length: $len\r\n";
            $header .= "Connection: close\r\n\r\n";//×¢ÒâÊÇË«Ð±¸Ü
            fwrite($fp,$header);
            /*while (!feof($fp)) {
                echo fgets($fp, 1024);
            }*/
            fclose($fp);
            echo 'ok';
        }else{
            echo $errstr;
        }

    }   
--------------------- 
popen 
本质打开一个指向进程的管道，该进程由派生给定的 command 命令执行而产生。 但运行代码还是会 等待数据返回 在向下执行。 
由于运行的是一个命令，在Linux中 在命令后面 加 & 就表示把进程放到后台运行，如果我们需要运行的结果我们可 通过 数据流重定向 获取。



pclose(popen("/home/work/php/bin/php /home/work/index.php Pollpayment_order pull >> /home/work/tmp/errorlog &",
            "r"));

注意下面执行还是阻塞模式
pclose(popen("/home/work/php/bin/php /home/work/index.php Pollpayment_order pull >> /home/work/tmp/errorlog",
            "r"));
--------------------- 

