<?php

//获得友情链接
function get_link_by_id($id, $limit = "") {
    if (empty($limit)) {
        $limit = "";
    } else {
        $limit = " limit $limit";
    }
    $g_links = $GLOBALS['db']->getAllCached("select * from " . DB_PREFIX . "link where is_effect = 1 and show_index = 1 and group_id = $id order by sort desc" . $limit);
    if ($g_links) {
        foreach ($g_links as $kk => $vv) {
            if (substr($vv['url'], 0, 7) == 'http://') {
                $g_links[$kk]['url'] = $vv['url'];
            }
        }
    }
    return $g_links;
}

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
