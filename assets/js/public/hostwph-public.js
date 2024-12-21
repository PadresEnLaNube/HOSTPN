(function($) {
	'use strict';

	function hostwph_timer(step) {
		var step_timer = $('.hostwph-player-step[data-hostwph-step="' + step + '"] .hostwph-player-timer');
		var step_icon = $('.hostwph-player-step[data-hostwph-step="' + step + '"] .hostwph-player-timer-icon');
		
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

	$(document).on('click', '.hostwph-popup-player-btn', function(e){
  	hostwph_timer(1);
	});

  $(document).on('click', '.hostwph-steps-prev', function(e){
    e.preventDefault();

    var steps_count = $('#hostwph-recipe-wrapper').attr('data-hostwph-steps-count');
    var current_step = $('#hostwph-popup-steps').attr('data-hostwph-current-step');
    var next_step = Math.max(0, (parseInt(current_step) - 1));
    
    $('.hostwph-player-step').addClass('hostwph-display-none-soft');
    $('#hostwph-popup-steps').attr('data-hostwph-current-step', next_step);
    $('.hostwph-player-step[data-hostwph-step=' + next_step + ']').removeClass('hostwph-display-none-soft');

    if (current_step <= steps_count) {
    	$('.hostwph-steps-next').removeClass('hostwph-display-none');
    }

    if (current_step <= 2) {
    	$(this).addClass('hostwph-display-none');
    }

    hostwph_timer(next_step);
	});

	$(document).on('click', '.hostwph-steps-next', function(e){
    e.preventDefault();

    var steps_count = $('#hostwph-recipe-wrapper').attr('data-hostwph-steps-count');
    var current_step = $('#hostwph-popup-steps').attr('data-hostwph-current-step');
    var next_step = Math.min(steps_count, (parseInt(current_step) + 1));

    $('.hostwph-player-step').addClass('hostwph-display-none-soft');
    $('#hostwph-popup-steps').attr('data-hostwph-current-step', next_step);
    $('.hostwph-player-step[data-hostwph-step=' + next_step + ']').removeClass('hostwph-display-none-soft');

    if (current_step >= 1) {
    	$('.hostwph-steps-prev').removeClass('hostwph-display-none');
    }

    if (current_step >= (steps_count - 1)) {
    	$(this).addClass('hostwph-display-none');
    }

    hostwph_timer(next_step);
	});

	$(document).on('click', '.hostwph-ingredient-checkbox', function(e){
    e.preventDefault();

    if ($(this).text() == 'radio_button_unchecked') {
    	$(this).text('task_alt');
    }else{
    	$(this).text('radio_button_unchecked');
    }
	});

	$('.hostwph-carousel-main-images .owl-carousel').owlCarousel({
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
