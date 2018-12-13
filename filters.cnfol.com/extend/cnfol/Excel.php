<?php
namespace cnfol;


use \PhpOffice\PhpSpreadsheet\IOFactory;
/**
 * Excel 读取与写入
*/
class Excel{
    protected $fileType='Xls';
    protected $readerFile;
   
    public function __set($name,$value){
        $this->$name=$value;
    }
    
    public function __get($name){
        return $this->$name;
    }

    /**
     * 获取Excel数据 
     */
    public function get_excel_data(){
        if(!file_exists($this->readerFile)) die( 'file not exists');
        $reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($this->fileType);
        return $reader->load($this->readerFile);
    }
    
    /**
     * 获取指定sheet内容
     * @param $reader_data 读取excel对象
     * @param $num 第几个sheet 
     * @param $row 开始行数
    */
    public function get_one_sheet($reader_data,$num=0,$srow=1){
        if(!is_object($reader_data)) die( 'not fund data');
        $sheet=$reader_data->getSheet($num);// 读取一個工作表
        $rowCount = $sheet->getHighestRow(); // 取得总行数
        $columnCount = $sheet->getHighestColumn(); // 取得总列数
        
        /** 循环读取每个单元格的数据 */
        $list=array();
        for ($row=$srow ; $row <= $rowCount; $row++){
            //行数是以第1行开始
            for ($column = 'A'; $column <= $columnCount; $column++) {
                //列数是以A列开始
                $list[$row][] = $sheet->getCell($column.$row)->getFormattedValue();
            }
        }
        $data=array();
        $data['sheet_num']=$num;
        $data['sheet_name']=$sheet->getTitle();
        $data['row_count']=$rowCount;
        $data['column_count']=$columnCount;
        $data['list']=$list;
        return $data;
    }

     /**
     * 获取所有sheet内容
     * @param $reader_data 读取excel对象
     * @param $row 开始行数
    */
    public function get_all_sheet($reader_data,$srow=1){
        if(!is_object($reader_data)) die( 'not fund obj data');
        $sheetCount=$reader_data->getSheetCount();
        $data=array();
        for ($num = 0; $num < $sheetCount; $num++){
            $sheet=$reader_data->getSheet($num);// 读取一個工作表
            $rowCount = $sheet->getHighestRow(); // 取得总行数
            $columnCount = $sheet->getHighestColumn(); // 取得总列数
            
            /** 循环读取每个单元格的数据 */
            $list=array();
            for ($row=$srow; $row <= $rowCount; $row++){
                //行数是以第1行开始
                for ($column = 'A'; $column <= $columnCount; $column++) {
                    //列数是以A列开始
                    $list[$row][] = $sheet->getCell($column.$row)->getFormattedValue();
                }
            }
            $data[$num]['sheet_num']=$num;
            $data[$num]['sheet_name']=$sheet->getTitle();
            $data[$num]['row_count']=$rowCount;
            $data[$num]['column_count']=$columnCount;
            $data[$num]['list']=$list;
        }
        return $data;
    }
}
