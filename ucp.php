<?php
	include('classes/main.class.php');
	$Main = new Main;
	$Main->Init();
	if(isset($_GET['mode'])) {
		$mode = htmlentities($_GET['mode']);
		if($mode == 'login') {
			if(isset($_GET['go'])) {
				if(isset($_POST['username']) and isset($_POST['password'])) {
					$username = $_POST['username'];
					$password = $_POST['password'];
					$Main->Login($username, $password);
				} else {
					print "Some fields are empty.<br />\n";
				}
			} else {
				print "<form action='ucp.php?mode=login&go' method='POST'>\n
				#username <input type='text' name='username'><br />\n
				#password <input type='password' name='password'><br />\n
				<input type='submit' value='login'><br />\n
				</form>\n";
			}
		} else if($mode == 'logout') {
			if(!$Main->Is_Logged())
				print "You're not logged.<br />\n";
			else
				session_destroy();
		} else if($mode == 'register') {
			if(isset($_GET['go'])) {
				if(isset($_POST['username']) and isset($_POST['password']) and isset($_POST['mail'])) {
					$username = $_POST['username'];
					$password = $_POST['password'];
					$mail = $_POST['mail'];
					if(empty($username) or empty($password) or empty($mail))
						die("Some fileds are empty.<br />\n");
					$password = md5($password);
					$Main->Register($username, $password, $mail);
				} else {
					print "You must compile the form.<br />\n";
				}
			} else {
				print "<form action='ucp.php?mode=register&go' method='POST'>\n
				#username <input type='text' name='username'><br />\n
				#password <input type='password' name='password'><br />\n
				#mail <input type='text' name='mail'><br />\n
				<input type='submit' value='register'><br />\n
				</form>\n";
			}
		}
	} else {
		if(isset($_GET['msn_edit'])) {
			if(isset($_POST['msn'])) {
				$msn = $_POST['msn'];
				if(empty($msn))
					die("Empty field.<br />\n");
				$Main->UCPEditInfo('msn', $msn);
			}
		} else {
			print "
			<a href='#' onMouseOver=\"document.getElementById('msn_edit').style.display='block';\"
			onClick=\"document.getElementById('msn_edit').style.display='none';\">~msn</a><br />
			<div style='display: none' id='msn_edit'>
				<form action='ucp.php?msn_edit' method='POST'>
					#new msn <input type='text' name='msn'>&emsp;
					<input type='submit' value='edit'>
				</form>
			</div>
			<a href='#' onMouseOver=\"document.getElementById('ws_edit').style.display='block';\"
			onClick=\"document.getElementById('ws_edit').style.display='none';\">~website</a><br />
			<div style='display: none' id='ws_edit'>
				<form action='ucp.php?msn_edit' method='POST'>
					#new website <input type='text' name='website'>&emsp;
					<input type='submit' value='edit'>
				</form>
			</div>
			<a href='#' onMouseOver=\"document.getElementById('from_edit').style.display='block';\"
			onClick=\"document.getElementById('from_edit').style.display='none';\">~from</a><br />
			<div style='display: none' id='from_edit'>
				<form action='ucp.php?msn_edit' method='POST'>
					#new address <input type='text' name='from'>&emsp;
					<input type='submit' value='edit'>
				</form>
			</div>
			<a href='#' onMouseOver=\"document.getElementById('job_edit').style.display='block';\"
			onClick=\"document.getElementById('job_edit').style.display='none';\">~job</a><br />
			<div style='display: none' id='job_edit'>
				<form action='ucp.php?msn_edit' method='POST'>
					#new job <input type='text' name='job'>&emsp;
					<input type='submit' value='edit'>
				</form>
			</div>
			<a href='#' onMouseOver=\"document.getElementById('avatar_edit').style.display='block';\"
			onClick=\"document.getElementById('avatar_edi').style.display='none';\">~avatar</a><br />
			<div style='display: none' id='avatar_edit'>
				<form action='ucp.php?msn_edit' method='POST'>
					#new msn <input type='text' name='avatar'>&emsp;
					<input type='submit' value='edit'>
				</form>
			</div>";
		}
	}
	$Main->Finish();
?>
