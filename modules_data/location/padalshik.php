<?php
if(!defined('GAME'))
{
    die();
}
session_start();
$user=$u->info;
$timer = time()+24*60*60;

$is_eff=mysql_fetch_array(mysql_query("SELECT id,timeUse FROM eff_users where id_eff='396' and `delete` =0 and uid='".$u->info['id']."'"));
if($_GET['get']=='10'  && $is_eff[0]==0){
    if(mysql_query("UPDATE `users` SET `money`=`money`+'10' where `id`='".$user['id']."'") and
        mysql_query('INSERT INTO `eff_users` (
				`id_eff`,`uid`,`name`,`data`,`overType`,`timeUse`
			) VALUES (
				"396","'.$user['id'].'","�������������� ����������","add_exp=5|add_m10=2|add_m11=2|nofastfinisheff=1","101","'.time().'"
			)'));
    {
        $err = "��������� ������� � ������� ����������. �� �������� ������������� ���������� � 10 ��������!";
    }
}
elseif($_GET['get']=='10'  && $is_eff[0]>0){
    $wait_sec=$is_eff['timeUse'];
    $new_t=time();
    $left_time=$wait_sec-$new_t;
    $left_min=floor($left_time/3600);
    $left_sec=floor(($left_time-$left_min*3600)/60);
    if($left_min==1){$time_h="���";}
    if($left_min>1 and $left_min<5){$time_h="����";}
    if($left_min>4){$time_h="�����";}
    $time_left=$left_min." ".$time_h." ".$left_sec." ���";

    $err="�� ��� �������� ������������� ����������. ��������� ��������� �������� ��������������";
}

?>
<HTML><HEAD>
    <link rel=stylesheet type="text/css" href="http://img.xcombats.com/i/main.css">
    <meta content="text/html; charset=utf8" http-equiv=Content-type>
    <META Http-Equiv=Cache-Control Content=no-cache>
    <meta http-equiv=PRAGMA content=NO-CACHE>
    <META Http-Equiv=Expires Content=0>
</HEAD>
<body bgcolor=e2e0e0 style="background-image: url(/i/misc/statue.png);background-repeat:no-repeat;background-position:top right">
<div id=hint4 class=ahint></div>
<TABLE width=100%>
<TR><TD valign=top width=100%><center><font style="font-size:24px; color:#000033"><h3>"�������� ����������"</h3></font></center>
<div style="float:right;width:300px;">
<?
	//id ��������
	$arr = array(
	//���� ��� ��������� ����
		);
	if( isset($_GET['takeartefact']) ) {
		$i = (int)$_GET['takeartefact'];
		$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($arr[$i]).'" LIMIT 1'));
		if(isset($itm['id'])) {
			$take_today = mysql_fetch_array(mysql_query('SELECT COUNT(*) FROM `items_users` WHERE `uid` = "'.$u->info['id'].'" AND `data` LIKE "%musor_art=1%" AND `delete` < 1234567890 LIMIT 1'));
			$take_today = 0 + $take_today[0];
			if( $take_today < 2 ) {
				$error .= '�� ����� � ������ �'.$itm['name'].'� ����� �� ��� ������ � ���� � �������.';
				$u->addItem($itm['id'],$u->info['id'],'|sudba='.$u->info['login'].'|nosale=1|frompisher=1|musor_art=1');
			}else{
				$error .= '����� ��� ��� ����� �������, ������ �� �� ��������, ���� ������ �������� ���� ����, ������� ������ �� ������� �� ��� ����� !';
			}
		}else{
			$error .= '������� �� ������!';
		}
	}elseif(isset($_GET['restart'])) {
		if(mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `uid` = "'.$u->info['id'].'" AND `data` LIKE "%musor_art=1%" AND `delete` < 1234567890')) {
			$error .= '�� ������ ��� ���� ���������� � ��� �������, �������� ���� ����� ��������.';
		}else{
			$error .= '� ��� ��� ��������� �� ������.';
		}
	}
	echo '<b style="color:red">'.$error.'</b><br>';
	$i = 0;
	$items = '';
	while($i < count($arr)) {
		$itm = mysql_fetch_array(mysql_query('SELECT * FROM `items_main` WHERE `id` = "'.mysql_real_escape_string($arr[$i]).'" LIMIT 1'));
		if(isset($itm['id'])) {
			$items .= ' <a href="?takeartefact='.$i.'"><img src="http://img.xcombats.com/i/items/'.$itm['img'].'" title="����� &quot;' . $itm['name'] . '&quot;"></a> &nbsp; ';
		}
		$i++;
	}
	/*echo '<a href="?restart=1">������ ������ ����</a><br><b>�������� ����� ����:</b><br>';
	if( $items != '' ) {
		echo '<br><br>'.$items;
	}else{
		echo '��� ���������';
	}*/
?>
        </div>
            <center><img src="http://xcombats.com/i/statue/bigcarreat.jpg" ></center>
        <TD nowrap valign=top>
            <BR><DIV align=right>
                <td width=280 valign=top><TABLE cellspacing=0 cellpadding=0><TD width=100%>&nbsp;</TD><TD><HTML>
                            <table  border="0" cellpadding="0" cellspacing="0">
                                <tr align="right" valign="top">
                                    <td>


                                    </td>
                                    <td>

                                        <TABLE height=15 border="0" cellspacing="0" cellpadding="0">

                                            <table  border="0" cellpadding="0" cellspacing="0">
                                                <tr align="right" valign="top">
                                                    <td>


                                                    </td>
                                                    <td>

                                                    <td width="280" valign="top">
                                                        <TABLE cellspacing="0" cellpadding="0"><TD width="100%">&nbsp;</TD><TD>
                                                                <table  border="0" cellpadding="0" cellspacing="0">
                                                                    <tr align="right" valign="top">
                                                                        <td>
                                                                            <!-- -->
                                                                            <? echo $goLis; ?>
                                                                            <!-- -->
                                                                            <table border="0" cellspacing="0" cellpadding="0">
                                                                                <tr>
                                                                                    <td nowrap="nowrap">
                                                                                        <table width="100%"  border="0" cellpadding="0" cellspacing="1" bgcolor="#DEDEDE">
                                                                                            <tr>
                                                                                                <td bgcolor="#D3D3D3"><img src="http://<? echo $c['img']; ?>/i/move/links.gif" width="9" height="7" /></td>
                                                                                                <td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onClick="location='main.php?loc=1.180.0.415&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.415',1); ?>">������� �������</a></td>
                                                                                            </tr>
                                                                                        </table>
                                                                                    </td>
                                                                                </tr>
                                                                            </table>
                                                                        </td></table>
                                                            </td></table>
                                                    </td>
                                                </tr>
                                            </table>
                                            <!-- <br /><span class="menutop"><nobr>���������</nobr></span>-->
                                            </td>
                                            </tr>
                                        </table>
                                        
                                        <div id="mmoves" style="background-color:#FFFFCC; visibility:hidden; overflow:visible; position:absolute; border-color:#666666; border-style:solid; border-width: 1px; padding: 2px; white-space: nowrap;"></div>

</TD></TR>

</HTML>
</TD></TABLE>
</table>
</DIV>
</TD>

</TR>
<tr>
    <td>
        <div align="left"><font color="red"><b><?=$err?></b></font></div>
        �������������� ����������: <a href="?get=10">��������</a>
    </td>
</tr>
</TABLE>
<br>

</BODY>
<br>
<br>
<br>
<TABLE width=100% align="right">
    <tr><td>

        </TD></tr>
</TABLE>
</HTML>