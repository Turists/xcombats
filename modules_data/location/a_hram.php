<?php
if(!defined('GAME'))
{
	die();
}


if($u->room['file']=='a_hram')
{
	$shopProcent = 50;
	if(date('d',time())==22)
	{
		//$shopProcent = 1;
	}
	
	if(!isset($_GET['otdel']) && !isset($_GET['sale'])) 
	{
		$_GET['sale'] = 1;
	}elseif(isset($_GET['otdel']) && isset($_GET['sale'])){
		$_GET['sale'] = 1;
		unset($_GET['otdel']);
	}
	
	if(isset($u->stats['shopSale'],$_GET['sale']))
	{
		$bns = 0+$u->stats['shopSale'];
		if($bns!=0)
		{
			if($bns>0)
			{
				$bns = '+'.$bns;
			}
			$shopProcent -= $bns;
			if($shopProcent>99){ $shopProcent = 99; }
			if($shopProcent<1){ $shopProcent = 1; }
			echo '<div style="color:grey;"><b>� ��� ��������� ����� ��� �������: '.$bns.'%</b><br><small>�� ������� ��������� �������� �� '.(100-$shopProcent).'% �� �� ���������</small></div>';
		}
	}

	$sid = 14;

	$error = '';
	
	if(isset($_GET['buy']))
	{
		if($u->newAct($_GET['sd4'])==true)
		{
			$re = $u->buyItem($sid,(int)$_GET['buy'],(int)$_GET['x']);
		}else{
			$re = '�� ������� ��� ������ ������ ���� �������?';
		}
	}elseif(isset($_GET['sale']) && isset($_GET['sale1kr']))
	{
		$id = (int)$_GET['sale1kr'];
		$itm = mysql_fetch_array(mysql_query('SELECT `im`.*,`iu`.*,`iu`.`id` AS `id_user` FROM `items_users` AS `iu` LEFT JOIN `items_main` AS `im` ON (`im`.`id` = `iu`.`item_id`) WHERE `iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" AND `iu`.`id` = "'.mysql_real_escape_string($id).'" LIMIT 1'));
		$po = $u->lookStats($itm['data']);
		$po['toclan1'] = explode('#',$po['toclan']);
		$po['toclan1'] = $po['toclan1'][0];
		$see1 = 1;
		if($po['toclan1'] > 0) {
			$see1 = 0;
		}
		if($po['frompisher'] == 0) {
			$see1 = 0;
		}
		if($itm['gift'] > 0) {
			$see1 = 0;
		}	
		if(!isset($po['hprs'])) {
			$po['hprs'] = 0.001;
		}
		if(!isset($po['hprp'])) {
			$po['hprp'] = 0.0001;
		}
		$col = $u->itemsX($itm['id_user']);
		$prs1 = $u->floordec($po['hprs']*$col,2); //��
		$rps1 = $u->floordec($po['hprp']*$col); //���.
		if(isset($po['nosale']) || $see1 == 0)
		{
			$error = '�� ������� ������� ������� ...';
		}elseif($pl['type']<29 && ($po['srok'] > 0 || $pl['srok'] > 0))
		{
			$error = '�� ������� ������� ������� ...';
		}elseif(isset($itm['id']))
		{
			$colx = '';
			if($col > 0) {
				$colx = ' (x'.$col.')';
			}
			$error = '�� ������� �������� ������� &quot;'.$itm['name'].''.$colx.'&quot; �� '.$prs1.' ��.';
			$u->info['money'] += $prs1;
			
			mysql_query('UPDATE `items_users` SET `delete` = "'.time().'" WHERE `id` = "'.$itm['id'].'" LIMIT 1');
			mysql_query('UPDATE `items_users` SET `inGroup` = "0",`delete` = "'.time().'" WHERE `inGroup` = "'.$itm['id'].'" AND `uid` = "'.$u->info['id'].'" LIMIT '.$itm['group_max'].'');
			$u->addDelo(2,$u->info['id'],'&quot;<font color="green">System.shop</font>&quot;: ������� &quot;'.$itm['name'].' (x'.$col.')&quot; [itm:'.$itm['id'].'] ��� ������ � ������� �� <B>'.$prs1.' ��.</B> (���� ���������).',time(),$u->info['city'],'System.shop',0,0);
					
			mysql_query('UPDATE `users` SET `money` = "'.$u->info['money'].'" WHERE `id` = "'.$u->info['id'].'" LIMIT 1');
		}else{
			$error = '������� �� ������ � ���������.';
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
		if(isset($_GET['otdel'])) 
		{
			$otdels_small_array = array (1=>'<b>�����&nbsp;&quot;������: �������,����&quot;</b>',2=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',3=>'<b>�����&nbsp;&quot;������: ������,������&quot;</b>',4=>'<b>�����&nbsp;&quot;������: ����&quot;</b>',5=>'<b>�����&nbsp;&quot;������: ���������� ������&quot;</b>',6=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',7=>'<b>�����&nbsp;&quot;������: ��������&quot;</b>',8=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',9=>'<b>�����&nbsp;&quot;������: ������ �����&quot;</b>',10=>'<b>�����&nbsp;&quot;������: ������� �����&quot;</b>',11=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',12=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',13=>'<b>�����&nbsp;&quot;������: �����&quot;</b>',14=>'<b>�����&nbsp;&quot;������: ������&quot;</b>',15=>'<b>�����&nbsp;&quot;����&quot;</b>',16=>'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',17=>'<b>�����&nbsp;&quot;��������� ������: ��������&quot;</b>',18=>'<b>�����&nbsp;&quot;��������� ������: ������&quot;</b>',19=>'<b>�����&nbsp;&quot;����������: �����������&quot;</b>',20=>'<b>�����&nbsp;&quot;����������: ������ � ��������&quot;</b>',21=>'<b>�����&nbsp;&quot;��������&quot;</b>',22=>'<b>�����&nbsp;&quot;��������&quot;</b>',23=>'<b>�����&nbsp;&quot;�������&quot;</b>',24=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',25=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',26=>'<b>�����&nbsp;&quot;�������: ��������&quot;</b>',27=>'<b>�����&nbsp;&quot;�������: ����������&quot;</b>');
			if(isset($otdels_small_array[$_GET['otdel']]))
			{
				echo $otdels_small_array[$_GET['otdel']];	
			}
			
		} elseif (isset($_GET['sale']) && $_GET['sale']) 
		{
			echo '
			<B>�����&nbsp;&quot;������ �����&quot;</B><br>
			����� �� ������ �������� ���� ����, �� ������ ����� ��� ���������...<br>
			� ��� � �������: 
			';
		}
	?>
	</tr>
	<tr><td>
	<!--������ / ��������-->
	<table width="100%" CELLSPACING="1" CELLPADDING="1" bgcolor="#a5a5a5">
<?
		if(!isset($_GET['sale']))
		{
			//������� ���� � �������� ��� �������
			$u->shopItems($sid);
		}else{
			//������� ���� � ��������� ��� �������
			$itmAll = $u->genInv(69,'`iu`.`uid`="'.$u->info['id'].'" AND `iu`.`delete`="0" AND `iu`.`inOdet`="0" AND `iu`.`inShop`="0" ORDER BY `lastUPD` DESC');
			if($itmAll[0]==0)
			{
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
	<td bgcolor="#D3D3D3"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" /></td>
	<td bgcolor="#D3D3D3" nowrap><a href="#" id="greyText" class="menutop" onclick="location='main.php?loc=1.180.0.9&rnd=<? echo $code; ?>';" title="<? thisInfRm('1.180.0.9',1); ?>">����������� �������</a></td>
	</tr>
	</table>
	</td>
	</tr>
	</table>
	</td></table>
	</td></table>
	<div><br />
      <div align="right">
      <small>
	  �����: <?=$u->aves['now']?>/<?=$u->aves['max']?> &nbsp;<br />
	  � ��� � �������: <b style="color:#339900;"><?php echo round($u->info['money'],2); ?> ��.</b> &nbsp;
      </small>
      </div>
	  <br />
	  <?php
	/*��������*/
	if(!isset($_GET['sale']))
	{
	echo '
	<INPUT TYPE="button" value="�������� ����" onclick="location=\'?otdel='.$_GET['otdel'].'&sale=1\'">&nbsp;
	';
	} else {
	echo '
	<INPUT TYPE="button" value="������ ����" onclick="location=\'?otdel='.$_GET['otdel'].'\'">&nbsp;
	';
	}
	?>
    <INPUT TYPE="button" value="��������" onclick="location = '<? echo $_SERVER['REQUEST_URI']; ?>';"><BR>
	  </div>
	<div style="background-color:#A5A5A5;padding:1"><center><B>������ ��������</B></center></div>
	<div style="line-height:17px;">
	<?php
		/*�������� �������� (������)*/
		$otdels_array = array (1=>'������: �������,����',2=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',3=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������,������',4=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����',5=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;���������� ������',6=>'������: ������',7=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',8=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',9=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ �����',10=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������� �����',11=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����',12=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',13=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;�����',14=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',15=>'����',16=>'��������� ������: ������',17=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',18=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������',19=>'����������: �����������',20=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;������ � ��������',21=>'��������',22=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',23=>'�������',24=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',25=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',26=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;��������',27=>'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;����������');
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
			echo '
			<A HREF="?otdel='.$i.'"><DIV style="background-color: #'.$color.'">
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