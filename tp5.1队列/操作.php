<?php 

/*��ģ���ݿⷽʽ*/
//��composer install thinkphp-queue   ע��汾������̽�ѵ��

//��configĿ¼����queus.php �����²�������

return [
    // 'connector' => 'Sync'
     'connector' => 'Database',   // ���ݿ�����
      'expire'    => 60,           // ����Ĺ���ʱ�䣬Ĭ��Ϊ60��; ��Ҫ���ã�������Ϊ null
      'default'   => 'default',    // Ĭ�ϵĶ�������
      'table'     => 'tp5_jobs',   // �洢��Ϣ�ı���������ǰ׺
      'dsn'       => [],
];


/*1.���ڿ����������������������*/

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

/*2.��app/job/Job1.php�����ļ�����������´���*/
//Job1 �����app\job ���½�һ��.php дһ��Job1��



namespace app\job;

use think\queue\Job;
use think\Db;
use think\Controller;

class Job1 extends Controller
{

    public function fire(Job $job, $data)
    {

        //....����ִ�о��������
      $data = json_decode($data,true);
      if($this->jobDone($data))
      {
          $job->delete();
          print("<info>Hello Job has been done and deleted"."</info>\n");
      }else{
          $job->release(3); //$delayΪ�ӳ�ʱ��
      }

      if ($job->attempts() > 3) {
          //ͨ������������Լ����������Ѿ������˼�����

      }

        //�������ִ�гɹ��� �ǵ�ɾ�����񣬲�Ȼ���������ظ�ִ�У�ֱ���ﵽ������Դ�����ʧ�ܺ�ִ��failed����
        // $job->delete();

        // Ҳ�������·����������
        // $job->release($delay); //$delayΪ�ӳ�ʱ��

    }

    public function failed($data)
    {

        // ...����ﵽ������Դ�����ʧ����
    }

    public function jobDone($data)
    {

      print("<info>Job is Done status!"."</info> \n");

       return Db::name('tp5_test')->where('order_no',$data['order_no'])->update(['status'=>2]);

    }

}

/*3. ������ű�*/

//���ݲ��Ա�

DROP TABLE IF EXISTS `tp5_test`;
CREATE TABLE `tp5_test` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_no` varchar(50) NOT NULL COMMENT '������',
  `msg` varchar(255) NOT NULL COMMENT '��Ϣ����',
  `status` tinyint(1) NOT NULL COMMENT '״̬ 0δִ�У�1 ִ��',
  `create_time` datetime NOT NULL COMMENT '����ʱ��',
  `update_time` datetime NOT NULL COMMENT '����ʱ��',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8 COMMENT='���Ա�';



//��Ϣ���б�

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



/*4. ��Ϣ�Ĵ���������*/

//����������

http://demain/index/index/queueTest


/* 5.��Ϣ��������ɾ��*/

//windows �� cmd������վ��Ŀ¼����     linux�ҵ���ǰ��վ��Ŀ¼  ��Ȼ��php���������ִ�С�

//ִ��һ��

php think queue:work --queue -v

//ѭ��ִ��

php think queue:work --daemon


//����Լ������ݿ���������ԭ��