<?php session_start();
	foreach (array("main","q","base") as $key) {
		$f='class/class_'.$key.'.php';
		if (file_exists($f)) { include_once($f); }
	}
	if (class_exists("main")) {
		$main=new main;
		$q=$main->engine();
		echo $q->html;
		unset($main);
	}
?>
