<?php
/* ### Código feito por Thiago Arthur / Criado: 03/03/2017 16:56:32 ### */
require("functions.php");

//global vars
$server = null;


instanceFixData();
//final response
$response = array();
$response["success"] = 0;



if(isset($_POST['page']))
{
	global $server;
	$url = $_POST['page'];
	$server = getServerHost($url);
	$response["server"] = $server;
	$code = getCode($url);
	if($code!=false){
		$dom = new DOMDocument;
		@$dom->loadHTML($code);

		addToResponse(verifyHtmlVersion($code));
		addToResponse(getTitle($dom));
		addToResponse(getLinks($dom));
		addToResponse(getDeprecatedTags($dom));
		addToResponse(getNewerTags($dom));


		$response['uniqueInternals'] = array_unique($response["internals"]);
		$response['uniqueInternalsCount']	 = count($response['uniqueInternals']);
		$response['uniqueExternals']	 = array_unique($response["externals"]);
		$response['uniqueExternalsCount']	 = count($response['uniqueExternals']);
		$response['uniqueIds'] = array_unique($response["ids"]);
		$response['uniqueIdsCount']	 = count($response['uniqueIds']);
		$response['uniqueJs']	 = array_unique($response["js"]);
		$response['uniqueJsCount']	 = count($response['uniqueJs']);


		$response["internalsCount"] = count($response["internals"]);
		$response["externalsCount"] = count($response["externals"]);
		$response["jsCount"] = count($response["js"]);
		$response["idsCount"] = count($response["ids"]);

		$response["deprecatedCount"] = count($response["deprecated"]);
		$response["newerCount"] = count($response["newer"]);
		$response["message"] = "OK";
		$response["success"] = 1;
	}
	else{
		$response["message"] = "CURL Http Request Error";	
	}
}
else
{
	$response["message"] = "Please input an url";

}
$response["done"] = 1;
echo json_encode($response);
?>