(function($) {
	'use strict';

  $(document).ready(function() {
    if ($('.hostwph-password-checker').length) {
      var pass_view_state = false;

      function hostwph_pass_check_strength(pass) {
        var strength = 0;
        var password = $('.hostwph-password-strength');
        var low_upper_case = password.closest('.hostwph-password-checker').find('.low-upper-case i');
        var number = password.closest('.hostwph-password-checker').find('.one-number i');
        var special_char = password.closest('.hostwph-password-checker').find('.one-special-char i');
        var eight_chars = password.closest('.hostwph-password-checker').find('.eight-character i');

        //If pass contains both lower and uppercase characters
        if (pass.match(/([a-z].*[A-Z])|([A-Z].*[a-z])/)) {
          strength += 1;
          low_upper_case.text('task_alt');
        } else {
          low_upper_case.text('radio_button_unchecked');
        }

        //If it has numbers and characters
        if (pass.match(/([0-9])/)) {
          strength += 1;
          number.text('task_alt');
        } else {
          number.text('radio_button_unchecked');
        }

        //If it has one special character
        if (pass.match(/([!,%,&,@,#,$,^,*,?,_,~,|,¬,+,ç,-,€])/)) {
          strength += 1;
          special_char.text('task_alt');
        } else {
          special_char.text('radio_button_unchecked');
        }

        //If pass is greater than 7
        if (pass.length > 7) {
          strength += 1;
          eight_chars.text('task_alt');
        } else {
          eight_chars.text('radio_button_unchecked');
        }

        // If value is less than 2
        if (strength < 2) {
          $('.hostwph-password-strength-bar').removeClass('hostwph-progress-bar-warning hostwph-progress-bar-success').addClass('hostwph-progress-bar-danger').css('width', '10%');
        } else if (strength == 3) {
          $('.hostwph-password-strength-bar').removeClass('hostwph-progress-bar-success hostwph-progress-bar-danger').addClass('hostwph-progress-bar-warning').css('width', '60%');
        } else if (strength == 4) {
          $('.hostwph-password-strength-bar').removeClass('hostwph-progress-bar-warning hostwph-progress-bar-danger').addClass('hostwph-progress-bar-success').css('width', '100%');
        }
      }

      $(document).on('click', ('.hostwph-show-pass'), function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var hostwph_btn = $('.hostwph-show-pass');

        if (pass_view_state) {
          hostwph_btn.siblings('#hostwph-password').attr('type', 'password');
          hostwph_btn.find('i').text('visibility_off');
          pass_view_state = false;
        }else{
          hostwph_btn.siblings('#hostwph-password').attr('type', 'text');
          hostwph_btn.find('i').text('visibility');
          pass_view_state = true;
        } 
      });

      $(document).on('keyup', ('.hostwph-password-strength'), function(e){
        hostwph_pass_check_strength($('.hostwph-password-strength').val());

        if (!$('#hostwph-popover-pass').is(':visible')) {
          $('#hostwph-popover-pass').fadeIn('slow');
        }

        if (!$('.hostwph-show-pass').is(':visible')) {
          $('.hostwph-show-pass').fadeIn('slow');
        }
      });
    }
    
    $(document).on('mouseover', '.hostwph-input-star', function(e){
      if (!$(this).closest('.hostwph-input-stars').hasClass('clicked')) {
        $(this).text('star');
        $(this).prevAll('.hostwph-input-star').text('star');
      }
    });

    $(document).on('mouseout', '.hostwph-input-stars', function(e){
      if (!$(this).hasClass('clicked')) {
        $(this).find('.hostwph-input-star').text('star_outlined');
      }
    });

    $(document).on('click', '.hostwph-input-star', function(e){
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();

      $(this).closest('.hostwph-input-stars').addClass('clicked');
      $(this).closest('.hostwph-input-stars').find('.hostwph-input-star').text('star_outlined');
      $(this).text('star');
      $(this).prevAll('.hostwph-input-star').text('star');
      $(this).closest('.hostwph-input-stars').siblings('.hostwph-input-hidden-stars').val($(this).prevAll('.hostwph-input-star').length + 1);
    });

    $(document).on('change', '.hostwph-input-hidden-stars', function(e){
      $(this).siblings('.hostwph-input-stars').find('.hostwph-input-star').text('star_outlined');
      $(this).siblings('.hostwph-input-stars').find('.hostwph-input-star').slice(0, $(this).val()).text('star');
    });

    if ($('.hostwph-field[data-hostwph-parent]').length) {
      hostwph_form_update();

      $(document).on('change', '.hostwph-field[data-hostwph-parent~="this"]', function(e) {
        hostwph_form_update();
      });
    }

    if ($('.hostwph-html-multi-group').length) {
      $(document).on('click', '.hostwph-html-multi-remove-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var hostwph_users_btn = $(this);

        if (hostwph_users_btn.closest('.hostwph-html-multi-wrapper').find('.hostwph-html-multi-group').length > 1) {
          $(this).closest('.hostwph-html-multi-group').remove();
        }else{
          $(this).closest('.hostwph-html-multi-group').find('input, select, textarea').val('');
        }
      });

      $(document).on('click', '.hostwph-html-multi-add-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        $(this).closest('.hostwph-html-multi-wrapper').find('.hostwph-html-multi-group:first').clone().insertAfter($(this).closest('.hostwph-html-multi-wrapper').find('.hostwph-html-multi-group:last'));
        $(this).closest('.hostwph-html-multi-wrapper').find('.hostwph-html-multi-group:last').find('input, select, textarea').val('');

        $(this).closest('.hostwph-html-multi-wrapper').find('.hostwph-input-range').each(function(index, element) {
          $(this).siblings('.hostwph-input-range-output').html($(this).val());
        });
      });

      $('.hostwph-html-multi-wrapper').sortable({handle: '.hostwph-multi-sorting'});

      $(document).on('sortstop', '.hostwph-html-multi-wrapper', function(event, ui){
        hostwph_get_main_message(hostwph_i18n.ordered_element);
      });
    }

    if ($('.hostwph-input-range').length) {
      $('.hostwph-input-range').each(function(index, element) {
        $(this).siblings('.hostwph-input-range-output').html($(this).val());
      });

      $(document).on('input', '.hostwph-input-range', function(e) {
        $(this).siblings('.hostwph-input-range-output').html($(this).val());
      });
    }

    if ($('.hostwph-image-btn').length) {
      var image_frame;

      $(document).on('click', '.hostwph-image-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (image_frame){
          image_frame.open();
          return;
        }

        var hostwph_input_btn = $(this);
        var hostwph_images_block = hostwph_input_btn.closest('.hostwph-images-block').find('.hostwph-images');
        var hostwph_images_input = hostwph_input_btn.closest('.hostwph-images-block').find('.hostwph-image-input');

        var image_frame = wp.media({
          title: (hostwph_images_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_images : hostwph_i18n.select_image,
          library: {
            type: 'image'
          },
          multiple: (hostwph_images_block.attr('data-hostwph-multiple') == 'true') ? 'true' : 'false',
        });

        image_frame.states.add([
          new wp.media.controller.Library({
            id: 'post-gallery',
            title: (hostwph_images_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.edit_images : hostwph_i18n.edit_image,
            priority: 20,
            toolbar: 'main-gallery',
            filterable: 'uploaded',
            library: wp.media.query(image_frame.options.library),
            multiple: (hostwph_images_block.attr('data-hostwph-multiple') == 'true') ? 'true' : 'false',
            editable: true,
            allowLocalEdits: true,
            displaySettings: true,
            displayUserSettings: true
          })
        ]);

        image_frame.open();

        image_frame.on('select', function() {
          var ids = '', attachments_arr = [];

          attachments_arr = image_frame.state().get('selection').toJSON();
          hostwph_images_block.html('');

          $(attachments_arr).each(function(e){
            sep = (e != (attachments_arr.length - 1))  ? ',' : '';
            ids += $(this)[0].id + sep;
            hostwph_images_block.append('<img src="' + $(this)[0].url + '" class="">');
          });

          hostwph_input_btn.text((hostwph_images_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_images : hostwph_i18n.select_image);
          hostwph_images_input.val(ids);
        });
      });
    }

    if ($('.hostwph-audio-btn').length) {
      var audio_frame;

      $(document).on('click', '.hostwph-audio-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (audio_frame){
          audio_frame.open();
          return;
        }

        var hostwph_input_btn = $(this);
        var hostwph_audios_block = hostwph_input_btn.closest('.hostwph-audios-block').find('.hostwph-audios');
        var hostwph_audios_input = hostwph_input_btn.closest('.hostwph-audios-block').find('.hostwph-audio-input');

        var audio_frame = wp.media({
          title: (hostwph_audios_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_audios : hostwph_i18n.select_audio,
          library : {
            type : 'audio'
          },
          multiple: (hostwph_audios_block.attr('data-hostwph-multiple') == 'true') ? 'true' : 'false',
        });

        audio_frame.states.add([
          new wp.media.controller.Library({
            id: 'post-gallery',
            title: (hostwph_audios_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_audios : hostwph_i18n.select_audio,
            priority: 20,
            toolbar: 'main-gallery',
            filterable: 'uploaded',
            library: wp.media.query(audio_frame.options.library),
            multiple: (hostwph_audios_block.attr('data-hostwph-multiple') == 'true') ? 'true' : 'false',
            editable: true,
            allowLocalEdits: true,
            displaySettings: true,
            displayUserSettings: true
          })
        ]);

        audio_frame.open();

        audio_frame.on('select', function() {
          var ids = '', attachments_arr = [];

          attachments_arr = audio_frame.state().get('selection').toJSON();
          hostwph_audios_block.html('');

          $(attachments_arr).each(function(e){
            sep = (e != (attachments_arr.length - 1))  ? ',' : '';
            ids += $(this)[0].id + sep;
            hostwph_audios_block.append('<div class="hostwph-audio hostwph-tooltip" title="' + $(this)[0].title + '"><i class="dashicons dashicons-media-audio"></i></div>');
          });

          $('.hostwph-tooltip').tooltipster({maxWidth: 300,delayTouch:[0, 4000]});
          hostwph_input_btn.text((hostwph_audios_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_audios : hostwph_i18n.select_audio);
          hostwph_audios_input.val(ids);
        });
      });
    }

    if ($('.hostwph-video-btn').length) {
      var video_frame;

      $(document).on('click', '.hostwph-video-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (video_frame){
          video_frame.open();
          return;
        }

        var hostwph_input_btn = $(this);
        var hostwph_videos_block = hostwph_input_btn.closest('.hostwph-videos-block').find('.hostwph-videos');
        var hostwph_videos_input = hostwph_input_btn.closest('.hostwph-videos-block').find('.hostwph-video-input');

        var video_frame = wp.media({
          title: (hostwph_videos_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_videos : hostwph_i18n.select_video,
          library : {
            type : 'video'
          },
          multiple: (hostwph_videos_block.attr('data-hostwph-multiple') == 'true') ? 'true' : 'false',
        });

        video_frame.states.add([
          new wp.media.controller.Library({
            id: 'post-gallery',
            title: (hostwph_videos_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_videos : hostwph_i18n.select_video,
            priority: 20,
            toolbar: 'main-gallery',
            filterable: 'uploaded',
            library: wp.media.query(video_frame.options.library),
            multiple: (hostwph_videos_block.attr('data-hostwph-multiple') == 'true') ? 'true' : 'false',
            editable: true,
            allowLocalEdits: true,
            displaySettings: true,
            displayUserSettings: true
          })
        ]);

        video_frame.open();

        video_frame.on('select', function() {
          var ids = '', attachments_arr = [];

          attachments_arr = video_frame.state().get('selection').toJSON();
          hostwph_videos_block.html('');

          $(attachments_arr).each(function(e){
            sep = (e != (attachments_arr.length - 1))  ? ',' : '';
            ids += $(this)[0].id + sep;
            hostwph_videos_block.append('<div class="hostwph-video hostwph-tooltip" title="' + $(this)[0].title + '"><i class="dashicons dashicons-media-video"></i></div>');
          });

          $('.hostwph-tooltip').tooltipster({maxWidth: 300,delayTouch:[0, 4000]});
          hostwph_input_btn.text((hostwph_videos_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_videos : hostwph_i18n.select_video);
          hostwph_videos_input.val(ids);
        });
      });
    }

    if ($('.hostwph-file-btn').length) {
      var file_frame;

      $(document).on('click', '.hostwph-file-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (file_frame){
          file_frame.open();
          return;
        }

        var hostwph_input_btn = $(this);
        var hostwph_files_block = hostwph_input_btn.closest('.hostwph-files-block').find('.hostwph-files');
        var hostwph_files_input = hostwph_input_btn.closest('.hostwph-files-block').find('.hostwph-file-input');

        var file_frame = wp.media({
          title: (hostwph_files_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_files : hostwph_i18n.select_file,
          multiple: (hostwph_files_block.attr('data-hostwph-multiple') == 'true') ? 'true' : 'false',
        });

        file_frame.states.add([
          new wp.media.controller.Library({
            id: 'post-gallery',
            title: (hostwph_files_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.select_files : hostwph_i18n.select_file,
            priority: 20,
            toolbar: 'main-gallery',
            filterable: 'uploaded',
            library: wp.media.query(file_frame.options.library),
            multiple: (hostwph_files_block.attr('data-hostwph-multiple') == 'true') ? 'true' : 'false',
            editable: true,
            allowLocalEdits: true,
            displaySettings: true,
            displayUserSettings: true
          })
        ]);

        file_frame.open();

        file_frame.on('select', function() {
          var ids = '', attachments_arr = [];

          attachments_arr = file_frame.state().get('selection').toJSON();
          hostwph_files_block.html('');

          $(attachments_arr).each(function(e){
            sep = (e != (attachments_arr.length - 1))  ? ',' : '';
            ids += $(this)[0].id + sep;
            hostwph_files_block.append('<embed src="' + $(this)[0].url + '" type="application/pdf" class="hostwph-embed-file"/>');
          });

          hostwph_input_btn.text((hostwph_files_block.attr('data-hostwph-multiple') == 'true') ? hostwph_i18n.edit_files : hostwph_i18n.edit_file);
          hostwph_files_input.val(ids);
        });
      });
    }
  });

  $(document).on('click', '.hostwph-toggle', function(e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    var hostwph_toggle = $(this);

    if (hostwph_toggle.find('i').length) {
      if (hostwph_toggle.siblings('.hostwph-toggle-content').is(':visible')) {
        hostwph_toggle.find('i').text('add');
      }else{
        hostwph_toggle.find('i').text('clear');
      }
    }

    hostwph_toggle.siblings('.hostwph-toggle-content').fadeToggle();
  });
})(jQuery);
