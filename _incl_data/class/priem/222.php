<?
if(!defined('GAME')) {
	die();
}
/*
	Прием: Последний удар
	Следующий удар по противнику наносит на 4 ед. больше
*/
$pvr = array();
if( isset($pr_tested_this) ) {
		$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl;	
		//
		//Параметры приема
		$pvr['used'] = 0;
		//		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid1 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid2 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {	
				mysql_query('UPDATE `eff_users` SET `delete` = "'.time().'" WHERE `id` = "'.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].'" AND `uid` = "'.$uid.'" LIMIT 1');
				unset($btl->stats[$btl->uids[$uid]]['u_priem'][$j_id]);
		}
		//
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}elseif( isset($pr_used_this) ) { 
	$fx_priem = function(  $id , $at , $uid, $j_id ) {
		// -- начало приема
		global $u, $btl;	
		//
		//Параметры приема
		$pvr['used'] = 0;
		//		
		$uid1 = $btl->atacks[$id]['uid1'];
		$uid2 = $btl->atacks[$id]['uid2'];			
		if( $uid == $uid1 ) {
			$a = 1;
			$b = 2;
			$u1 = ${'uid1'};
			$u2 = ${'uid2'};
		}elseif( $uid == $uid2 ) {
			$a = 2;
			$b = 1;
			$u1 = ${'uid2'};
			$u2 = ${'uid1'};
		}
		if( $a > 0 ) {
			$j = 0; $k = 0; $wp = 3;
			while($j < count($at['p'][$a]['atack'])) {
				if( isset($at['p'][$a]['atack'][$j]['yron']) && (
				$at['p'][$a]['atack'][$j][1] == 1 ||
				$at['p'][$a]['atack'][$j][1] == 4 ||
				$at['p'][$a]['atack'][$j][1] == 5 )) {
					if( $pvr['used'] == 0 && !isset($at['p'][$a]['priems']['kill'][$uid][$j_id]) ) {
						//
						$pvr['procgo'] = 50 + ( 1 * $btl->users[$btl->uids[$u1]]['tactic7'] );
						$pvr['procgo'] = round($pvr['procgo']/100);
						$at['p'][$a]['atack'][$j]['yron']['y'] += round($at['p'][$a]['atack'][$j]['yron']['y']*$pvr['procgo']);
						$at['p'][$a]['atack'][$j]['yron']['r'] += round($at['p'][$a]['atack'][$j]['yron']['r']*$pvr['procgo']);
						$at['p'][$a]['atack'][$j]['yron']['k'] += round($at['p'][$a]['atack'][$j]['yron']['k']*$pvr['procgo']);
						$at['p'][$a]['atack'][$j]['yron']['m_y'] += round($at['p'][$a]['atack'][$j]['yron']['m_y']*$pvr['procgo']);
						$at['p'][$a]['atack'][$j]['yron']['m_k'] += round($at['p'][$a]['atack'][$j]['yron']['m_k']*$pvr['procgo']);
						//
						$at['p'][$a]['atack'][$j]['yron']['plog'][] = '$this->deleffm(222,'.(0+$uid).','.$btl->stats[$btl->uids[$uid]]['u_priem'][$j_id][3].');
							$this->priemAddLog( '.$id.', '.$a.', '.$b.', '.$u1.', '.$u2.',
							"Последний удар",
							"{tm1} '.$btl->addlt($a , 17 , $btl->users[$btl->uids[$u1]]['sex'] , NULL).'",
						'.($btl->hodID + 1).' );';						
						//
						$at['p'][$a]['atack'][$j]['yron']['used'][] = array($j_id,$uid,$pvr['used']);
						$at['p'][$a]['atack'][$j]['yron']['kill'][] = array($j_id,$uid,$pvr['kill']);
						//
						$at['p'][$a]['priems']['kill'][$uid][$j_id] = true;
						//Обновляем НР текущего игрока
						mysql_query('UPDATE `stats` SET `hpNow` = -10000,`tactic7` = 0 WHERE `id` = "'.$u1.'" LIMIT 1');
						$btl->users[$btl->uids[$u1]]['hpNow'] = -10000;
						$btl->stats[$btl->uids[$u1]]['hpNow'] = -10000;
						$btl->users[$btl->uids[$u1]]['tactic7'] = 0;
						$btl->stats[$btl->uids[$u1]]['tactic7'] = 0;
						//
						$pvr['used']++;
					}
				}
				$j++;
			}	
		}
		// -- конец приема
		return $at;
	};
	unset( $pr_used_this );
}else{
	//Действие при клике
	$this->addEffPr($pl,$id);
}
unset($pvr);
?>