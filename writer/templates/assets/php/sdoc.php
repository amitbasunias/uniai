<?php
if (isset($_POST['save']))
{
	$getcontent = $_POST['savetext'];
	$uniquefile = $_POST['uniquefile'];
	$savefile = fopen('../doc/'.$uniquefile.'.php', "w");
	fwrite($savefile, $getcontent);
	fclose($savefile);
	
	echo '<p style="color: green;">Saved</p>';
	
}