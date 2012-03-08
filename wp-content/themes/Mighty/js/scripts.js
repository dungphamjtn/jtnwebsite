
$(document).ready(function() {
	dropDownMenu();
	autoSlide(5000, 0);
	addSeparators();
	autoTab();
	popPosts();
	closeNot();
	portfolioFade();
	
	Cufon.replace('h1, h2, h3, h4, h5');
	
	$("#login_link a").fancybox();
	
	$(".lightbox").fancybox({
				'titleShow'		: false
			});
	
});

function dropDownMenu() {
	
	$("#menu ul a").removeAttr('title');
	$("#menu ul ul").css({display: "none"}); // Fixes opera Bug
	  
	$("#menu ul li").hover(function() {
		$(this).find('ul:first').css({display: "block", opacity: 0}).stop().animate({ opacity: 1 }, 200); //Slides down when hover the UL
		$(this).children('a').addClass("hovered"); //Adds a hovered class, so you can see the menu path you are following
	}
	,function() {
		$(this).find('ul:first').css({display: "none"}); //Slides up on mouseleave
		$(this).children('a').removeClass("hovered"); //removes the hovered class.
	});
	
}

function nextSlide() {
	
	$current = $('#slider ul li.current');
	$next = $('#slider ul li.current').next();
	
	$current_sel = $('#slider_selector ul li.current');
	$next_sel = $('#slider_selector ul li.current').next();
	
	
	if($next.children('a').children('img').attr('alt') == undefined) {
		$next = $("#slider ul li:first");
		$next_sel = $("#slider_selector ul li:first");
	} else {
		//Do nothing. The $next element exists
	}
	
	$next.addClass('next').css({ opacity: 0 }).stop().animate({ opacity: 1 }, 300, function() {
																							
		$next.addClass('current').removeClass('next');
		$current.removeClass('current');
																							
	});
	
	$current_sel.removeClass('current');
	$next_sel.addClass('current');
	
	
}

function autoSlide($time, $stop) {
	
	$('#slider ul li:first').addClass('current');
	
	$total_slides = ($('#slider ul li').length);
	$i = 1
	
	
	
	$('#slider_selector').prepend('<ul></ul>');
	
	$('#slider ul li').each(function() {
									 
		$(this).addClass('slider_'+$i);
		
		if($i == 1) {
			$('#slider_selector ul').append('<li class="current sel_'+$i+'"></li>');
		} else {
			$('#slider_selector ul').append('<li class="sel_'+$i+'"></li>');
		}
		
		$i++;
									 
	})
	
	if(typeof(isClicked) == 'undefined') { var isClicked = 0; }
	
	$('#slider_selector ul li').click(function() {
											   
		$id = $(this).attr('class').split(' ');
		
		if($id[0] == 'current' || $id[1] == 'current') {
			
			//do nothing
			
		} else {
			
			$id = $id[0].split('_');
			callSlide($id[1]);
			
			isClicked = 1;
			
		}
											   
	});
	
	if(typeof(isHovered) == 'undefined') { var isHovered = 0; }
	//If no item has been hovered yet, the variable isHovered doesn't exist, so we create it. 
		
	$("#slider ul li").hover(function() { // Whenever an item is hovered
		isHovered = 1; //Setting isHovered 1, we stop the autsliding from going on
	}, function() {
		isHovered = 0;//Setting isHovered 1, we make the autsliding go on
	});
	
	setInterval(function() {
		
		if($stop == 1) {
			
			if(isHovered == 0 && isClicked == 0) { //It only calls the next item IF no item is hovered
			
				nextSlide();
			
			}
			
		} else if(isClicked == 0) {
			
			nextSlide();
			
		}
		
		
						 
	}, $time);
	
}

function callSlide($slideID) {
	
	$current = $('#slider ul li.current');
	$next = $('#slider ul li.slider_'+$slideID);
	
	$current_sel = $('#slider_selector ul li.current');
	$next_sel = $('#slider_selector ul li.sel_'+$slideID);
		
	$next.addClass('next').css({ opacity: 0 }).stop().animate({ opacity: 1 }, 300, function() {
																							
		$next.addClass('current').removeClass('next');
		$current.removeClass('current');
																							
	});
	
	$current_sel.removeClass('current');
	$next_sel.addClass('current');
	
}

function addSeparators() {
	
	$('#menu ul:first').children('li').each(function() {
														 
		$(this).after('<li class="border"></li>'); 
														 
	});
	
	$('.border:last').remove();
	
	$('#menu ul li ul').each(function() {
								
		$(this).children('li:first').children('a').css('border-top', 'none');
		$(this).children('li:last').children('a').css('border-bottom', 'none');
								
	});
	
	$('.content_item').each(function() {
		
		$(this).after('<div class="content_item_separator"></div>');
		
	});
	
	$('.content_item_separator:last').remove();
	
	$('ul.ctabs_content').each(function() {
		$(this).children('li:last').children('a').css({ 'border-bottom': 'none' });
	});
	
	
	
}

function nextTab() {
	
	$current_t = $('#ctabs li.current');
	$next_t = $('#ctabs li.current').next();
	
	$current_sel_t = $('#tabs li.current');
	$next_sel_t = $('#tabs li.current').next();
	
	
	if($next_t.children('ul').attr('class') == undefined) {
		$next_t = $("#ctabs li:first");
		$next_sel_t = $('#tabs li:first');
	} else {
		//Do nothing. The $next element exists
	}
	
	$next_t.addClass('next').css({ opacity: 0 }).stop().animate({ opacity: 1 }, 300, function() {
																							
		$next_t.addClass('current').removeClass('next');
		$current_t.removeClass('current');
																							
	});
	
	$current_sel_t.removeClass('current');
	$next_sel_t.addClass('current');
	
}

function autoTab() {
	
	$('#tabs li:first').addClass('current');
	$('#ctabs li:first').addClass('current');
	
	$i = 1;
	$('#tabs li').each(function() {
								
		$(this).attr('id', 'tab_'+$i);
		
		$i++;
								
	});
	
	$i = 1;
	$('#ctabs li:not(ul.ctabs_content li)').each(function() {
								
		$(this).attr('id', 'ctab_'+$i);
		
		$i++;
								
	});
	
	$('#tabs li').click(function() {
								 
		if($(this).attr('class') == 'current') {
			
			
			
		} else {
			
			$id = $(this).attr('id').split('_');
			
			callTab($id[1]);	
			
		}
		
						 
								 
	});
	
}

function callTab($id) {
	
	$current_t = $('#ctabs li.current');
	$next_t = $('#ctabs li#ctab_'+$id);
	
	$current_sel_t = $('#tabs li.current');
	$next_sel_t = $('#tabs li#tab_'+$id);
		
	$current_t.slideUp(250, function() {
									 
		$(this).removeClass('current');							 
									 
	});
	
	$next_t.slideDown(400).addClass('current').removeClass('next');
	
	$current_sel_t.removeClass('current');
	$next_sel_t.addClass('current');
	
}

function nextPop() {
	
	$current_p = $('#popular_posts li.current');
	$next_p = $('#popular_posts li.current').next();
	
	if($next_p.length) {
		
	} else {
		
		$next_p = $('#popular_posts li:first');
		
	}
	
	$current_p.stop().animate({ left: '-280px' }, 200).removeClass('current');
	$next_p.css({ left: '280px' }).stop().animate({ left: 0 }, 200).addClass('current');
	
}

function prevPop() {
	
	$current_pop = $('#popular_posts li.current');
	$prev_pop = $('#popular_posts li.current').prev();
	
	if($prev_pop.length) {
		
	} else {
		
		$prev_pop = $('#popular_posts li:not(ul.popular_posts_content li):last');
		
	}
	
	$current_pop.stop().animate({ left: '280px' }, 200).removeClass('current');
	$prev_pop.css({ left: '-280px' }).stop().animate({ left: 0 }, 200).addClass('current');
	
}

function popPosts() {
	
	$('#popular_posts li:first').addClass('current');
	
	$('#next_pop').click(function() {
								  
		nextPop();
								  
	});
	
	$('#prev_pop').click(function() {
								  
		prevPop();
								  
	});
	
}

function closeNot() {
	
	$('.close').click(function() {
							   
		$(this).parent().fadeOut(400);					   
							   
	});
	
}

function portfolioFade() {
	
	$('#portfolio li ul li').hover(function() {
											
		$('#portfolio li ul li').stop().animate({ opacity: .3 }, 300);
		$(this).stop().css('opacity', 1);
		$(this).children('a').prepend('<div class="portfolio_hover">'+$(this).children('a').children('img').attr('title')+'</div>');
		$('.portfolio_hover').css({ 'display': 'block', 'opacity': 0 }).stop().animate({opacity: .8  }, 300);
											
	}, function() {
		
		$('#portfolio li ul li').stop().animate({ opacity: 1 }, 200);
		$('.portfolio_hover').remove();
		
	});
	
}