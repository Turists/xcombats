<?php
if(!defined('GAME'))
{
	die();
}

if($u->room['file']=='dungeon_enter_all')
{
	$shopProcent = 100-$c['shop_type1'];
	
	if(!isset($_GET['otdel'])) {
		$_GET['otdel'] = 1;
	}
	$sid = 12;
	$error = '';
	if(isset($_GET['buy'])){
		if($u->newAct($_GET['sd4'])==true){
			$re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x']);
		}else{
			$re = '�� ������� ��� ������ ������ ���� �������?';
		}
	}
	
	if($re!=''){ echo '<div align="right"><font color="red"><b>'.$re.'</b></font></div>'; } ?>
	<script type="text/javascript">
	function AddCount(name, txt)
	{
		document.getElementById("hint4").innerHTML = '<table border=0 width=100% cellspacing=1 cellpadding=0 bgcolor="#CCC3AA"><tr><td align=center><B>������ ����. ����</td><td width=20 align=right valign=top style="cursor: pointer" onclick="closehint3();"><BIG><B>x</TD></tr><tr><td colspan=2>'+
		'<form method=post><table border=0 width=100% cellspacing=0 cellpadding=0 bgcolor="#FFF6DD"><tr><INPUT TYPE="hidden" name="set" value="'+name+'"><td colspan=2 align=center><B><I>'+txt+'</td></tr><tr><td width=80% align=right>'+
		'���������� (��.) <INPUT TYPE="text" NAME="count" id=count size=4></td><td width=20%>&nbsp;<INPUT TYPE="submit" value=" �� ">'+
		'</TD></TR></form></TABLE></td></tr></table>';
		document.getElementById("hint4").style.visibility = 'visible';
		document.getElementById("hint4").style.left = '100px';
		document.getElementById("hint4").style.top = '100px';
		document.getElementById("count").focus();
	}
	function closehint3() {
	document.getElementById('hint4').style.visibility='hidden';
	Hint3Name='';
	}	
	</script>
	<style type="text/css"> 
	
	.pH3			{ COLOR: #8f0000;  FONT-FAMILY: Arial;  FONT-SIZE: 12pt;  FONT-WEIGHT: bold; }
	.class_ {
		font-weight: bold;
		color: #C5C5C5;
		cursor:pointer;
	}
	.class_st {
		font-weight: bold;
		color: #659BA3;
		cursor:pointer;
	}
	.class__ {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
	}
	.class__st {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #659BA3;
		font-size: 10px;
	}
	.class_old {
		font-weight: bold;
		color: #919191;
		cursor:pointer;
	}
	.class__old {
		font-weight: bold;
		color: #FFFFFF;
		cursor:pointer;
		background-color: #838383;
		font-size: 10px;
	}
    .shop_menu_txt { background-color: #d5d5d5; }
	</style>
	<TABLE width="100%" cellspacing="0" cellpadding="0">
	<tr><td valign="top"><?php
	echo '<b style="color:red">'.$error.'</b>';
	?>
	<br />
	<TABLE width="100%" cellspacing="0" cellpadding="4">
	<TR>
	<form name="F1" method="post">
	<TD valign="top" align="left">
	<!--�������-->
	<table width="100%" cellspacing="0" cellpadding="0" bgcolor="#a5a5a5">
	<div id="hint3" style="visibility:hidden"></div>
	<tr>
	<td align="center" height="21">
    <?php	
		/*�������� �������� (������)*/
		if(!isset($_GET['sale']) && isset($_GET['otdel'])) 
		{
			$otdels_small_array = array (1=>'<b>�����&nbsp;&quot;������: �������,����&quot;</b>',2=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',3=>'<b>�����&nbsp;&quot;������: ������,������&quot;</b>',4=>'<b>�����&nbsp;&quot;������: ����&quot;</b>',5=>'<b>�����&nbsp;&quot;������: ���������� ������&quot;</b>',6=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',7=>'<b>�����&nbsp;&quot;������: ��������&quot;</b>',8=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',28=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',9=>'<b>�����&nbsp;&quot;������: ������ �����&quot;</b>',10=>'<b>�����&nbsp;&quot;������: ������� �����&quot;</b>',11=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',12=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',13=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',14=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',15=>'<b>�����&nbsp;&quot;����&quot;</b>',16=>'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',17=>'<b>�����&nbsp;&quot;��������� ������: ��������&quot;</b>',18=>'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',19=>'<b>�����&nbsp;&quot;����������: �����������&quot;</b>',20=>'<b>�����&nbsp;&quot;����������: ������ � ��������&quot;</b>',21=>'<b>�����&nbsp;&quot;��������&quot;</b>',22=>'<b>�����&nbsp;&quot;��������: ��������&quot;</b>',23=>'<b>�����&nbsp;&quot;�������&quot;</b>',24=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',25=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',26=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',27=>'<b>�����&nbsp;&quot;�������: ����������&quot;</b>',29=>'<b>�����&nbsp;&quot;�������� �������&quot;</b>');
			if(isset($otdels_small_array[$_GET['otdel']]))
			{
				echo $otdels_small_array[$_GET['otdel']];	
			}
			
		} elseif (isset($_GET['sale'])) 
		{
			echo '
			<B>�����&nbsp;&quot;������� ���������&quot;</B>';	
		}
	?>
	</tr>
	<tr><td>
	<!--������ / ��������-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
    <?php
	if(isset($_GET['gifts']))
	{
	?>
    
<tr><td bgcolor="#D5D5D5">
�� ������ ������� ������� �������� ��������. ��� ������� ����� ������������ � ���������� � ���������.
<OL>
<LI>������� ����� ���������, �������� ������ ������� �������<BR>
����� 
  <INPUT TYPE=text NAME=to_login value="">
<LI>���� �������. ����� ������������ � ���������� � ��������� (�� ����� 60 ��������)<BR>
<INPUT TYPE=text NAME=podarok2 value="" maxlength=60 size=50>
<LI>�������� ����� ���������������� ������� (� ���������� � ��������� �� ������������)<BR>
<TEXTAREA NAME=txt ROWS=6 COLS=80></TEXTAREA>
<LI>��������, �� ����� ����� �������:<BR>
<INPUT TYPE=radio NAME=from value=0 checked> <B><?=$u->info['login']?></B> [<?=$u->info['level']?>]<BR>
<INPUT TYPE=radio NAME=from value=1 > ��������<BR>
<? if($u->info['clan']>0){ ?><INPUT TYPE=radio NAME=from value=2 > �� ����� �����<BR><? } ?>
<LI>������� ������ <B>��������</B> ��� ���������, ������� ������ ����������� � �������:<BR>
</OL>
<input name="itemgift" id="itemgift" type="hidden" value="0" />
</td></tr>
    <?php
	}
		if(isset($_GET['gifts']))
		{
			$htmlg2 = '';			
			$sp = mysql_query('SELECT * FROM `users_gifts` WHERE `uid` = "'.$u->info['id'].'"');
			while( $pl = mysql_fetch_array($sp) ) {
				$itmg2 = '<table width="100%" border="0" cellspacing="0" cellpadding="0"><tr><td width="160" align="center" style="border-right:#A5A5A5 1px solid; padding:5px;">'.
				//
					'<img style="padding-bottom:5px;" src="http://img.xcombats.com/i/items/'.$pl['img'].'"><br>'.
					'<input onClick="document.getElementById(\'itemgift\').value='.(1000000000000+$pl['id']).';document.F1.submit();" type="button" value="�������� �� '.$pl['money'].' ��.">'.
				//
				'</td><td align="left" valign="top" style="border-right:#A5A5A5 1px solid; padding:5px;">'.
				//
					'<a href="http://xcombats.com/item/0">'.$pl['name'].'</a> &nbsp; (�����: 1)<br>�������������: 0/1<br>'.
					'<small><b>��������:</b><br>��� ������� �������, ��� ������ �������� ������ ��.<br>������� � Capital city</small>'.
				//
				'</td></tr></table>';
				$htmlg2 .= '<tr><td align="center" bgcolor="#e2e0e0">'.$itmg2.'</td></tr>';
			}			
			if( $htmlg2 != '' ) {
				echo '<tr><td align="center" bgcolor="#e2e0e0"><h3>���������� �������</h3>' . $htmlg2 . '</td></tr>';
				echo '<tr><td align="center" bgcolor="#e2e0e0"><h3>����������� �������</h3></td></tr>';
			}
			unset($htmlg2,$itmg2);
			//
			$itmAll = $u->genInv(3,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND (`im`.`type` = "28" OR `im`.`type` = "38" OR `im`.`type` = "63" OR `im`.`type` = "64") AND (`iu`.`gift` = "" OR (`iu`.`data` LIKE "%|zazuby=%" AND `iu`.`gift` = 1)) ORDER BY `lastUPD` DESC');
			if($itmAll[0]==0){
				$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">� ��� ��� ���������� ���������</td></tr>';
			}else{
				$itmAllSee = $itmAll[2];
			}
			echo $itmAllSee;
		}elseif(!isset($_GET['sale'])){
			//������� ���� � �������� ��� �������
			$u->shopItems($sid);
		}else{
			//������� ���� � ��������� ��� �������
			$itmAll = $u->genInv(2,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD`  DESC');
			if($itmAll[0]==0){
				$itmAllSee = '<tr><td align="center" bgcolor="#e2e0e0">�����</td></tr>';
			}else{
				$itmAllSee = $itmAll[2];
			}
			echo $itmAllSee;
		}
	?>
	</TABLE>	 
	</TD></TR>
	</TABLE>
	</TD>
	</FORM>
	</TR>
	</TABLE>	
	<td width="280" valign="top"><div><br />
      <div align="right">
      <small>
	  �����: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	  ��������� ����� ��������: <b style="color:#339900;"><?php echo round(($u->rep['repitems']-$u->rep['nu_items']),2); ?> ��.</b> &nbsp;
      </small>
      </div>
	  <br />
	  <INPUT TYPE="button" class="btnnew" value="����� �������" onclick="location = 'main.php?repsale';">
	  <INPUT TYPE="button" class="btnnew" value="��������" onclick="location = '<? echo str_replace('item','',str_replace('buy','',$_SERVER['REQUEST_URI'])); ?>';">
	  <INPUT TYPE="button" class="btnnew" value="���������" onclick="location = 'main.php';">
	  <BR><BR>
	  </div>
	<div style="background-color:#A5A5A5;padding:1"><center><B>������ ��������</B></center></div>
	<div style="line-height:17px;">
	<?php
		/*�������� �������� (������)*/
		$otdels_array = array (1=>'&nbsp;&nbsp;�������,����',2=>'&nbsp;&nbsp;������',3=>'&nbsp;&nbsp;������,������',4=>'&nbsp;&nbsp;����',5=>'&nbsp;&nbsp;���������� ������',6=>'&nbsp;&nbsp;������',7=>'&nbsp;&nbsp;��������',8=>'&nbsp;&nbsp;������',9=>'&nbsp;&nbsp;������ �����',10=>'&nbsp;&nbsp;������� �����',11=>'&nbsp;&nbsp;�����',12=>'&nbsp;&nbsp;������',13=>'&nbsp;&nbsp;�����',14=>'&nbsp;&nbsp;������',15=>'&nbsp;&nbsp;����',16=>'&nbsp;&nbsp;������',17=>'&nbsp;&nbsp;��������',18=>'&nbsp;&nbsp;������',19=>'&nbsp;&nbsp;�����������',20=>'&nbsp;&nbsp;������ � ��������',21=>'&nbsp;&nbsp;��������',22=>'&nbsp;&nbsp;��������',23=>'&nbsp;&nbsp;�������',24=>'&nbsp;&nbsp;��������',25=>'&nbsp;&nbsp;��������',26=>'&nbsp;&nbsp;��������',27=>'&nbsp;&nbsp;����������',28=>'&nbsp;&nbsp;����� � �������','29'=>'&nbsp;&nbsp;�������� �������',/*29=>'���������� �����������'/*,29=>'����� �����: ����������',30=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������� ����� �����'*/);
		$i=1;
		while ($i!=-1)
		{
			if(isset($otdels_array[$i]))
			{
				if(isset($_GET['otdel']) && $_GET['otdel']==$i) 
				{
				$color = 'C7C7C7';	
				} else {
				$color = 'e2e0e0';
				}
				if($i == 1) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/1.png"> <b>������:</b></div>';
			} elseif($i == 6) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/2.png"> <b>������:</b></div>';
			} elseif($i == 15) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/3.png"> <b>����:</b></div>';
			} elseif($i == 16) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/4.png"> <b>��������� ������:</b></div>';
			} elseif($i == 19) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/6.png"> <b>����������:</b></div>';
			} elseif($i == 21) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/7.png"> <b>��������:</b></div>';
			} elseif($i == 22) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/5.png"> <b>��������:</b></div>';
			} elseif($i == 23) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/8.png"> <b>�������:</b></div>';
			} elseif($i == 28) {
			  echo '<div class="shop_menu_txt"><img height="12" width="12" src="i/shop_ico/9.png"> <b>�������������:</b></div>';
			}
			echo '
			<A HREF="?repshop&otdel='.$i.'"><DIV style="background-color: #'.$color.'">
			'.$otdels_array[$i].'
			</A></DIV>
			';
			} else {
			$i = -2;
			}
			$i++;
		}
		
		if(isset($_GET['gifts'])) 
		{
		$color = 'C7C7C7';	
		} 
		echo '</DIV>';
	?>
	</div>
	</td>
	</table>
    <br>
	<div id="textgo" style="visibility:hidden;"></div>
<?
}
?>