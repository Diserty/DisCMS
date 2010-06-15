<?php
	session_start();
	class Main {
		private $stylename = "default";
		private $lang = "it";
		function Init() {
			if(!$this->mysqli = new mysqli('localhost', 'root', 'lulzmysql88', 'discms'))
				throw new Exception($this->mysqli->error);
			include("styles/{$this->stylename}/header.php");
		}
		
		function Blog() {
			$query = $this->mysqli->query("SELECT * FROM articles");
			while($post = $query->fetch_array()) {
				$username = $this->get_Username_ById($post['author_id']);
				print "
				<div id='post'>
				#title: {$post['title']}<br />
				#content:<br />{$post['message']}<br />
				<p style='text-align: right'>Posted by {$username} on {$post['date']} at {$post['time']}</p><br />
				<br />
				<a onClick=\"spoil('comments_{$post['id']}')\">
				#here for view comments</a><br />
				<div id='comments_{$post['id']}' style='display: none'>
				";
				$qc = $this->mysqli->query("SELECT * FROM comments WHERE art_id={$post['id']}");
				while($comment = $qc->fetch_array()) {
					print "
					{$comment['text']}<br />
					<p style='text-align: right'>
					Posted by {$comment['author']} on {$comment['date']} at {$comment['time']}</p><hr><br />";
				}
				print "
				</div>
				<p style='text-align: center'>
				<a style='text-align: center' onClick=\"spoil('insert_post{$post['id']}')\">insert post</a>
				<div id='insert_post{$post['id']}' style='display: none; text-align: center'>
				<form action='index.php?mode=insert&art_id={$post['id']}' method='POST'>
					#author <input type='text' name='author'><br />
					#text<br /><textarea style='width: 500px; height: 200px' name='text'></textarea><br />
					<input type='submit' value='insert'><br />
				</form>
				</div>
				</p>
				<hr style='text-align: left; width: 580px'>
				</div>";
			}
		}
		
		function Blog_Insert($author, $text, $art_id, $date, $time) {
			$query = $this->mysqli->query("INSERT INTO comments (
			author,
			text,
			art_id,
			date,
			time
			) VALUES (
			'{$author}',
			'{$text}',
			{$art_id},
			'{$date}',
			'{$time}'
			);");
			if($query) {
				print "Comment successfully inserted.<br />\n";
				setcookie("comment_time", "10", time()+10, "/");
			} else
				print "Error with comment inserting: " . $this->mysqli->error;
		}
				
		function ViewProfile($id) {
			$query = $this->mysqli->query("SELECT * FROM users WHERE id={$id}");
			$user = $query->fetch_array();
			$q = $this->mysqli->query("SELECT * FROM users_info WHERE user_id={$id}");
			$u_info = $q->fetch_array();
			print "
			<table id='profile'>\n
				<tr>\n
					<td style='border-right: 1px dashed #FFFFFF'>
						<p style='text-align: center; font-size: 23px;'>{$user['username']}</p>
					</td>\n
				</tr>\n
				<tr>\n
					<td style='border-right: 1px dashed #FFFFFF; text-align: center'>
						<img src='users/avatars/{$user['user_avatar']}' /><br />\n
					</td>\n
					<td style='margin-top: 0px'>\n
						#about {$user['username']}<br /><br />\n
						@mail...... {$user['mail']}<br />\n
						@msn....... {$u_info['msn']}<br />\n
						@website... <a href='{$u_info['website']}'>{$u_info['website']}</a><br />\n
						@from...... {$u_info['from']}<br />\n
						@job....... {$u_info['job']}<br /><br />\n
						#forum signature<br />\n
						{$u_info['forum_sign']}
					</td>\n
				</tr>\n
			</table>\n";
		}
		
		function ViewTopic($id) {
			$query = $this->mysqli->query("SELECT * FROM topics WHERE id={$id}");
			$topic = $query->fetch_array();
			$username = $this->get_Username_ById($topic['author_id']);
			$user_avatar = $this->get_Info_ById($topic['author_id'], 'user_avatar');
			print "
			<table id='topic'>
				<tr>
					<td style='border-right: 1px dashed #FFFFFF'><p style='text-align: center; font-size: 23px;'>
					<a href='profiles.php?id={$topic['author_id']}'>{$username}</a></p></td>
					<td style='padding-left: 10px; font-stylef: italic'><h4>{$topic['name']}</h4></td>
				</tr>
				<tr>
					<td width='20%' style='border-right: 1px dashed #FFFFFF; padding-right: 10px'><p style='text-align: center'><img src='users/avatars/{$user_avatar}' /></p></td>
					<td style='padding-left: 10px'>{$topic['message']}</td>
				</tr>
			</table>
			";
		}
		
		function NewTopic($title, $topic, $forum_id) {
			if(isset($_COOKIE['topic_time']))
				die("Must wait for 20 seconds before posting another topic");
			$uex = explode("3324", $_SESSION['user_hash']); //[username]3324[id]
			$author_id = $uex[1];
			$date = date("d-m-y");
			$time = date("G:i:s");
			$query = $this->mysqli->query("INSERT INTO topics (
			name,
			message,
			date,
			time,
			forum_id,
			author_id
			) VALUES (
			'{$title}',
			'{$topic}',
			'{$date}',
			'{$time}',
			'{$forum_id}',
			'{$author_id}'
			)");
			$q = $this->mysqli->query("UPDATE forums SET last_author_id={$uex[1]} WHERE id='{$forum_id}';");
			if($query)
				return true;
			else
				return false;
		}
		
		function UCPEditInfo($info, $val) {
			$uex = explode("3324", $_SESSION['user_hash']);
			if($info == 'msn') {
				$val = htmlentities($val);
				$query = $this->mysqli->query("UPDATE users_info SET msn='{$val}' WHERE user_id={$uex[1]}");
				if($query) {
					print "Info successfully edited.<br />";
				} else {
					print "Error with info editing: {$this->mysqli->error}";
				}
			} else if($info == 'website') {
				$val = htmlentities($val);
				$query = $this->mysqli->query("UPDATE users_info SET website='{$val}' WHERE user_id={$uex[1]}");
				if($query) {
					print "Info successfully edited.<br />";
				} else {
					print "Error with info editing: {$this->mysqli->error}";
				}
			} else if($info == 'from') {
				$val = htmlentities($val);
				$query = $this->mysqli->query("UPDATE users_info SET from='{$val}' WHERE user_id={$uex[1]}");
				if($query) {
					print "Info successfully edited.<br />";
				} else {
					print "Error with info editing: {$this->mysqli->error}";
				}
			} else if($info == 'job') {
				$val = htmlentities($val);
				$query = $this->mysqli->query("UPDATE users_info SET job='{$val}' WHERE user_id={$uex[1]}");
				if($query) {
					print "Info successfully edited.<br />";
				} else {
					print "Error with info editing: {$this->mysqli->error}";
				}
			} else if($info == 'avatar') {
				$val = htmlentities($val);
				$query = $this->mysqli->query("UPDATE users SET user_avatar='{$val}' WHERE id={$uex[1]}");
				if($query) {
					print "Info successfully edited.<br />";
				} else {
					print "Error with info editing: {$this->mysqli->error}";
				}
			}
		}
		
		function ViewForum($id) {
			$query = $this->mysqli->query("SELECT * FROM topics WHERE forum_id={$id}");
			if($this->Is_Logged())
				print "<a href='community.php?mode=newtopic&fid={$id}'>#new topic</a><br /><br />";
			while($topic = $query->fetch_array()) {
				$username = $this->get_Username_ById($topic['author_id']);
				print "
				<div id='topics'>
					<a href='community.php?mode=viewtopic&id={$topic['id']}'>{$topic['name']}</a> (<a href='profiles.php?id={$topic['author_id']}'>{$username}</a>)
				</div>";
			}
		}
		
		function ViewForums() {
			$query = $this->mysqli->query("SELECT * FROM forums");
			print "<table id='viewforums'>";
			while($forum = $query->fetch_array()) {
				print "
				<tr style='border-bottom: 1px solid #FFF'>
					<td style='width: 70%'>
						<span id='forumname'><a href='community.php?mode=viewforum&id={$forum['id']}'>{$forum['name']}</a></span><br />
						<span id='forumdesc'>{$forum['desc']}</span><br />
					</td>
					<td style='width: 30%; border-left: 2px dashed #696969;'>
						<p style='text-align: right'>
							last post<br />
							<a href='profiles.php?id={$forum['last_author_id']}'>{$this->get_Username_ById($forum['last_author_id'])}</a>
						</p>
					</td>
				</tr>";
			}
			print "</table>";
		}
		
		function Register($username, $password, $mail) {
			$user_avatar = "noavatar.jpg";
			$query = $this->mysqli->query("INSERT INTO users (
			username,
			password,
			mail,
			user_avatar,
			level
			) VALUES (
			'{$username}',
			'{$password}',
			'{$mail}',
			'{$user_avatar}',
			'0'
			);");
			if($query) {
				$_SESSION['user_hash'] = "{$username}3324{$this->mysqli->insert_id}";
				print "You're successfully registered. You are now autmatically logged on.<br />\n";
			} else {
				print "Error with you registration: {$this->mysqli->error} ...";
			}
		}
		
		function Login($username, $password) {
			$password = md5($password);;
			$query = $this->mysqli->query("SELECT * FROM users WHERE username='{$username}' and password='{$password}';");
			$user = $query->fetch_array();
			$row = mysqli_num_rows($query);
			if($row == 1) {
				$_SESSION['user_hash'] = "{$username}3324{$user['id']}";
				print "You're now successfully logged.<br />\n";
			} else {
				print $this->mysqli->error . "<br />\n";
				print "Error with login. Check username and password.<br />\n";
			}
		}
		
		function Is_Logged() {
			if(isset($_SESSION['user_hash'])) {
				return true;
			} else {
				return false;
			}
		}
		
		function Terminal_Username() {
			if($this->Is_Logged()) {
				$uex = explode('3324', $_SESSION['user_hash']);
				print $uex[0];
			} else {
				print 'guest';
			}
		}
		
		function get_Info_ById($id, $info) {
			$query = $this->mysqli->query("SELECT * FROM users WHERE id='{$id}'");
			$user = $query->fetch_array();
			return $user[$info];
		}
		function get_Username_ById($id) {
			$query = $this->mysqli->query("SELECT * FROM users WHERE id='{$id}'");
			$user = $query->fetch_array();
			return $user['username'];
		}
		
		function Mysqlizer($string) {
			$string = $this->mysqli->real_escape_string($string);
			return $string;
		}
		
		function Finish() {
			include("styles/{$this->stylename}/footer.php");
		}
	}
?>
