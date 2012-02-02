//<![CDATA[
var map;
var meshlium_id = new Array();
var meshlium = new Array();
var last_meshlium = 0;
var center_view_position;
var markers = new Array();
var lines = new Array();
var numLines = 0;


var mrkr_class = function(){
	myText='hi';
}
mrkr_class.prototype = {
	myCall : function() {
		xajax.call("marker_info",{parameters:[],context:this});
	},
	myReturn : function(value) {
		this.myText=value;
	}		
}


//#########################################################
    function load_map(div_id) {
//#########################################################
	last_meshlium=0;
	map = new GMap2(document.getElementById(div_id));
	map.addControl(new GMapTypeControl());
	map.addControl(new GLargeMapControl());
    map.addControl(new GScaleControl());
    map.addControl(new GOverviewMapControl()); 
    map.setCenter(new GLatLng(41.6823,-0.8866), 17);   
	map.setMapType(G_SATELLITE_TYPE);
	map.enableScrollWheelZoom();
	map.hideControls();
	
	GEvent.addListener(map, "mouseover", function() {
		map.showControls();
	});
	GEvent.addListener(map, "mouseout", function(){
		map.hideControls(); 
	});
}


//#########################################################
	function set_map_center(lat,lng,zoom_level){	
//#########################################################

	map.setCenter(new GLatLng(lat,lng), zoom_level);
	centroInicial = bounds.getCenter();
}


//#########################################################
	function center_view()
//#########################################################
{
	map.panTo(new GLatLng(centroInicial.lat(),centroInicial.lng()));
}


//#########################################################
	function clean_map(){	
//#########################################################
	
	var i;
	
	// 1st remove all overlays and clean lines struct
	map.clearOverlays();
	for (i=0;i<lines[numLines];i++)
	{
		//map.removeOverlay(lines[i]);
		lines[i]=null;
		lines[i]=undefined;
	}
	numLines=0;
	
	// 2� clean other structs
	for (i=0;i<last_meshlium;i++)
	{
		meshlium[i]=null;
		meshlium[i]=undefined;
		meshlium_id[i]=null;
		meshlium_id[i]=undefined;
		markers[i]=null;
		markers[i]=undefined;
	}
	last_meshlium=0;
	
	
	// It should be done.
	
}



//#########################################################
	function add_meshlium_to_map(lat,lng,text,type,unique_id){
//#########################################################
	var i=0;
	var done=false;
	
	for (i=0;i<last_meshlium;i++)
	{
		if (meshlium_id[i]==unique_id)
		{
			updateMarker(i,lat,lng);
			meshlium[i].lat=lat;
			meshlium[i].lng=lng;
			meshlium[i].pos=new GLatLng(lat,lng);
			updateConnections(i); 
			done=true;
		}
	}
	if (!done)
	{
		meshlium[last_meshlium]=nodoAdd(new GIcon(),lat,lng,text,type,last_meshlium,unique_id);
		meshlium_id[last_meshlium]=unique_id;
		last_meshlium++;
	}
}

//#########################################################
	function set_line(meshlium_1_unique_id,meshlium_2_unique_id){	
//#########################################################
	var i;
	var from='na';
	var to='na';
	var new_line=true;
	
	for (i=0;i<last_meshlium;i++)
	{
		if (meshlium_id[i]==meshlium_1_unique_id)
		{
			from=i;
		}
		if (meshlium_id[i]==meshlium_2_unique_id)
		{
			to=i;
		}
	}	
	for (i=0;i<meshlium[from].numBrothers;i++)
	{
		if (meshlium[from].brothers[i]==to)
		{			
			new_line=false;
		}		
	}
	
	if ((new_line)&&(from!='na')&&(to!='na'))	
	{
		addConnection(from, to);	
	}	
}


//#########################################################
    function nodoAdd(icon,lat,lng,text,type,id,title)
//#########################################################
    {
    	
    	switch (type)
    	{    	
    	case 'Meshlium':
    		icon.image = "images/wifi_50.png";
        	icon.shadow = "images/wifi_50.png";
        	icon.iconSize = new GSize(50, 50);
        	icon.shadowSize = new GSize(50, 50);
        	icon.iconAnchor = new GPoint(25, 25); //20 and 30 is to adjust the line in the center of the node
        	icon.infoWindowAnchor = new GPoint(31, 8);        	
    	break;    	
    	default:
    		icon.image = "images/iconos/GPL/editdelete.png";
        	icon.shadow = "images/iconos/GPL/editdelete.png";
        	icon.iconSize = new GSize(50, 50);
        	icon.shadowSize = new GSize(50, 50);
        	icon.iconAnchor = new GPoint(37-20, 59-30); //20 and 30 is to adjust the line in the center of the node
        	icon.infoWindowAnchor = new GPoint(31, 8);  
    	break;    	
    	}    
	//we add some new parametres to the node
	icon.lat = lat;
	icon.lng = lng;
	icon.pos = new GLatLng(lat,lng);
	icon.text = text;
	icon.id = id;
	icon.title = title;
	icon.numBrothers=0;
	icon.brothers = new Array();
	icon.lines = new Array();
	icon.type = type; //there are two kind: Meshlium and SquidBee
	markers[id]=addMarker(icon.pos,icon,text,title,id);
	map.addOverlay(markers[id]);
	return icon;
    } 

//#########################################################
    function addMarker(point, icon, text, title,id) {
//#########################################################
	
	//take attention the way the GMarkerProperties are defined (javascript object...)
      var ops = new Object();
      ops.draggable = true;
      ops.icon = new GIcon(icon);

      var marker = new GMarker(point, {icon:icon, draggable: true, title: title});
      GEvent.addListener(marker, "click", function() {
      	//marker.openInfoWindowHtml('<div id=info_marker_'+title+' style="color:red"></div>');
      	var prueba;
      	prueba='<div id=info_marker_'+title+'><table style="text-align: left; width: 610px; height:360px;" border="0"  cellpadding="0" cellspacing="0"><tbody><tr><td></td></tr></tbody></table></div>';
      	marker.openInfoWindowHtml(prueba);
      	//marker.showMapBlowup();
      	xajax_marker_info('info_marker_'+title,'0',id,meshlium_id[id]);        
      	});      
      GEvent.addListener(marker, "infowindowbeforeclose", function() {
      	xajax_marker_info(title,'1','');        
      	});	
      GEvent.addListener(marker, "dragend", function() {
        marker.getIcon().pos= marker.getPoint(); //we update the new position
		updateConnections(marker.getIcon().id);
		return(marker);
      	});
      GEvent.addListener(marker, "drag", function() {
        marker.getIcon().pos= marker.getPoint(); //we update the new position
		updateConnections(marker.getIcon().id);
		return(marker);
		});
      marker.enableDragging();
	  return marker;
    }
    
//#########################################################
	function updateMarker(id,lat,lng){
//#########################################################
		// This comments help to keep the infowindow displayed up of the marker but must be detected when has to be shown and when not.
		//markers[id].closeInfoWindow();
		markers[id].setLatLng(new GLatLng(lat,lng));
		//map.panTo(new GLatLng(lat,lng));
		//alert('here'+id+' '+lat+' '+lng);
		//markers[id].openInfoWindowHtml('<div id='+markers[id].title+' style="color:red"><br/><img src="images/hormiga.png" /></div>');
	}
    
//#########################################################
    function updateConnections(id0) {
//#########################################################

	var i;
	var bro;
	for(i=0;i<meshlium[id0].numBrothers;i++)
	{
		bro = meshlium[id0].brothers[i];
		lines[meshlium[id0].lines[i]].hide();
		lines[meshlium[id0].lines[i]]=null;
		lines[meshlium[id0].lines[i]]=undefined;
        lines[meshlium[id0].lines[i]]= new GPolyline([meshlium[id0].pos,meshlium[bro].pos],"#ff0000",5);
	    map.addOverlay(lines[meshlium[id0].lines[i]]);
	    lines[meshlium[id0].lines[i]].show();
	}

}
    
//#########################################################
    function clean_meshlium_brothers(unique_id) {
//#########################################################	
	var i;
	var bro;
	var it;
	for (it=0;it<last_meshlium;it++)
	{
		if (meshlium_id[it]==unique_id)
		{
			for(i=0;i<meshlium[it].numBrothers;i++)
			{						
				lines[meshlium[it].lines[i]].hide();
				lines[meshlium[it].lines[i]]=null;
				lines[meshlium[it].lines[i]]=undefined;
				meshlium[it].brothers[i]=null;
				meshlium[it].brothers[i]=undefined;			
			}
			meshlium[it].numBrothers=0;
		}
	}
}

//#########################################################
    function addConnection(id0, id1) {
//#########################################################

	meshlium[id0].brothers[meshlium[id0].numBrothers]=id1;
	meshlium[id0].lines[meshlium[id0].numBrothers]=numLines;
	meshlium[id0].numBrothers++;

	meshlium[id1].brothers[meshlium[id1].numBrothers]=id0;
	meshlium[id1].lines[meshlium[id1].numBrothers]=numLines;
	meshlium[id1].numBrothers++;

    lines[numLines] = new GPolyline([meshlium[id0].pos,meshlium[id1].pos],"#ff0000",5);
    map.addOverlay(lines[numLines]);
    lines[numLines].show();
	numLines++;
}
//]]>