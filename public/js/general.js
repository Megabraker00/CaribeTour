//Elements
var themeElements = {
	ajaxForm: '.ajax-form',
	colorboxLink: '.colorbox',
	contentSlider: '.content-slider',
	dateField: '.date-field',
	headerMenu: '.header-menu',
	hiddenCaption: '.hidden-caption',
	imageCaption: '.featured-image-caption',
	mapContainer: '.map-container',
	placeholderFields: '.placeholder-form input',
	popupID: '.popup-id',
	popupTitle: '.popup-title',
	rangeSlider: '.range-slider',
	selectField: '.select-field',
	selectMenu: '.select-menu',
	submitButton: '.submit-button',
	tabsContainer: '.tabs-container',
	tabsPane: '.pane',
	tabsTitles: '.tabs',
	toggleContent: '.toggle-content',
	toggleTitle: '.toggle-title',
	twitterWidget: '.widget-twitter',
	header: '.site-header',	
}

//DOM Loaded
jQuery(document).ready(function($) {

	//Menu Pattern
	var menuPatternSRC=$('body').css('background-image').replace(/^url\(["']?/, '').replace(/["']?\)$/, '');
	var fullwidthPattern=false;
	
	if($(themeElements.header).find('.substrate img').length) {
		menuPatternSRC=$(themeElements.header).find('.substrate img').attr('src');
		fullwidthPattern=true;
	}
	
	$(themeElements.headerMenu).find('div > ul > li > a').each(function() {	
		$(this).textPattern({
			patternID: 'pattern-'+$(this).parent().index(),
			patternSRC: menuPatternSRC,
			fullwidth: fullwidthPattern
		});
	});		
	
	//Dropdown Menu
	$(themeElements.headerMenu).find('ul').parent('li').addClass('parent');
	$(themeElements.headerMenu).find('.parent').hoverIntent(
		function() {
			var menuItem=$(this);			
			menuItem.children('ul').hide().slideToggle(200);
			menuItem.addClass('hover');
		},
		function() {
			var menuItem=$(this);
			menuItem.children('ul').show().slideToggle(200, function() {
				menuItem.removeClass('hover');
			});
		}
	);
	
	//Sliders
	$(themeElements.contentSlider).each(function() {
		var sliderOptions={};
		
		sliderOptions.controls=true;		
		sliderOptions.pause=parseInt($(this).find('.slider-pause').val());
		sliderOptions.speed=parseInt($(this).find('.slider-speed').val());
		sliderOptions.effect=$(this).find('.slider-effect').val();
		$(this).fadeSlider(sliderOptions);
	});
	
	//Placeholders
	$('input, textarea').each(function(){
		if($(this).attr('placeholder')) {
			$(this).placeholder();
		}		
	});
	
	$(themeElements.placeholderFields).each(function(){
		var placeholder=$(this).val();
	
		$(this).focus(function () {
			if($(this).val()==placeholder){
				$(this).val('');
			}
		});
		$(this).blur(function () {	
			if(!$(this).val()){
				$(this).val(placeholder);
			}
		});
	});
	
	//Select Field
	$(themeElements.selectField).each(function() {
		if($(this).find('option:selected').length) {
			$(this).find('span').text($(this).find('option:selected').text());
		}
	});
	
	$(themeElements.selectField).find('select').fadeTo(0, 0);
	$(themeElements.selectField).find('select').change(function() {
		$(this).parent().find('span').text($(this).find('option:selected').text());
	});
	
	//Select Menu
	$(themeElements.selectMenu).find('option').each(function() {
		if(window.location.href==$(this).val()) {
			$(themeElements.selectMenu).find('span').text($(this).text());
			$(this).attr('selected', 'selected');
		}
	});
	
	$(themeElements.selectMenu).find('select').change(function() {
		window.location.href=$(this).find('option:selected').val();
	});
	
	//Range Slider
	$(themeElements.rangeSlider).each(function() {
		var slider=$(this);
		var minPrice=parseInt(slider.find('input.range-min').val());
		var maxPrice=parseInt(slider.find('input.range-max').val());
		var minPriceValue=parseInt(slider.find('input.range-min-value').val());
		var maxPriceValue=parseInt(slider.find('input.range-max-value').val());
		
		if(minPrice==0) {
			minPrice=minPriceValue;
		}
		
		if(maxPrice==0) {
			maxPrice=maxPriceValue;
		}
		
		slider.find('.ui-slider').slider({
			range: true,
			min: minPriceValue,
			max: maxPriceValue,
			values: [minPrice, maxPrice],
			slide: function(event, ui) {
				slider.find('.range-min').val(ui.values[0]).find('span').text(ui.values[0]);
				slider.find('.range-max').val(ui.values[1]).find('span').text(ui.values[1]);
			}
		});
		
		slider.find('.range-min').val(minPrice).find('span').text(minPrice);
		slider.find('.range-max').val(maxPrice).find('span').text(maxPrice);
	});
	
	//Date Field
	$(themeElements.dateField).each(function() {
		$(this).datepicker({
			dateFormat: labels.dateFormat,
			dayNames: labels.dayNames,
			dayNamesMin: labels.dayNamesMin,
			monthNames: labels.monthNames,
			firstDay: labels.firstDay,
			prevText: labels.prevText,
			nextText: labels.nextText,
		});
	});
	
	//Google Map
	$(themeElements.mapContainer).each(function() {
		var container=$(this);		
		var position = new google.maps.LatLng(container.find('input.map-latitude').val(), container.find('input.map-longitude').val());
		
		var myOptions = {
		  zoom: parseInt(container.find('input.map-zoom').val()),
		  center: position,
		  mapTypeId: google.maps.MapTypeId.ROADMAP
		};
		
		var map = new google.maps.Map(
			document.getElementById(container.find('div').attr('id')),
			myOptions);
	 
		var marker = new google.maps.Marker({
			position: position,
			map: map,
			title:container.find('input.map-description').val()
		});  
	 
		var infowindow = new google.maps.InfoWindow({
			content: container.find('input.map-description').val()
		});
	 
		google.maps.event.addListener(marker, 'click', function() {
			infowindow.open(map,marker);
		});
	});
	
	//Colorbox
	$(themeElements.colorboxLink).each(function() {
		$(this).colorbox({
			rel: $(this).data('group'),
			inline: $(this).hasClass('inline'),
			current: '',
			maxWidth: '100%',
			maxHeight: $(this).hasClass('inline')?false:'100%',
		});	
		
		$(themeElements.colorboxLink).click(function() {
			if($(this).hasClass('inline') && $(this).data('title') && $(this).data('id')) {
				var element=$($(this).attr('href')),
					ID=$(this).data('id'),
					title=$(this).data('title');
				
				if(ID && title) {
					element.find(themeElements.popupTitle).find('*').text(title);
					element.find(themeElements.popupID).val(ID);
					element.find('.message').html('');
				}
			}
		});		
	});
	
	//AJAX Form
	$(themeElements.ajaxForm).each(function() {
		var form=$(this);
		
		form.submit(function() {
			var message=form.find('.message'),
				button=form.find(themeElements.submitButton);
				
			var data={
					action: form.find('.action').val(),
					nonce: form.find('.nonce').val(),
					data: form.serialize(),
				}
						
			button.addClass('disabled');
			form.append('<div class="loader"></div>');
			message.slideUp(300, function() {
				$(themeElements.colorboxLink).colorbox.resize();
			});		
			
			$.post(form.attr('action'), data, function(response) {
				if($('.redirect', response).length) {
					if($('.redirect', response).attr('href')) {
						window.location.href=$('.redirect',response).attr('href');
					} else {
						window.location.reload();
					}
				} else {
					form.find('.loader').remove();
					button.removeClass('disabled');
					message.html(response).slideDown(300, function() {
						$(themeElements.colorboxLink).colorbox.resize();
					});
				}				
			});
			
			return false;
		});
	});
	
	//Twitter Widget
	// $(themeElements.twitterWidget).each(function() {
		// var widget=$(this);
		// var ID=$(this).find('input.twitter-id').val();
		// var number=parseInt($(this).find('input.twitter-number').val());
		// twitterFetcher.fetch(ID, 'tweets', number, true, false, false);
	// });
	
	//Images Caption
	$(themeElements.imageCaption).click(function() {
		if($(this).parent().find(themeElements.colorboxLink).eq(0).length) {
			$(this).parent().find('a.colorbox:eq(0)').trigger('click');
			return false;
		}
	});
	
	$(themeElements.hiddenCaption).each(function() {
		$(this).css('bottom', -$(this).outerHeight());
	});
	
	//Submit Button
	$(themeElements.submitButton).not('.disabled').click(function() {
		var form=$($(this).attr('href'));
		
		if(!form.length || !form.is('form')) {
			form=$(this).parent();
			while(!form.is('form')) {
				form=form.parent();
			}
		}
			
		form.submit();		
		return false;
	});
	
	//Tabs
	$(themeElements.tabsContainer).each(function() {			
		var tabs=$(this);
		
		if(window.location.hash && $(window.location.hash+'-tab').length) {
			var currentPane=$(window.location.hash+'-tab');
			currentPane.show();
			$(window).scrollTop(tabs.offset().top);
			tabs.find(themeElements.tabsTitles).find('li').eq(currentPane.index()).addClass('current');
		} else {
			tabs.find(themeElements.tabsPane).eq(0).show().addClass('current');
			tabs.find(themeElements.tabsTitles).find('li').eq(0).addClass('current');
		}
		
		tabs.find('.tabs li').click(function() {
			tabs.find(themeElements.tabsTitles).find('li').removeClass('current');
			$(this).addClass('current');
			window.location.hash=$(this).find('a').attr('href');
			
			tabs.find(themeElements.tabsPane).hide();
			tabs.find(themeElements.tabsPane).eq($(this).index()).show();			
		});
	});
	
	//Toggles
	$(themeElements.toggleTitle).click(function() {
		var toggle=$(this).parent();
	
		toggle.find(themeElements.toggleContent).slideToggle(400, function() {
			if($(this).is(':visible')) {
				toggle.addClass('expanded');
			} else {
				toggle.removeClass('expanded');
			}
		});
	});
	
	//DOM Elements
	$('p:empty').remove();
	$('h1,h2,h3,h4,h5,h6,blockquote').prev('br').remove();	
	$('ul, ol').each(function() {
		if($(this).css('list-style-type')!='none') {
			$(this).css('padding-left', '1em');
			$(this).css('text-indent', '-1em');
		}
	});
});