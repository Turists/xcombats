<?php
define('GAME',true);
setlocale(LC_CTYPE ,"ru_RU.CP1251");
include('/var/www/xcombats.com/data/www/xcombats.com/_incl_data/__config.php');
include('/var/www/xcombats.com/data/www/xcombats.com/_incl_data/class/__db_connect.php');
include('/var/www/xcombats.com/data/www/xcombats.com/_incl_data/class/__user.php');

/*

	CRON ������� ������� �� �������� ����������
	��������:
	1.  ������� ����
	2.	������� ������ � ��������
	3.	������� ������ � ������
	4.	������� �������

*/

//1. ������� ����, �������� ��� ������ �� ��������� 3 ���
mysql_query('DELETE FROM `chat` WHERE `time` < '.(time()-86400*3).'');

//2. ������� ������ � ��������
mysql_query('DELETE FROM `zayvki` WHERE `start` > 0 OR `cancel` > 0 OR `time` < "'.(time()-86400*1).'"');

//3. ������� ������ � ������
mysql_query('DELETE FROM `dungeon_zv` WHERE `delete` > 0 OR `time` < "'.(time()-86400*1).'"');

//4. ������� �������
$sp = mysql_query('SELECT * FROM `dungeon_now` WHERE `time_start` < "'.(time()-86400*1).'" OR `time_finish` > 0');
while( $pl = mysql_fetch_array($sp) ) {
	mysql_query('DELETE FROM `dungeon_actions` WHERE `dn` = "'.$pl['id'].'"');
	mysql_query('DELETE FROM `dungeon_bots` WHERE `dn` = "'.$pl['id'].'"');
	mysql_query('DELETE FROM `dungeon_items` WHERE `dn` = "'.$pl['id'].'"');
	mysql_query('DELETE FROM `dungeon_obj` WHERE `dn` = "'.$pl['id'].'"');
	mysql_query('DELETE FROM `dungeon_now` WHERE `id` = "'.$pl['id'].'"');
}
?>