<?php
//获取商城公告
function get_notice($limit='', $type_id=2,$cate_id=0,$date_form='Y-m-d') {
    
    if (!empty($limit)) {
        $limit_str = " limit " . $limit;
    } else {
        $limit_str = "";
    }
	
    if($cate_id>0){
    	if($cate_id==24){
    		//echo "select a.*,ac.type_id from " . DB_PREFIX . "article as a left join " . DB_PREFIX . "article_cate as ac on a.cate_id = ac.id where ac.id = $cate_id and ac.is_effect = 1 and ac.is_delete = 0 order by a.sort desc " . $limit_str;exit;
    	}
         $list = $GLOBALS['db']->getAllCached("select a.*,ac.type_id from " . DB_PREFIX . "article as a left join " . DB_PREFIX . "article_cate as ac on a.cate_id = ac.id where ac.id = $cate_id and ac.is_effect = 1 and ac.is_delete = 0 order by a.sort desc " . $limit_str);
    }
    else{
        $list = $GLOBALS['db']->getAllCached("select a.*,ac.type_id from " . DB_PREFIX . "article as a left join " . DB_PREFIX . "article_cate as ac on a.cate_id = ac.id where ac.type_id = $type_id and ac.is_effect = 1 and ac.is_delete = 0 order by a.sort desc " . $limit_str); 
    }
    
    foreach ($list as $k => $v) {
        $module = 'article';
        $list[$k]['content']=mb_substr($v['content'],0,20);
        $list[$k]['url'] = url_shop($module, $v['id'], $v['uname']);
        $list[$k]['date']=date($date_form,$v['update_time']);
    }
    return $list;
}
?>