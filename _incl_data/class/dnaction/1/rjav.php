<?
if( isset($s[1]) && $s[1] == '1/rjav' ) {
	/*
		������: ������ �����
		* ����� ����������� �� �������
	*/
	//��� ���������� ��������� � ������� $vad !
	$vad = array(
		'go' => true
	);
	$vad['test1'] = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `dungeon_actions` WHERE `dn` = "'.$u->info['dnow'].'" AND `vars` = "obj_act'.$obj['id'].'" LIMIT 1'));
	if( $vad['test1'][0] > 0 ) {
		$r = '���-�� ������� &quot;'.$obj['name'].'&quot; �� ���...';
		$vad['go'] = false;
	}
	
	$vad['itm'] = mysql_fetch_array(mysql_query('SELECT * FROM `items_users` WHERE `item_id` = 1004 AND `uid` = "'.$u->info['id'].'" AND `delete` = 0 AND `inShop` = 0 AND `inTransfer` = 0 LIMIT 1'));
	if(!isset($vad['itm']['id'])) {
		$r = '� ��� ��� ����������� �������� ����� ��������� �������...';
		$vad['go'] = false;
	}
	
	if( $vad['go'] == true ) {
		mysql_query('INSERT INTO `dungeon_actions` (`dn`,`time`,`x`,`y`,`uid`,`vars`,`vals`) VALUES (
			"'.$u->info['dnow'].'","'.time().'","'.$obj['x'].'","'.$obj['y'].'","'.$u->info['id'].'","obj_act'.$obj['id'].'","'.$vad['bad'].'"
		)');
		mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$vad['itm']['id'].'" LIMIT 1');
		$this->pickitem($obj,1005,$u->info['id'],'',false);
		$r = '�� ������� ��������� �������! (������� &quot;��������&quot; ��� �����)';
	}
	
	unset($vad);
}
?>