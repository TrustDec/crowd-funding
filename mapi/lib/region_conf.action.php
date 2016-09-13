<?php
/**
 * @author 作者 
 * @version 创建时间：2015-3-27 上午11:00:34 类说明 省份城市列表
 */
class region_conf
{
	public function index()
	{
		$min_region_level = $GLOBALS ['db']->getOne ( "select min(region_level) from " . DB_PREFIX . "region_conf" );
		
		$sql = "select id,pid,name,region_level from " . DB_PREFIX . "region_conf where region_level = $min_region_level order by pid";
		$region_list = $GLOBALS ['db']->getAll ( $sql );
		foreach ( $region_list as $k => $v )
		{
			$this->getNext ( $region_list [$k], $v ['id'] );
		}
		
		$root ['response_code'] = 1;
		$root ['region_list'] = $region_list;
		output ( $root );
	}
	function getNext(&$region, $pid)
	{
		$sql = "select id,pid,name,region_level from " . DB_PREFIX . "region_conf where pid = " . $pid . " or region_level=2";
		$list = $GLOBALS ['db']->getAll ( $sql );
		
		if ($list === false)
		{
			$region ['child'] = array ();
		} else
		{
			$region ['child'] = $list;
			foreach ( $region ['child'] as $k => $v )
			{
				$this->getNext2 ( $region ['child'] [$k], $v ['id'] );
			}
		}
	}
	private function getNext2(&$region, $pid)
	{
		$sql = "select id,pid,name,region_level from " . DB_PREFIX . "region_conf where pid = " . $pid;
		$list = $GLOBALS ['db']->getAll ( $sql );
		if ($list === false)
		{
			$region ['child'] = array ();
		} else
		{
			$region ['child'] = $list;
			foreach ( $region ['child'] as $k => $v )
			{
				$this->getNext2 ( $region ['child'] [$k], $v ['id'] );
			}
		}
	}
}

?>