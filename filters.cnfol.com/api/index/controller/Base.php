<?php
/**
 * 财经号控制器基类
 */
namespace api\index\controller;

use think\Controller;
use think\exception\HttpResponseException;
use think\Response;

class Base extends Controller
{
	/**
	 * 允许访问的请求类型
	 * @var string
	 */
	protected $rest_method_list = 'get|post|put|delete|patch|head|options';

	/**
	 * 初始化->子类继承
	 */
	public function _init(){

	}

	/**
	 * 成功返回
	 * @param  string  $data [description]
	 * @param  integer $flag 1 正常  0 错误 -1未登录
	 * @param  string  $clueAbs  [description]
	 * @return [type]        [description]
	 */
	protected function api_success( $data = '',$clueAbs = 'ok!',$flag=10000)
	{
		$result = [
			'flag' => $flag,
			'clueAbs'  => $clueAbs,
			'data' => $data,
		];
		$response = Response::create($result, 'json');
		throw new HttpResponseException($response);
	}

	/**
	 * 错误返回
	 * @param  string  $data [description]
	 * @param  integer $flag 1 正常  0 错误 -1未登录
	 * @param  string  $clueAbs  [description]
	 * @return [type]        [description]
	 */
	protected function api_error($clueAbs = 'error!',$data = '',$flag=10001)
	{
		$result = [
			'flag' => $flag,
			'clueAbs'  => $clueAbs,
			'data' => $data,
		];
		$response = Response::create($result, 'json');
		throw new HttpResponseException($response);
	}

}

/* End of file Common.php */
/* Location: ./app_api/common/controller/Common.php */