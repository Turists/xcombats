<?
if(!defined('GAME')) { die(); }

if($u->room['file'] == 'dungeon_enter_all') {

	if(isset($_GET['repshop'])) {
		include('repshop.php');
		die();
	}elseif(isset($_GET['repsale'])) {
		if(isset($_GET['repitm'])) {
			$id = (int)$_GET['repitm'];
			$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.*, count(`iuu`.id) as inGroupCount
				FROM `items_users` AS `iu`
				LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`)
				LEFT JOIN `items_users` as `iuu` ON (`iuu`.inGroup = `iu`.inGroup AND `iuu`.item_id = `im`.id )
				WHERE `iuu`.`uid`="'.$u->info['id'].'" AND `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
			$po = $u->lookStats($itm['data']);	
			if( !isset($itm['id']) ) {
				
			}elseif( $po['frompisher'] <= 1 ) {
				
			}else{
				$col = $u->itemsX($itm['id']);
				mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
				if($col>1){
					mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `item_id`="'.$itm['item_id'].'" AND `uid`="'.$itm['uid'].'" AND `inGroup` = "'.$itm['inGroup'].'" LIMIT '.$col.'');	
				}
				$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.Repshop</font>&quot;: ������� &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] ��� ������� �� '.($col).' ��������� ����� ��������!.',time(),$u->info['city'],'System.Repshop',0,0);
				echo '<font color="red">�� ������� �������� &quot;'.$itm['name'].' (x'.$col.')&quot; �� '.$col.' ��. ��������� ����� ��������</font><br>';
				$u->rep['repitems'] += $col;
				mysql_query('UPDATE `rep` SET `repitems` = "'.$u->rep['repitems'].'" WHERE `id` = "'.$u->rep['id'].'" LIMIT 1');
			}
		}
		//������� ���� � ��������� ��� �������
		$itmAll = $u->genInv(70,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD`  DESC');
		if($itmAll[0]==0) {
			$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">�����</td></tr>';
		}else{
			$itmAllSee = $itmAll[2];
		}
		echo '<h3><small style="float:left;color:blue;">��������� ����� ��������: '.($u->rep['repitems']-$u->rep['nu_items']).' ��.</small>�������� ��� ������ �� ��������� <input onclick="location.href=\'main.php?rz=1\'" type="button" value="���������" style="float:right" class="btnnew"><input onclick="location.href=\'main.php?repsale\'" type="button" value="��������" style="float:right" class="btnnew"></h3><br>';
		echo '<table width="100%" border="0" style="border:1px solid #aaa;" cellpadding="0" cellspacing="0">'.$itmAllSee.'</table>';
		die();
	}

	$error = ''; // �������� ������.
	$dungeonGroupList = ''; // ���� �������� ������ �����.
	$dungeonGo = 1; // �� ���������, �� ���� � ������.
	$dungeon = mysql_fetch_assoc( mysql_query('SELECT `id` as room, city, `dungeon_room` as d_room, city, `shop`, `dungeon_id` as id, `dungeon_name` as name, quest FROM `dungeon_room` WHERE `id`="'.$u->room['id'].'" LIMIT 1') );

	//
	$dungeon['quest'] = 1;
	//

	$dunname = array(
		12 => array('capitalcity','������ ������ ���������'),
		3 => array('demonscity','���������'),
		101 => array('angelscity','������'),
		105 => array('sandcity','������ ����'),
		108 => array('emeraldscity','���������� ����'),
		10 => array('suncity','��������'),
		106 => array('devilscity','�������� ������'),
		0 => array('items','���� ��������'),
	);
	if($u->info['admin'] > 0) $dunname[999] = array('capitalcity','�������� �������');
if( $dungeon['id'] == 104 && isset($_GET['freego'])) {
	if( $u->info['level'] > 7 ) {
		
	}elseif( $u->info['money4'] < $u->info['level'] * 5 ) {
		echo '<div><b style="color:#F00">������������ �����, ���������� '.$u->zuby( ($u->info['level'] * 5) ,1).'</b></div>';
	}else{
		mysql_query('DELETE FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `vars` LIKE "psh%" AND `vars` NOT LIKE "psh_%" AND `time` >= '.(time()-60*60*3).' LIMIT 1');
		$u->info['money4'] -= $u->info['level'] * 5;
		mysql_query('UPDATE `users` SET `money4` = "'.$u->info['money4'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		echo '<div><b style="color:#F00">�� ������� ����� �������� �� ����� �� '.$u->zuby( ($u->info['level'] * 5) ,1).'</b></div>';
	}
}

if(isset($_GET['rz']) && $dungeon['quest'] == 1) $roomSection = 1; // �������� �������
	else $roomSection = 0;  // �������� ������ ��� ������
//if( $u->info['admin'] > 0 ) var_info($dungeon);

$all_dungeon = mysql_query('SELECT `city` FROM `dungeon_room` WHERE `city` IS NOT NULL AND `active`=1 ');
while( $t = mysql_fetch_array($all_dungeon) ) { $dungeon['list'][] = $t['city']; }
unset($all_dungeon);
 
if( $u->info['dn'] > 0 ) {
	$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `id`="'.$u->info['dn'].'" AND `delete` = "0" LIMIT 1'));
	if(!isset($zv['id'])){
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
	}
}

//$dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh0" AND `time` > '.(time()-60*60*3).' LIMIT 1',1);
//if($u->info['admin']>0) unset($dungeon_timeout); // $dungeon_timeout - �������� �� ��������� ������.
/*
if(isset($dungeon_timeout['id'])) // ���-�� ��������� � �� ������ � ������, ���-��� ��� ��� ���.
{
	$dungeonGo = 0;
	if(isset($_GET['start'])){
		$error = '�� ���������� ������ �������� ���: '.$u->timeOut(60*60*3-time()+$dungeon_timeout['time']);
	}
}
*/

if(isset($zv['id'])) {
	$dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh'.$zv['dun'].'" AND `time` > '.(time()-60*60*4).' LIMIT 1',1);
	if(isset($dungeon_timeout['id'])) // ���-�� ��������� � �� ������ � ������, ���-��� ��� ��� ���.
	{
		$dungeonGo = 0;
		if(isset($_GET['start'])){
			$error = '�� ���������� ������ �������� ���: '.$u->timeOut(60*60*4-time()+$dungeon_timeout['time']);
		}
	}
}

if( isset( $_GET['start'] ) && $zv['uid'] == $u->info['id'] && $dungeonGo == 1 ) {
	$ig = 1;
	if( $ig > 0 ){ //���������� ������� � ������
		//$u->addAction(time(),'psh'.$dun,'');
		
		//
		$dungeon['room'] = 321;
		$dungeon['id'] = $zv['dun'];	
		//
		$ins = mysql_query('INSERT INTO `dungeon_now` (`city`,`uid`,`id2`,`name`,`time_start`)
		VALUES ("'.$zv['city'].'","'.$zv['uid'].'","'.$zv['dun'].'","'.$dunname[$zv['dun']][1].'","'.time().'")');
		if($ins){
			$zid = mysql_insert_id();
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			//��������� �������������
			$su = mysql_query('SELECT `u`.`id`,`st`.`dn` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$zv['id'].'" /*LIMIT '.($zv['team_max']+1).'*/');
			$ids = '';
			
			$map_locs = array();
			$spm2 = mysql_query('SELECT `id`,`x`,`y` FROM `dungeon_map` WHERE `id_dng` = "'.$zv['dun'].'"');
			while( $plm2 = mysql_fetch_array( $spm2 ) ) {
				$map_locs[] = array($plm2['x'],$plm2['y']);
			}
			unset( $spm2 , $plm2 );
						
			$pxd = 0;
			while( $pu = mysql_fetch_array($su) ) {
				$pxd++;
				$ids .= ' `id` = "'.$pu['id'].'" OR';
				if( $u->stats['silver'] >= 3 ) {
					$u->addAction( ( time() - ((60*60*3)/100*30) ) ,'psh'.$zv['dun'].'',$pu['id'],$pu['id']);
				}else{
					$u->addAction(time(),'psh'.$zv['dun'].'',$pu['id'],$pu['id']);
				}
				//
				mysql_query('UPDATE `dailybonus` SET `dun_go` = `dun_go` + 1 WHERE `date_finish` != "'.date('d.m.Y').'" AND `uid` = "'.$pu['id'].'" LIMIT 1');
				//
				//��������� ��������� ������� ��� ���������� 
				$sp = mysql_query('SELECT * FROM `actions` WHERE `uid` = "'.$u->info['id'].'" AND `room` = '.$dungeon['room'].' AND `vars` LIKE "%start_quest%" AND `vals` = "go" LIMIT 100');
				while($pl2 = mysql_fetch_array($sp)){
					$pl = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.(str_replace('start_quest','',$pl2['vars'])).'" AND `line` = "'.$dungeon['id'].'" LIMIT 1')); 
					if( isset($pl['id']) ) {
						$act = explode(',',$pl['act_date']);
						$i = 0;
						while( $i < count($act) ) {
							$act_date = explode(':|:',$act[$i]);
							foreach($act_date as $key=>$val){ 
								$val = explode(':=:',$val);
								$actdate[$val[0]] = $val[1];
 							}
							//���� ��������
							if( isset($actdate['tk_itm']) && $actdate['tk_itm'] != '' ) {								
								$xr2 = explode('=',$actdate['tk_itm']);
								if( $xr2[2] == 0 ) {
									if( isset($actdate['tk_itm_fromY']) && isset($actdate['tk_itm_toY']) ) {
										$actdate['tk_itm_fromY'] = (integer)$actdate['tk_itm_fromY'];
										$actdate['tk_itm_toY'] = (integer)$actdate['tk_itm_toY'];
									}
									$ml_arr = array();
									foreach($map_locs as $ml){ // tk_itm_fromY  tk_itm_toY  - ��������� ������� ��� ����� ���������.
										if( (isset($actdate['tk_itm_fromY']) && isset($actdate['tk_itm_toY'])) OR (!isset($actdate['tk_itm_fromY']) && isset($actdate['tk_itm_toY'])) ) {
											if( $ml[1] > $actdate['tk_itm_fromY'] && $actdate['tk_itm_toY'] > $ml[1] )$ml_arr[] = $ml;
											elseif( !isset($actdate['tk_itm_fromY']) && $actdate['tk_itm_toY'] > $ml[1] ) $ml_arr[] = $ml;
										} else $ml_arr[] = $ml;
									}
									if( isset($ml_arr) && count($ml_arr) == 0 ) $ml_arr = $map_locs; 
									//��������� ������ ��� �����
									$j = 0;
									while( $j < $xr2[1] ){
										$cord = $ml_arr[rand(0,count($ml_arr)-1)];
										if( $cord[0] != 0 || $cord[1] != 0 ) {
											mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`,`onlyfor`,`quest`) VALUES (
												"'.$zid.'","'.$u->info['id'].'","'.$xr2[0].'","'.time().'","'.$cord[0].'","'.$cord[1].'","'.$u->info['id'].'","'.$pl['id'].'"
											)');
										}
										$j++;
									}
								}else{
									//������� ��������� � ���������� �����
									mysql_query('INSERT INTO `dungeon_items` (`dn`,`user`,`item_id`,`time`,`x`,`y`,`onlyfor`,`quest`) VALUES (
										"'.$zid.'","'.$u->info['id'].'","'.$xr2[0].'","'.time().'","'.$xr2[2].'","'.$xr2[3].'","'.$u->info['id'].'","'.$pl['id'].'"
									)');
								}
							}
							$i++;
						}
					}
				}
				
			}
			$ids = rtrim($ids,'OR');
			$snew = 1;
			$upd1 = mysql_query('UPDATE `stats` SET `s`="'.$snew.'",`res_s`="1",`x`="0",`y`="0",`res_x`="0",`res_y`="0",`dn` = "0",`dnow` = "'.$zid.'" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
			if( $upd1 ){
				$upd2 = mysql_query('UPDATE `users` SET `room` = "405" WHERE '.$ids.' LIMIT '.($zv['team_max']+1).'');
				//��������� ����� � ������� � ������ $zid � for_dn = $dungeon['id']
				//��������� �����
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "'.$zv['dun'].'"');
				while( $pl = mysql_fetch_array( $sp ) ) {
					if( $pl['id_bot'] == 0 && $pl['bot_group'] !=''){
						$bots = explode( ',', $pl['bot_group'] );
						$pl['id_bot'] = (int)$bots[rand(0, count($bots)-1 )];
					}
					if( $pl['id_bot'] > 0 )$vls .= '("'.$zid.'","'.$pl['id_bot'].'","'.$pl['colvo'].'","'.$pl['items'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['dialog'].'","'.$pl['items'].'","'.$pl['go_bot'].'","'.$pl['noatack'].'"),';
					unset($bots);
				}
				$vls = rtrim($vls,',');				
				$ins1 = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`,`go_bot`,`noatack`) VALUES '.$vls.'');
				//��������� �������
				$vls = '';
				$sp = mysql_query('SELECT * FROM `dungeon_obj` WHERE `for_dn` = "'.$zv['dun'].'"');
				while($pl = mysql_fetch_array($sp))
				{
					$vls .= '("'.$zid.'","'.$pl['name'].'","'.$pl['img'].'","'.$pl['x'].'","'.$pl['y'].'","'.$pl['action'].'","'.$pl['type'].'","'.$pl['w'].'","'.$pl['h'].'","'.$pl['s'].'","'.$pl['s2'].'","'.$pl['os1'].'","'.$pl['os2'].'","'.$pl['os3'].'","'.$pl['os4'].'","'.$pl['type2'].'","'.$pl['top'].'","'.$pl['left'].'","'.$pl['date'].'"),';
				}

				//���������� �������� (���� ������� ���������)
				if( floor(date('m')) == 2 && floor(date('d')) >= 7 && floor(date('d')) <= 20) {
					//if( floor(date('m')) == 2 && floor(date('d')) >= 14 && floor(date('d')) <= 20) {
						//���������� ���� ������� ��������� �����
						$vlsbts = '';
						$ins1bts = NULL;
						if( $zv['dun'] == 1 ) {
							//4 ������ (�����������)
							$vlsbts .='("'.$zid.'","410","1","","-5","3","8","0","0"),';
							//4-7 ������
							$vlsbts .='("'.$zid.'","413","1","","8","46","9","0","0"),';
						}elseif( $zv['dun'] == 12 ) {
							//(���)
							//4-7 ������
							$vlsbts .='("'.$zid.'","413","1","","-3","18","9","0","0"),';
							//4-9 ������
							$vlsbts .='("'.$zid.'","414","1","","-2","29","10","0","0"),';
						}elseif( $zv['dun'] == 3 ) {
							//(���������)
							//4-7 ������
							$vlsbts .='("'.$zid.'","413","1","","15","8","9","0","0"),';
							//4-9 ������
							$vlsbts .='("'.$zid.'","414","1","","3","35","10","0","0"),';
						}elseif( $zv['dun'] == 101 ) {
							//(������)
							//4-7 ������
							$vlsbts .='("'.$zid.'","413","1","","-2","21","9","0","0"),';
							//4-9 ������
							$vlsbts .='("'.$zid.'","414","1","","2","43","10","0","0"),';
						}
						
						if( $vlsbts != '' ) {
							$vlsbts = rtrim($vlsbts,',');
							$ins1bts = mysql_query('INSERT INTO `dungeon_bots` (`dn`,`id_bot`,`colvo`,`items`,`x`,`y`,`dialog`,`atack`,`go_bot`) VALUES '.$vlsbts.'');
						}
						unset($vlsbts,$ins1bts);
					//}
					//����������� �������� �� ������ (������ ���������)
					$dcords = array();
					$c_sp = mysql_query('SELECT * FROM `dungeon_map` WHERE `id_dng` = "'.$zv['dun'].'"');
					while( $c_pl = mysql_fetch_array($c_sp)) {
						$dcords[] = array($c_pl['x'],$c_pl['y']);
					}
					$fcords = array();
					$i = 1;
					while($i <= $pxd) {
						$j = rand(1,10);
						while( $j >= 0 ) {
							$rndxy = rand(0,count($dcords)-1);
							$rndx = $dcords[$rndxy][0];
							$rndy = $dcords[$rndxy][1];
							$fcords[$rndx][$rndy] = true;
							unset($dcords[$rndxy]);
							$vls .= '("'.$zid.'","������� ���������","vbig1.gif","'.$rndx.'","'.$rndy.'","fileact:vbig1","0","81","81","0","0","5","8","12","0","0","0","0","{use:\'takeit\',rt2:154,rl2:146,rt3:139,rl3:154,rt4:125,rl4:161}"),';
							$j--;
						}
						$i++;
					}
					//����������� �������� �� ������ (���������� ���������)
					$sp = mysql_query('SELECT * FROM `dungeon_bots` WHERE `for_dn` = "'.$zv['dun'].'"');
					$test = array();
					$dcords2 = array();
					$dcords3 = array();
					while( $pl = mysql_fetch_array( $sp ) ) {
						if(!isset($test[$pl['id_bot']])) {
							$test[$pl['id_bot']] = mysql_fetch_array(mysql_query('SELECT * FROM `test_bot` WHERE `id` = "'.$pl['id_bot'].'" LIMIT 1'));
						}
						if( isset($test[$pl['id_bot']]['id']) && $test[$pl['id_bot']] != 2 ) {
							if( $test[$pl['id_bot']]['level'] > 6 ) {
								$dcords2[] = array($pl['x'],$pl['y']);
							}
							if( $test[$pl['id_bot']]['level'] >= 8 && $test[$pl['id_bot']]['align'] == 9 ) {
								$dcords3[] = array($pl['x'],$pl['y']);
							}
						}else{
							$test[$pl['id_bot']] = 2;
						}
					}
					$i = 1;
					while($i <= $pxd) {
						$j = rand(1,5);
						while( $j >= 0 ) {
							$rndxy = rand(0,count($dcords2)-1);
							$rndx = $dcords2[$rndxy][0];
							$rndy = $dcords2[$rndxy][1];
							if(!isset($fcords[$rndx][$rndy]) && isset($dcords2[$rndxy][0])) {
								$fcords[$rndx][$rndy] = true;
								unset($dcords2[$rndxy]);
								$vls .= '("'.$zid.'","���������� ���������","vbig2.gif","'.$rndx.'","'.$rndy.'","fileact:vbig2","0","81","81","0","0","5","8","12","0","0","0","0","{use:\'takeit\',rt2:154,rl2:146,rt3:139,rl3:154,rt4:125,rl4:161}"),';
							}
							$j--;
						}
						$i++;
					}
					$i = 1;
					while($i <= $pxd) {
						$j = rand(1,2);
						while( $j >= 0 ) {
							$rndxy = rand(0,count($dcords3)-1);
							$rndx = $dcords3[$rndxy][0];
							$rndy = $dcords3[$rndxy][1];
							if(!isset($fcords[$rndx][$rndy]) && isset($dcords3[$rndxy][0])) {
								$fcords[$rndx][$rndy] = true;
								unset($dcords3[$rndxy]);
								$vls .= '("'.$zid.'","�������� ���������","vbig3.gif","'.$rndx.'","'.$rndy.'","fileact:vbig3","0","81","81","0","0","5","8","12","0","0","0","0","{use:\'takeit\',rt2:154,rl2:146,rt3:139,rl3:154,rt4:125,rl4:161}"),';
							}
							$j--;
						}
						$i++;
					}
					unset($test);
				}
				//
				$vls = rtrim($vls,',');	
				if( $vls != '' ) {			
					$ins2 = mysql_query('INSERT INTO `dungeon_obj` (`dn`,`name`,`img`,`x`,`y`,`action`,`type`,`w`,`h`,`s`,`s2`,`os1`,`os2`,`os3`,`os4`,`type2`,`top`,`left`,`date`) VALUES '.$vls.'');
				} else {
					$ins2 = true;
				}
				if( $upd2 && $ins1 && $ins2 ){
					die('<script>location="main.php?rnd='.$code.'";</script>');
				} else {
					$error = '������ �������� � ����������...';
				}
			} else {
				$error = '������ �������� � ����������...';
			}
		} else {
			$error = '������ �������� � ����������...';
		}
	}
} elseif( isset( $_POST['go'] , $_POST['goid'] ) && $dungeonGo == 1 ) {
	if(!isset($zv['id'])) {
		$zv = mysql_fetch_array(mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "all" AND `id`="'.mysql_real_escape_string($_POST['goid']).'" AND `delete` = "0" LIMIT 1'));
		if(isset($zv['id'])) {
			$dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh'.$zv['dun'].'" AND `time` > '.(time()-60*60*4).' LIMIT 1',1);
		}
		
		if( isset($dungeon_timeout['id'])) {
			$error = '�� ���������� ������ �������� ���: '.$u->timeOut(60*60*4-time()+$dungeon_timeout['time']);
		}elseif( isset( $zv['id'] ) && $u->info['dn'] == 0) {
			if( $zv['pass'] != '' && $_POST['pass_com'] != $zv['pass'] ) {
				$error = '�� ����� ������������ ������';				
			} elseif( $u->info['level'] > 7 && $zv['dun'] != 1 ){
				$row = 0;
				if( 5 > $row ) {
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if( !$upd ){
						$error = '�� ������� �������� � ��� ������';
						unset($zv);
					} else {
						$u->info['dn'] = $zv['id'];
					}
				} else {
					$error = '� ������ ��� �����';
					unset($zv);
				}
			} elseif( $zv['dun'] == 1 ){
				//�����������
				$row_max = 5;
				if( $u->info['level'] == 4 ) {
					$row_max = 4;
				}elseif( $u->info['level'] == 5 ) {
					$row_max = 3;
				}elseif( $u->info['level'] == 6 ) {
					$row_max = 2;
				}elseif( $u->info['level'] >= 7 ) {
					$row_max = 1;
				}
				$row = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 1'));
				$row1 = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 1'));
				$row2 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$row1['id'].'" LIMIT 1'));
				$row = $row[0];
				if( $row2['level'] != $u->info['level'] ) {
					$error = '�� �� ��������� �� ������';
				}elseif( $row_max > $row ) {
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if( !$upd ){
						$error = '�� ������� �������� � ��� ������';
						unset($zv);
					} else {
						$u->info['dn'] = $zv['id'];
					}
				} else {
					$error = '� ������ ��� �����';
					unset($zv);
				}
			} elseif( $u->info['level'] < 4 && $zv['dun'] == 104 ){
				//�����
				$row_max = 3;
				if( $u->info['level'] > 3 ) {
					$row_max = 1;
				}
				$row = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 1'));
				$row1 = mysql_fetch_array(mysql_query('SELECT * FROM `stats` WHERE `dn` = "'.$zv['id'].'" LIMIT 1'));
				$row2 = mysql_fetch_array(mysql_query('SELECT * FROM `users` WHERE `id` = "'.$row1['id'].'" LIMIT 1'));
				$row = $row[0];
				if( $row2['level'] != $u->info['level'] && $u->info['level'] > 3 ) {
					$error = '�� �� ��������� �� ������';
				}elseif( $row_max > $row ) {
					$upd = mysql_query('UPDATE `stats` SET `dn` = "'.$zv['id'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
					if( !$upd ){
						$error = '�� ������� �������� � ��� ������';
						unset($zv);
					} else {
						$u->info['dn'] = $zv['id'];
					}
				} else {
					$error = '� ������ ��� ��� ��� �����';
					unset($zv);
				}
			} else {
				$error = '�� �� ��������� �� ������';
				unset($zv);
			}
		} else {
			$error = '������ �� �������';
		}
	} else {
		$error = '�� ��� ���������� � ������';
	}
} elseif( isset( $_POST['leave'] ) && isset( $zv['id'] ) && $dungeonGo == 1 ) {
	if( $zv['uid'] == $u->info['id'] ) {
		//������ � ������ ������ ������������
		$ld = mysql_fetch_array(mysql_query('SELECT `id` FROM `stats` WHERE `dn` = "'.$zv['id'].'" AND `id` != "'.$u->info['id'].'" LIMIT 1'));
		if( isset($ld['id']) ){
			$zv['uid'] = $ld['id'];
			mysql_query('UPDATE `dungeon_zv` SET `uid` = "'.$zv['uid'].'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		} else {
			//������� ������ �������
			mysql_query('UPDATE `dungeon_zv` SET `delete` = "'.time().'" WHERE `id` = "'.$zv['id'].'" LIMIT 1');
			mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$u->info['dn'] = 0;
			unset($zv);
		}
	} else {
		//������ ������� � ������
		mysql_query('UPDATE `stats` SET `dn` = "0" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		$u->info['dn'] = 0;
		unset($zv);
	}
} elseif( isset($_POST['add']) && $u->info['level'] > 1 && $dungeonGo == 1 ) {
	if( $u->info['dn'] == 0 ) {	
		$dun5 = $dungeon['id'];	
		//
		if(isset($_POST['rpmg1'])) {
			if($_POST['rpmg1']==2) {
				$dun5 = 101; //������
			}elseif($_POST['rpmg1']==3) {
				$dun5 = 16; //������ ����
			}elseif($_POST['rpmg1']==4) {
				$dun5 = 3; //���������
			}elseif($_POST['rpmg1']==5) {
				$dun5 = 108; //���������� ����
			}elseif($_POST['rpmg1']==6) {
				$dun5 = 10; //��������
			}elseif($_POST['rpmg1']==7) {
				$dun5 = 15; //������
			}elseif($_POST['rpmg1']==999) {
				$dun5 = 999; //�������� �������
			}else{
				$dun5 = 12; //���
			}
		}else{
			$dun5 = 12; //���
		}
		//
		$ins = mysql_query('INSERT INTO `dungeon_zv`
		(`city`,`time`,`uid`,`dun`,`pass`,`com`,`lvlmin`,`lvlmax`,`team_max`) VALUES
		("all","'.time().'","'.$u->info['id'].'","'.$dun5.'",
		"'.mysql_real_escape_string($_POST['pass']).'",
		"'.mysql_real_escape_string($_POST['text']).'",
		"8",
		"21",
		"5")');
		if( $ins ) {
			$u->info['dn'] = mysql_insert_id();
			$zv['id'] = $u->info['dn'];
			$zv['uid'] = $u->info['id'];
			mysql_query('UPDATE `stats` SET `dn` = "'.$u->info['dn'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			$error = '�� ������� ������� ������';
		} else {
			$error = '�� ������� ������� ������';
		}
	} else {
		$error = '�� ��� ���������� � ������';
	}
}

//���������� ������ �����
$sp = mysql_query('SELECT * FROM `dungeon_zv` WHERE `city` = "all" AND `delete` = "0" AND `time` > "'.(time()-60*60*2).'"');
while( $pl = mysql_fetch_array( $sp ) ){
	$dungeonGroupList .= '<div style="padding:2px;">';
	if( $u->info['dn'] == 0 ) $dungeonGroupList .= '<input type="radio" name="goid" id="goid" value="'.$pl['id'].'" />';
	$dungeonGroupList .= '<span class="date">'.date('H:i',$pl['time']).'</span> ';
	
	$dungeonGroupList .= '<span><img title="'.$dunname[$pl['dun']][1].'" style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/'.$dunname[$pl['dun']][0].'.gif" /></span> ';
	
	$pus = ''; //������
	$su = mysql_query('SELECT `u`.`id`,`u`.`login`,`u`.`level`,`u`.`align`,`u`.`clan`,`st`.`dn`,`u`.`city`,`u`.`room` FROM `stats` AS `st` LEFT JOIN `users` AS `u` ON (`st`.`id` = `u`.`id`) WHERE `st`.`dn`="'.$pl['id'].'" LIMIT '.($pl['team_max']+1).'');
	while( $pu = mysql_fetch_array( $su ) ) {
		$pus .= '<b>'.$pu['login'].'</b> ['.$pu['level'].']<a href="info/'.$pu['id'].'" target="_blank"><img src="http://img.xcombats.com/i/inf_'.$pu['city'].'.gif" title="���. � '.$pu['login'].'"></a>'; 
		$pus .= ', ';
	}
	$pus = trim( $pus, ', ' );
	
	$dungeonGroupList .= $pus; unset($pus);
	
	if( $pl['pass'] != '' && $u->info['dn'] == 0 ) $dungeonGroupList .= ' <small><input type="password" name="pass_com" value=""></small>';
	
	if( $pl['com'] != '' ) {
		$dl = '';
		// ���� ���������, ���� ����������� ������� ����������� � ������.
		$moder = mysql_fetch_array(mysql_query('SELECT * FROM `moder` WHERE `align` = "'.$u->info['align'].'" LIMIT 1'));
		if( ( $moder['boi'] == 1 || $u->info['admin'] > 0 ) && $pl['dcom'] == 0 ){
			$dl .= ' (<a href="?delcom='.$pl['id'].'&key='.$u->info['nextAct'].'&rnd='.$code.'">������� �����������</a>)';
			if( isset( $_GET['delcom'] ) && $_GET['delcom'] == $pl['id'] && $u->newAct( $_GET['key'] ) == true ) {
				mysql_query('UPDATE `dungeon_zv` SET `dcom` = "'.$u->info['id'].'" WHERE `id` = "'.$pl['id'].'" LIMIT 1');
				$pl['dcom'] = $u->info['id'];
			}
		}
		$pl['com'] = htmlspecialchars($pl['com'],NULL,'cp1251');
		if( $pl['dcom'] > 0 ) {
			$dl = ' <font color="grey"><i>����������� ������ �����������</i></font>';
		}
		if( $pl['dcom'] > 0 ) {
			if( $moder['boi'] == 1 || $u->info['admin'] > 0 ) {
				$pl['com'] = '<font color="red">'.$pl['com'].'</font>';
			} else {
				$pl['com'] = '';
			}
		}
		$dungeonGroupList .= '<small> | '.$pl['com'].''.$dl.'</small>';
	}
	$dungeonGroupList .= '</div>';
}
?>
<style>
body {
	/*background-color: #E0E0E0;
	background-image: url(http://img.xcombats.com/i/misc/showitems/dungeon.jpg);
	background-repeat: no-repeat;
	background-position: top right;*/
}
</style>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td><div style="padding-left:0px;" align="center">
      <h3><? echo $u->room['name']; ?></h3>
    </div></td>
    <td width="200"><div align="right">
      <table cellspacing="0" cellpadding="0">
        <tr>
          <td width="100%">&nbsp;</td>
          <td>
          <? if($roomSection==0) { ?>
          <table  border="0" cellpadding="0" cellspacing="0">
              <tr align="right" valign="top">
                <td><!-- -->
                    <? echo $goLis; ?>
                    <!-- -->
                    <table border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td nowrap="nowrap"><table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                            <tr>
								<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
								<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=<? if($u->info['city']=='abandonedplain') { echo '3.180.0.267'; } elseif($u->info['city']=='fallenearth') { echo '6.180.0.102'; } elseif($u->info['room']==188) { echo '1.180.0.4'; } elseif($u->info['room']==393) { echo '1.180.0.2'; } elseif($u->info['room']==372) { echo '1.180.0.323'; }elseif($u->info['room']==395) { echo '2.180.0.231'; }elseif($u->info['room']==397) { echo '2.180.0.229'; } elseif($u->info['room']==242) { echo '2.180.0.236'; } elseif($u->info['room']==321) { echo '1.180.0.213'; } else { echo '1.180.0.321'; } ?>&rnd=<? echo $code; ?>';" title="<? 
								if($u->info['city']=='fallenearth'){
									thisInfRm('6.180.0.102',1); 
								}elseif($u->info['city']=='abandonedplain'){
									thisInfRm('3.180.0.267',1); 
								}elseif($u->info['room']==188){
									thisInfRm('1.180.0.4',1); 
								}elseif($u->info['room']==393){
									thisInfRm('1.180.0.2',1); 
								}elseif($u->info['room']==372){
									thisInfRm('1.180.0.323',1); 
								}elseif($u->info['room']==395){
									thisInfRm('2.180.0.231',1); 
								}elseif($u->info['room']==397){
									thisInfRm('2.180.0.229',1); 
								}elseif($u->info['room']==242){
									thisInfRm('2.180.0.236',1); 
								}elseif($u->info['room']==321){
									thisInfRm('1.180.0.213',1); 
								}else {
									thisInfRm('1.180.0.321',1);
								}
								?>"><?
								if($u->info['city']=='fallenearth'){
								  echo "������ ������";
								} elseif($u->info['city']=='abandonedplain'){
								  echo "����������� �������";
								} elseif($u->info['room']==188){
								  echo "��� ������";
								} elseif($u->info['room']==393){
								  echo "��� ������ 2";
								} elseif($u->info['room']==372){
								  echo "������� �������� �����";
								} elseif($u->info['room']==395){
								  echo "��� ������";
								} elseif($u->info['room']==397){
								  echo "��� ������ 2";
								} elseif($u->info['room']==242){
								  echo "����������� �����";
								} elseif($u->info['room']==321){
								  echo "������� �������� �����";
								} else {
								  echo "���������� ������";
								}
								?></a></td>
                            </tr>
							<tr>
								<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
								<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.408&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.408',1); ?>">��������� �������</a></td>
                            </tr>
							<tr>
								<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
								<td bgcolor="#D3D3D3" nowrap="nowrap"><a href="#" id="greyText" class="menutop" onclick="location='main.php?repshop&rnd=<? echo $code; ?>';" title="������� ���������">������� ���������</a></td>
                            </tr>
                        </table>
						</td>
                      </tr>
                  </table></td>
              </tr>
          </table>
          <? } ?>
          </td>
        </tr>
      </table>
      </div></td>
  </tr>
</table>
<? if( $roomSection == 1 ) { ?>
	<div align="center" style="float:right;width:100px;">
	  <p>
		<input style="width:84px" class="btnnew" type='button' onclick='location="main.php?rz=1"' value="��������" /><br />
		<input style="width:84px" class="btnnew" type='button' onclick='location="main.php"' value="���������" />
	  </p>
	</div>
	<? } else { ?>
	<div align="center" style="float:right;width:100px; padding-top:10px;">
		<input class="btnnew" type='button' style="width:84px;" onclick='location="main.php"' value="��������" /><br />
		<input class="btnnew" type='button' style="width:84px;" onclick='location="main.php?rz=1"' value="�������" />
	</div>
<? } ?>
<?
if($error!='')echo '<font color="red"><b>'.$error.'</b></font><br>';

//����������
if( $dungeonGroupList == '' ) {
	$dungeonGroupList = '';
} else {
	if( !isset( $zv['id'] ) || $u->info['dn'] == 0 ) {
		if($dungeonGo==1 || $u->info['dn'] == 0 ) {
			$pr = '<input name="go" type="submit" value="�������� � ������">';
		}
		$dungeonGroupList = '<form autocomplete="off" action="main.php?rnd='.$code.'" method="post">'.$pr.'<br>'.$dungeonGroupList.''.$pr.'</form>';
	}
	$dungeonGroupList .= '<hr>';
}

if( $roomSection == 0 ) { echo $dungeonGroupList; }
if( $roomSection == 1 ) { 
	# endQuest ��������� ������� �� �������.
	if( isset( $_GET['endQuest'] ) && $_GET['endQuest'] != '' ){ 
		$action = mysql_fetch_array(mysql_query('SELECT * FROM `actions` WHERE `uid` = '.$u->info['id'].' AND `id`="'.$_GET['endQuest'].'" AND `vals` = "go" LIMIT 1'));
		$quest = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$action['vars']).'" LIMIT 1'));
		if( $q->questCheckEnd($quest)==1 ){ 
			$q->questSuccesEnd($quest, $action);
		}
	}
?>
<div>
	<form autocomplete="off" action='/main.php' method="post" name="F1" id="F1">
<?
	$qsee = '';
	$hgo = $u->testAction('`uid` = "'.$u->info['id'].'" AND `room` = "'.$u->info['room'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_'.$dungeon['city'].'" LIMIT 1',1);
	$qc=0; // Quest Count
	$qcc = array();
	//���������� ������ ������� �������
	$sp = mysql_query('SELECT * FROM `actions` WHERE `vars` LIKE "%start_quest%" AND `vals` = "go" AND `uid` = "'.$u->info['id'].'" LIMIT 100');
	while( $pl = mysql_fetch_array( $sp ) ) {
		if($pl['room'] == $u->info['room']){
			$pq = mysql_fetch_array(mysql_query('SELECT * FROM `quests` WHERE `id` = "'.str_replace('start_quest','',$pl['vars']).'" LIMIT 1'));
			if( $q->questCheckEnd($pq)==1 ) $qsee2 = '<input style="margin-top:6px;" type="button" value="��������� �������" onclick="location=\'main.php?rz=1&amp;endQuest='.$pl['id'].'\'">'; else $qsee2 = '';
			 
			$qsee .= '
			<a href="main.php?rz=1&end_qst_now='.$pq['id'].'"><img src="http://img.xcombats.com/i/clear.gif" title="���������� �� �������"></a>
			<b>'.$pq['name'].'</b>
			<div style="padding-left:15px;padding-bottom:5px;border-bottom:1px solid grey"><small>'.$pq['info'].'<br>'.$q->info($pq).''.$qsee2.' </small></div>
			<br>';
			#
			$qcc[$pq['line']]++;
			# 
			$qc++;
		}
	}
	
	if( isset( $_GET['add_quest'] )) {
		$qst_city = $_GET['city_quest'];
		//
		if($qst_city=='angelscity') {
			$dun5 = 101; //������
		}elseif($qst_city=='sandcity') {
			$dun5 = 16; //������ ����
		}elseif($qst_city=='demonscity') {
			$dun5 = 3; //���������
		}elseif($qst_city=='emeraldscity') {
			$dun5 = 17; //���������� ����
		}elseif($qst_city=='suncity') {
			$dun5 = 10; //��������
		}elseif($qst_city=='devilscity') {
			$dun5 = 15; //������
		}else{
			$dun5 = 12; //���
		}
		//
		$hgo = $u->testAction('`uid` = "'.$u->info['id'].'" AND `room` = "'.$u->info['room'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_'.mysql_real_escape_string($qst_city).'" LIMIT 1',1);
		//$qcc
		if( $qcc[$dun5] > 0 ) {
			echo '<font color="red"><b>�� �� ��������� ������� ������� ��� ���� ������</b></font><br>';
		}elseif( $qst_city != 'capitalcity' && $qst_city != 'angelscity' && $qst_city != 'suncity' && $qst_city != 'demonscity' ) {
			echo '<font color="red"><b>��� ���� ������ ��� ��� �������</b></font><br>';
		}elseif(!isset($u->city_id[$qst_city])) {
			echo '<font color="red"><b>����� ������������ ������������ �������� ������?</b></font><br>';
		}elseif( isset( $hgo['id'] ) ) {
			echo '<font color="red"><b>������ �������� ������� ���� ������ ���� � �����</b></font><br>';
		} else {
			//



			//
			$sp = mysql_query('SELECT * FROM `quests` WHERE `line` = '.$dun5.'');
			$dq_add = array();
			while( $pl = mysql_fetch_array( $sp ) ) {
				if( $u->rep['rep'.$qst_city] == 9999 ) {
					//����, ���������� �������
					if( $pl['kin'] == 1 ) {
						$dq_add = array( 0 => $pl );
					}
				} elseif( $u->rep['rep'.$qst_city] == 24999 ) {
					//����, ���������� �������
					if( $pl['kin'] == 2 ) {
						$dq_add = array( 0 => $pl );
					}
				} else {
					if( $pl['kin'] == 0 ) {
						$dq_add[count($dq_add)] = $pl;
					}
				}
			}
			$dq_add = $q->onlyOnceQuest($dq_add, $u->info['id']);
			$dq_add = $dq_add[rand(0,count($dq_add)-1)];
			
			
			if( $q->testGood($dq_add) == 1 && $dq_add > 0 ) {
				$q->startq_dn($dq_add['id']);
				echo '<font color="red"><b>�� ������� �������� ����� ������� &quot;'.$dq_add['name'].'&quot;.</b></font><br>'; 
				$u->addAction(time(),'psh_qt_'.$qst_city,$dq_add['id']);
			} else {
				if ( $u->rep['rep'.$qst_city] == 9999 ) {
					//�����, ���������� �������
					echo '<font color="red"><b>�� ��� �������� ������� �� ���������� ������ ������!</b></font><br>';
				} elseif( $u->rep['rep'.$qst_city] >= 24999 ) {
					//�����, ���������� �������
					echo '<font color="red"><b>�� ��������� ��������� �����, �������� ����� �������!</b></font><br>';
				} else {
					echo '<font color="red"><b>�� ������� �������� ������� &quot;'.$dq_add['name'].'&quot;. ���������� ���...</b></font><br>';
				}	
			}
			unset( $dq_add );
		}
	} elseif( isset( $_GET['add_quest'] ) && $qc > 0 ) {
		echo '<font color="red"><b>���-�� ����� �� ���... ����������.. <br/><br/></b></font><br>';
	}
	if( $qsee == '' ) {
		$qsee = '� ��������� � ��� ��� �� ������ �������<br/><br/>';
	}
?>
<Br />	<style>
		.btnnewred {
			background-color:#ca726d;
			color:#f8d2d0;
			border-color:#b44039;
			cursor:default;
		}
		.btnnewred:hover {
			background-color:#ca726d;
			cursor:default;
		}
		</style>
		<FIELDSET>
		<LEGEND><B>������� �������: </B></LEGEND>
		<?=$qsee?>
		<span style="padding-left: 10">
		<?
		//if( $qc > 0 ){
		//	echo '�� ��� �� ���������� � ������� ��������.';
		//} elseif( !isset( $hgo['id'] ) && $qc == 0 ) {
			$hgo1 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `room` = "'.$u->info['room'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_capitalcity" LIMIT 1',1);
			$hgo2 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `room` = "'.$u->info['room'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_angelscity" LIMIT 1',1);
			$hgo3 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `room` = "'.$u->info['room'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_suncity" LIMIT 1',1);
			$hgo4 = $u->testAction('`uid` = "'.$u->info['id'].'" AND `room` = "'.$u->info['room'].'" AND `time` >= '.(time()-60*60*24).' AND `vars` = "psh_qt_demonscity" LIMIT 1',1);
			//
			if( !isset($hgo1['id']) ) { ?>
            <input class="btnnew" type='button' value='�������� ������� (������ ������ ���������)' onclick='location="main.php?rz=1&add_quest=1&city_quest=capitalcity"' />
            <? }else{ ?>
            <input disabled="disabled" class="btnnew btnnewred" type='button' value='������� ����� ����� <?=$u->timeOut(60*60*24+$hgo1['time']-time())?> (������ ������ ���������)' onclick='location="main.php?rz=1&add_quest=1&city_quest=capitalcity"' />
            <? }
			if( !isset($hgo2['id']) ) { ?>
            <input class="btnnew" type='button' value='�������� ������� (������)' onclick='location="main.php?rz=1&add_quest=1&city_quest=angelscity"' />
            <? }else{ ?>
            <input disabled="disabled" class="btnnew btnnewred" type='button' value='������� ����� ����� <?=$u->timeOut(60*60*24+$hgo2['time']-time())?> (������)' onclick='location="main.php?rz=1&add_quest=1&city_quest=angelscity"' />
            <? }
			if( !isset($hgo3['id']) ) { ?>
            <input class="btnnew" type='button' value='�������� ������� (��������)' onclick='location="main.php?rz=1&add_quest=1&city_quest=suncity"' />
            <? }else{ ?>
            <input disabled="disabled" class="btnnew btnnewred" type='button' value='������� ����� ����� <?=$u->timeOut(60*60*24+$hgo2['time']-time())?> (��������)' onclick='location="main.php?rz=1&add_quest=1&city_quest=suncity"' />
            <? }
			if( !isset($hgo4['id']) ) { ?>
            <input class="btnnew" type='button' value='�������� ������� (���������)' onclick='location="main.php?rz=1&add_quest=1&city_quest=demonscity"' />
            <? }else{ ?>
            <input disabled="disabled" class="btnnew btnnewred" type='button' value='������� ����� ����� <?=$u->timeOut(60*60*24+$hgo2['time']-time())?> (���������)' onclick='location="main.php?rz=1&add_quest=1&city_quest=demonscity"' />
            <? } ?>
			<?
		//} else {
		//	echo '�������� ����� ������� ����� <b>'.date('d.m.Y H:i',$hgo['time']+60*60*24).'</b> <font color="">( ����� '.$u->timeOut($hgo['time']+60*60*24-time()).' )</font>';
		//}
		?>
		</span>
		</FIELDSET>
	</form>
	<br />
	<? 
	//���������� ������ �������
	if( isset( $_GET['buy1'] ) ) {
		$rt = 1;
		if( $_GET['buy1'] == 1 ) {
			//�������� �����
			$price = 2000+($u->rep['add_stats']*100);
			$cur_price = array('price'=>0);
			if( 25 - $u->rep['add_stats'] > 0 && $u->rep['allrep'] - $u->rep['allnurep'] >= $price ) { // ��������������!
				foreach( $dungeon['list'] as $key => $val ) {
					if( !( $cur_price['price'] >= $price ) ) {
						$cur_price['price'] += $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1)));
						$cur_price['nu_'.$val] 	= $cur;
					}
				}
				if( $price == $cur_price['price'] ) {
					foreach( $dungeon['list'] as $key => $val ) {
						if( isset( $cur_price['nu_'.$val] ) && isset( $u->rep['nu_'.$val] ) && $rt == 1 ) {
							$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
							$r = mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if($r) $rt = 1; else $rt = 0;
						}
					}
					if($rt==1){
						$u->info['ability']  += 1; $u->rep['add_stats'] += 1;
						mysql_query('UPDATE `rep` SET `add_stats` = "'.$u->rep['add_stats'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `ability` = "'.$u->info['ability'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						echo '<font color="red"><b>�� ������� ��������� 1 ����������� �� '.$price.' ��. �������</b></font><br>';
					} else {
						echo '<font color="red"><b>������ �� ����������...</b></font><br>';
					}
				} else echo '<font color=red><b>������������ ���������.</b></font>';
			} else {
			   echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
			}
		} elseif( $_GET['buy1'] == 2 ) { // ������!
			$price = 2000;
			$cur_price = array('price'=>0); 
			if(12-$u->rep['add_skills']>0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // ������!
				foreach($dungeon['list'] as $key=>$val){
					if( !( $cur_price['price'] >= $price ) ) {
						$cur_price['price'] += $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1)));
						$cur_price['nu_'.$val] 	= $cur;
					}
				}
				if( $price == $cur_price['price'] ) {
					foreach( $dungeon['list'] as $key => $val ) {
						if( isset( $cur_price['nu_'.$val] ) && isset( $u->rep['nu_'.$val] ) && $rt == 1 ) {
							$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
							$r = mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if($r) $rt = 1; else $rt = 0;
						}
					}
					if($rt==1){
						$u->info['skills']  += 1; $u->rep['add_skills'] += 1;
						mysql_query('UPDATE `rep` SET `add_skills` = "'.$u->rep['add_skills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `skills` = "'.$u->info['skills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						echo '<font color="red"><b>�� ������� ��������� 1 ������ �� '.$price.' ��. �������</b></font><br>';
					} else {
						echo '<font color="red"><b>������ �� ����������...</b></font><br>';
					}
				} else echo '<b><font color=red>������������ ���������.</font></b>';
			} else {
				echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
			}
		} elseif( $_GET['buy1'] == 3 ) { // �������
			$price = 100;
			$cur_price = array('price'=>0); 
			if( $u->rep['allrep'] - $u->rep['allnurep'] >= $price) { // �������� �������
				foreach($dungeon['list'] as $key=>$val){
					if( !( $cur_price['price'] >= $price ) ) {
						$cur_price['price'] += $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1)));
						$cur_price['nu_'.$val] 	= $cur;
					}
				}
				if( $price == $cur_price['price'] ) {
					foreach( $dungeon['list'] as $key => $val ) {
						if( isset( $cur_price['nu_'.$val] ) && isset( $u->rep['nu_'.$val] ) && $rt == 1 ) {
							$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
							$r = mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if($r) $rt = 1; else $rt = 0;
						}
					}
					if($rt==1){
						$u->info['money']  += 10; $u->rep['add_money'] += 10;
						mysql_query('UPDATE `rep` SET `add_money` = "'.$u->rep['add_money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						echo '<font color="red"><b>�� ������� ��������� 10 ��. �� '.$price.' ��. �������</b></font><br>';
					} else {
						echo '<font color="red"><b>������ �� ����������...</b></font><br>';
					}
				} else echo '<b><font color=red>������������ ���������.</font></b>';
			}else{
				echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
			}
		} elseif( $_GET['buy1'] == 4 ) { // �����������
			$price = 3000;
			$cur_price = array('price'=>0);
			if( 5 - $u->rep['add_skills2'] > 0 && $u->rep['allrep']-$u->rep['allnurep'] >= $price ) { // �����������
				foreach($dungeon['list'] as $key=>$val){
					if( !( $cur_price['price'] >= $price ) ) {
						$cur_price['price'] += $cur = ( $price > ($cur_price['price'] + ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) ) ? ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) : ( ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) -  (( ( $price - $cur_price['price'] ) - ( $u->rep['rep'.$val] - $u->rep['nu_'.$val] ) )*-1)));
						$cur_price['nu_'.$val] 	= $cur;
					}
				}
				if( $price == $cur_price['price'] ) {
					foreach( $dungeon['list'] as $key => $val ) {
						if( isset( $cur_price['nu_'.$val] ) && isset( $u->rep['nu_'.$val] ) && $rt == 1 ) {
							$u->rep['nu_'.$val] += $cur_price['nu_'.$val];
							$r = mysql_query('UPDATE `rep` SET `nu_'.$val.'` = "'.$u->rep['nu_'.$val].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
							if($r) $rt = 1; else $rt = 0;
						}
					}
					if($rt==1){
						$u->info['sskills']  += 1; $u->rep['add_skills2'] += 1;
						mysql_query('UPDATE `rep` SET `add_skills2` = "'.$u->rep['add_skills2'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						mysql_query('UPDATE `stats` SET `sskills` = "'.$u->info['sskills'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
						echo '<font color="red"><b>�� ������� ��������� 1 ����������� �� '.$price.' ��. �������</b></font><br>';
					} else {
						echo '<font color="red"><b>������ �� ����������...</b></font><br>';
					}
				} else echo '<b><font color=red>������������ ���������.</font></b>';
				
			} else {
				echo '<font color="red"><b>������ �� ����������...</b></font><br>'; 
			}
	  	}
	}
	?>
	<fieldset>
        <legend>�������: <b> <? echo ( isset( $rt ) && $rt == 1 ? ($u->rep['allrep']-$u->rep['allnurep'])-$cur_price['price'] : ($u->rep['allrep']-$u->rep['allnurep']) );?>
        ��.</b></legend>
        <table>
			<tr>
				<td>����������� (��� <?=(25-$u->rep['add_stats'])?>)</td>
				<td style='padding-left: 10px'>�� <?=2000+($u->rep['add_stats']*100);?> ��.</td>
				<td style='padding-left: 10px'><input class="btnnew" type='button' value='������'
	  onclick="if (confirm('������: �����������?\n\n����� �����������, �� ������� ��������� �������������� ���������.\n��������, ����� ��������� ����.')) {location='main.php?rz=1&buy1=1'}" /></td>
			</tr>
			<tr>
				<td>������ (��� <?=(12-$u->rep['add_skills'])?>)</td>
				<td style='padding-left: 10px'>�� <?=2000+($u->rep['add_skills']*2000);?> ��.</td>
				<td style='padding-left: 10px'><input class="btnnew" type='button' value='������'
	  onclick="if (confirm('������: ������?\n\n������ ��� ����������� ������������ ���� �������� ����, ������, ����� � �.�.')) {location='main.php?rz=1&buy1=2'}" /></td>
			</tr>
			<tr>
				<td>������ (10 ��.)</td>
				<td style='padding-left: 10px'>�� 100 ��.</td>
				<td style='padding-left: 10px'><input class="btnnew" type='button' value='������'
	  onclick="if (confirm('������: ������ (10 ��.)?\n\n������� ����� �������� ������������ ���������.')) {location='main.php?rz=1&buy1=3'}" /></td>
			</tr>
			<tr>
				<td>����������� (��� <?=(5-$u->rep['add_skills2'])?>)</td>
				<td style='padding-left: 10px'>�� 3000 ��.</td>
				<td style='padding-left: 10px'><input class="btnnew" type='button' value='������'
	  onclick="if (confirm('������: �����������?\n\n����������� - ��� �������������� ����������� ���������, �� ������ ������������ � ����.\n��������, ����� ��������� �������� �������������� HP')) {location='main.php?rz=1&buy1=4'}" /></td>
			</tr>
        </table>
	</fieldset>
        <? 
		$chk = mysql_fetch_array(mysql_query('SELECT COUNT(`u`.`id`),SUM(`m`.`price1`) FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `u`.`item_id` = `m`.`id` WHERE `m`.`type` = "61" AND `u`.`delete` = "0" AND `u`.`inOdet` = "0" AND `u`.`inShop` = "0" AND `u`.`inTransfer` = "0" AND `u`.`uid` = "'.$u->info['id'].'" LIMIT 1000'));
		if(isset($_GET['buy777']) && $chk[0]>0) {
			?>
	<fieldset style='margin-top:15px;'>
		<p><span style="padding-left: 10px">
			<?
			$chk_cl = mysql_query('SELECT `u`.`id`,`m`.`price1` FROM `items_users` AS `u` LEFT JOIN `items_main` AS `m` ON `u`.`item_id` = `m`.`id` WHERE `m`.`type` = "61" AND `u`.`delete` = "0" AND `u`.`inOdet` = "0" AND `u`.`inShop` = "0" AND `u`.`inTransfer` = "0" AND `u`.`uid` = "'.$u->info['id'].'" LIMIT 1000');
			while($chk_pl = mysql_fetch_array($chk_cl)) {
				if(mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$chk_pl['id'].'" LIMIT 1'));
				{ 
					$x++; $prc += $chk_pl['price1'];
				}
			}
			$u->info['money'] += $prc;
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
			echo '<font color="red"><b>�� ������� ����� ���� � ���������� '.$x.' ��. �� ����� '.$prc.' ��.</b></font><br>'; 
			$chk[0] = 0;
		?>
        </span></p>
      </fieldset><?
		}
		if($chk[0]>0) {
		?>
          <input class="btnnew" type='button' value='����� ����'
onclick="if (confirm('����� ��� ���� (<?=$chk[0]?> ��.) ����������� � ��� � ��������� �� <?=$chk[1]?> ��. ?')) {location='main.php?rz=1&buy777=1'}" />
		<? } ?>
        <fieldset style='margin-top:15px;'>
        	<legend>����� � �������:</legend>
            <input onclick="location.href='main.php?repsale'" type="button" value="����� �������� �� ���������" class="btnnew">
            <input onclick="location.href='main.php?repshop'" type="button" value="������ ������� �� ���������" class="btnnew">
        </fieldset>
	  <fieldset style='margin-top:15px;'>
		<table>
		<?
			echo '<tr>
				<td width="200">��������� ����� ��������:</td>
				<td>'.$u->rep['repitems'].' ��. </td>
			</tr>';
			foreach($dungeon['list'] as $key=>$val){
				//if( $u->rep['rep'.$val] > 0 ) {
					if( $val != 'items' ) {
						echo '<tr>
							<td width="200">��������� � '.ucfirst(str_replace('city',' city',$val)).':</td>
							<td>'.$u->rep['rep'.$val].' ��. </td>
						</tr>';
					}
				//}
			}
		?> 
        </table>
        <legend>������� ���������:</legend> 
      </fieldset>
</div>
<?
	} else {
		if($dungeonGo == 1){
			if($u->info['dn']==0){
			?>
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td valign="top">
						<form id="from" autocomplete="0" name="from" action="main.php?pz1=<? echo $code; ?>" method="post">
							<fieldset style='padding-left: 5; width=50%'>
							<legend><b> ������ </b> </legend>                                
                                �������� ������:<hr />
                              <label>
                                <?
								/*
								if($_POST['rpmg1']==2) {
									$dun5 = 101; //������
								}elseif($_POST['rpmg1']==3) {
									$dun5 = 16; //������ ����
								}elseif($_POST['rpmg1']==4) {
									$dun5 = 3; //���������
								}elseif($_POST['rpmg1']==5) {
									$dun5 = 108; //���������� ����
								}elseif($_POST['rpmg1']==6) {
									$dun5 = 10; //��������
								}elseif($_POST['rpmg1']==7) {
									$dun5 = 15; //������
								}elseif($_POST['rpmg1']==999) {
									$dun5 = 999; //�������� �������
								}else{
									$dun5 = 12; //���
								}
								*/
								$dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh12" AND `time` > '.(time()-60*60*4).' LIMIT 1',1);
								if(isset($dungeon_timeout['id'])) {
									echo '<img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/capitalcity.gif" width="34" height="19" /> ������ ������ ��������� - �������� �� ��������� ��� '.$u->timeOut(60*60*4-time()+$dungeon_timeout['time']).'<br>';
								}else{
								?>
                                <input type="radio" name="rpmg1" id="rpmg1" checked="checked" value="1" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/capitalcity.gif" width="34" height="19" /> ������ ������ ���������</label><br />
                                <? } ?>
                                <hr>
								<?
                                $dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh101" AND `time` > '.(time()-60*60*4).' LIMIT 1',1);
								if(isset($dungeon_timeout['id'])) {
									echo '<img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/angelscity.gif" width="34" height="19" /> ������ - �������� �� ��������� ��� '.$u->timeOut(60*60*4-time()+$dungeon_timeout['time']).'<br>';
								}else{
								?>
                                <label><input type="radio" name="rpmg1" id="rpmg1" value="2" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/angelscity.gif" width="34" height="19" /> ������</label><br />
                                <? } ?>
                                <hr>
								<?
                                $dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh10" AND `time` > '.(time()-60*60*4).' LIMIT 1',1);
								if(isset($dungeon_timeout['id'])) {
									echo '<img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/suncity.gif" width="34" height="19" /> �������� - �������� �� ��������� ��� '.$u->timeOut(60*60*4-time()+$dungeon_timeout['time']).'<br>';
								}else{
								?>
							    <label><input type="radio" name="rpmg1" id="rpmg1" value="6" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/suncity.gif" width="34" height="19" /> ��������</label>
                                <br />
                                <? } ?>
                                <hr>
								<?
                                $dungeon_timeout = $u->testAction('`uid` = "'.$u->info['id'].'" AND `vars` = "psh3" AND `time` > '.(time()-60*60*4).' LIMIT 1',1);
								if(isset($dungeon_timeout['id'])) {
									echo '<img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/demonscity.gif" width="34" height="19" /> ��������� - �������� �� ��������� ��� '.$u->timeOut(60*60*4-time()+$dungeon_timeout['time']).'<br>';
								}else{
								?>
                                <label><input type="radio" name="rpmg1" id="rpmg1" value="4" />
                                <img style="vertical-align:bottom" src="http://img.xcombats.com/i/city_ico2/demonscity.gif" width="34" height="19" />���������</label><br />
                                <? } ?><hr />
                                �����������
							  	<input autocomplete="off" type="text" name="text" maxlength="40" size="40" />
								<br />
								������
								<input autocomplete="off" type="password" name="pass" maxlength="25" size="25" />
								<br />
								<input class="btnnew" type="submit" name="add" value="������� ������" />
								&nbsp;<br />
							</fieldset>
						</form>
					</td>
				</tr>
			</table>
			<?
			} else {
				$psh_start = '';
				if(isset($zv['id'])){
					if($zv['uid']==$u->info['id']){
						$psh_start = '<INPUT class=btnnew type=\'button\' name=\'start\' value=\'������\' onClick="top.frames[\'main\'].location = \'main.php?start=1&rnd='.$code.'\'"> &nbsp;';
					}
					echo '<br><FORM autocomplete="off" id="REQUEST" method="post" style="width:210px;" action="main.php?rnd='.$code.'">
					<FIELDSET style=\'padding-left: 5; width=50%\'>
					<LEGEND><B> ������ </B> </LEGEND>
					'.$psh_start.'
					<INPUT class=btnnew type=\'submit\' name=\'leave\' value=\'�������� ������\'> 
					</FIELDSET>
					</FORM>';
				}
			}
		}else{
			echo '����� � ������ �������� ���� ��� � ��� ����. �������� ���: '.$u->timeOut(60*60*3-time()+$dungeon_timeout['time']).'<br><small style="color:grey">�� �� ������ ������ ���������� ���� �� ������� � ������ &quot;�������� �����&quot; � �������� ���� ;)</small>';
			if( $dungeon['id'] == 104 ) {
				echo '<hr>�� ������ �������� ���������� ��� ��������: <button  onClick="if(confirm(\'�� ������� ��� ������ ��������� '.($u->info['level'] * 5).' �����?\')){ location.href = \'/main.php?freego=1\'; }" class="btnnew">����� �������� �� '.$u->zuby(($u->info['level'] * 5),1).'</button>';
			}
		}
	}
}
?>
