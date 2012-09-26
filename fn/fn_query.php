<? class fn_query {

function onLoad(&$q) {
	eval($this->query());
}

function select(&$q,$a,&$r="") { $ms=array();
	if (is_array($a)) { $ms=$a; } else { $ms["sql"]=$a; }
	return $this->execQuery($q,"select",$ms,$r);
}

function update(&$q,$a,&$r="") { $ms=array();
	if (is_array($a)) { $ms=$a; } else { $ms["sql"]=$a; }
	return $this->execQuery($q,"update",$ms);
}

function insert(&$q,$a,&$r="") { $ms=array();
	if (is_array($a)) { $ms=$a; } else { $ms["sql"]=$a; }
	return $this->execQuery($q,"insert",$ms);
}

function delete(&$q,$a,&$r="") { $ms=array();
	if (is_array($a)) { $ms=$a; } else { $ms["sql"]=$a; }
	return $this->execQuery($q,"delete",$ms);
}



function fbUpdate($q,$fb,&$r="") { $rt=false;
	$query=@ibase_query($q->$fb["cn"]->$fb["it"],$fb["sql"]);
	if (isset($query)) { if ($query) { 
		$rt=true;
		ibase_commit($q->$fb["cn"]->$fb["it"]);
		$q->$fb["cn"]->$fb["it"]=ibase_trans(IBASE_WRITE+IBASE_COMMITTED+IBASE_REC_VERSION+IBASE_NOWAIT,$q->$fb["cn"]->db);
	} }
	$r=$rt;
	return $rt;
}

function fbInsert($q,$fb,&$r="") { $rt=false;
	$query=@ibase_query($q->$fb["cn"]->$fb["it"],$fb["sql"]);
	if (isset($query)) { if ($query) { 
		$rt=true;
		ibase_commit($q->$fb["cn"]->$fb["it"]);
		$q->$fb["cn"]->$fb["it"]=ibase_trans(IBASE_WRITE+IBASE_COMMITTED+IBASE_REC_VERSION+IBASE_NOWAIT,$q->$fb["cn"]->db);
	} }
	$r=$rt;
	return $rt;
}

function fbDelete($q,$fb,&$r="") { $rt=false;
	$query=@ibase_query($q->$fb["cn"]->$fb["it"],$fb["sql"]);
	if (isset($query)) { if ($query) { 
		$rt=true;
		ibase_commit($q->$fb["cn"]->$fb["it"]);
		$q->$fb["cn"]->$fb["it"]=ibase_trans(IBASE_WRITE+IBASE_COMMITTED+IBASE_REC_VERSION+IBASE_NOWAIT,$q->$fb["cn"]->db);
	} }
	$r=$rt;
	return $rt;
}

function fbSelect($q,$fb,&$r="") { $rt=false; $ms=array();
	$query=@ibase_query($q->$fb["cn"]->$fb["it"],$fb["sql"]);
	if (isset($query)) { if ($query) { $rt=true;
		if ($fb["get"]=="object") {
			while ($line=ibase_fetch_object($query)) {
				if (isset($line)) {
					if (is_object($line)) {
						$ms[]=$line;
						if (sizeof($ms)>=$fb["limit"]) break;
					}
				}
			}
		}
		if ($fb["get"]=="array") {
			while ($line=ibase_fetch_row($query)) {
				if (isset($line)) {
					if (is_array($line)) {
						$ms[]=$line;
						if (sizeof($ms)>=$fb["limit"]) break;
					}
				}
			}
		}
	} }
	if (sizeof($ms)==1) { $r=$ms[0]; } else { $r=$ms; }
	return $rt;
}

function execQuery(&$q,$method,$ms,&$r="") { $a=array();
	if (in_array($method,array("select","insert","update","delete"))) {
		$a=$this->getConnection($q,$ms);
		if (isset($a["type"])) { $type=$a["type"]; }
		if (isset($a["cn"])) { $cn=$a["cn"]; }
		if (isset($a["it"])) { $it=$a["it"]; }
		if (isset($ms["sql"])) {
			if ($ms["sql"]!="") {
				$sql=$ms["sql"];
			}
		}
		if (isset($ms["get"])) {
			if (in_array($ms["get"],array("object","array"))) {
				$get=$ms["get"];
			}
		}
		if (!isset($get)) { 
			if (isset($q->database->settings->type_of_data_return)) {
				$get=$q->database->settings->type_of_data_return;
			} else {
				$get="object";
			}
		}
		if (isset($ms["limit"])) {
			$limit=intval($ms["limit"]);
		}
		if (!isset($limit)) { 
			if (isset($q->database->settings->limit_of_rows)) {
				$limit=$q->database->settings->limit_of_rows;
			} else {
				$limit=1000;
			}
		}
		if ((isset($cn)) && (isset($type)) && (isset($sql))) {
			if ($type=="firebird") {
				if (isset($it)) {
					if (isset($q->$cn)) {
						if ((isset($q->$cn->$it)) && (isset($q->$cn->db))) {
							switch ($method) {
								case "select": $rt=$this->fbSelect($q,array("cn"=>$cn,"it"=>$it,"sql"=>$sql,"get"=>$get,"limit"=>$limit),$r); break;
								case "update": $rt=$this->fbUpdate($q,array("cn"=>$cn,"it"=>$it,"sql"=>$sql),$r); break;
								case "insert": $rt=$this->fbInsert($q,array("cn"=>$cn,"it"=>$it,"sql"=>$sql),$r); break;
								case "delete": $rt=$this->fbSelect($q,array("cn"=>$cn,"it"=>$it,"sql"=>$sql),$r); break;
							}
						}
					}
				}
			}										
		}
	}
	if (!isset($rt)) { $rt=false; }
	return $rt;
}


function getConnection(&$q,$ms){ $a=array();
	if (isset($ms["connection"])) {
		if ($ms["connection"]!="") { 
			$ms_cn=$ms["connection"];
			if (isset($q->$ms_cn)) {
				if (isset($q->$ms_cn->db)) {
					if (isset($q->$ms_cn->type)) {
						if ($q->$ms_cn->type=="firebird") {
							if (isset($ms["transaction"])) {
								$ms_it=$ms["transaction"];
								if (isset($q->$ms_cn->$ms_it)) {
									$type="firebird";
									$cn=$ms_cn;
									$it=$ms_it;
								}
							}
							if (!isset($it)) {
								if (isset($q->database->connections->$ms_cn)) {
									if (isset($q->database->connections->$ms_cn->transactions)) {
										foreach ($q->database->connections->$ms_cn->transactions as $it_alias=>$it_data) {
											if (!isset($it)) {
												if (isset($q->$ms_cn->$it_alias)) {
													$type="firebird";
													$cn=$ms_cn;
													$it=$it_alias;
												}
											}	
										}
									}
								}
							}
						}					
					}
				}
			}						
		}
	}
	if (!isset($cn)) {
		if (isset($q->database->default)) {
			if (isset($q->database->default->connection)) {
				$de_cn=$q->database->default->connection;
				if (isset($q->$de_cn)) {
					if (isset($q->$de_cn->db)) {
						if (isset($q->$de_cn->type)) {
							if ($q->$de_cn->type=="firebird") {
								if (isset($q->database->default->transaction)) {
									$de_it=$q->database->default->transaction;
									if (isset($q->$de_cn->$de_it)) {
										$type="firebird";
										$cn=$de_cn;
										$it=$de_it;
									}
								}
								if (!isset($it)) {
									if (isset($q->database->connections->$de_cn)) {
										if (isset($q->database->connections->$de_cn->transactions)) {
											foreach ($q->database->connections->$de_cn->transactions as $it_alias=>$it_data) {
												if (!isset($it)) {
													if (isset($q->$de_cn->$it_alias)) {
														$type="firebird";
														$cn=$de_cn;
														$it=$it_alias;
													}
												}
											}
										}
									}
								}
							}					
						}
					}					
				}
			}			
		}		
	}
	if (isset($type)) { $a["type"]=$type; }
	if (isset($cn)) { $a["cn"]=$cn; }
	if (isset($it)) { $a["it"]=$it; }
	return $a;
}

function query() { $s="";
	$s.='function query(&$q,$a,&$r=""){ $ms=array();';
	$s.='if (is_array($a)) { $ms=$a; $sql=$a["sql"]; } else { $ms["sql"]=$a; $sql=$a; }';
	$s.='switch (strtolower(substr($sql,0,6))){';
	$s.='case "select": return $q->fn_query->execQuery($q,"select",$ms,$r); break;';
	$s.='case "update": return $q->fn_query->execQuery($q,"update",$ms,$r); break;';
	$s.='case "insert": return $q->fn_query->execQuery($q,"insert",$ms,$r); break;';
	$s.='case "delete": return $q->fn_query->execQuery($q,"delete",$ms,$r); break;';
	$s.='}';
	$s.='}';
	return $s;
}

} ?>
