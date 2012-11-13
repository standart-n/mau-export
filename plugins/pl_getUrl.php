<?php class pl_getUrl {

function engine(&$q) { 
	$er=false; $ms=array(); 
	$q->url->method="site";
	if ($q->base->controls($q,"validate")) {
		if ((isset($q->queryString)) && (isset($q->url))) {
			$qs=$_SERVER["QUERY_STRING"];
			$q->validate->urlQuery($qs);
			foreach (explode("/",$qs) as $key) {
				if (($key!="none") && ($key!="")) {
					$k=explode(":",$key);
					if (isset($k[0])) { $field=$k[0]; } else { $field=""; }
					if (isset($k[1])) { $value=$k[1]; } else { $value=""; }
					if (($field!="") && ($value!="")) {
						$ms[$field]=$value;
					}
				}
			}
			foreach ($q->queryString as $key=>$options) {
				if (isset($options->value)) {
					$q->url->$key=$options->value;
				} else {
					if (isset($ms[$key])) {
						$val=$ms[$key];
						if (isset($options->validate)) {
							foreach ($options->validate as $v) {
								$q->validate->$v($val);
							}
						}
						$q->url->$key=$val;
					} else {
						if (isset($options->default)) {
							$q->url->$key=$options->default;
						} else {
							$q->url->$key="";
						}
					}
				}
			}		
		}	
	}
}

}
