<?
if( isset($s[1]) && $s[1] == '3/fontan' ) {
	/*
		������
		* ������������� �� ����������� ������ � -3 ,  � 7
		* ��� ������� ��������� 1 ������ ���� 881 ��� 878
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = '������ �� ���������...';
		$vad['go'] = false;
	}
	
	//��������� �����
	if( $vad['go'] == true ) {
	$vad['sp'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE (`item_id` = "881" OR `item_id` = "878" OR `item_id` = "907" OR `item_id` = "908" OR `item_id` = "909") AND `uid` = "'.$u->info['id'].'" AND `delete` = "0" AND `inOdet` = "0" AND `inShop` = "0" AND `inTransfer` = "0" LIMIT 1'));
	if( isset($vad['sp']['id']) ) {
		// ������� �������� �� ����������� ��������� 13.10.2015
		$vad['pl'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.$vad['sp']['item_id'].'" LIMIT 1'));
		$vad['go'] = true;
	}
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		$vad['items'] = array(2413,2414,2415,2416);
		$upd = mysql_query('UPDATE `items_users` SET `lastUPD`="'.time().'",`delete`="'.time().'" WHERE `id`="'.$vad['sp']['id'].'" LIMIT 1');
		$vad['items'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($vad['items'][rand(0,count($vad['items'])-1)]).'" LIMIT 1'));
		$r = '������� &quot;'.$obj['name'].'&quot; �� ���������� &quot;'.$vad['items']['name'].'&quot;';
		$r = '�� ������������ ������ &quot;'.$vad['pl']['name'].'&quot;';
		$this->pickitem($obj,$vad['items']['id'],$u->info['id'],'');
	}elseif( !isset($vad['sp']['id']) ) {
		$r = '� ��� ���� �� ������ �� ����������� ������';
	}
	unset($vad);
}
}
?>