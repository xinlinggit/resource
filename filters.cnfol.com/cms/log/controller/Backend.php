<?php
namespace cms\log\controller;
use cnfol\api\Cloud;
use cnfol\unit\ArrayUnit;
use think\Loader;

/**
 * 日志管理
 */
class Backend extends Base
{
	/**
	 * @return mixed
	 */
	public function fetch_list(){
		/*获取排序参数，默认按ID倒序*/
		$order = $this->request->param('order', 'id');
		$by = $this->request->param('by', 'desc');
		$this->get_thead(['id'],$order,$by);

		/*分页设置，默认20，小于100*/
		$per_page = $this->request->param('num',20);
		$per_page = min(100, $per_page);

		/*全局筛选条件*/
		$map = $this->get_map();
		/*操作人筛选*/
		$this->get_map_like($map,'real_name','backend_user');
		/*模块筛选*/
		$this->get_map_equal($map,'menu_id');
		$this->get_map_like($map,'operate');

		/*分页查询*/
		$page = Loader::model('backend_log')->with('backend_user,backend_menu')->where($map)->order($order . ' ' . $by)->paginate($per_page);
		$this->view->assign('page', $page);
		$this->view->assign('list', $page->items());

		/*菜单处理*/
		$all_menu = Loader::model('backend_menu')->get_no_del();
		$menu_select = ArrayUnit::array_to_select($all_menu,$this->request->get('menu_id'),['limit'=>2]);
		$this->view->assign('menu_select', $menu_select);
		return $this->fetch();
	}
}

/* End of file Log.php */
/* Location: ./app_cms/log/controller/Backend.php */