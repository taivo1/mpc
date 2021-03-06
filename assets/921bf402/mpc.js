"use strict"

var Mpc = {
    
    slideId: "slide",
    handleId: "pos",
    slider: null,
    queryAction: 0,
    playlistType: 'playlist',
    idleStatus: false,
    status: null,
    volume: null,
     
    init: function(){
	
	$(document).delegate('a.disabled','click',function(e){e.preventDefault()});
	$(document).delegate('a.player:not(.disabled)','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.playerControl,this,e);
	});
	
	//playlist events
	$(document).delegate('#playlist li','dblclick',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.playPlaylistSong,this,e)
	});
	$(document).delegate('#playlist li:not(.playlist-head)','singleclick',function(e){
	    
	    Mpc.beforeHandlerAction(Mpc.handlers.selectPlaylistSong,this,e)
	});
	$(document).delegate('#playlist a.remove','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.removePlaylistSong,this,e)
	});
	$(document).delegate('#playlist-actions a.clear:not(.disabled)','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.clearPlaylist,this,e)
	});
	$(document).delegate('#playlist-actions a.save:not(.disabled)','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.savePlaylist,this,e)
	});
	$(document).delegate('#playlist-actions a.stream:not(.disabled)','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.showUrlInput,this,e)
	});
	$(document).delegate('#playlist-actions a.cancel:not(.disabled)','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.hideUrlInput,this,e)
	});
	$(document).delegate('#playlist-actions a.shuffle:not(.disabled)','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.shufflePlaylist,this,e)
	});
	$(document).delegate('#playlist-name, #stream-url','change',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.validatePLSave,this,e)
	});
	
	
	//browse events
	$(document).delegate('ul.folder>li.directory>a','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.queryDir,this,e)
	});
	$(document).delegate('ul.folder>li:not(.directory)>a','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.selectFolderItem,this,e)
	});
	$(document).delegate('ul.folder>li.file>a','dblclick',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.addItemToPlaylist,this,e)
	});
	$(document).delegate('ul.folder>li span.addfolder','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.addFolderToPlaylist,this,e)
	});
	$(document).delegate('ul.folder>li.playlist>a','dblclick',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.addPlToPlaylist,this,e)
	});
	$(document).delegate('ul.folder>li span.play, #songs>li span.play','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.addToPlaylistAndPlay,this,e)
	});
	$(document).delegate('ul.folder>li span.addnext, #songs>li span.addnext','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.addNextToPlaylist,this,e)
	});
	$(document).delegate('ul.folder>li.directory span.update','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.updateDatabase,this,e)
	});
	$(document).delegate('ul.folder>li.playlist span.delete','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.deletePlaylist,this,e)
	});
	
	
	//log
	$(document).delegate('#show-log','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.showLog,this,e)
	});
	$(document).delegate('#clear-log','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.clearLog,this,e)
	});
	
	//library
	$(document).delegate('#library li.artist>a','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.selectArtist,this,e)
	});
	$(document).delegate('#library li.album>a','click',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.selectAlbum,this,e)
	});
	$(document).delegate('#library select','change',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.filterByGenre,this,e)
	});
	
	
	//search
	
	
	$(document).delegate('#searchform','submit',function(e){
	    Mpc.beforeHandlerAction(Mpc.handlers.makeSearch,this,e)
	});
	
	
	$( "#"+Mpc.handleId ).draggable({
	    containment: "parent",
	    axis: "x",
	    snapTolerance: 100,
	    start: function(event, ui){
		if(Mpc.slider instanceof Object) Mpc.slider.Clear();
	    },
	    stop: function(event, ui){
		Mpc.seek(ui.position.left);
	    }
	});
	$(document).delegate('#'+Mpc.slideId,'click',function(e){Mpc.handlers.playerSlideClick(this,e)});
	
	// we should update satatus when initializing Mpc obj
	Mpc.getStatus();
	

	
    },
    
    beforeHandlerAction: function(callback,el,e){
//	console.log('before action we stop idle');
//	if(Mpc.idleStatus){
//	    Ajax.query(mainUrl + '/mpc/player/idle', 'action=false', function(data){
//		console.log('noidle-resp');
//		console.log(data);
//		Mpc.idleStatus = false;
//		if(callback instanceof Function) callback(el,e);
//	    },"POST","json");
//	}else{
	    
	    if(callback instanceof Function) callback(el,e);
	//}
    },
        
    
    
    Idle: function(action){
	
//	action = Boolean(action);
//	if(action && Mpc.idleStatus == false){
//	    console.log('idle-sent');
//	    Ajax.query(mainUrl + '/mpc/player/idle', 'action=true', function(data){
//
//		console.log('idle-resp');
//		console.log(data);
//		Mpc.idleStatus = true;
//
//	    },"POST","json");
//	}else if(!action && Mpc.idleStatus == true){
//	    console.log('noidle-sent');
//	    Ajax.query(mainUrl + '/mpc/player/idle', 'action=false', function(data){
//
//		console.log('noidle-resp');
//		console.log(data);
//		Mpc.idleStatus = false;
//	    },"POST","json");
//	}
	
    },
    
    
    
    disablePlayer: function(){
	if(!$('a.player').hasClass('disabled')) $('a.player').addClass('disabled');
    },
    enablePlayer: function(){
	if($('a.player').hasClass('disabled')) $('a.player').removeClass('disabled');
    },
    
    initVolumeBar: function(vol){
	
	$( "#volbar" ).slider({
			value: parseInt(vol),
			min: 0,
			max: 100,
			step: 1,
			range: "min",
			slide: function( event, ui ){
			    $("#vol-value").text(ui.value)
			},
			stop: function( event, ui ){
			    Mpc.changeVolume(ui.value);
			}
		});
	
	
    },
    
    initPlaylistSorting: function(){
	$('#playlist').sortable({
	    placeholder: "ui-state-highlight",
	    delay: 300,
	    items: 'li.sortable',
	    cursor: 'crosshair',
	    containment: 'div.container',
	    start: function(event, ui){
		$('#playlist>li').removeClass('selected');
		ui.item.addClass('selected');
		    ui.item.height('22');
		    ui.item.find('div.track-details').hide();    
	    },
	    stop: function(event, ui){
		var position = $('#playlist>li').index(ui.item);
		Mpc.movePlaylistTrack(ui.item,position);
	    }
	});
	$("#playlist").disableSelection();
	$("#playlist>li.playlist-head").colResize({
	    
	});
	
    },
    
    movePlaylistTrack: function(element,position){
	var trackId = element.attr('id').split('-')[1];
	    
	    element.css('border-bottom','1px solid #ddd');
	    Base.CreateLoader(element);
	Ajax.query(mainUrl + '/mpc/playlist/move', 'trackid='+trackId+'&position='+position+'&idle='+Mpc.idleStatus, function(data){			
		$('#content').html(data);
		Mpc.initPlaylistSorting();
	},"POST");
    },
    
    updatePlaylist: function(){
	Ajax.query(mainUrl + '/mpc/playlist', 'idle='+Mpc.idleStatus, function(data){
	    $('#content').html(data);
	    Mpc.initPlaylistSorting();
	},"GET");
    },
    updateCurTrack: function(current){
	if(current && current instanceof Object){
	    var curElement = $('#playlist').find('li#song-'+current.Id);
	    if(!curElement.hasClass('stream-item')){
		$('#playlist li')
			    .removeClass('current')
			    .find('a.remove').show();


		curElement.addClass('current').trigger('singleclick').find('a.remove').hide();
	    }else{
		Mpc.updatePlaylist();
	    }
	}else{
	    $('#playlist li')
			.removeClass('current')
			.find('a.remove').show();
	}
    },  
    play: function(data){
	console.log(data);
	
	if(data.status.playlistlength != "0"){
	    Mpc.enablePlayer();
	    $('a.player').removeClass('active');
	    $('#play').addClass('active');
	    var html = '';
	    html += '<h3>';
		if(data.current.hasOwnProperty('Title') && data.current.hasOwnProperty('Artist')){
		    html += data.current.Artist+' - '+data.current.Title;
		}else if(data.current.hasOwnProperty('Title')){
		    html += data.current.Title;
		}else{
		    html += data.current.file;
	      }

	      if(data.current.hasOwnProperty('Time')){
		    // we clear last slider animation before making a new instance
		    if(Mpc.slider && Mpc.slider.Clear instanceof Function) Mpc.slider.Clear();
		    //and now we create new instance
		    Mpc.slider = new Slider(data.current.Time, data.status.elapsed);
		    Mpc.slider.Play(Mpc.getStatus);
	      }
	    html += '</h3>';
	    //we update player songinfo
	    $('#cursong').html(html);
	    //and we update current playlist track
	    Mpc.updateCurTrack(data.current);
	    Mpc.Idle(true);
	}else{
	    Mpc.disablePlayer();
	}
    
    },
    stop: function(data){
	if(data) Mpc.updateCurTrack(data.current);
	$('a.player').removeClass('active');
	if(Mpc.slider instanceof Object) Mpc.slider.Reset();
	$('#cursong').html("");
	Mpc.Idle(true);
    },
    pause: function(data){
	$('a.player').removeClass('active');
	$('#pause').addClass('active');
	if(Mpc.slider instanceof Object) Mpc.slider.Clear();
	Mpc.Idle(true);
    },
    seek: function(position){
	if(Mpc.slider instanceof Object){
	    var time = Mpc.slider.calcTime(position);
	    Ajax.query(mainUrl + 'mpc/player/seek', 'pos='+time, Mpc.play,"POST",'json');
	}
    },
    changeVolume: function(vol){
	var volume = parseInt(vol);
	Ajax.query(mainUrl + 'mpc/player/changevolume', 'vol='+volume, function(data){
	    console.log(data);
	},"POST",'json');	    
    },
    
    getStatus: function(){

	Ajax.query(mainUrl + '/mpc/player/status', 'idle='+Mpc.idleStatus, function(data){

	    var callback = function(data){};
	    Mpc.status = data.status;
	    switch(data.status.state){
		case "play":
		    callback = Mpc.play;
		    break;
		default:
		    callback = Mpc.stop;
	    }
	    if(data.status.playlistlength == "0"){
		Mpc.disablePlayer();
	    }else{
		Mpc.enablePlayer();
	    }
	    Mpc.initVolumeBar(data.status.volume);
	    callback(data);
	},"POST",'json');
    },

    
    
    handlers: {
		
		
		
		playerSlideClick: function(el,e){
		    e.preventDefault();
		    
		    if(e.target.id == Mpc.slideId && Mpc.slider instanceof Object){
			
			var x = e.offsetX - (Mpc.slider.handle.width() / 2);
			    if(x < 0) x = 0;
			    if(x > Mpc.slider.width) x = Mpc.slider.width;
			    
			    Mpc.seek(x);
			
			return false;
		    }
		},
		
		playerControl: function(el,e){
		    e.preventDefault();
		    var url = $(el).attr('href');
		    var id = $(el).attr('id');
		    var callback = function(data){};
		    switch(id){
			case 'stop':
			    callback = Mpc.stop;
			    break;
			case 'pause':
			    callback = Mpc.pause;
			    break;
			default:
			    callback = Mpc.play;
		    }		   
		    Ajax.query(url, null, callback,"POST",'json');
		    return false;
		},
		
		
		playPlaylistSong: function(el,e){
		    e.stopPropagation();
		    var songid = $(el).attr('id').split('-')[1];
		    Ajax.query(mainUrl + '/mpc/player/play', 'id='+songid, Mpc.play,"POST",'json');
		},	
		selectPlaylistSong: function(el,e){
		    
		    if(!$(el).hasClass('selected')){
			
			$('#playlist li').removeClass('selected');
			$(el).addClass('selected');
			$('#playlist li>div.track-details').hide(200);
			$(el).find('div.track-details').show(200);
		    }
		},
		removePlaylistSong: function(el,e){
		    e.preventDefault();
		    if(IE.version() <= 8){
			    e.cancelBubble = true;			    
		    }else{    
			    e.stopPropagation();
		    }
		    var item = $(el).closest('li'),
			songid = item.attr('id').split('-')[1];
		    
		    if(!item.hasClass('current')){		    
			$(el).html('<img src="'+mainUrl+'/images/ajax-loader.gif" />');
			Ajax.query(mainUrl + '/mpc/playlist/remove', 'songid='+songid, function(data){

			    $('#content').html(data);
			    Mpc.initPlaylistSorting();
			},"POST");
		    }
		},
		clearPlaylist: function(el,e){
		    e.preventDefault();
		    Base.ShowPageLoader();
		    Ajax.query(mainUrl + '/mpc/playlist/clear', null, function(data){
			Mpc.stop();
			Mpc.disablePlayer();
			$('#content').html(data);
		    },"POST");
		},
		showUrlInput: function(el,e){
		    e.preventDefault();
		    $('a.stream, #save-playlist span.or').fadeOut(200, function(){
			$('#stream-url, #save-playlist a.cancel').fadeIn(200);
		    });		    
		    Mpc.playlistType = 'stream';
		   
		},
		hideUrlInput: function(el,e){
		    e.preventDefault();
		    $('#stream-url, #save-playlist a.cancel').fadeOut(200,function(){
			$('#save-playlist span.or, a.stream').fadeIn(200);
		    });
		    Mpc.playlistType = 'playlist';
		    
		    
		},
		validatePLSave: function(el,e){
		    if($(el).val() == "")
			$(el).removeClass('error').addClass('error');
		    else
			$(el).removeClass('error');
		},
		savePlaylist: function(el,e){
		    e.preventDefault();
		    var name = $('#playlist-name').val();
		    if(Mpc.playlistType == 'playlist'){
		  			
			if(name){
			    $('#playlist-name').removeClass('error');
			    $('div.spinner').show();
			    Ajax.query(mainUrl + '/mpc/playlist/save', 'name='+name, function(data){
				console.log(data);
				$('div.spinner').hide();
			    },"POST");
			}else{
			    $('#playlist-name').addClass('error');
			}
		    }else if(Mpc.playlistType == 'stream'){
			var url =  $('#stream-url').val();
			
			if(name && url){
			    $('#playlist-name, #stream-url').removeClass('error');
			    $('div.spinner').show();
			    Ajax.query(mainUrl + '/mpc/playlist/save', 'name='+name+'&url='+url, function(data){
				console.log(data);
				$('div.spinner').hide();
			    },"POST");
			}else{
			    if(!name) $('#playlist-name').addClass('error');
			    if(!url) $('#stream-url').addClass('error');
			}
			
		    }
		},
		shufflePlaylist: function(el,e){
		    e.preventDefault();
		    Base.ShowPageLoader();
		    Ajax.query(mainUrl + '/mpc/playlist/shuffle', null, function(data){			
			$('#content').html(data);
			Mpc.initPlaylistSorting();
		    },"POST");
		},
		queryDir: function(el,e){
		    e.preventDefault();
		    e.stopImmediatePropagation();
		    var row = $(el).closest('li'),
			uri = $(el).attr('href'),
			child = row.find('ul.folder').first();
			
		    if(Mpc.queryAction != 1 && row.hasClass('closed') && child.length == 0){
			Mpc.queryAction = 1;
			Ajax.query(mainUrl + '/mpc/browse', 'uri='+uri, function(data){
			    
			    var d = $(data);
			    console.log(d);
			    d.css('display','none');
			    row.append(d).removeClass('closed').addClass('open').find('span.update').first().fadeIn(200);
			    row.find('ul.folder').show(200);
			    Base.pageData['browse'] = $('#content').find('ul.folder').first();
			    Mpc.queryAction = 0;
			    
			},"POST");
		    }else if(row.hasClass('closed') && child.length > 0){			
			child.show(200, function(){
			    
			    row.removeClass('closed').addClass('open').find('span.update').first().fadeIn(200);
			    Base.pageData['browse'] = $('#content').find('ul.folder').first();
			    
			});
		    }else if(row.hasClass('open') && child.length > 0){
			child.hide(200,function(){
			    
			    row.removeClass('open').addClass('closed').find('span.update').first().fadeOut(200);
			    Base.pageData['browse'] = $('#content').find('ul.folder').first();
			});
			
		    }
		    return false;
		},
		selectFolderItem: function(el,e){
		    e.preventDefault();
		    $('ul.folder li:not(.directory)').removeClass('selected');
		    $(el).closest('li').addClass('selected');
		    if(Base.page == 'browse'){
			Base.pageData[Base.page] = $('#content').find('ul.folder').first();
		    }else if(Base.page == 'search'){
			Base.pageData[Base.page] = $('#search');
		    }
		},
		addItemToPlaylist: function(el,e){
		    e.preventDefault();
		    var uri = $(el).attr('href');
		    Ajax.query(mainUrl + '/mpc/browse/addtoplaylist', 'uri='+uri, function(data){
			
		    },"POST");
		},
		addFolderToPlaylist: function(el,e){
		    e.stopPropagation();
		    e.preventDefault();
		    var uri = $(el).closest('a').attr('href');
		    Ajax.query(mainUrl + '/mpc/browse/addtoplaylist', 'uri='+uri, function(data){
			
		    },"POST");
		},
		addPlToPlaylist: function(el,e){
		    e.preventDefault();
		    var uri = $(el).attr('href');
		    Ajax.query(mainUrl + '/mpc/browse/addpltoplaylist', 'uri='+uri, function(data){
		
		    },"POST");
		},
		addToPlaylistAndPlay: function(el,e){
		    e.preventDefault();
		    var uri = $(el).closest('a').attr('href'),
			type = $(el).closest('li').attr('class');
		    Ajax.query(mainUrl + '/mpc/browse/play', 'uri='+uri+'&type='+type, Mpc.play,"POST","json");
		},
		addNextToPlaylist: function(el,e){
		    e.preventDefault();
		    var uri = $(el).closest('a').attr('href'),
			type = $(el).closest('li').attr('class');
		    Ajax.query(mainUrl + '/mpc/browse/addnext', 'uri='+uri+'&type='+type, function(data){
			Mpc.enablePlayer();
		    },"POST","json");
		},
		deletePlaylist: function(el,e){
		    e.preventDefault();
		    var uri = $(el).closest('a').attr('href');
		    $(el).html('<img src="'+mainUrl+'/images/ajax-loader.gif" />');
		    Ajax.query(mainUrl + '/mpc/browse/deleteplaylist', 'uri='+uri, function(data){
			$(el).closest('li').hide(200).remove();
		    },"POST","json");
		    
		},
		updateDatabase: function(el,e){
		    e.stopPropagation();
		    e.preventDefault();
		    var uri = $(el).closest('a').attr('href'),
			row = $(el).closest('li');
		    $(el).html('<img src="'+mainUrl+'/images/ajax-loader.gif" />');
		    Ajax.query(mainUrl + '/mpc/browse/update', 'uri='+uri, function(data){
			Ajax.query(mainUrl + '/mpc/browse', 'uri='+uri, function(data){
			    var d = $(data);
			    d.css('display','none');
			    row.find('ul.folder').first().hide(200).remove();
			    row.append(d).removeClass('open').removeClass('closed').addClass('open').find('span.update').first().fadeIn(200);
			    row.find('ul.folder').show(200);
			    Base.pageData['browse'] = $('#content').find('ul.folder').first();
			    Mpc.queryAction = 0;
			    
			},"POST");
			
			$(el).html('');
			
			
			
		    },"POST");
		},
		
		
		//log
		showLog: function(el,e){
		    e.preventDefault();
		    Ajax.query(mainUrl + '/mpc/log', null, function(data){
			$('#log').html(data);
		    });
		},
		clearLog: function(el,e){
		    e.preventDefault();
		    Ajax.query(mainUrl + '/mpc/log/clear', null, function(data){
			$('#log').html(data);
		    });
		},
		
		
		//library
		
		selectArtist: function(el,e){
		    e.preventDefault();
		    $('#artists>li.artist').removeClass('selected');
		    $(el).closest('li').addClass('selected');
		    var artist = encodeURI($(el).text());
		    
		    Ajax.query(mainUrl + '/mpc/library/albums', 'artist='+artist, function(data){
			
			$('#library>div.col-center').html(data);
			Base.pageData['library'] = $('#library');
		    },"POST");
		},
		selectAlbum: function(el,e){
		    e.preventDefault();
		    $('#albums>li.album').removeClass('selected');
		    $(el).closest('li').addClass('selected');
		    var album = encodeURI($(el).text());
		    
		    Ajax.query(mainUrl + '/mpc/library/find', 'album='+album, function(data){
			
			$('#library>div.col-right').html(data);
			Base.pageData['library'] = $('#library');
		    },"POST");
		},
		filterByGenre: function(el,e){
		    e.preventDefault();
		    var genre = $(el).val();
		    
		    Ajax.query(mainUrl + '/mpc/library/artists', 'genre='+genre, function(data){
			
			$('#library>div.col-left').html(data);
			Base.pageData['library'] = $('#library');
		    },"POST");
		},
		
		
		//search
		
		makeSearch: function(el,e){
		    e.preventDefault();
		    var formData = $(el).serialize();
		    Base.CreateLoader($('#search-results'));
		    Ajax.query(mainUrl + '/mpc/search/find', formData, function(data){
			
			$('#search-results').html(data);
			Base.pageData['search'] = $('#search');
			$(window).scrollTop(0);
		    },"POST");
		    return false;
		}
		
    }
    
    
}


	
   
    
