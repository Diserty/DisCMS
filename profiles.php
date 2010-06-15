<?php
	include('classes/main.class.php');
	$Main = new Main;
	$Main->Init();
	if(isset($_GET['id'])) {
		$id = strval($_GET['id']);
		$Main->ViewProfile($id);
	}
	$Main->Finish();
?>
