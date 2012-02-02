
(function($) {
    $.fn.extend({
        colResize: function(options){
	    
	    var defaults = {
		childNodes: "div.resize",
		handlerClass: "resizeHandler",
		handlerSize: 20,
		cursor: 'move'
            };

            var options = $.extend(defaults, options),
		position = {
		    first: false,
		    last: false,
		    index: null
		};
	
	    return this.each(function(){
		
		var obj = $(this);
		    obj.css('position','relative');
		    
		var elements = obj.find(options.childNodes);
		
		elements.each(function(index, value){
		
			$(this).attr('id','colr-'+index);    
			var pos = $(this).outerWidth(true) + $(this).position(obj).left - (options.handlerSize / 2);
			
			
			
			var span = $(document.createElement('span'));
			    span.addClass(options.handlerClass);
			    span.attr('data-row','colr-'+index);
			    span.css('width',options.handlerSize+'px');
			    span.css('position','absolute');
			    //span.css('background','red');
			    span.css('margin','0');
			    span.css('padding','0');
			    span.css('height','16px');
			    span.css('left',pos+'px');
			    			 
			obj.append(span);

		})
		var handles = obj.find("span."+options.handlerClass);
		    handles.css('cursor',options.cursor);
		handles.mousedown(function(e) {
		    
		    e.preventDefault();
		    e.stopPropagation();
			$("#playlist").css('cursor',options.cursor);
		    var el = $(this),
			prevPos,
			nextPos;
			
			if(el.prev('span.'+options.handlerClass).length == 0){
			    position.first = true;
			    position.last = false;
			    prevPos = 0;
			}else{
			    position.first = false;
			    position.last = false;
			    prevPos = el.prev('span.'+options.handlerClass).position(obj).left + options.handlerSize / 2;
			}
			if(el.next('span.'+options.handlerClass).length == 0){
			    position.last = true;
			    position.first = false;
			    nextPos = obj.width() - 15;
			}else{
			    position.first = false;
			    position.last = false;
			    nextPos = el.next('span.'+options.handlerClass).position(obj).left - options.handlerSize / 2;
			}
			
			nextPos = nextPos - options.handlerSize;
			prevPos = prevPos + options.handlerSize;
		    
    		    

		    $(document).bind('mousemove', function(e){

			e.preventDefault();
			e.stopPropagation();
			
			var xPos = Math.ceil(e.clientX - obj.position().left);
			var rowId = el.attr('data-row');
			var rowIndex = $('#playlist li div').index($('#'+rowId));
			
			var start = $('#'+rowId).position(obj).left;
			var next = obj.find('div').eq(rowIndex + 1);
			

			
			
			if(xPos < nextPos && xPos > prevPos){
				
				el.css('left',xPos+'px');
				var firstW,secondW;
				
				if(position.last){
				    firstW = xPos - start,
				    secondW = next.outerWidth(true) - (firstW - $('#'+rowId).outerWidth(true)) - (options.handlerSize / 2) -11;
				
				}else{
				    firstW = xPos - start,
				    secondW = next.outerWidth(true) - (firstW - $('#'+rowId).outerWidth(true)) - (options.handlerSize / 2) -12;
				}
				
				$('#playlist li').each(function(){
				    
				   
					$(this).find('div').eq(rowIndex).css('width',firstW+'px');
					$(this).find('div').eq(rowIndex + 1).css('width',secondW+'px');					
				    
				})
				
			}
  
			
		    });
		});
		
		$(document).on('mouseup', function(e){
		    e.preventDefault();
		    e.stopPropagation();
		    $("#playlist").css('cursor','auto');
		    $(document).unbind('mousemove');
		    
		});
	    
	    });	    
        }
    });
})(jQuery);


