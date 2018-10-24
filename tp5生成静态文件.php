<?php
public function index()
    {	
        $id = "5201314";
        $this->buildHtml($id,'index/'.date('Ymd').'/');
    }


    /**
	* 创建静态页面
	* @access protected
	* @htmlfile 生成的静态文件名称
	* @htmlpath 生成的静态文件路径
	* @param string $templateFile 指定要调用的模板文件
	* 默认为空 由系统自动定位模板文件
	* @return string
	*
	*/
	protected function buildHtml($htmlfile = '', $htmlpath = '', $templateFile = '')
	{
		$content = $this->fetch('demo');
		$htmlpath = !empty($htmlpath) ? $htmlpath : './appTemplate/';
		$htmlfile = $htmlpath . $htmlfile . '.'.config('url_html_suffix');
		$File = new \think\template\driver\File();
		$File->write($htmlfile, $content);
		return $content;
	}

//config // URL伪静态后缀
    'url_html_suffix'        => 'html',