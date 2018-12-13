<?php

namespace PFinal\Excel;

use DateTime;

/**
 * Excel操作类
 * @author  HuDeyong <sniffrose@outlook.com>
 * @since   1.0
 */
class Excel_export
{

	protected $workSheetName = 'Worksheet';

	protected $map;

	protected $filename;

	protected $writer;

	protected $tempfile;

	public function __construct($filename, $map) {
		$this->filename = $filename;
		$this->map = $map;
		$this->writer = new \XLSXWriter();
		$header = array();
		foreach ($this->map['title'] as $key => $val) {
			if (isset($this->map['simpleFormat'][$key])) {
				$header[$val] = $this->map['simpleFormat'][$key];
			} else {
				$header[$val] = 'GENERAL';
			}
		}
		$this->writer->writeSheetHeader($this->workSheetName, $header);
		$this->tempfile =  tempnam(sys_get_temp_dir(), 'excel');
	}

	// 将数据写入 Excel
	public function write2excel($data){
		foreach ($data as $row) {
			$temp = array();
			foreach ($this->map['title'] as $key => $value) {
				if (isset($row[$key])) {
					$temp[] = $row[$key];
				} else {
					$temp[] = '';
				}
			}
			$this->writer->writeSheetRow($this->workSheetName, $temp);
		}
		$this->writer->writeToFile($this->tempfile);
	}

	// 下载数据
	public function output(){
		//弹出下载对话框
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename=' . $this->filename);

		readfile($this->tempfile);
		unlink($this->tempfile);
	}
}