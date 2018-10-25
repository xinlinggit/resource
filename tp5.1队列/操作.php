<?php 

/*单模数据库方式*/
//先composer install thinkphp-queue   注意版本啊，深刻教训。

//在config目录配置queus.php 做如下参数配置

return [
    // 'connector' => 'Sync'
     'connector' => 'Database',   // 数据库驱动
      'expire'    => 60,           // 任务的过期时间，默认为60秒; 若要禁用，则设置为 null
      'default'   => 'default',    // 默认的队列名称
      'table'     => 'tp5_jobs',   // 存储消息的表名，不带前缀
      'dsn'       => [],
];


/*1.先在控制器里添加下面两个方法*/

namespace app\index\controller;

use think\Controller;
use think\Queue;
use think\Db;

class Index extends Controller
{

    public function queueTest(){
        $data = [
            'order_no' =>rand(100000,999999),
        ];
        $this->add($data['order_no']);
        $data = json_encode($data);
        $res =Queue::push('Job1', $data, $queue = null);
        var_dump($res);
        
    }

    public function add($orderNo){
        $data =[
            'order_no'=>$orderNo,
            'msg'=>$orderNo,
            'create_time'=>date('Y-m-d H:i:s'),
        ];
        Db::name('tp5_test')->insert($data);
    }

}

/*2.在app/job/Job1.php创建文件，并添加如下代码*/
//Job1 这个在app\job 下新建一个.php 写一个Job1类



namespace app\job;

use think\queue\Job;
use think\Db;
use think\Controller;

class Job1 extends Controller
{

    public function fire(Job $job, $data)
    {

        //....这里执行具体的任务
      $data = json_decode($data,true);
      if($this->jobDone($data))
      {
          $job->delete();
          print("<info>Hello Job has been done and deleted"."</info>\n");
      }else{
          $job->release(3); //$delay为延迟时间
      }

      if ($job->attempts() > 3) {
          //通过这个方法可以检查这个任务已经重试了几次了

      }

        //如果任务执行成功后 记得删除任务，不然这个任务会重复执行，直到达到最大重试次数后失败后，执行failed方法
        // $job->delete();

        // 也可以重新发布这个任务
        // $job->release($delay); //$delay为延迟时间

    }

    public function failed($data)
    {

        // ...任务达到最大重试次数后，失败了
    }

    public function jobDone($data)
    {

      print("<info>Job is Done status!"."</info> \n");

       return Db::name('tp5_test')->where('order_no',$data['order_no'])->update(['status'=>2]);

    }

}

/*3. 添加两张表*/

//数据测试表

DROP TABLE IF EXISTS `tp5_test`;
CREATE TABLE `tp5_test` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(50) NOT NULL COMMENT '订单号',
  `msg` varchar(255) NOT NULL COMMENT '消息内容',
  `status` tinyint(1) NOT NULL COMMENT '状态 0未执行，1 执行',
  `create_time` datetime NOT NULL COMMENT '创建时间',
  `update_time` datetime NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='测试表';



//消息队列表

DROP TABLE IF EXISTS `tp5_jobs`;
CREATE TABLE `tp5_jobs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) unsigned NOT NULL,
  `reserved` tinyint(3) unsigned NOT NULL,
  `reserved_at` int(10) unsigned DEFAULT NULL,
  `available_at` int(10) unsigned NOT NULL,
  `created_at` int(10) unsigned NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;



/*4. 消息的创建与推送*/

//在浏览器里打开

http://demain/index/index/queueTest


/* 5.消息的消费与删除*/

//windows 在 cmd里在网站根目录运行     linux找到当前网站根目录  当然你php命令必须能执行。

//执行一行

php think queue:work --queue -v

//循环执行

php think queue:work --daemon


//最后自己在数据库中体会队列原理。