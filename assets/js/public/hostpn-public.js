(function($) {
	'use strict';

	function hostpn_timer(step) {
		var step_timer = $('.hostpn-player-step[data-hostpn-step="' + step + '"] .hostpn-player-timer');
		var step_icon = $('.hostpn-player-step[data-hostpn-step="' + step + '"] .hostpn-player-timer-icon');
		
		if (!step_timer.hasClass('timing')) {
			step_timer.addClass('timing');

      setInterval(function() {
      	step_icon.fadeOut('fast').fadeIn('slow').fadeOut('fast').fadeIn('slow');
      }, 5000);

      setInterval(function() {
      	step_timer.text(Math.max(0, parseInt(step_timer.text()) - 1)).fadeOut('fast').fadeIn('slow').fadeOut('fast').fadeIn('slow');
      }, 60000);
		}
	}

	$(document).on('click', '.hostpn-popup-player-btn', function(e){
  	hostpn_timer(1);
	});

  $(document).on('click', '.hostpn-steps-prev', function(e){
    e.preventDefault();

    var steps_count = $('#hostpn-recipe-wrapper').attr('data-hostpn-steps-count');
    var current_step = $('#hostpn-popup-steps').attr('data-hostpn-current-step');
    var next_step = Math.max(0, (parseInt(current_step) - 1));
    
    $('.hostpn-player-step').addClass('hostpn-display-none-soft');
    $('#hostpn-popup-steps').attr('data-hostpn-current-step', next_step);
    $('.hostpn-player-step[data-hostpn-step=' + next_step + ']').removeClass('hostpn-display-none-soft');

    if (current_step <= steps_count) {
    	$('.hostpn-steps-next').removeClass('hostpn-display-none');
    }

    if (current_step <= 2) {
    	$(this).addClass('hostpn-display-none');
    }

    hostpn_timer(next_step);
	});

	$(document).on('click', '.hostpn-steps-next', function(e){
    e.preventDefault();

    var steps_count = $('#hostpn-recipe-wrapper').attr('data-hostpn-steps-count');
    var current_step = $('#hostpn-popup-steps').attr('data-hostpn-current-step');
    var next_step = Math.min(steps_count, (parseInt(current_step) + 1));

    $('.hostpn-player-step').addClass('hostpn-display-none-soft');
    $('#hostpn-popup-steps').attr('data-hostpn-current-step', next_step);
    $('.hostpn-player-step[data-hostpn-step=' + next_step + ']').removeClass('hostpn-display-none-soft');

    if (current_step >= 1) {
    	$('.hostpn-steps-prev').removeClass('hostpn-display-none');
    }

    if (current_step >= (steps_count - 1)) {
    	$(this).addClass('hostpn-display-none');
    }

    hostpn_timer(next_step);
	});

	$(document).on('click', '.hostpn-ingredient-checkbox', function(e){
    e.preventDefault();

    if ($(this).text() == 'radio_button_unchecked') {
    	$(this).text('task_alt');
    }else{
    	$(this).text('radio_button_unchecked');
    }
	});

	$('.hostpn-carousel-main-images .owl-carousel').owlCarousel({
    margin: 10,
    center: true,
    nav: false, 
    autoplay: true, 
    autoplayTimeout: 5000, 
    autoplaySpeed: 2000, 
    pagination: true, 
    responsive:{
      0:{
        items: 2,
      },
      600:{
        items: 3,
      },
      1000:{
        items: 4,
      }
    }, 
  });
})(jQuery);
