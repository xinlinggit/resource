<?php
namespace cms\set\controller;
use cnfol\api\Cloud;
use cnfol\unit\ArrayUnit;
use think\Config;
use think\Loader;
use think\Url;

/**
 * 菜单管理类
 */
class Menu extends Base
{
	protected $table = 'backend_menu';

	public function fetch_list(){
		/*获取排序参数，默认按ID倒序*/
		$order = $this->request->param('order', 'id');
		$by = $this->request->param('by', 'asc');
		$this->get_thead(['id'],$order,$by);

		$param = $this->request->param();
		/*分页设置，默认20，小于100*/
		$per_page = $this->request->param('num',20);
		$per_page = min(100, $per_page);

		/*全局筛选条件*/
		$map = $this->get_map();

		/*全部查询*/
		$list = Loader::model('backend_menu')->where($map)->order($order . ' ' . $by)->select()->toArray();
		$all_menu = $this->model->get_no_del();
		$menu_select = ArrayUnit::array_to_select($all_menu,$this->request->get('menu_id'));

		$this->view->assign('list', ArrayUnit::array_to_tree($list));
		$this->view->assign('menu_select', $menu_select);
		return $this->fetch();
	}

	public function model_action_add(){
		$this->view->assign('action',url('operate_action_add'));
		return $this->get_model(2);
	}
	public function model_action_edit(){
		$this->view->assign('action',url('operate_action_edit'));
		return $this->get_model(2);
	}
	/**
	 * 添加框
	 */
	public function model_add(){
		$this->view->assign('action',url('operate_add'));
		return $this->get_model(1);
	}
	/**
	 * 修改框
	 */
	public function model_edit(){
		$this->view->assign('action',url('operate_edit'));
		return $this->get_model(1);
	}
	/**
	 * 添加操作
	 */
	public function operate_add(){
		$param = $this->request->post();
		$result = $this->validate($param,'menu.add');
		if(true !== $result){
			return $this->api_error($result);
		}
		/*生成云平台请求数组*/
		$content = [
			Config::get('cloud.project_id'),//项目id
			$param['pid'],//父模块ID
			$param['title'],//模块名称
			$param['url'] ? Url::build($param['url'],'','',true):'',//URL
			$param['description'] //简要描述
		];

		$result = Cloud::add_sys_module($content);
		list($id,$msg) = explode('|',$result);
		if(!is_numeric($id) || $id<=0){
			return $this->api_error($result);
		}
		$param['id'] = $id;
		$param['type'] = 1;
		$result = $this->model->isUpdate(false)->save($param);
		if(false === $result) {
			// 更新失败 输出错误信息
			return $this->api_error($this->model->getError());
		}
		$this->log['data_ids'] = $id;
		return $this->api_success();
	}
	/**
	 * 添加操作
	 */
	public function operate_action_add(){
		$param = $this->request->post();
		$result = $this->validate($param,'menu.add');
		if(true !== $result){
			return $this->api_error($result);
		}
		/*生成云平台请求数组*/
		$content = [
			$param['pid'],//模块ID
			7,//功能ID，统一为其它
			$param['title'],//功能名称
			$param['url'] ? Url::build($param['url'],'','',true):'',//URL
		];

		$result = Cloud::add_sys_fun($content);
		list($id,$msg) = explode('|',$result);
		if(!is_numeric($id) || $id<=0){
			return $this->api_error($result);
		}
		$param['id'] = $id;
		$param['type'] = 2;
		$result = $this->model->isUpdate(false)->save($param);
		if(false === $result) {
			// 更新失败 输出错误信息
			return $this->api_error($this->model->getError());
		}
		$this->log['data_ids'] = $id;
		return $this->api_success();
	}
	/**
	 * 修改操作
	 */
	public function operate_edit(){
		$param = $this->request->post();
		$result = $this->validate($param,'menu.edit');
		if(true !== $result){
			return $this->api_error($result);
		}
		/*生成云平台请求数组*/
		$content = [
			Config::get('cloud.project_id'),//项目id
			$param['pid'],//父模块ID
			$param['title'],//模块名称
			$param['url'] ? Url::build($param['url'],'','',true):'',//URL
			$param['description'] //简要描述
		];

		$result = Cloud::update_sys_module($content,$param['id']);
		list($id,$msg) = explode('|',$result);
		if(!is_numeric($id) || $id<=0){
			return $this->api_error($result);
		}
		$result = $this->model->isUpdate(true)->save($param);
		if(false === $result){
			// 验证失败 输出错误信息
			return $this->api_error($this->model->getError());
		}
		$this->log['data_ids'] = $id;
		return $this->api_success();
	}
	/**
	 * 修改操作
	 */
	public function operate_action_edit(){
		$param = $this->request->post();
		$result = $this->validate($param,'menu.edit');
		if(true !== $result){
			return $this->api_error($result);
		}
		/*生成云平台请求数组*/
		$content = [
			$param['pid'],//模块ID
			7,//功能ID，统一为其它
			$param['title'],//功能名称
			$param['url'] ? Url::build($param['url'],'','',true):'',//URL
		];

		$result = Cloud::update_sys_fun($content,$param['id']);
		list($id,$msg) = explode('|',$result);
		if(!is_numeric($id) || $id<=0){
			return $this->api_error($result);
		}
		$result = $this->model->isUpdate(true)->save($param);
		if(false === $result){
			// 验证失败 输出错误信息
			return $this->api_error($this->model->getError());
		}
		$this->log['data_ids'] = $id;
		return $this->api_success();
	}

	/**
	 * 显示
	 * @author 秦晓武
	 * @time 2017-06-16
	 *
	 * @param int $type
	 *
	 * @internal param int $id 数据ID
	 */
	public function get_model($type = 1){
		switch($type){
			//模块
			case 1:
				$limit = 1;
				$template = 'model_operate';
				break;
			//功能
			case 2:
				$limit = 2;
				$template = 'model_action_operate';
				break;
			default :
				;
		}
		$id = $this->request->get('id');
		$info = $this->model->find($id);
		$all_menu = $this->model->get_no_del();
		$pid_select = ArrayUnit::array_to_select($all_menu, (isset($info->pid)?$info->pid : 0), ['limit'=>$limit]);
		$this->view->assign('pid_select',$pid_select);
		$this->view->assign('info',$info);
		$html = $this->fetch($template);
		return $this->api_success($html);
	}


}

/* End of file StatusMap.php */
/* Location: ./app_cms/content/controller/StatusMap.php */