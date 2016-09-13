<?php
// +----------------------------------------------------------------------
// | Fanwe 方维众筹商业系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 甘味人生(526130@qq.com)
// +----------------------------------------------------------------------

class tree{
 /**
     +----------------------------------------------------------
     * 把返回的数据集转换成Tree
     +----------------------------------------------------------
     * @access public
     +----------------------------------------------------------
     * @param array $list 要转换的数据集
     * @param string $pid parent标记字段
     * @param string $level level标记字段
     +----------------------------------------------------------
     * @return array
     +----------------------------------------------------------
     */
    public function toTree($list, $pk='id',$pid = 'pid',$child = '_child')
    {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            
            foreach ($list as $key => $data) {
                $_key = is_object($data)?$data->$pk:$data[$pk];
                $refer[$_key] =& $list[$key];
            }            
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = is_object($data)?$data->$pid:$data[$pid];
                $is_exist_pid = false;
                foreach($refer as $k=>$v)
                {
                	if($parentId==$k)
                	{
                		$is_exist_pid = true;
                		break;
                	}
                }
                if ($is_exist_pid) { 
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                } else {
                    $tree[] =& $list[$key];
                }
            }
        }
        return $tree;
    }
    
    
	/**
	 * 将格式数组转换为树
	 *
	 * @param array $list
	 * @param integer $level 进行递归时传递用的参数
	 */
	private $formatTree; //用于树型数组完成递归格式的全局变量
	private function _toFormatTree($list,$level=0,$title = 'title') 
	{
			  foreach($list as $key=>$val)
			  {
				$tmp_str=str_repeat("&nbsp;&nbsp;",$level*2);
				$tmp_str.="";

				$val['level'] = $level;
				$val['title_show'] = $tmp_str.$val[$title];
				if(!array_key_exists('_child',$val))
				{
				   array_push($this->formatTree,$val);
				}
				else
				{
				   $tmp_ary = $val['_child'];
				   unset($val['_child']);
				   array_push($this->formatTree,$val);
				   $this->_toFormatTree($tmp_ary,$level+1,$title); //进行下一层递归
				}
			  }
			  return;
	}
	
	public function toFormatTree($list,$title = 'title')
	{
		$list = $this->toTree($list);
		$this->formatTree = array();
		$this->_toFormatTree($list,0,$title);
		return $this->formatTree;
	}
	public function toNameFormatTree($list,$title = 'name')
	{
		$list = $this->toTree($list);
		$this->formatTree = array();
		$this->_toFormatTree($list,0,$title);
		return $this->formatTree;
	}
}
?>