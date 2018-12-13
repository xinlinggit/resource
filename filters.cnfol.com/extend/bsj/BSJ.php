<?php
    namespace bsj;
    /**
    * 保10洁审核
    */
    class BSJ
    {
        /**
         * @param $type 审核类型：mp_article文章、mp_comment评论  必填
         * @param $batch 审核类型：0单条、1批量                   必填
         * @param $data['article_id'] 文章id                      必填
         * @param $data['url']        文章url                     必填
         * @param $data['title']    文章标题                      选填
         * @param $data['content']  文章内容                      必填
         * @param $data['class_id']   文章分类                  必填
         * @param $data['pubDate']  添加时间                      必填
         * @param $data['user_id']  用户id                        选填
         * @param $data['user_name']    用户名                    选填
         * @param $data['ip']    用户ip                           选填
         * @param $data['authorEx']    作者扩展信息               选填
         * @param $data['contentEx']    内容扩展信息              选填
         * @param $data['structureEx']    结构扩展信息            选填
         * @param $data['rules']    自定义规则                    选填
         * @return xml或者json，已配置json
         * @return 主要参数：text_id、appType、class(栏目)、flag(建议的操作)、matchResult(匹配结果)、clueClass(错误类型)、cfdLevel(可信度)、clueAbs(简略线索)、clueSpec(详细线索)
         */
        public static function bsj_verify($type,$batch,$data)
        {
            //EXTEND_PATH 扩展类库目录（默认为 ROOT_PATH . 'extend/')
            require_once(EXTEND_PATH."bsj".DS."src".DS."BCPurifyClient.php");
            require_once(EXTEND_PATH."bsj".DS."src".DS."BCPurifyItem.php");
            require_once(EXTEND_PATH."bsj".DS."src".DS."BCPurifyResult.php");
            $param = array();
            $task = 'Purify';	//用户自定义，Purify/Feedback/getNotify/getIndexResult
            if ($batch == 0) {
                //标引接口
                //单条请求               
                $param['isBatch'] = 0;
                $param['appType'] = $type;
                $param['textId'] = $data['article_id'];
                $param['threadId'] = $data['article_id'];
                $param['class'] = $data['class_id'];
                $param['ip'] = empty($data['ip']) ? '' : $data['ip'];
                $param['userId'] = empty($data['user_id']) ? '' : $data['user_id'];
                $param['author'] = empty($data['user_name']) ? '' : $data['user_name'];
                $param['title'] = empty($data['title']) ? '' : $data['title'];
                $param['text'] = $data['content'];
                $param['url'] = empty($data['url']) ? '' : $data['url'];
                $param['pubDate'] = $data['create_time'];
                $param['authorEx'] = empty($data['authorEx']) ? '' : $data['authorEx'];
                $param['contentEx'] = empty($data['contentEx']) ? '' : $data['contentEx'];
                $param['structureEx'] = empty($data['structureEx']) ? '' : $data['structureEx'];
                $param['rules'] = empty($data['rules']) ? '' : $data['rules'];
            }else{
                //批量请求
                
                $param[0]['isBatch'] = 1;
                $param[0]['appType'] = "bbs";
                $param[0]['textId'] = "12323".rand();
                $param[0]['threadId'] = "123456";
                $param[0]['class'] = "11";
                $param[0]['ip'] ="23.23.3.3";
                $param[0]['userId'] ="abcd";
                $param[0]['author'] ="";
                $param[0]['title']="01010101010101";
                $param[0]['text'] ="01010101010101 qq 号 123457";
                $param[0]['url'] ="http://test";
                $param[0]['pubDate'] = "2010-12-01 00:00:00";
                $param[0]['authorEx'] = "作者扩展";
                $param[0]['contentEx'] = "内容扩展";
                $param[0]['structureEx'] = "structureEx";
                $param[0]['rules'] = "规则";
                $param[1]['isBatch'] = 1;
                $param[1]['appType'] = "bbs";
                $param[1]['textId'] = "123231".rand();
                $param[1]['threadId'] = "123456";
                $param[1]['class'] = "11";
                $param[1]['ip'] ="23.23.3.3";
                $param[1]['userId'] ="abcd";
                $param[1]['author'] ="";
                $param[1]['title']="01010101010101";
                $param[1]['text'] ="01010101010101 qq 号 123457";
                $param[1]['url'] ="http://test";
                $param[1]['pubDate'] = "2010-12-01 00:00:00";
                $param[1]['authorEx'] = "作者扩展";
                $param[1]['contentEx'] = "内容扩展";
                $param[1]['structureEx'] = "structureEx";
                $param[1]['rules'] = "规则";
            }


            $item = new \BCPurifyItem();
            $item->m_hlParmas = $param;
            $client = new \BCPurifyClient();
            $result = $client->{$task}($item);		//	Purify/Feedback/getNotify/getIndexResult
            if (!$result) {
                //客户端内部错误,返回字段校验错误(例:appType cannot be empty)或网络请求错误(例:http code:404)
                return $client->getError();

            }elseif ($result->isBusinessSuccess()){
                //成功时取结果集,返回数组对象
                if ($result->getMarkResult() != null) {
                    //dump($result->getMarkResult());die;
                    return $result->getMarkResult();
                }else {
                    return false;
                }
            }else {
                //失败时取结果集
                return $result->getMarkResult();
            }
        }
    }




/*   

if ($batch == 0) {
    //标引接口

    //单条请求
   
    $param['isBatch'] = 0;
    $param['appType'] = $type;
    $param['textId'] = $data['article_id'];
    $param['threadId'] = $data['article_id'];
    $param['class'] = $data['class_name'];
    //$param['ip'] ="23.23.3.3";
    $param['userId'] = $data['user_id'];
    $param['author'] = "";
    $param['title'] = $data['title'];
    $param['text'] = $data['content'];
    $param['url'] = url('index/article.index/detail',['ids'=>$data['article_id']]);
    $param['pubDate'] = $data['add_time'];
    $param['authorEx'] = "作者扩展";
    $param['contentEx'] = "内容扩展";
    $param['structureEx'] = "structureEx";
    $param['rules'] = "规则"; 
}else{
    //批量请求
    
    $param[0]['isBatch'] = 1;
    $param[0]['appType'] = "bbs";
    $param[0]['textId'] = "12323".rand();
    $param[0]['threadId'] = "123456";
    $param[0]['class'] = "11";
    $param[0]['ip'] ="23.23.3.3";
    $param[0]['userId'] ="abcd";
    $param[0]['author'] ="";
    $param[0]['title']="01010101010101";
    $param[0]['text'] ="01010101010101 qq 号 123457";
    $param[0]['url'] ="http://test";
    $param[0]['pubDate'] = "2010-12-01 00:00:00";
    $param[0]['authorEx'] = "作者扩展";
    $param[0]['contentEx'] = "内容扩展";
    $param[0]['structureEx'] = "structureEx";
    $param[0]['rules'] = "规则";
    $param[1]['isBatch'] = 1;
    $param[1]['appType'] = "bbs";
    $param[1]['textId'] = "123231".rand();
    $param[1]['threadId'] = "123456";
    $param[1]['class'] = "11";
    $param[1]['ip'] ="23.23.3.3";
    $param[1]['userId'] ="abcd";
    $param[1]['author'] ="";
    $param[1]['title']="01010101010101";
    $param[1]['text'] ="01010101010101 qq 号 123457";
    $param[1]['url'] ="http://test";
    $param[1]['pubDate'] = "2010-12-01 00:00:00";
    $param[1]['authorEx'] = "作者扩展";
    $param[1]['contentEx'] = "内容扩展";
    $param[1]['structureEx'] = "structureEx";
    $param[1]['rules'] = "规则";
    }*/