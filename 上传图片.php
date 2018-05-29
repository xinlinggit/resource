<?php
	/*安卓 ios上传图片**/
	
    /**
     * ios 流
     */
    
    public function repair_upload1(){
        $filename = mt_rand(1000,9999).time().'.jpg';
        $time = date("Y-m-d",time());
        $dir = "./data/upload/repair/".$time.'/';
        if(!file_exists($dir)) {
          mkdir($dir, 0766, true);
          chmod($dir, 0766);
          chmod($dir, 0777);
        }else if(!is_writable($dir)) {
          chmod($dir, 0766);
          chmod($dir, 0777);
          if(!is_writable($dir)) {
            $this->ajaxReturn(500,"上传失败");
          }
        }
        $file = $dir.$filename;
        $byte = $_POST['file'];
        $byte = str_replace(' ', '', $byte);
        $byte = str_ireplace("<", '', $byte);
        $byte = str_ireplace(">", '', $byte);
        $byte = pack("H*", $byte);
        if(file_put_contents($file, $byte, FILE_APPEND)){
            $filepath = "http://coffee.hbbluesea.com/data/upload/repair/".$filename;
            $data = array('filepath'=>$filepath);
            $this->ajaxReturn(200,"上传成功",$data);
        }else{
            $this->ajaxReturn(500,"上传失败");
        }
    
    }

    /**
     * 安卓
     */
    
    public function repair_upload(){
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './data/upload/repair/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件 
        $info   =   $upload->upload();
        //dump($info['file']['savepath']);exit;
        if(!$info) {// 上传错误提示错误信息
            $this->ajaxReturn(500,"上传失败");
        }else{// 上传成功
            $filepath = "http://coffee.hbbluesea.com/data/upload/repair/".$info['file']['savepath'].$info['file']['savename'];
            $data = array('filepath'=>$filepath);
            $this->ajaxReturn(200,"上传成功",$data);
        }
    
    }