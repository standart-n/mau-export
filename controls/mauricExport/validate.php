<?php class validate {

function idForSql(&$id) {
	$id=intval($id); if ($id<1) { $id=1; }
}

function skipForSql(&$id) {
	$id=intval($id); if ($id<0) { $id=0; }
}

function searchForSql(&$s,$conv=true) { $rt=false;
	if (($s!="") && ($s!="Поиск по всем товарам...")) {
		if ($conv) {
			$s=iconv("UTF-8","cp1251",$s);
		}
		$rt=true;
	} else {
		$rt=false;
	}
	return $rt;
}

function rmTags($a) { $s=$a;
	//$s=preg_replace("/<(?:([a-zA-Z\?][\w:\-]*)(\s(?:\s*[a-zA-Z][\w:\-]*(?:\s*=(?:\s*\"(?:\\"|[^\"])*\"|\s*'(?:\\'|[^'])*'|[^\s>]+))?)*)?(\s*[\/\?]?)|\/([a-zA-Z][\w:\-]*)\s*|!--((?:[^\-]|-(?!->))*)--|!\[CDATA\[((?:[^\]]|\](?!\]>))*)\]\])>/i","",$s);
	return $s;
}

function delSqlWords($a) { $s=$a;
	foreach (array("insert","delete","from","update","into","where","drop","dump","select","\*","\;") as $key) {
		$s=preg_replace("/".$key."/i","",$s);
	}
	return $s;
}

function urlSort(&$sort) {
	$sort=trim(strtoupper($sort));
	switch ($sort) {
		case "SNAME": break;
		case "SCOUNTRY": break;
		case "SERIA": break;
		case "PRICE": break;
		default: $sort="SNAME";
	} 
}

function urlGrad(&$grad) {
	$grad=trim(strtoupper($grad));
	switch ($grad) {
		case "ASC": break;
		case "DESC": break;
		default: $grad="ASC";
	}
}

function urlPresence(&$pr) {
	$pr=trim(strtolower($pr));
	switch ($pr) {
		case "false": break;
		case "true": break;
		default: $pr="false";
	}
}

function baseStr(&$s) {
	$s=strval(trim(iconv("cp1251","UTF-8",$s)));	
}


function urlInt(&$id) {
	$id=intval(trim($this->rmTags($id)));	
}

function urlStr(&$s) {
	$s=trim(strval($s));
	$s=stripslashes($s);
	$s=$this->rmTags($s);
	$s=$this->delSqlWords($s);
	if (strlen($s)>255) { $s=substr($s,0,255); }	
}

function urlQuery(&$s) {
	$s=trim(strval($s));
	$s=stripslashes($s);
	$s=$this->rmTags($s);
	if (strlen($s)>999) { $s=substr($s,0,999); }	
}

function toUTF($a) { $s="";
	$s.=trim(iconv("cp1251","UTF-8",$a));
	return $s;
}

function toWin($a) { $s="";
	$s.=trim(iconv("UTF-8","cp1251",$a));
	return $s;
}

function checkName($s,&$e){ $check=false; $e="";
	if ($s!="") {
		if ((strlen($s)>3) && (strlen($s)<30)) { 
			if (sizeof(explode(" ",trim($s)))<2) {
				if (preg_match("/[^a-z0-9\.\,\"\?\!\;\:\#\$\%\&\(\)\*\+\-\/\<\>\=\@\[\]\\\^\_\{\}\|\~]+/i",$s)) {
					$check=true;
				} else { $e="введенное значение некорректно"; }
			} else { $e="pначение должно состоять из одного слова"; }
		} else { $e="введенное значение некорректно"; }
	} else { $e="вы ничего не ввели"; }
	if ($check) { $e="поле обязательно для заполнения"; }
	return $check;
}

function checkPhone($s,&$e){ $check=false; $e="";
	if ($s!="") {
		if ((strlen($s)>3) && (strlen($s)<30)) {
			if (preg_match("/\+?\d{1,3}(?:\s*\(\d+\)\s*)?(?:(?:\-\d{1,3})+\d|[\d\-]{6,}|(?:\s\d{1,3})+\d)/i",$s)) {
				$check=true;
			} else { $e="введенное значение некорректно"; }
		} else { $e="введенное значение некорректно"; }
	} else { $e="вы ничего не ввели"; }
	if ($check) { $e="поле обязательно для заполнения"; }
	return $check;
}


function checkEmail($s,&$e){ $check=false; $e="";
	if ($s!="") {
		if ((strlen($s)>3) && (strlen($s)<30)) {
			if (sizeof(explode(" ",trim($s)))<2) {
				if (preg_match("/\b[\w\.-]+@[\w\.-]+\.\w{2,4}\b/i",$s)) {
					$check=true;
				} else { $e="введенное значение некорректно"; }
			} else { $e="e-mail адрес не должен содержать пробелы"; }
		} else { $e="введенное значение некорректно"; }
	} else { $e="вы ничего не ввели"; }
	if ($check) { $e="поле обязательно для заполнения"; }
	return $check;
}

function checkByType($f,$s,&$e,&$cl_hint,&$cl_icon) {
	if ($this->$f($s,$e)) {
		$cl_hint=" orders-mount-hint-silver";
		$cl_icon=" orders-mount-check-true";
	} else {
		$cl_hint=" orders-mount-hint-red";		
		$cl_icon=" orders-mount-check-false";
	}
}

function comment($a) { $s=$a;
	$s=trim(strval($s));
	$s=stripslashes($s);
	$s=$this->rmTags($s);
	$s=$this->delSqlWords($s);
	if (strlen($s)>999) { $s=substr($s,0,999); }	
	return $s;
}

} ?>
