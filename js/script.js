/* ### Código feito por Thiago Arthur / Criado: 03/03/2017 20:10:20 ### */
jQuery(document).ready(function(){
	jQuery.noConflict();
	jQuery("#loaded").hide();
	jQuery("#butt").click(function(e){
		analyze();
	});
	jQuery("#loading").hide();
	jQuery("#loaded").hide();
});
var analyze = function(){
	var url = jQuery("#urlForm").val();
	jQuery("#loaded").hide();
	jQuery("#loading").fadeIn(750);
	if (validURL(url)){
		
		resetContent();	
		sendURLRequest(url);
	}
	else
	{
		jQuery(".status").html("Error: Invalid URL");	
		jQuery("#loading").hide();
	}

	
}
var sendURLRequest  = function(url){
	jQuery.post("analyzerTool.php", {page:url}, function( data ) {
		feedContent(JSON.parse(data));	
		
	});
}
var validURL = function(str) {
	var pattern = new RegExp( /^(http[s]?:\/\/){0,1}(www\.){0,1}[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,5}[\.]{0,1}/);
	return pattern.test(str);
}
var resetContent = function(){
	jQuery("#mInternals .modal-dialog .modal-body .cTable").html("");
	jQuery("#mExternals .modal-dialog .modal-body .cTable").html("");
	jQuery("#mDepEls .modal-dialog .modal-body .cTable").html("");
	jQuery("#mNewEls .modal-dialog .modal-body .cTable").html("");
	jQuery("#mIds .modal-dialog .modal-body .cTable").html("");
	jQuery("#mJs .modal-dialog .modal-body .cTable").html("");
	jQuery(".status").html("");

}
var feedContent = function(response){
	if(Number(response.success)==1){
		if(response.version>0) {jQuery("#htmlVersion").html(response.version); } else { jQuery("#htmlVersion").html("Doctype not found.");}

		jQuery("#pageTitle").html(response.title);
		jQuery("#depEls").html(response.deprecatedCount);
		jQuery("#newEls").html(response.newerCount);

		jQuery("#intCount").html(response.internalsCount);
		jQuery("#extCount").html(response.externalsCount);
		jQuery("#jsCount").html(response.jsCount);
		jQuery("#idsCount").html(response.idsCount);

		jQuery("#intCountU").html(response.uniqueInternalsCount);
		jQuery("#extCountU").html(response.uniqueExternalsCount);
		jQuery("#jsCountU").html(response.uniqueJsCount);
		jQuery("#idsCountU").html(response.uniqueIdsCount);

		var c = 0;
		if(response.deprecatedCount>0){for(j in response.deprecated){jQuery("#mDepEls .modal-dialog .modal-body .cTable").append("<tr><td> <span class=\"badge\">" + (Number(c)+1) + "</span></td><td>" +  response.deprecated[j].replace("<", "&lt;").replace(">", "&gt;") + "</td></tr>");c++;}}
		else{ jQuery("#mDepEls .modal-dialog .modal-body .cTable").html("<tr><td> No records </td></tr>");}
		c=0;
		if(response.newerCount>0){for(j in response.newer){jQuery("#mNewEls .modal-dialog .modal-body .cTable").append("<tr><td> <span class=\"badge\">" + (Number(c)+1) + "</span></td><td>" +  response.newer[j].replace("<", "&lt;").replace(">", "&gt;") + "</td></tr>");c++;}}
		else{jQuery("#mNewEls .modal-dialog .modal-body .cTable").html("<tr><td> No records </td></tr>");}
		c=0;
		if(response.internalsCount>0){for(j in response.uniqueInternals){jQuery("#mInternals .modal-dialog .modal-body .cTable").append("<tr><td> <span class=\"badge\">" + (Number(c)+1) + "</span></td><td>" +  response.uniqueInternals[j] + "</td></tr>");c++;}}
		else{jQuery("#mInternals .modal-dialog .modal-body .cTable").html("<tr><td> No records </td></tr>");}
		c=0;
		if(response.externalsCount>0){for(j in response.uniqueExternals){jQuery("#mExternals .modal-dialog .modal-body .cTable").append("<tr><td> <span class=\"badge\">" + (Number(c)+1) + "</span></td><td>"  +  response.uniqueExternals[j] + "</td></tr>");c++;}}
		else{jQuery("#mExternals .modal-dialog .modal-body .cTable").html("<tr><td> No records </td></tr>");}
		c=0;
		if(response.idsCount>0){for(j in response.uniqueIds){jQuery("#mIds .modal-dialog .modal-body .cTable").append("<tr><td> <span class=\"badge\">" + (Number(c)+1) + "</span></td><td>" +  response.uniqueIds[j] + "</td></tr>");c++;}}
		else{jQuery("#mIds .modal-dialog .modal-body .cTable").html("<tr><td> No records </td></tr>");}
		c=0;
		if(response.jsCount>0){for(j in response.uniqueJs){jQuery("#mJs .modal-dialog .modal-body .cTable").append("<tr><td> <span class=\"badge\">" + (Number(c)+1) + "</span></td><td>" +  response.uniqueJs[j] + "</td></tr>");c++;}}
		else{jQuery("#mJs .modal-dialog .modal-body .cTable").html("<tr><td> No records </td></tr>");}
		jQuery("#loading").fadeOut(750, function(){
			jQuery("#loaded").fadeIn(750);
		});
	} 
	else 
	{ 
		jQuery(".status").html("Error: " + response.error);
		jQuery("#loading").fadeOut(750);
	}

}