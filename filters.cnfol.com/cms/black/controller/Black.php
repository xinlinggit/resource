<?php
namespace cms\black\controller;
use cnfol\unit\ArrayUnit;
use think\Db;
use think\Loader;
use think\Config;
use cms\common\model;

/**
 * 黑名单管理类
 */
class Black extends Base
{
    protected $table='black';
    /**
     * 黑名单列表
     * @return mixed
     */
    public function fetch_list(){
       
        /*设置数组*/
        $set = [];
        $set['order'] = 'id';
        $set['thead'] = ['id','create_time'];
        
        $this->view->assign('status',ArrayUnit::array_to_options([2=>'关闭',1=>'正常'],$this->request->param('status')));
        $this->view->assign('type',ArrayUnit::array_to_options([1=>'词组',2=>'QQ',3=>'URL',4=>'微信',5=>'邮箱',6=>'正则'],$this->request->param('type')));

        return $this->fetch_base($set);
    }

    /**
     * 添加框
     */
    public function model_add(){
        $this->view->assign('action',url('operate_add'));
        
        return $this->get_model();
    }

    /**
     * 修改框
     */
    public function model_edit(){
        $this->view->assign('action',url('operate_edit'));
        return $this->get_model();
    }

    /**
     * 添加操作
     */
    public function operate_add(){
        $param = $this->request->post();
        $result = $this->model->isUpdate(false)->save($param);
        if(false === $result) {
            // 验证失败 输出错误信息
            return $this->api_error($this->model->getError());
        }
        return $this->api_success();
    }

    /**
     * 修改操作
     */
    public function operate_edit(){

        $param = $this->request->post();
        $result = $this->model->isUpdate(true)->save($param);
        if(false === $result){
            // 验证失败 输出错误信息
            return $this->api_error($this->model->getError());
        }
        return $this->api_success();
    }

    /**
     * 显示
     * @param int $id 数据ID
     */
    public function get_model(){
        $id = $this->request->get('id');

        $info = $this->model->find($id);
        $this->view->assign('info',$info);
        $html = $this->fetch('model_operate');
        return $this->api_success($html);
    }


    /**
     * 锁定'0'
     */
    public function operate_status_2(){
        $param = $this->request->param();
        if(!$param['id']){
            return $this->api_error('请选择数据');
        }
        $data = [
            'status' => 2,
        ];
        $result = $this->model->isUpdate(true,['id' => ['in',$param['id']]])->save($data);
        if(false === $result){
            // 操作失败 输出错误信息
            return $this->api_error($this->model->getError());
        }
        return $this->api_success();
    }


    /**
     * 开启'0'
     */
    public function operate_status_1(){
        $param = $this->request->param();
        if(!$param['id']){
            return $this->api_error('请选择数据');
        }
        $data = [
            'status' => 1,
        ];
        $result = $this->model->isUpdate(true,['id' => ['in',$param['id']]])->save($data);
        if(false === $result){
            // 操作失败 输出错误信息
            return $this->api_error($this->model->getError());
        }
        return $this->api_success();
    }

    /**
     * 删除（回收站）'3'
     */
    public function operate_status_3(){
        $param = $this->request->param();
        if(!$param['id']){
            return $this->api_error('请选择数据');
        }
        $data = [
            'status' => 3,
        ];
        $result = $this->model->isUpdate(true,['id' => ['in',$param['id']]])->save($data);
        if(false === $result){
            // 操作失败 输出错误信息
            return $this->api_error($this->model->getError());
        }
        return $this->api_success();
    }


    /**
     * 页面渲染基础方法
     *
     * @param array $set 配置数组
     *
     * @return mixed
     */
    protected function fetch_base($set = [])
    {
        /*获取排序参数，默认按ID倒序*/
        $order = $this->request->param('order', isset($set['order'])?$set['order']:'id');
        $by = $this->request->param('by', isset($set['by'])?$set['by']:'desc');
        $this->get_thead(isset($set['thead'])?$set['thead']:[$order], $order, $by);

        /*分页设置，默认20，小于100*/
        $per_page = $this->request->param('num', 20);
        $per_page = min(100, $per_page);

        /*全局筛选条件*/
        $map = $this->get_map();
        $this->get_map_like($map, 'name');
        $this->get_map_equal($map, 'type');
        /*默认值回调处理*/
        if (isset($set['map']) && is_callable($set['map'])) {
            $set['map']($map);
        };
        /*分页查询*/
        $page = $this->model
            ->with('backend_user')
            ->where($map)
            ->order($order . ' ' . $by)
            ->paginate($per_page);

        $this->view->assign('page', $page);
        $this->view->assign('list', $page->items());

        return $this->fetch();
    }

    public function update_cache(){
        ini_set('memory_limit', '512M');
        unlink('../runtime/blackword.txt');
        unlink('../runtime/blackword.tree');
        unlink('../runtime/blackwordpreg.txt');
        $map['status'] = 1;
        $map['type'] = array('neq',6);
        $data = Db::table('filter_black')->where($map)->field('name')->select();
        foreach ($data as $key => $value) {
            $value['name'] = trim($value['name']);
            error_log($value['name'].PHP_EOL,3,config('root_url').'blackword.txt');//正常非正则
        }

        $where['status'] = 1;
        $where['type'] = 6;
        $datas = Db::table('filter_black')->where($where)->field('name')->select();
        foreach ($datas as $key => $value) {
            $value['name'] = trim($value['name']);
            error_log($value['name'].PHP_EOL,3,config('root_url').'blackwordpreg.txt');//正则
        }


        
        $arrWord = file('../runtime/blackword.txt');
        
        $resTrie = trie_filter_new();

        foreach ($arrWord as $k => $v) {
            trie_filter_store($resTrie, $v);
        }
        trie_filter_save($resTrie,'../runtime/blackword.tree');

        trie_filter_free($resTrie);
        return $this->api_success();
        //echo "更新tree成功".date('Y-m-d H:i:s',time());
            
    }

}
/* End of file User.php */
/* Location: ./app_cms/user/controller/User.php */