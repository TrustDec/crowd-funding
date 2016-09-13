<?php
//$param['deal_id'],
class lottery_luckyers_auto_cache extends auto_cache{
	public function load($param)
	{
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$return = $GLOBALS['cache']->get($key);
		if($return === false)
		{
			$deal_id=intval($param['deal_id']);
			if($deal_id)
			{
				$winner_sn=array();//幸运号
				$sn_section=array();//幸运号所属号段;
				$deal_item_list=$GLOBALS['db']->getAll("select * from ".DB_PREFIX."deal_item where deal_id=".$deal_id." and type=2 order by id asc");
				
				foreach($deal_item_list as $k=>$v)
				{
					$lottery_list=$GLOBALS['db']->getAll("select lot.* from ".DB_PREFIX."deal_order_lottery as lot left join ".DB_PREFIX."deal_order as ord on ord.id = lot.order_id where lot.is_winner=0 and lot.deal_id=".$deal_id." and lot.deal_item_id=".$v['id']." and ord.order_status =3 order by id asc");
					if($lottery_list)
					{
						//总数
						$total_count=count($lottery_list);
						$lottery_numer=ceil($total_count/$v['lottery_measure']);
						$yu=$total_count%$v['lottery_measure'];
						
						for($i=1;$i<=$lottery_numer;++$i)
						{
							$time_sum=0;
							if( $i==$lottery_numer &&  $yu >0 )
							{
								$start_key=($i-1)*$v['lottery_measure'];
								$last_key=$total_count-1;
								$sum_last_key=$last_key;
								if($yu<10)
								{	
									$last_itme=microtime_format($lottery_list[$sum_last_key]['time_msec'],'Hisx');
									$time_sum=$last_itme*10;
								}else
								{
									for($j=0;$j<10;++$j)
									{
										$time_sum+=microtime_format($lottery_list[$sum_last_key]['time_msec'],'Hisx');
										--$sum_last_key;
									}
								}
									
							}else
							{
								$start_key=($i-1)*$v['lottery_measure'];
								$last_key=$i*$v['lottery_measure']-1;
								$sum_last_key=$last_key;
								for($j=0;$j<10;++$j)
								{
									$time_sum+=microtime_format($lottery_list[$sum_last_key]['time_msec'],'Hisx');
									--$sum_last_key;
								}
							}
							
							if($i==$lottery_numer &&  $yu >0)
								$winner_num=$time_sum%$yu+10000001+($i-1)*$v['lottery_measure'];//最后一次，不满抽奖量数，按最后一次个数（余数）求余
							else
								$winner_num=$time_sum%$v['lottery_measure']+10000001+($i-1)*$v['lottery_measure'];
							$winner_sn[]=$v['id'].$winner_num;
							//list($start_item, $start_sn)=explode('_',$lottery_list[$start_key]['lottery_sn']);
							//list($last_item, $last_sn)=explode('_',$lottery_list[$last_key]['lottery_sn']);
							$start_sn_array=split_lottery_sn($lottery_list[$start_key]['lottery_sn']);
							$start_sn=$start_sn_array['sn_number'];
							
							$last_sn_array=split_lottery_sn($lottery_list[$last_key]['lottery_sn']);
							$last_sn=$last_sn_array['sn_number'];
							
							$sn_section[$v['id'].$winner_num]=array(
																		 'number'=>$v['id']."_".$i,
																		 'section_view'=>$lottery_list[$start_key]['lottery_sn']."~".$lottery_list[$last_key]['lottery_sn'],
																		 'section'=>array('start_sn'=>$start_sn,'last_sn'=>$last_sn)
																	   );
						}
						//for end
					}//if end
				}//foreach end
				
				$winner_list=$GLOBALS['db']->getAll("select lot.*,ord.deal_price from ".DB_PREFIX."deal_order_lottery as lot left join ".DB_PREFIX."deal_order as ord on ord.id = lot.order_id where lot.deal_id=".$deal_id." and lot.lottery_sn in ('".implode("','",$winner_sn)."')  and ord.order_status =3 order by lot.id asc");
				$winner_list_array=array();
				foreach($winner_list as $k=>$v)
				{
					$key_val=$sn_section[$v['lottery_sn']]['number'];
					$winner_list_array[$key_val]=$v;
					$winner_list_array[$key_val]['number']=$key_val;
					$winner_list_array[$key_val]['section_view']=$sn_section[$v['lottery_sn']]['section_view'];
					$winner_list_array[$key_val]['section']=$sn_section[$v['lottery_sn']]['section'];
				}
				$return['winner_list']=$winner_list_array;
			}else{
				$return=array();
			}

			$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
			$GLOBALS['cache']->set($key,$return);
		}
		return $return;
	}
	public function rm($param)
	{
		$key = $this->build_key(__CLASS__,$param);
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->rm($key);
	}
	public function clear_all()
	{
		$GLOBALS['cache']->set_dir(APP_ROOT_PATH."public/runtime/data/".__CLASS__."/");
		$GLOBALS['cache']->clear();
	}
}
?>