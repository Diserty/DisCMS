<html>
	<head>
		<title>DisCMS</title>
		<link rel="stylesheet" type="text/css" href="styles/<?php print $this->stylename ?>/style.css" />
	</head>
	<body>
		<div id='header'>
			[<?php $this->Terminal_Username() ?>@DisCMS ~]$ > <span style='text-decoration: blink'>_</span>
		</div>
		<div id='content'>
			<div id='menu'>
				<hr>
					|--> <a href='./index.php'>#main</a> :: 
					<a href='./community.php'>#community</a> :: 
					<a href='./sources.php'>#sources</a> :: 
					<a href='./projects.php'>#projects</a> :: 
					<a href='./aboutme.php'>#about_me</a> <--|
				<hr>
				<p style='text-align: right'>
					<a href='./ucp.php?mode=login'>#login</a> -- 
					<a href='./ucp.php?mode=register'>#register</a> -- 
					<a href='./ucp.php?mode=logout'>#logout</a>
				</p>
			</div>
			<div id='page'>
