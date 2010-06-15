<?php
	include('classes/main.class.php');
	$Main = new Main;
	$Main->Init(array('localhost', 'root', 'lolfight82', 'main'));
	if(isset($_GET['mode'])) {
		$mode = htmlentities($_GET['mode']);
		if($mode == 'viewforum') {
			if(isset($_GET['id'])) {
				$id = intval($_GET['id']);
				$Main->ViewForum($id);
			}
		} else if($mode == 'viewtopic') {
			if(isset($_GET['id'])) {
				$id = strval($_GET['id']);
				$Main->ViewTopic($id);
			}
		} else if($mode == 'newtopic') {
			if(!$Main->Is_Logged())
				die("You must login before send a new topic.<br />\n");
			if(isset($_GET['fid'])) {
				$fid = strval($_GET['fid']);
				if(isset($_GET['go'])) {
					if(isset($_POST['title']) and isset($_POST['topic'])) {
						if(isset($_COOKIE['topic_time']))
						    die("Must wait 20 seconds before posting.");
						$title = htmlentities($_POST['title']);
						$topic = htmlentities($_POST['topic']);
						$topic = nl2br($topic);
						$topic = $Main->Mysqlizer($topic);
						if($Main->NewTopic($title, $topic, $fid)) {
						    setcookie('topic_time', '20', time()+20, '/');
							print "Topic successfully inserted<br />\n";
						} else
							print "Error with topic inserting<br />\n";
					} else {
						print "Some fileds are empty.<br />\n";
					}
				} else {
					print "<form action='community.php?mode=newtopic&fid={$fid}&go' method='POST'>\n
						#title <input type='text' name='title'><br />\n
						#topic<br /><textarea name='topic' style='width: 600px; height: 300px'></textarea><br />\n
						<input type='submit' value='insert'><br />\n
					</form>\n";
				}
			}
		}
	} else {
		$Main->ViewForums();
	}
	$Main->Finish();
?>
