//<![CDATA[

var map;

//#########################################################
    function load_map(div_id,center)
//#########################################################
{
    if(!center)
        {
            center=new GLatLng(41.6823,-0.8866);
        }
	map = new GMap2(document.getElementById(div_id));
	map.addControl(new GMapTypeControl());
	map.addControl(new GLargeMapControl());
    map.addControl(new GScaleControl());
    map.addControl(new GOverviewMapControl());
    map.setCenter(center, 17);
	map.setMapType(G_HYBRID_MAP);
	map.enableScrollWheelZoom();
	map.hideControls();

    var marker = new GMarker(center, {draggable:false});
    map.addOverlay(marker);

	GEvent.addListener(map, "mouseover", function() {
		map.showControls();
	});
	GEvent.addListener(map, "mouseout", function(){
		map.hideControls();
	});
}

//#########################################################
    function draw_center(section,plugin,div_id)
//#########################################################
{
    submit_data="section="+section+"&plugin="+plugin+"&function=get_center";
    $('#'+div_id).html('<img src="plugins/'+section+'/'+plugin+'/images/progress.gif" alt="Progress..." title="Loading Map." />');
    $.ajax({
               type: "POST",
               url: "index.php",
               data: submit_data,
               success: function(datos){
                       // A JSON array is expected
                      var ret = eval('(' + datos + ')');
                      var x= 0;
                      var y= 0;
                      $.each(ret.item, function(i,item){
                          if (item['type']=="x_pos")
                          {
                              x=item['value'];
                          }
                          else if (item['type']=="y_pos")
                          {
                              y=item['value'];
                          }
                      });
                      //alert(x+" "+y);
                      center=new GLatLng(x,y);
                      load_map(div_id,center);
               }
            });

}

//#########################################################
    function show_nmea(section,plugin,div_id)
//#########################################################
{
    submit_data="section="+section+"&plugin="+plugin+"&function=show_nmea";
    $('#'+div_id).html('<img src="plugins/'+section+'/'+plugin+'/images/progress.gif" alt="Progress..." title="Adquiring Data." />');
    $.ajax({
               type: "POST",
               url: "index.php",
               data: submit_data,
               success: function(datos){
                       // A JSON array is expected
                      var ret = eval('(' + datos + ')');
                      $.each(ret.item, function(i,item){
                          if (item['type']=="nmea_data")
                          {
                            $('#'+div_id).html(item['value']);
                          }
                      });
               }
            });
}
//#########################################################
    function save_google_key(section,plugin,div_id)
//#########################################################
{
    submit_data="section="+section+"&plugin="+plugin+"&function=save_google_key&google_key="+$('#google_key').val();
    $('#'+div_id).html('<img src="plugins/'+section+'/'+plugin+'/images/progress.gif" alt="Progress..." title="Adquiring Data." />');
    $.ajax({
               type: "POST",
               url: "index.php",
               data: submit_data,
               success: function(datos){
                       // A JSON array is expected
                      var ret = eval('(' + datos + ')');
                      $.each(ret.item, function(i,item){
                          if (item['type']=="return")
                          {
                            // Uncomment for demo.
                            //$('#output').html(item['value']);
                            window.location.reload()
                          }
                      });
               }
            });
}

//]]>