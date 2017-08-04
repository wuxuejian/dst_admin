<?php
namespace common\classes;

class Category{
	/**
	 *	说明：实现无限极分类，重组的是一个新的二维数组，让父和子压到一个数组中
	 *	@param $cates 需要重组的分类数据
	 *	@param $html 区分层级的html
	 *	@param $id 需要被寻找子级的分类的id
	 *	@param $level 当前的级别
	 *	@return $arr 返回重组后的数组
	 */
	public static function unlimitedForLever($cates,$html='--',$id = 0,$level = 0){
		$arr = array();//定义一个空数组用于存入筛选后的信息
		foreach($cates as $cate){
			if($cate['pid'] == $id){
				$cate['html'] = str_repeat($html,$level);
				$cate['level'] = $level+1;
				$arr[] = $cate;
				$arr = array_merge($arr,self::unlimitedForLever($cates,$html,$cate['id'],$level+1));
			}
		}
		return $arr;
	}
	/**
	 * 说明：实现无限极分类，重组为一个新的多维数组
	 * @param  array  $cates     需要重组的分类数据
	 * @param  string $parentKey 数据表中保存的与父级关联的字段的名称
	 * @param  int    $pid       表示需要从哪一层开始重组
	 * @return array  $arr       返回重组后的数组
	 */
	public static function unlimitedForLayer($cates,$parentKey='pid',$pid=0){
		$arr = array();//定义一个空数组用于存入筛选后的信息
		foreach($cates as $cate){
			if($cate[$parentKey] == $pid){
                $r = self::unlimitedForLayer($cates,$parentKey,$cate['id']);
				$cate['children'] = $r;
				$arr[] = $cate;
			}
		}
		return $arr;
	}
	/**
	 *	说明：通过一个子分类找到它的所有的上级分类
	 *	@param $cates 分类种子
	 *	@param $id 当前子分类的id
	 *	@param $field 取出一个指定的字段
	 *	@return $arr 返回查找出的结果数组
	 */
	public static function getParents($cates,$id,$field=''){
		$arr = array();//定义一个空数组用于存入筛选后的信息
		foreach ($cates as $cate){
			if($cate['id'] == $id){
				//判断是否要拿出一个指定字段
				if(empty($field)){
					$arr[] = $cate;
				}else{
					$arr[] = $cate[$field];
				}
				$arr = array_merge(self::getParents($cates,$cate['pid'],$field),$arr);
			}
		}
		return $arr;
	}
	/**
	 *	说明：通过一个父分类找到它的所有子孙的id
	 *	@param $cates 分类种子
	 *	@param $id 当前父分类的id
	 *	@return $arr 返回查找出的结果数组
	 */
	public static function getChildsId($cates,$id){
		$arr = array();//定义一个空数组用于存入筛选后的信息
		foreach($cates as $cate){
			if($cate['pid'] == $id){
				$arr[] = $cate['id'];
				$arr = array_merge($arr,self::getChildsId($cates,$cate['id']));
			}
		}
		return $arr;
	}
}