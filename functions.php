<?php
function instanceFixData(){
	global $newTags;
	global $deprecatedTags;
	$newTags = array();
	$deprecatedTags = array();

	$newTags[] = "article";
	$newTags[] = "aside";
	$newTags[] = "bdi";
	$newTags[] = "details";
	$newTags[] = "dialog";
	$newTags[] = "figcaption";
	$newTags[] = "section";
	$newTags[] = "figure";
	$newTags[] = "footer";
	$newTags[] = "header";
	$newTags[] = "main";
	$newTags[] = "mark";
	$newTags[] = "menuitem";
	$newTags[] = "meter";
	$newTags[] = "nav";
	$newTags[] = "progress";
	$newTags[] = "rp";
	$newTags[] = "rt";
	$newTags[] = "ruby";
	$newTags[] = "summary";
	$newTags[] = "time";
	$newTags[] = "wbr";
	$newTags[] = "canvas";
	$newTags[] = "svg";
	$newTags[] = "audio";
	$newTags[] = "source";
	$newTags[] = "track";
	$newTags[] = "video";
	$deprecatedTags[] = "acronym";
	$deprecatedTags[] = "applet";
	$deprecatedTags[] = "baseFront";
	$deprecatedTags[] = "big";
	$deprecatedTags[] = "center";
	$deprecatedTags[] = "dir";
	$deprecatedTags[] = "font";
	$deprecatedTags[] = "frame";
	$deprecatedTags[] = "frameset";
	$deprecatedTags[] = "isindex";
	$deprecatedTags[] = "noframes";
	$deprecatedTags[] = "s";
	$deprecatedTags[] = "strike";
	$deprecatedTags[] = "tt";


}
function verifyHtmlVersion($code){
	
	$doctype = "0";
	$version = 0;
	$res = array();

	$pos = strrpos($code, "DTD HTML");
	if($pos!== false){
		$docVersion = substr($code, $pos, strpos($code, "//", $pos)-$pos);
		$version =substr($docVersion, strpos($docVersion, "HTML ")-strlen($docVersion));
		$version = trim(str_replace("HTML", "", $version));
		$doctype = $docVersion;
	}
	else
	{
		$doctype = "HTML 5";
		$version = 5;
	}
	$res["version"] = $version;
	$res["doctype"] = $doctype;
	return $res;

}
function getTitle($dom){
	$els = $dom->getElementsByTagName('title');
	if($els->length>0) {$title = $els->item(0)->textContent;	}
	else{$title="Title not found.";}
	$r["title"] = $title;
	return $r;

}
function isLocal($link){
	global $server;
	$link = trim($link);
	$link_server = getServerHost($link);
	if((strpos($link, "http://") !== false) || (strpos($link, "https://") !== false)) {
		if(strcmp($server, $link_server)==0) {return true;}		
		return false;
	}
	else{
		return true;
	}
}
function getDeprecatedTags($dom){
	global $deprecatedTags;
	$ar["deprecated"] = array();
	foreach ($deprecatedTags as $value)
	{
		$links = $dom->getElementsByTagName($value); if($links->length>0) { $ar["deprecated"][] = "<".$value.">"; }
	}
	return $r["deprecated"] = $ar;
}
function getNewerTags($dom){
	global $newTags;
	$ar["newer"] = array();
	foreach ($newTags as $value)
	{
		$links = $dom->getElementsByTagName($value); if($links->length>0) { $ar["newer"][] = "<".$value.">"; }
	}
	return $r["newer"] = $ar;
}


function getLinks($dom){
	global $server;
	$int = array();
	$ext = array();
	$ids = array();
	$jsfunc = array();
	$links = $dom->getElementsByTagName('a');
	foreach ($links as $link){
		$v = trim($link->getAttribute('href'));
		if($v[0]!=='#')
		{
			if(strpos($v, "javascript:" )===false || (strpos($v, "(")===false && strpos($v, ")")===false))
			{
				if(strlen($v)>0)
				{
					if(isLocal($v))	{$int[] = $v;}
					else{$ext[] = $v;}
				}
			}
			else{
				$jsfunc[] = $v;
			}
		}
		else
		{
			$ids[] = $v;
		}
	}
	$res["internals"] = $int;
	$res["externals"] = $ext;
	$res["ids"] = $ids;
	$res["js"] = $jsfunc;
	return $res;
}

function addToResponse($arr){
	global $response;
	$response = array_merge($arr, $response);
}
function getCode($url){
	$ch = curl_init();
	$userAgent = 'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)'; //User Agent aleatório falso
	curl_setopt($ch, CURLOPT_USERAGENT, $userAgent);
	curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, 0); //Algumas páginas só utilizam GET, logo evito conflitos com servidor (400 bad request). 
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT , 5);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_ENCODING, "UTF-8");
    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    curl_setopt($ch, CURLOPT_MAXREDIRS, 15); 
	$server_output = curl_exec ($ch);
	if(curl_error($ch))
	{
		$r["error"] = curl_error($ch);
		addToResponse($r);
		return false;
	}

	curl_close ($ch);
	return $server_output;

}
function getServerHost($url){
	$tUrl = str_replace("http://", "", $url);
	$tUrl = str_replace("https://", "", $tUrl);
	$parts = explode("/", $tUrl);
	return trim($parts[0]);
}


?>