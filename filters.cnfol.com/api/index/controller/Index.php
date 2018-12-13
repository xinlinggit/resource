<?php
namespace api\index\controller;

use think\Cache;
use think\Queue;
use think\Db;
use think\Request;
class Index extends Base
{
    public function index()
    {   
        $str = $this->request->Post('str');
        if(!empty($str)){
            $resTrie = trie_filter_load('../runtime/blackword.tree');
      
            $str = strip_tags($str);
            if(!empty($str)){
                $arrRet = trie_filter_search_all($resTrie, $str);
                //正则匹配
                //$regKeyWord = $this->filterRegKey($str);
                $regKeyWord = '';
                $return =  $this->print_all($str, $arrRet,$regKeyWord);
                echo "<script language=javascript>alert('{$return}');history.back();</script>";
                trie_filter_free($resTrie);
            }
            
        }else{
            return $this->fetch();
        }
           
    }

    /**
     * [filter 过滤接口]
     * @param  [type]  $content   [过滤文本]
     * @param  integer $model [模式 1：返回单一 2：返回全部]
     * @return [type]         [description]
     */
    public function filter($content = null,$model = 1,$type = 1){
        $content = $this->request->Post('content');

        if(!empty($content)){
            $resTrie = trie_filter_load('../runtime/blackword.tree');
      
            $content = strip_tags($content);
            if(!empty($content)){
                $return = '';
                switch ($model)
                    {
                    case 1:
                        $arrRet = trie_filter_search($resTrie, $content);

                        if($type == 1){
                            $return = $this->print_return_single($content, $arrRet);
                        }else{
                            $return = $this->print_return_singles($content, $arrRet);
                        }
                                                       
                      break;  
                    case 2:
                        $arrRet = trie_filter_search_all($resTrie, $content);
                        //正则匹配
                        //$regKeyWord = $this->filterRegKey($content);
                        $regKeyWord = '';
                        $return =  $this->print_return($content, $arrRet,$regKeyWord);
                      break;
                    default:
                      $return = "param is error";
                    }
      
                trie_filter_free($resTrie);
                
                if($return){
                    $this->api_error($return);
                }
            }
            
            
        }

        $this->api_success();

    }

    private function print_return_single($content, $arrRet= null) {
        
        if(!empty($arrRet)){
            return substr($content, $arrRet[0], $arrRet[1]);
        }else{
          return '';
        }
        
    }

    private function print_return_singles($content, $arrRet= null) {
        
        if(!empty($arrRet)){
            return "包含违禁词：".substr($content, $arrRet[0], $arrRet[1]);
        }else{
          return '';
        }
        
    }


    private function print_return($str, $res,$regKeyWord) {
        $i = 0;
        foreach ($res as $k => $v) {
            $Sensi[$i] = '['.substr($str, $v[0], $v[1]).']';
            $i++;
        }
        if(!empty($regKeyWord))       $Sensi[] = '['.$regKeyWord.']';
        
        if(!empty($Sensi)){
            return "含有敏感词:".implode("、",array_unique($Sensi));
        }
        
    }

    public function print_all($str, $res,$regKeyWord) {
        $i = 0;
        foreach ($res as $k => $v) {
            $Sensi[$i] = '['.substr($str, $v[0], $v[1]).']';
            $i++;
        }
        if(!empty($regKeyWord))       $Sensi[] = '['.$regKeyWord.']';
        
        if(!empty($Sensi)){
            return "含有敏感词:".implode("、",array_unique($Sensi));
        }else{
            return "文本正常，没有敏感词！";
        }
        
    }
  
    
  /** 2018-11-29 匹配正则规则 匹配第一个就返回不继续匹配
     */
  private function filterRegKey($str){
     //获取正则列表 
     /*$regList = [
           '绮(?:\W|[a-zA-Z\d丶\s_丨]){0,4}魅(?:\W|[a-zA-Z\d丶\s_丨]){0,4}尔',//关键字正则
           '法.{0,150}輪.{0,150}佛.{0,150}法.{0,150}好',
       ];*/
       $regList = file('../runtime/blackwordpreg.txt');
       $filterKey = '';
       foreach($regList as $val){
            $val =trim($val);
            preg_match('/'.$val.'/',$str,$match);
            if( isset($match[0]) && $match[0]!=''){
               $filterKey = $match[0];
               break; 
            }
         
       }
       return $filterKey;
  }
}
