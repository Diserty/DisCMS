<?php
	include('classes/main.class.php');
	$Main = new Main;
	$Main->Init();
	if(isset($_GET['mode'])) {
		$mode = $_GET['mode'];
		if($mode == 'insert') {
			if(isset($_GET['art_id'])) {
				$id = strval($_GET['art_id']);
				if(isset($_POST['author']) and isset($_POST['text'])) {
					$author = $_POST['author'];
					$text = $_POST['text'];
					if(empty($author) or empty($text))
						die('Some fields are empty.<br />\n');
					if(isset($_COOKIE['comment_time']))
						die('You must wait 10 minutes for insert another comment.<br />\n');
					$author = htmlentities($author);
					$author = nl2br($author);
					$author = $Main->Mysqlizer($author);
					$text = htmlentities($text);
					$text = nl2br($text);
					$text = $Main->Mysqlizer($text);
					$date = date("d-m-y");
					$time = date("G:i:s");
					$Main->Blog_Insert($author, $text, $id, $date, $time);
				} else {
					print "Must compile the form.<br />\n";
				}
			} else {
				print "Must compile the form.<br />\n";
			}
		}
	} else {				
		$Main->Blog();
	}
	$Main->Finish();
?>
