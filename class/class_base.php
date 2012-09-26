<?php class base {

function fn(&$q,$s) { $rtn=true;
	foreach (explode("|",$s) as $key) { $fn="fn_".$key;
		if (!isset($q->$fn)) { 
			if (file_exists("fn/".$fn.".php")) { 
				include_once("fn/".$fn.".php"); 
				if (class_exists($fn)) {
					$q->$fn=new $fn;
					if (method_exists($q->$fn,"onLoad")) {
						$q->$fn->onLoad($q);
					}
				}
			}
		}
		if (!isset($q->$fn)) { $rtn=false; }
	}
	return $rtn;
}

function controls(&$q,$s) { $rtn=true;
	foreach (explode("|",$s) as $key) { $ct="".$key;
		if (!isset($q->$ct)) { 
			if (file_exists("controls/".$q->folders->controls."/".$ct.".php")) { 
				include_once("controls/".$q->folders->controls."/".$ct.".php"); 
				if (class_exists($ct)) {
					$q->$ct=new $ct;
				}
			}
		}
		if (!isset($q->$ct)) { $rtn=false; }
	}
	return $rtn;
}

function tpl(&$q,$s) { $rtn=true;
	foreach (explode("|",$s) as $key) { 
		$tpl="tpl_".$key; 
		$path="templates/".$q->folders->templates."/".$tpl.".php";
		if (!isset($q->$tpl)) { 
			if (file_exists($path)) { include_once($path); 
				if (class_exists($tpl)) {
					$q->$tpl=new $tpl;
				}
			}
		}
		if (!isset($q->$tpl)) { $rtn=false; }
	}
	return $rtn;
}

function sql(&$q,$s) { $rtn=true;
	foreach (explode("|",$s) as $key) { 
		$sql="sql_".$key; 
		$path="sql/".$q->folders->sql."/".$sql.".php";
		if (!isset($q->$sql)) { 
			if (file_exists($path)) { include_once($path); 
				if (class_exists($sql)) {
					$q->$sql=new $sql;
				}
			}
		}
		if (!isset($q->$sql)) { $rtn=false; }
	}
	return $rtn;
}

function ajax(&$q,$s) { $rtn=true;
	foreach (explode("|",$s) as $key) { 
		$ajax="ajax_".$key; 
		$path="ajax/".$q->folders->ajax."/".$ajax.".php";
		if (!isset($q->$ajax)) { 
			if (file_exists($path)) { include_once($path); 
				if (class_exists($ajax)) {
					$q->$ajax=new $ajax;
				}
			}
		}
		if (!isset($q->$ajax)) { $rtn=false; }
	}
	return $rtn;
}

} ?>
