<?php class main { 

var $pl=array();

function engine() {
	$q=new q;
	foreach (array("configure","plugins","base","display") as $key) { 
		$this->$key($q); 
	}
	return $q;
}

function configure(&$q) {
	chdir("./settings"); 
	$dir=opendir(".");
	while ($d=readdir($dir)) {
        if (is_file($d)) { if (preg_match("/[0-9a-z]+\.json/i",$d)) {
			$nm=str_replace('.json','',$d);
			$f=file_get_contents($d);
			$json=json_decode($f);
			$q->$nm=$json;
		} }
 	}
	closedir($dir); chdir("..");	
}

function plugins(&$q) { 
	chdir("./plugins"); 
	$dir=opendir(".");
	while ($d=readdir($dir)) {
        if (is_file($d)) { if (preg_match("/pl_[0-9a-z]+\.php/i",$d)) {
			include_once('../plugins/'.$d); $nm=str_replace('.php','',$d);
			if (class_exists($nm)) {
				$this->$nm=new $nm; $this->pl[]=$nm;
			}
		}   }
	}
	closedir($dir); chdir("..");	

}
function base(&$q) {
	if (class_exists("base")) { $q->base=new base; }
}

function display(&$q) {
    	asort($this->pl); reset($this->pl);
        foreach ($this->pl as $plugin) { 
			$this->$plugin->engine($q);
			unset($this->$plugin);
		}
}

} ?>
