<?php
namespace common\models;
/**
 * 虚拟模型用于导出excel
 */
class Excel{
    public static $PHPExcel;
    public static $activeSheetObj;
    public static $line;
    public function __construct()
    {
        include_once(dirname(dirname(dirname(__FILE__))).'/extension/PHPExcel_1.8.0/Classes/PHPExcel.php');
        self::$PHPExcel = new \PHPExcel();
        self::$line = 1;
        //设置激活sheet为0
        $this->setActiveSheetIndex();
    }

    /**
     * 设置激活的sheet
     */
    public function setActiveSheetIndex($index = 0)
    {
        self::$activeSheetObj = self::$PHPExcel->setActiveSheetIndex();
    }

    /**
     * 获取PHPExcel对象
     */
    public function getPHPExcel()
    {
        return self::$PHPExcel;
    }

    /**
     * 设置excel头信息
     * @param $properties 要设置的属性
     * 包括creator、lastModifiedBy、title、subject、description、keywords、category
     */
    public function setHeader($properties)
    {
        $properties['creator'] = isset($properties['creator']) ? $properties['creator'] : '' ;
        $properties['lastModifiedBy'] = isset($properties['lastModifiedBy']) ? $properties['lastModifiedBy'] : '' ;
        $properties['title'] = isset($properties['title']) ? $properties['title'] : '' ;
        $properties['subject'] = isset($properties['subject']) ? $properties['subject'] : '' ;
        $properties['description'] = isset($properties['description']) ? $properties['description'] : '' ;
        $properties['keywords'] = isset($properties['keywords']) ? $properties['keywords'] : '' ;
        $properties['category'] = isset($properties['category']) ? $properties['category'] : '' ;
        self::$PHPExcel->getProperties()
            ->setCreator($properties['creator'])
            ->setLastModifiedBy($properties['lastModifiedBy'])
            ->setTitle($properties['title'])
            ->setSubject($properties['subject'])
            ->setDescription($properties['description'])
            ->setKeywords($properties['keywords'])
            ->setCategory($properties['category']);
    }

    /**
     * 从数组创建excel
     * $array = [
     *      [
     *           [
     *              'content'=>'','width'=>'100','height'=>'100','font-size'=>100,
     *              'font-weight'=>true,'align'=>'center/right/left','valign'=>'center',
     *              'color'=>'fff','background-rgba'=>'ffffff','colspan'=>2,
     *              'border-type'=>'thick/thin','border-color'=>'00ff0000'
     *           ]
     *      ]
     * ];
     */
    public function createExcelFromArray($data){
        foreach($data as $line){
            $this->addLineToExcel($line);
        }
    }

    /**
     * 向excel中添加一行
     */
    public function addLineToExcel($line)
    {
        if(empty($line)){
            return false;
        }
        $ceilIndex = 0;//列索引
        foreach($line as $ceil){
			if(!empty($ceil)){
                $ceilIndexAsString = \PHPExcel_Cell::stringFromColumnIndex($ceilIndex);
                self::$activeSheetObj->setCellValue($ceilIndexAsString.self::$line,$ceil['content']);
                //设置列宽
                if(isset($ceil['width'])){
                    self::setColumnWidth([$ceilIndexAsString=>$ceil['width']]);
                }
                //设置行高
                if(isset($ceil['height'])){
                    self::setLineHeight([self::$line=>$ceil['height']]);
                }
                //合并单元格-列
                if(isset($ceil['colspan']) && $ceil['colspan'] > 1){
                    $ceilIndex += ($ceil['colspan'] - 1);
                    $colspanToColumn = \PHPExcel_Cell::stringFromColumnIndex($ceilIndex);
                    self::$activeSheetObj->mergeCells($ceilIndexAsString.self::$line.':'.$colspanToColumn.self::$line);
                }
				//合并单元格-行
				if(isset($ceil['rowspan']) && $ceil['rowspan'] > 1){
					$endLine = self::$line + ($ceil['rowspan'] - 1);
					$rowspanToColumn = \PHPExcel_Cell::stringFromColumnIndex($ceilIndex);
					$cellRange = $ceilIndexAsString.self::$line.':'.$rowspanToColumn.$endLine;
					self::$activeSheetObj->mergeCells($cellRange);
				}
				
                $styleObj = self::$activeSheetObj->getStyle($ceilIndexAsString.self::$line);      
				$styleArr = [];
                $styleArr['font'] = [];
                $styleArr['alignment'] = [];
                
                //设置单元格背景颜色
                if(isset($ceil['background-rgba'])){
                    //echo 100;
                    $styleObj->getFill()->setFillType(\PHPExcel_Style_Fill::FILL_SOLID);
                    $styleObj->getFill()->getStartColor()->setARGB($ceil['background-rgba']);;
                }
                //设置单元格边框
                if(isset($ceil['border-type'])){
                    switch ($ceil['border-type']) {
                        case 'thick':
                            $styleArr['borders']['allborders']['style'] = \PHPExcel_Style_Border::BORDER_THICK;
                            break;
                        default:
                            $styleArr['borders']['allborders']['style'] = \PHPExcel_Style_Border::BORDER_THIN;
                            break;
                    }
                }
                //设置单无格边框颜色
                if(isset($ceil['border-color'])){
                    $styleArr['borders']['allborders']['color'] = ['argb'=>$ceil['border-color']];
                }
                //设置字体大小
                if(isset($ceil['font-size'])){
                    $styleArr['font']['size'] = $ceil['font-size'];
                }
                //设置是否加粗
                if(isset($ceil['font-weight']) && $ceil['font-weight']){
                    $styleArr['font']['bold'] = true;
                }
                //设置字体颜色
                if(isset($ceil['color'])){
                    $styleArr['font']['color'] = ['argb'=>$ceil['color']];
                }
                //文本对齐方式
                if(isset($ceil['align'])){
                    switch ($ceil['align']) {
                        case 'center':
                            $styleArr['alignment']['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_CENTER;
                            break;
                        case 'right':
                            $styleArr['alignment']['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_RIGHT;
                            break;
                        default:
                            $styleArr['alignment']['horizontal'] = \PHPExcel_Style_Alignment::HORIZONTAL_LEFT;
                            break;
                    }
                }
                //垂直居中方式
                if(isset($ceil['valign'])){
                    switch ($ceil['valign']) {
                        case 'center':
                            $styleArr['alignment']['vertical'] = \PHPExcel_Style_Alignment::VERTICAL_CENTER;
                            break;
                        case 'right':
                            $styleArr['alignment']['vertical'] = \PHPExcel_Style_Alignment::VERTICAL_CENTER;
                            break;
                        default:
                            $styleArr['alignment']['vertical'] = \PHPExcel_Style_Alignment::VERTICAL_CENTER;
                            break;
                    }
                }
                $styleObj->applyFromArray($styleArr);
			}
            $ceilIndex ++;
        }
        self::$line ++;
    }

    /**
     * 批量设置行高
     * @param $line
     * ['行1'=>'行1高',
     *  '行2'=>'行2高']
     */
    public function setLineHeight($line)
    {
        if(!is_array($line)){
            return false;
        }
        foreach($line as $key=>$val){
            self::$activeSheetObj->getRowDimension($key)->setRowHeight($val);
        }
    }

    /**
     * 批量设置列宽
     * @param $column 要设置的列
     * ['列1'=>'列1宽',
     *  '列2'=>'列2宽']
     */
    public static function setColumnWidth($column)
    {
        if(!is_array($column)){
            return false;
        }
        foreach($column as $key=>$val){
            self::$activeSheetObj->getColumnDimension($key)->setWidth($val);
        }
    }
}