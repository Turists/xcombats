<?php
header("Content-Type: text/plain; charset=utf-8");
if(isset($_POST['unique'], $_POST['country'], $_POST['operator'], $_POST['number'], $_POST['phone'], $_POST['message'], $_POST['hash'])) {
	$number = array("1234", "5678");
	if(!in_array($_POST['number'], $number)) {
		exit('��������� ���������� �� ������������ �����');
	}
	//
	$secret_key = 'kasdnKWK234mSDlk';
	//
	$md5 = md5($_POST['unique'].$_POST['country'].$_POST['operator'].$_POST['number'].$_POST['phone'].$_POST['message'].$secret_key);
	if(strcasecmp($md5, $_POST['hash']) == 0) {
		exit('���������� �� �������! ��� ���: 1234'); 
	}else{
		exit('���-�� �� ���...');
	}
} else{
		exit('���-�� �� ���');
}

?>