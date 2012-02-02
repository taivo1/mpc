"use strict"

var Base = {
    
    page: 'playlist',
    pageData: {},
    /**
     * Function creates specified size 
     * div element with spinner in the center
     * @return string - html
     */
    CreateLoader: function(el){

	//if(isNaN(width) || isNaN(height)) return false;
	
	var element = $(el),
	    width = element.width(),
	    height = element.height(),
	    spinnerHeight = 16,
	    marginTop = (height / 2) - (spinnerHeight / 2),
	    html = '<div class="loader-container" style="width:'+width+'px; height:'+height+'px; text-align: center; border: 0; margin: 0; padding: 0;" >'+
			'<div class="spinner-container" style="margin-top: '+marginTop+'px;">'+
			    '<div class="spinner" style="display: block; margin:0;"><img src="'+ mainUrl +'/images/ajax-loader.gif"/></div>'+
			'</div>'+
		     '</div>';
	    element.addClass('loading');	     
	    element.html(html);
	    
    },
    
    ShowPageLoader: function(){

	//if(isNaN(width) || isNaN(height)) return false;
	var wHeight = $(window).height(),
	    scrollTop = $(window).scrollTop(),
	    element = $('#content'),
	    width = element.width(),
	    height = element.height(),
	    spinnerHeight = 16,
	    marginTop = scrollTop + ((wHeight / 2) - 250 - (spinnerHeight / 2)),
	    html = '<div class="loader-container" style="width:'+width+'px; height:'+height+'px; text-align: center;" >'+
			'<div class="spinner-container" style="margin-top: '+marginTop+'px;">'+
			    '<div class="spinner" style="display: block; margin:0;"><img src="'+ mainUrl +'/images/ajax-loader.gif"/></div>'+
			'</div>'+
		     '</div>';   
	    element.html(html);

    },
    
    // initializes Base object
    init: function(){
	
	
	
	$(document).delegate('a.ajax','click',function(e){Base.handlers.ajaxRequest(this,e)});
	$(window).bind('hashchange', function(e){Base.handlers.onHashChange(this,e)});

    },
    
    
    /**
     * event handler functions obj
     */
    handlers: {
		
		ajaxRequest: function(el,e){
		    e.preventDefault();
		    var url = $(el).attr('href'),
			target;
		    
		    Base.page = $(el).closest('li').attr('id').split('-')[2];
		    $('#mainmenu li').removeClass('active');
		    $(el).closest('li').addClass('active');
			
			
		    Ajax.hash.changeHash(url);		    
		    Base.ShowPageLoader();
		    if(Base.page == 'browse' && Base.pageData.browse instanceof Object){
			$('#content').html(Base.pageData.browse);
			target = $('#content').find('li.selected');
			$(window).scrollTop(target.offset().top - 175);
		    }else if(Base.page == 'library' && Base.pageData.library instanceof Object){
			$('#content').html(Base.pageData.library);
			$(window).scrollTop(0);
		    }else if(Base.page == 'search' && Base.pageData.search instanceof Object){
			$('#content').html(Base.pageData.search);
			target = $('#content').find('li.selected');
			$(window).scrollTop(target.offset().top - 248);
		    }else{
			Ajax.query(url, null, function(data){
			    $('#content').html(data);
			    if(Base.page == 'playlist'){
				Mpc.initPlaylistSorting();
			    }
			    target = $('#content').find('li.current');
			    if(target.length > 0){
				$(window).scrollTop(target.offset().top - 175);
			    }
			    
			});
		    }
		    return false;
		    
		},
		
		/**
		 * Bind an event to window.onhashchange that, when the history state changes,
		 * gets the url from the hash and displays either our cached content or fetches
		 * new content to be displayed. 
		 */
		onHashChange: function(el,e){

		    var fragment = Ajax.hash.getHash();
		    var url = mainUrl + fragment;
		    
		    var elementFound = false;
		    if(!fragment || !url) return false;

		    //in case we have <a> tag in our document
		    $("a").each(function(){
			 var href = $(this).attr( "href" );

			 if ( href ===  url ) {
			     
			    if(Ajax.hash.hashEvent==0) $(this).trigger('click');
			    else Ajax.hash.hashEvent=0;

			    elementFound = true; 
			 }
		    });

//		    //if we don't find <a> element we check allowed urls object'
//		    if(!elementFound){
//
//			for(var key in allowedUrls){
//
//			    var filter = Base.filter(url.split('/'),key.split('/'),true);
//			    var filteredUrl = filter.join('/');
//
//			    if(key == filteredUrl){
//
//				var pageData = allowedUrls[key];
//				var a = document.createElement('a');
//
//				a.href = url;
//				a.appendChild(document.createTextNode(pageData.label));
//				Base.addTab($(a));
//
//				//for a page redirect to mainurl we should remove hash part
//				if(pageData.removehash == true) window.location.hash = "";
//			    }
//			}
//		    }
		}
		

	
    }
    
    
    
}

var IE = {
  version: function() {
    var version = 999; // we assume a sane browser
    if (navigator.appVersion.indexOf("MSIE") != -1)
      // bah, IE again, lets downgrade version number
      version = parseFloat(navigator.appVersion.split("MSIE")[1]);
    return version;
  }
}

