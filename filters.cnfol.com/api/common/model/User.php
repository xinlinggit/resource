<?php
namespace app\common\model;
use think\Model;
class User extends Model
{	
	protected $table="record";

	protected $field=TRUE;

	/**
	 * @var string 设置返回数据集的对象名
	 */
	protected $resultSetType = 'collection';


	public function test(){

		return $this->select()->toArray();

	}
}
