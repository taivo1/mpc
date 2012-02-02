"use strict"

var Ajax = {
    
    hash:{
	    
	    hashEvent: 0,	// holds status of hash. if value is greater then 0 then hash change operation is allready in progress
	    
	    changeHash: function(state){
		Ajax.hash.hashEvent = 1;
		document.location.hash = '#'+state;
		//$(window).trigger( 'hashchange' );
	    },

	    getHash: function(){

		var hash = window.location.hash;

		if(hash == '') return '/';
		else return hash.split('#')[1]; 
	    }
    },
    
    requests: {},   // requests history
    
    
    query: function(url, data, callback, method, datatype){
    
	method = method || "GET";
	datatype = datatype || "html";
	$.ajax({
		url: url,
		cache: false,
		type: method,
		data: data,
		dataType: datatype,
		success: callback,
		error: Ajax.error
	});
    },
    
    // ajax query error function
    error: function(XMLHttpRequest, textStatus, errorThrown){},
    
    getRequest: function(hash){
	var id = hash.split('#');
	id.shift();
	id=id.join('#');

	return Ajax.requests[id];
    },
    
    /**
     * Function removes baseUrl from full uri
     * @param string href. 
     */
    calcUrl: function(href){

	var href = href.split('/'),                 //we get element href and make an array of it     
	    baseurl = mainUrl.split('/'),	    // then we make an array of our BaseUrl
	    url = Base.filter(href,baseurl);        // we filter url against BaseUrl

	return '/' + url.join('/');                 //now we join filtered result
    },
    
    /**
     *  Makes a hash string from url
     */
    tabHash: function(href){
	var hash = href.split('/');
	hash.shift();

	return hash.join('-');
    }
    
}

