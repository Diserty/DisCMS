<?php
	include('classes/main.class.php');
	$Main = new Main;
	$Main->Init();			
?>
<p style='text-align: center'><h3>My Sources</h3></p>
<br />
<div id='DisCMS'>
DisCMS e' un CMS realizzato in PHP/HTML/CSS con db MySQL usando MySQLi e qualche righetta di JavaScript.<br />
Ho realizzato questo cms per creare un sito veloce e minimale come piace a me<br />
Il mio CMS comprende...
[*] Blog<br />
[*] Forum<br />
[*] Profili utente<br />
[*] UCP (User Control Panel... Pannello di controllo utente)<br />
[*] Stili modificabili<br />
<br />
Naturalmente e' un cms opensource e potete averlo scaricandolo con git<br />
[link]
</div>
<?php
	$Main->Finish();
?>
