<?
if(!defined('GAME'))
{
	die();
}
$tattack = '';

if( date('H') >= 22 || date('H') < 6 ) {
	$tattack = '<a style="color:#D8D8D8" style="cursor:pointer" onclick="top.useMagic(\'Нападение на персонажа\',\'night_atack\',\'pal_button8.gif\',1,\'main.php?nightatack=1\');">Напасть</a> &nbsp; ';
}

if($u->room['file']=='an/cp1')
{
	if(date("H")>=6 && date("H")<22) {
		$now = 'day';
	}else{
		$now = 'night';
	}
?>
<script>
<?
if(date("H")<6 || date("H")>=22) 
{
?>
function AtackNoWindow()
{
	var dt = document.getElementById('atackDiv');
	if(dt.style.display=='none')
	{
		dt.style.display = '';
	}else{
		dt.style.display = 'none';
	}
}
<?
}
?>
var no = 20; // snow number
var speed = 15; // smaller number moves the snow faster
var sp_rel = 1.4; //speed relevation
var snowflake1 = "http://img.xcombats.com/i/itimeges/snow1.gif";
var snowflake2 = "http://img.xcombats.com/i/itimeges/snow2.gif";
 
var i, doc_width, doc_height;

dx = new Array();
xp = new Array();
yp = new Array();
am = new Array();
stx = new Array();
sty = new Array();

Array.prototype.exists = function(el)
{
    for(var i=0;i<this.length;i++)
	if(this[i]==el)
	    return true;
    return false;	
}

var rooms = ['0','1'];

function SetVariable(c) {
	dx[c] = 0;                        // set coordinate variables
	am[c] = Math.random()*15;         // set amplitude variables
	xp[c] = Math.random()*(doc_width-35) + 0 + am[c];  // set position variables
	yp[c] = 0;
	stx[c] = 0.02 + Math.random()/10; // set step variables
	sty[c] = 0.7 + Math.random();     // set step variables
}

function DrawWeather(room) {
  
    doc_width = document.getElementById('img_ione').width;
    doc_height = document.getElementById('img_ione').height;
    
	var div = '';
	for (i = 0; i < no; ++ i) {  
		SetVariable(i);
		div += "<div id=\"dot"+ i +"\" style=\"POSITION: absolute; Z-INDEX: 30" + i +"; VISIBILITY: visible; TOP: " + 0 + "px; LEFT: " + 0 + "px;\"><img id=\"im"+ i +"\" src=\"" + (sty[i]<sp_rel ? snowflake2 : snowflake1 ) + "\" border=\"0\" alt=\"Снежинка\"></div>";
	}
	
	document.getElementById('snow').innerHTML = div;	
	return 1;
}

function WeatherBegin() {  // IE main animation function
    
    for (i = 0; i < no; ++ i) {  // iterate for every dot
        yp[i] += sty[i] < sp_rel ? sty[i]/2 : sty[i];
        if (yp[i] > doc_height-40) {
            SetVariable(i);
            var im = document.getElementById('im'+i);
            im.src = (sty[i] < sp_rel) ? snowflake2 : snowflake1;
        }
        dx[i] += stx[i];
        document.getElementById('dot'+i).style.top = yp[i];
        document.getElementById('dot'+i).style.left = xp[i] +  am[i]*Math.sin(dx[i]);
    }
    setTimeout('WeatherBegin()', speed);
}


</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0">
  <tr>
    <td width="250" valign="top">      
        <? $usee = $u->getInfoPers($u->info['id'],0); if($usee!=false){ echo $usee[0]; }else{ echo 'information is lost.'; } ?>
    </td>
    <td width="230" valign="top" style="padding-top:19px;"><? include('modules_data/stats_loc.php'); ?></td>
    <td valign="top"><div align="right">
      <table  border="0" cellpadding="0" cellspacing="0">
        <tr align="right" valign="top">
          <td>
                <? if($re!=''){ echo '<font color="red"><b>'.$re.'</b></font>'; } ?>
                <table width="500" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                    <td>
                      <div style="position:relative; cursor: pointer;" id="ione"><!-- <? echo $now; ?> -->
                      <img src="http://img.xcombats.com/city/angelscity/<? echo $now; ?>/angel_bg1_1.jpg" alt="" name="img_ione" width="500" height="268" border="1" id="img_ione"/>
                      <div id="buttons_on_image" style="cursor:pointer; font-weight:bold; color:#D8D8D8; font-size:10px;">
                          <? echo $tattack; ?>
                          &nbsp;
                          <span onMouseMove="this.runtimeStyle.color = 'white';" onMouseOut="this.runtimeStyle.color = this.parentElement.style.color;" onclick="window.open('http://xcombats.com/forum', 'forum', 'location=yes,menubar=yes,status=yes,resizable=yes,toolbar=yes,scrollbars=yes,scrollbars=yes')">Форум</span> &nbsp;
                      </div>
                      <script language="javascript" type="text/javascript">
                        <!--
                        if(document.getElementById('ione'))
                        {
                            document.getElementById('ione').appendChild(document.getElementById('buttons_on_image'));
                            document.getElementById('buttons_on_image').style.position = 'absolute';
                            document.getElementById('buttons_on_image').style.bottom = '8px';
                            document.getElementById('buttons_on_image').style.right = '23px';
                        }else{
                            document.getElementById('buttons_on_image').style.display = 'none';
                        }
                        -->
                      </script>

<div style="position: absolute; left: 309px; top: 119px; width: 79px; height: 88px; z-index: 94;"><img  <? thisInfRm('2.180.0.239'); ?> src="http://img.xcombats.com/city/angelscity/day/angvokzal.gif" width="114" class="aFilter" height="89" /></div>

<div style="position: absolute; left: 436px; top: 180px; width: 40px; height: 23px; z-index: 94;"><img  <? thisInfRm('2.180.0.236'); ?> src="http://img.xcombats.com/city/angelscity/day/angstrelka.gif" width="34" class="aFilter" height="20" /></div>

<div style="position: absolute; left: 29px; top: 69px; width: 79px; height: 88px; z-index: 93;"><img  <? thisInfRm('2.180.0.235'); ?> src="http://img.xcombats.com/city/angelscity/day/angshop.gif" width="109" class="aFilter" height="69" /></div>

<div style="position: absolute; left: 80px; top: 132px; width: 79px; height: 71px; z-index: 93;"><img  <? thisInfRm('2.180.0.262'); ?> src="http://img.xcombats.com/city/angelscity/day/angpochta.gif" width="128" height="68" class="aFilter" /></div>

<div style="position: absolute; left: 112px; top: 47px; width: 79px; height: 67px; z-index: 92;"><img  <? thisInfRm('2.180.0.277'); ?> src="http://img.xcombats.com/city/angelscity/day/angcomission.gif" width="98" class="aFilter" height="59" /></div>

<div style="position: absolute; left: 355px; top: 87px; width: 79px; height: 57px; z-index: 93;"><img  <? thisInfRm('2.180.0.249'); ?> src="http://img.xcombats.com/city/angelscity/day/angremont.gif" width="112" class="aFilter" height="58" /></div>

<div style="position: absolute; left: 204px; top: 19px; width: 79px; height: 88px; z-index: 93;"><img  <? thisInfRm('2.180.0.230'); ?> src="http://img.xcombats.com/city/angelscity/day/angklub.gif" width="110" class="aFilter" height="129" /></div>

<div style="position: absolute; left: 214px; top: 137px; width: 79px; height: 88px; z-index: 93;"><img  src="http://img.xcombats.com/city/angelscity/day/angel1.gif" width="89" height="84" /></div>

<div style="position: absolute; left: 16px; top: 99px; width: 50px; height: 50px; z-index: 93;"><img  src="http://img.xcombats.com/city/angelscity/day/t1.gif" width="60" height="64" /></div>
<div style="position: absolute; left: 69px; top: 140px; width: 50px; height: 50px; z-index: 93;"><img  src="http://img.xcombats.com/city/angelscity/day/t1.gif" width="60" height="64" /></div>
<div style="position: absolute; left: 391px; top: 115px; width: 50px; height: 50px; z-index: 93;"><img  src="http://img.xcombats.com/city/angelscity/day/t1.gif" width="60" height="64" /></div>



                        <!-- <div style="position:absolute; left:166px; top:149px; width:27px; height:55px; z-index:99;"><img  src="http://img.xcombats.com/i/images/300x225/capital/2pm.gif" width="27" height="55" title="Памятник Мироздателю" /></div> -->
        
                        <!-- <div style="position:absolute; left:100px; top:146px; width:75px; height:90px; z-index:99;"><img src="http://img.xcombats.com/i/images/300x225/capital/stellav.gif" width="75" height="90" title="Стела выбора" class="aFilter" /></div> -->
                        <div id="snow"></div>
                        <? echo $goline; ?>
                      </div>
                    </td>
                  </tr>
                </table> 
<?
if(date("H")<6 || date("H")>=22) 
{
?>
                <div align="center" id="atackDiv" style="display:none;">
                   <form method="post" action="main.php">
                   <table width="300" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><div style="width:300px; padding:3px; margin:7px; background-color:#CCCCCC; border:1px solid #575757;"> Введите логин жертвы:<br />
                            <input name="atack" type="text" id="atack" size="35" maxlength="30" />
                            <input type="submit" name="button" id="button" class="btn" value="OK" />
                      </div></td>
                    </tr>
                  </table>                 
                  </form>
                </div> 
<?
}
?>
        <!-- -->
        <div style="width:500px; text-align:left; background-color:#D3D3D3;">
        <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('2.180.0.230'); ?>>Бойцовский Клуб</a></span>
        <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('2.180.0.277'); ?>>Комиссионный магазин</a></span>
        <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('2.180.0.235'); ?>>Магазин</a></span>
        <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('2.180.0.239'); ?>>Вокзал</a></span>
        <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('2.180.0.262'); ?>>Почта</a></span>
        <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('2.180.0.249'); ?>>Ремонтная мастерская</a></span>
        <span style="white-space:nowrap; padding-left:3px; padding-right:3px; height:10px"><img src="http://img.xcombats.com/i/move/links.gif" width="9" height="7" />&nbsp;<a href="#" class="menutop" <? thisInfRm('2.180.0.236'); ?>>Страшилкина улица</a></span>
        </div>
        <!-- -->
              <div style="display:none; height:0px " id="moveto"></div>
              </td>
          <td>
              <!-- <br /><span class="menutop"><nobr>Комната для новичков</nobr></span>-->
          </td>
        </tr>
      </table>
      	<small>
        <HR>
        <strong>Внимание!</strong> Никогда и никому не говорите пароль от своего персонажа. Не вводите пароль на других сайтах, типа "новый город", "лотерея", "там, где все дают на халяву". Пароль не нужен ни паладинам, ни кланам, ни администрации, <U>только взломщикам</U> для кражи вашего героя.<BR>
        <em>Администрация.</em></small> <BR>
       <? echo $rowonmax; ?><BR>
        
      </div></td>
  </tr>
</table>
<?
}

?>