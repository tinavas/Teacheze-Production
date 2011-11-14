(function($){

jQuery(document).ready(function($) {

	if ($('#slider-testimonials').length) {
		$("#slider-testimonials ul").jcarousel({
			scroll: 1,
			wrap: 'both',
			initCallback: mycarousel_initCallback,
			itemVisibleInCallback: mycarousel_itemVisibleInCallback,
			buttonNextHTML: null,
			buttonPrevHTML: null
	    });
	};

	$('#navigation').hover(function() {
		if ($(this).find('ul:first').width() > 725) {
			$(this).css('z-index', 300);
		}
	}, function() {
		$(this).css('z-index', 3);
	});

	$('#navigation li').each(function() {
		$(this).find('span').each(function() {
			if ($(this).hasClass('t')) {
				$(this).before($(this).html());
			}
			$(this).remove();
		});
	});

	$('#navigation li').hover(
		function() {
			$(this).find('a:eq(0)').addClass('hover').end().find('ul:eq(0)').show();
		},
		function() {
			$(this).find('a:eq(0)').removeClass('hover').end().find('ul:eq(0)').hide();
		}
	);

	$('.blink').focus(function () {
		if ($(this).val() == $(this).attr('title')) {
			$(this).val('');
		}
	}).blur(function () {
		if ($(this).val() == '') {
			$(this).val($(this).attr('title'));
		}
	});

	if ($.browser.msie && $.browser.version.substr(0,1)<7) {
		DD_belatedPNG.fix('#logo a, #navigation, img, .txt-learn-today, #featured .bar, #featured .bar a, .why-us, .why-us li, #slider-testimonials, .question-form .label-new, #footer, #footer-inner, h2.heading-learn, .call-us li span.icon-skype, .call-us li span.icon-phone');
	};
});
function mycarousel_initCallback(carousel) {
	$("#slider-testimonials .slider-navigation a").click(function(){
    	var x = parseInt($(this).text());
    	carousel.scroll(x);
    	return false;
    });
};

function mycarousel_itemVisibleInCallback(carousel, li, pos, state) {
	$("#slider-testimonials .slider-navigation a").removeClass('active');
	$("#slider-testimonials .slider-navigation a").eq(pos-1).addClass('active');
};

$(window).load(function(){
	$('#content .post *').each(function() {
		if ($(this).is('#slider-testimonials ul')) {
			return false;
		}
		if ($(this).width() > $('#content .post').width()) {
			// $(this).width($('#content .post').width());
			$(this).css('width', '100%');
		}
	});
	$('#content').css({'overflow': 'hidden'});
});

})(jQuery)