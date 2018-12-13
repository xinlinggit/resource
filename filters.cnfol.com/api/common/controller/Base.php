<?php
namespace app\common\controller;
use think\Db;
use think\Cache;
use think\Request;
use think\Controller;
class Base extends Controller
{

	/*protected $request;
    
	public function __construct(Request $request)
    {
    	$this->request = $request;
    }*/

    public function _initialize()
    {	
    	
        //echo 'init<br/>';
    }

}
