jQuery(function($){

	if($.browser.msie && $.browser.version == "8.0") {
		ie8 = true;
	} else if($.browser.msie && $.browser.version == "7.0") {
		$('body').addClass('ie7');
	}

	$(".irw-widget").each(function(i, e){
		irw_init($(e));
	});

});

var ie8 = false;

var image_set_width;
var image_set_height = 0;
var image_set_position;
var image_set;

function irw_init(element) {
	var widget = element
	var slider = widget.children(".irw-slider");
	var parent = widget.parent();
	var transition = widget.children(".irw-transition").val();
	widget.css({ 'position': 'relative', 'z-index': '1' });
	slider.css({ 'z-index': '2' }).find("li img").css('max-width', 'none');
	if(parent.width() > parent.height()) {
		var n = parent.width();
	} else {
		var n = parent.height();
	}
	widget.addClass('loading').css({
		width: n
	}).children('.irw-slider').css({ visibility: 'hidden' });

	switch(transition) {
		case "linear" :
			slider.imagesLoaded(function(img){
				irw_load_linear(img, widget, slider);
			});
			break;
		case "loop" :
			//slider.html(slider.html() + slider.html());
			slider.imagesLoaded(function(img){
				irw_load_loop(img, widget, slider);
			});
			break;
		case "fade" :
			slider.imagesLoaded(function(img){
				irw_load_fade(img, widget, slider);
			});
			break;
		default :
			alert("This isn't the transition you're looking for.");
			break;
	}
}


///////////////////////////////
//		Fade Animation
//////////////////////////////

function irw_load_fade(img, widget, slider) {
	var width_array = new Array();
	var height_array = new Array();
	img.each(function(i){
		width_array[i] = jQuery(this).width();
		height_array[i] = jQuery(this).height();
		if(height_array[i] > image_set_height) {
			image_set_height = height_array[i];
		}
	});
	widget.height(image_set_height + "px");

	slider.find("li:first-child").addClass("active");
	slider.find("li:not(.active)").css({
		position: "relative",
		top: "0px",
		left: "0px",
		display: "none"
	});
	widget.removeClass('loading').children('.irw-slider').css({ visibility: 'visible', margin: "0px" });
	setTimeout(function(){
		irw_fade(img, widget, slider);
	}, 2000);
}

function irw_fade(img, widget, slider) {
	var active = slider.children(".active");
	if(active.is(slider.find("li:last-child"))) {
		var next = slider.find("li:first-child");
	} else {
		var next = active.next();
	}

	active.fadeOut(1000, function() {
		active.removeClass("active");
		next.addClass("active").fadeIn(1000, function(){
			setTimeout(function(){
				irw_fade(img, widget, slider);
			}, 2000);	
		});
	});
}


///////////////////////////////
//		Linear Animation
//////////////////////////////

function irw_load_linear(img, widget, slider) {
	var width_array = new Array();
	var height_array = new Array();
	img.each(function(i){
		width_array[i] = jQuery(this).width();
		height_array[i] = jQuery(this).height();
		if(height_array[i] > image_set_height) {
			image_set_height = height_array[i];
		}
	});
	widget.height(image_set_height + "px");
	var slider_width = 0;
	for(i=0;i < width_array.length;i++) {
		slider_width += width_array[i];
	}
	slider_width += 20 * width_array.length;
	slider.children('li').css({ 'margin-right': '20px', 'max-width': 'none' });
	widget.removeClass('loading').children('.irw-slider').css({ visibility: 'visible', width: slider_width + "px", margin: "0px", position: 'relative'  });

	var w = slider_width - widget.width();
	var duration = slider_width * 20;

	irw_linear(w, slider, duration);
}

function irw_linear(width, slider, duration) {
	slider.animate({
		left: "-" + width + "px"
	}, duration, 'linear', function(){
		irw_linear_reverse(width, slider, duration);
	});
}

function irw_linear_reverse(width, slider, duration) {
	slider.animate({
		left: "0px"
	}, duration, 'linear', function(){
		irw_linear(width, slider, duration);
	});
}


///////////////////////////////
//		Loop Animation
//////////////////////////////

function irw_load_loop(img, widget, slider) {
	var width_array = new Array();
	var height_array = new Array();
	img.each(function(i){
		width_array[i] = jQuery(this).width();
		height_array[i] = jQuery(this).height();
		if(height_array[i] > image_set_height) {
			image_set_height = height_array[i];
		}
	});
	widget.height(image_set_height + "px");
	var slider_width = 0;
	for(i=0;i < width_array.length;i++) {
		slider_width += width_array[i];
	}
	slider_width += 20 * width_array.length;
	image_set_width = slider_width;
	slider.children('li').css('margin-right', '20px');
	widget.removeClass('loading').children('.irw-slider').css({ visibility: 'visible', width: slider_width + "px", margin: "0px", position: 'relative'  });

	var w = slider_width - widget.width();
	var first_duration = w * 30;
	var duration = slider_width * 30;

	slider.animate({
		left: "-" + w + "px"
	}, first_duration, 'linear', function(){
		image_set = slider.html();
		if(!ie8) {
			slider.append(image_set).width(slider.width() + image_set_width + "px");
		} else {
			slider.append(image_set).width(slider.width() + image_set_width + 10 + "px");
		}
		irw_loop(w, slider, duration, true);
	});

}

function irw_loop(width, slider, duration, first) {
	if(first) {
		image_set_position = width + image_set_width;
	} else {
		image_set_position += image_set_width;
	}
	slider.animate({
		left: "-" + image_set_position + "px"
	}, duration, 'linear', function(){
		if(!ie8) {
			slider.append(image_set).width(slider.width() + image_set_width + "px");
		} else {
			slider.append(image_set).width(slider.width() + image_set_width + 10 + "px");
		}
		irw_loop(width, slider, duration, false);
	});
}
