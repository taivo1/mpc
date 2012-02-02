"use strict"

function Slider(length, start){
    
    this.start = start;
    this.length = parseInt(length);
    this.handle = $('#pos');
    this.slide = $('#slide');		
    		
    this.width = this.slide.width() - this.handle.width();
    
    this.step = this.width / this.length;
    this.position = this.start * this.step;
    
    this.animation = null;

}

Slider.prototype.Play = function(callback){
    var self = this;
    this.handle.css('left',this.position+'px');
    
    this.animation = setInterval( function(){
	
	if(self.position >= self.width){
	    clearInterval(self.animation);
	    if(callback instanceof Function) callback();
	}
	
	self.position = self.position + self.step;
	
	self.handle.css('left',self.position+'px');
    
    }, 995 );
    
         
}



Slider.prototype.Reset = function(){
    
    if(this.animation) clearInterval(this.animation);
    this.handle.css('left','0px');
}




Slider.prototype.Clear = function(){
    
    if(this.animation) clearInterval(this.animation);
}

Slider.prototype.calcTime = function(offset){
    var time = parseInt(offset / this.step);
    return time;
    
}
