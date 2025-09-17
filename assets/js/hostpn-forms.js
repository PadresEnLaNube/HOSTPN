(function($) {
	'use strict';

  window.hostpn_select_country = function () {
    if (typeof $('#hostpn_country').val() !== 'undefined' && $('#hostpn_country').val() == 'esp') {
      $('#hostpn_country').closest('.hostpn_country').siblings('.hostpn_city').addClass('hostpn-display-none userspn-display-none').find('#hostpn_city').prop('required', false);
      
      $('#hostpn_country').closest('.hostpn_country').siblings('.hostpn_city_code').removeClass('hostpn-display-none userspn-display-none').find('#hostpn_city_code').prop('required', true);
    } else {
      $('#hostpn_country').closest('.hostpn_country').siblings('.hostpn_city').removeClass('hostpn-display-none userspn-display-none').find('#hostpn_city').prop('required', true);
      
      $('#hostpn_country').closest('.hostpn_country').siblings('.hostpn_city_code').addClass('hostpn-display-none userspn-display-none').find('#hostpn_city_code').prop('required', false);
    }
  }
  
  window.hostpn_select_identity = function () {
    if (typeof $('#hostpn_identity').val() !== 'undefined' && ($('#hostpn_identity').val() == 'nif' || $('#hostpn_identity').val() == 'nie')) {
      $('#hostpn_identity').closest('.hostpn_identity').siblings('.hostpn_identity_support_number').removeClass('hostpn-display-none userspn-display-none');
      $('#hostpn_identity').closest('.hostpn_identity').siblings('.hostpn_surname_alt').find('#hostpn_surname_alt').prop('required', true);

      if ($('#hostpn_identity').val() == 'nif') {
        $('#hostpn_identity').closest('.hostpn_identity').siblings('.hostpn_identity_support_number').find('#hostpn_identity_support_number').prop('required', true);
      } else {
        $('#hostpn_identity').closest('.hostpn_identity').siblings('.hostpn_identity_support_number').find('#hostpn_identity_support_number').prop('required', false);
      }
    }else{
      $('#hostpn_identity').closest('.hostpn_identity').siblings('.hostpn_identity_support_number').addClass('hostpn-display-none userspn-display-none');
      $('#hostpn_identity').closest('.hostpn_identity').siblings('.hostpn_surname_alt').find('#hostpn_surname_alt').prop('required', false);
    }
  }

// Auto-fill user data functionality
window.hostpn_auto_fill_user_data = function(user_id) {
    $.ajax({
      url: hostpn_ajax.ajax_url,
      type: 'POST',
      dataType: 'json',
      data: {
        action: 'hostpn_ajax',
        hostpn_ajax_type: 'hostpn_guest_get_user_data',
        hostpn_ajax_nonce: hostpn_ajax.hostpn_ajax_nonce,
        user_id: user_id
      },
      success: function(response) {
        // Force JSON parsing since jQuery isn't doing it automatically
        if (typeof response === 'string') {
          try {
            response = JSON.parse(response);
          } catch (e) {
            console.error('Failed to parse JSON response:', e);
            return;
          }
        }
        
        if (response.error_key === '') {
          // Fill form fields with user data
          $.each(response.user_data, function(field_id, field_value) {
            var $field = $('#' + field_id);
            if ($field.length) {
              if ($field.is('select')) {
                // For select fields, try multiple approaches
                $field.val(field_value);
                
                // If the value didn't set, try finding by option text or value
                if ($field.val() !== field_value) {
                  // Try to find option by value
                  var $option = $field.find('option[value="' + field_value + '"]');
                  if ($option.length) {
                    $option.prop('selected', true);
                  } else {
                    // Try to find option by text content
                    $option = $field.find('option').filter(function() {
                      return $(this).text().trim() === field_value;
                    });
                    if ($option.length) {
                      $option.prop('selected', true);
                    }
                  }
                }
                
                // Update custom selector display if it exists
                var selectorInstance = $field.data('-selector');
                if (selectorInstance && typeof selectorInstance.updateDisplay === 'function') {
                  selectorInstance.updateDisplay();
                }
                
                // Force refresh the select
                $field.trigger('change');
              } else if ($field.is('input[type="checkbox"]')) {
                if (field_value === 'on' || field_value === '1' || field_value === true) {
                  $field.prop('checked', true);
                } else {
                  $field.prop('checked', false);
                }
              } else {
                $field.val(field_value);
              }
              
              // Trigger change event to update dependent fields
              $field.trigger('change');
            }
          });
          
          // Trigger country and identity selection functions to update dependent fields
          hostpn_select_country();
          hostpn_select_identity();
        } else {
          console.error('Error loading user data:', response.error_content);
        }
      },
      error: function(xhr, status, error) {
        console.error('AJAX error:', error);
      }
    });
  };

  $(document).ready(function() {
    // Initialize toggle sections - hide all by default
    $('.hostpn-toggle-content').hide();
    
    hostpn_select_country();
    hostpn_select_identity();
    
    // Handle auto-fill button click
    $(document).on('click', '.hostpn-auto-fill-user-data', function(e) {
      e.preventDefault();
      var user_id = $(this).data('user-id');
      if (user_id) {
        hostpn_auto_fill_user_data(user_id);
      }
    });

    if ($('.hostpn-password-checker').length) {
      var pass_view_state = false;

      function hostpn_pass_check_strength(pass) {
        var strength = 0;
        var password = $('.hostpn-password-strength');
        var low_upper_case = password.closest('.hostpn-password-checker').find('.low-upper-case i');
        var number = password.closest('.hostpn-password-checker').find('.one-number i');
        var special_char = password.closest('.hostpn-password-checker').find('.one-special-char i');
        var eight_chars = password.closest('.hostpn-password-checker').find('.eight-character i');

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
          $('.hostpn-password-strength-bar').removeClass('hostpn-progress-bar-warning hostpn-progress-bar-success').addClass('hostpn-progress-bar-danger').css('width', '10%');
        } else if (strength == 3) {
          $('.hostpn-password-strength-bar').removeClass('hostpn-progress-bar-success hostpn-progress-bar-danger').addClass('hostpn-progress-bar-warning').css('width', '60%');
        } else if (strength == 4) {
          $('.hostpn-password-strength-bar').removeClass('hostpn-progress-bar-warning hostpn-progress-bar-danger').addClass('hostpn-progress-bar-success').css('width', '100%');
        }
      }

      $(document).on('click', ('.hostpn-show-pass'), function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var hostpn_btn = $('.hostpn-show-pass');

        if (pass_view_state) {
          hostpn_btn.siblings('#hostpn-password').attr('type', 'password');
          hostpn_btn.find('i').text('visibility_off');
          pass_view_state = false;
        }else{
          hostpn_btn.siblings('#hostpn-password').attr('type', 'text');
          hostpn_btn.find('i').text('visibility');
          pass_view_state = true;
        } 
      });

      $(document).on('keyup', ('.hostpn-password-strength'), function(e){
        hostpn_pass_check_strength($('.hostpn-password-strength').val());

        if (!$('#hostpn-popover-pass').is(':visible')) {
          $('#hostpn-popover-pass').fadeIn('slow');
        }

        if (!$('.hostpn-show-pass').is(':visible')) {
          $('.hostpn-show-pass').fadeIn('slow');
        }
      });
    }
    
    $(document).on('mouseover', '.hostpn-input-star', function(e){
      if (!$(this).closest('.hostpn-input-stars').hasClass('clicked')) {
        $(this).text('star');
        $(this).prevAll('.hostpn-input-star').text('star');
      }
    });

    $(document).on('mouseout', '.hostpn-input-stars', function(e){
      if (!$(this).hasClass('clicked')) {
        $(this).find('.hostpn-input-star').text('star_outlined');
      }
    });

    $(document).on('click', '.hostpn-input-star', function(e){
      e.preventDefault();
      e.stopPropagation();
      e.stopImmediatePropagation();

      $(this).closest('.hostpn-input-stars').addClass('clicked');
      $(this).closest('.hostpn-input-stars').find('.hostpn-input-star').text('star_outlined');
      $(this).text('star');
      $(this).prevAll('.hostpn-input-star').text('star');
      $(this).closest('.hostpn-input-stars').siblings('.hostpn-input-hidden-stars').val($(this).prevAll('.hostpn-input-star').length + 1);
    });

    $(document).on('change', '.hostpn-input-hidden-stars', function(e){
      $(this).siblings('.hostpn-input-stars').find('.hostpn-input-star').text('star_outlined');
      $(this).siblings('.hostpn-input-stars').find('.hostpn-input-star').slice(0, $(this).val()).text('star');
    });

    if ($('.hostpn-field[data-hostpn-parent]').length) {
      hostpn_form_update();

      $(document).on('change', '.hostpn-field[data-hostpn-parent~="this"]', function(e) {
        hostpn_form_update();
      });
    }

    if ($('.hostpn-html-multi-group').length) {
      $(document).on('click', '.hostpn-html-multi-remove-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        var hostpn_users_btn = $(this);

        if (hostpn_users_btn.closest('.hostpn-html-multi-wrapper').find('.hostpn-html-multi-group').length > 1) {
          $(this).closest('.hostpn-html-multi-group').remove();
        }else{
          $(this).closest('.hostpn-html-multi-group').find('input, select, textarea').val('');
        }
      });

      $(document).on('click', '.hostpn-html-multi-add-btn', function(e) {
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        $(this).closest('.hostpn-html-multi-wrapper').find('.hostpn-html-multi-group:first').clone().insertAfter($(this).closest('.hostpn-html-multi-wrapper').find('.hostpn-html-multi-group:last'));
        $(this).closest('.hostpn-html-multi-wrapper').find('.hostpn-html-multi-group:last').find('input, select, textarea').val('');

        $(this).closest('.hostpn-html-multi-wrapper').find('.hostpn-input-range').each(function(index, element) {
          $(this).siblings('.hostpn-input-range-output').html($(this).val());
        });
      });

      $('.hostpn-html-multi-wrapper').sortable({handle: '.hostpn-multi-sorting'});

      $(document).on('sortstop', '.hostpn-html-multi-wrapper', function(event, ui){
        hostpn_get_main_message(hostpn_i18n.ordered_element);
      });
    }

    if ($('.hostpn-input-range').length) {
      $('.hostpn-input-range').each(function(index, element) {
        $(this).siblings('.hostpn-input-range-output').html($(this).val());
      });

      $(document).on('input', '.hostpn-input-range', function(e) {
        $(this).siblings('.hostpn-input-range-output').html($(this).val());
      });
    }

    if ($('.hostpn-image-btn').length) {
      var image_frame;

      $(document).on('click', '.hostpn-image-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (image_frame){
          image_frame.open();
          return;
        }

        var hostpn_input_btn = $(this);
        var hostpn_images_block = hostpn_input_btn.closest('.hostpn-images-block').find('.hostpn-images');
        var hostpn_images_input = hostpn_input_btn.closest('.hostpn-images-block').find('.hostpn-image-input');

        var image_frame = wp.media({
          title: (hostpn_images_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_images : hostpn_i18n.select_image,
          library: {
            type: 'image'
          },
          multiple: (hostpn_images_block.attr('data-hostpn-multiple') == 'true') ? 'true' : 'false',
        });

        image_frame.states.add([
          new wp.media.controller.Library({
            id: 'post-gallery',
            title: (hostpn_images_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.edit_images : hostpn_i18n.edit_image,
            priority: 20,
            toolbar: 'main-gallery',
            filterable: 'uploaded',
            library: wp.media.query(image_frame.options.library),
            multiple: (hostpn_images_block.attr('data-hostpn-multiple') == 'true') ? 'true' : 'false',
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
          hostpn_images_block.html('');

          $(attachments_arr).each(function(e){
            sep = (e != (attachments_arr.length - 1))  ? ',' : '';
            ids += $(this)[0].id + sep;
            hostpn_images_block.append('<img src="' + $(this)[0].url + '" class="">');
          });

          hostpn_input_btn.text((hostpn_images_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_images : hostpn_i18n.select_image);
          hostpn_images_input.val(ids);
        });
      });
    }

    if ($('.hostpn-audio-btn').length) {
      var audio_frame;

      $(document).on('click', '.hostpn-audio-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (audio_frame){
          audio_frame.open();
          return;
        }

        var hostpn_input_btn = $(this);
        var hostpn_audios_block = hostpn_input_btn.closest('.hostpn-audios-block').find('.hostpn-audios');
        var hostpn_audios_input = hostpn_input_btn.closest('.hostpn-audios-block').find('.hostpn-audio-input');

        var audio_frame = wp.media({
          title: (hostpn_audios_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_audios : hostpn_i18n.select_audio,
          library : {
            type : 'audio'
          },
          multiple: (hostpn_audios_block.attr('data-hostpn-multiple') == 'true') ? 'true' : 'false',
        });

        audio_frame.states.add([
          new wp.media.controller.Library({
            id: 'post-gallery',
            title: (hostpn_audios_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_audios : hostpn_i18n.select_audio,
            priority: 20,
            toolbar: 'main-gallery',
            filterable: 'uploaded',
            library: wp.media.query(audio_frame.options.library),
            multiple: (hostpn_audios_block.attr('data-hostpn-multiple') == 'true') ? 'true' : 'false',
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
          hostpn_audios_block.html('');

          $(attachments_arr).each(function(e){
            sep = (e != (attachments_arr.length - 1))  ? ',' : '';
            ids += $(this)[0].id + sep;
            hostpn_audios_block.append('<div class="hostpn-audio hostpn-tooltip" title="' + $(this)[0].title + '"><i class="dashicons dashicons-media-audio"></i></div>');
          });

          $('.hostpn-tooltip').tooltipster({maxWidth: 300,delayTouch:[0, 4000]});
          hostpn_input_btn.text((hostpn_audios_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_audios : hostpn_i18n.select_audio);
          hostpn_audios_input.val(ids);
        });
      });
    }

    if ($('.hostpn-video-btn').length) {
      var video_frame;

      $(document).on('click', '.hostpn-video-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (video_frame){
          video_frame.open();
          return;
        }

        var hostpn_input_btn = $(this);
        var hostpn_videos_block = hostpn_input_btn.closest('.hostpn-videos-block').find('.hostpn-videos');
        var hostpn_videos_input = hostpn_input_btn.closest('.hostpn-videos-block').find('.hostpn-video-input');

        var video_frame = wp.media({
          title: (hostpn_videos_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_videos : hostpn_i18n.select_video,
          library : {
            type : 'video'
          },
          multiple: (hostpn_videos_block.attr('data-hostpn-multiple') == 'true') ? 'true' : 'false',
        });

        video_frame.states.add([
          new wp.media.controller.Library({
            id: 'post-gallery',
            title: (hostpn_videos_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_videos : hostpn_i18n.select_video,
            priority: 20,
            toolbar: 'main-gallery',
            filterable: 'uploaded',
            library: wp.media.query(video_frame.options.library),
            multiple: (hostpn_videos_block.attr('data-hostpn-multiple') == 'true') ? 'true' : 'false',
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
          hostpn_videos_block.html('');

          $(attachments_arr).each(function(e){
            sep = (e != (attachments_arr.length - 1))  ? ',' : '';
            ids += $(this)[0].id + sep;
            hostpn_videos_block.append('<div class="hostpn-video hostpn-tooltip" title="' + $(this)[0].title + '"><i class="dashicons dashicons-media-video"></i></div>');
          });

          $('.hostpn-tooltip').tooltipster({maxWidth: 300,delayTouch:[0, 4000]});
          hostpn_input_btn.text((hostpn_videos_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_videos : hostpn_i18n.select_video);
          hostpn_videos_input.val(ids);
        });
      });
    }

    if ($('.hostpn-file-btn').length) {
      var file_frame;

      $(document).on('click', '.hostpn-file-btn', function(e){
        e.preventDefault();
        e.stopPropagation();
        e.stopImmediatePropagation();

        if (file_frame){
          file_frame.open();
          return;
        }

        var hostpn_input_btn = $(this);
        var hostpn_files_block = hostpn_input_btn.closest('.hostpn-files-block').find('.hostpn-files');
        var hostpn_files_input = hostpn_input_btn.closest('.hostpn-files-block').find('.hostpn-file-input');

        var file_frame = wp.media({
          title: (hostpn_files_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_files : hostpn_i18n.select_file,
          multiple: (hostpn_files_block.attr('data-hostpn-multiple') == 'true') ? 'true' : 'false',
        });

        file_frame.states.add([
          new wp.media.controller.Library({
            id: 'post-gallery',
            title: (hostpn_files_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.select_files : hostpn_i18n.select_file,
            priority: 20,
            toolbar: 'main-gallery',
            filterable: 'uploaded',
            library: wp.media.query(file_frame.options.library),
            multiple: (hostpn_files_block.attr('data-hostpn-multiple') == 'true') ? 'true' : 'false',
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
          hostpn_files_block.html('');

          $(attachments_arr).each(function(e){
            sep = (e != (attachments_arr.length - 1))  ? ',' : '';
            ids += $(this)[0].id + sep;
            hostpn_files_block.append('<embed src="' + $(this)[0].url + '" type="application/pdf" class="hostpn-embed-file"/>');
          });

          hostpn_input_btn.text((hostpn_files_block.attr('data-hostpn-multiple') == 'true') ? hostpn_i18n.edit_files : hostpn_i18n.edit_file);
          hostpn_files_input.val(ids);
        });
      });
    }

    $(document).on('change', '#hostpn_country', function(e) {
      hostpn_select_country();
    });

    $(document).on('change', '#hostpn_identity', function(e) {
      hostpn_select_identity();
    });

    // UNIFIED SEARCH FUNCTIONALITY FOR ALL CPTS
    if (typeof hostpn_cpts !== 'undefined') {
      // Initialize search functionality for each CPT
      Object.keys(hostpn_cpts).forEach(function(cptKey) {
        var cptName = hostpn_cpts[cptKey];
        var searchToggleSelector = '.hostpn-' + cptKey + '-search-toggle';
        var searchInputSelector = '.hostpn-' + cptKey + '-search-input';
        var searchWrapperSelector = '.hostpn-' + cptKey + '-search-wrapper';
        var listSelector = '.hostpn-hostpn_' + cptKey + '-list';
        var listWrapperSelector = '.hostpn-hostpn_' + cptKey + '-list-wrapper';
        var addNewSelector = '.hostpn-' + cptKey + '[data-hostpn_' + cptKey + '-id="0"]';

        // Only initialize if elements exist
        if ($(searchToggleSelector).length) {
          
          // Toggle search input visibility
          $(document).on('click', searchToggleSelector, function(e) {
            e.preventDefault();
            e.stopPropagation();
            e.stopImmediatePropagation();

            var searchToggle = $(this);
            var searchInput = searchToggle.siblings(searchInputSelector);
            var searchWrapper = searchToggle.closest(searchWrapperSelector);
            var list = searchToggle.closest(listSelector);
            var listWrapper = list.find(listWrapperSelector);
            var itemsList = listWrapper.find('ul');

            if (searchInput.hasClass('hostpn-display-none')) {
              // Show search input
              searchInput.removeClass('hostpn-display-none').focus();
              searchToggle.text('close');
              searchWrapper.addClass('hostpn-search-active');
            } else {
              // Hide search input and clear filter
              searchInput.addClass('hostpn-display-none').val('');
              searchToggle.text('search');
              searchWrapper.removeClass('hostpn-search-active');
              
              // Show all items
              itemsList.find('li').show();
            }
          });

          // Filter items on keyup
          $(document).on('keyup', searchInputSelector, function(e) {
            var searchInput = $(this);
            var searchTerm = searchInput.val().toLowerCase().trim();
            var list = searchInput.closest(listSelector);
            var listWrapper = list.find(listWrapperSelector);
            var itemsList = listWrapper.find('ul');
            var items = itemsList.find('li:not(' + addNewSelector + ')');

            if (searchTerm === '') {
              // Show all items when search is empty
              items.show();
              // Also show the "Add new" item
              itemsList.find(addNewSelector).show();
            } else {
              // Filter items based on title
              items.each(function() {
                var itemTitle = $(this).find('.hostpn-display-inline-table a span').first().text().toLowerCase();
                if (itemTitle.includes(searchTerm)) {
                  $(this).show();
                } else {
                  $(this).hide();
                }
              });
              // Hide the "Add new" item when filtering
              itemsList.find(addNewSelector).hide();
            }
          });

          // Close search on escape key
          $(document).on('keydown', searchInputSelector, function(e) {
            if (e.keyCode === 27) { // Escape key
              var searchInput = $(this);
              var searchToggle = searchInput.siblings(searchToggleSelector);
              var searchWrapper = searchInput.closest(searchWrapperSelector);
              var list = searchInput.closest(listSelector);
              var listWrapper = list.find(listWrapperSelector);
              var itemsList = listWrapper.find('ul');

              searchInput.addClass('hostpn-display-none').val('');
              searchToggle.text('search');
              searchWrapper.removeClass('hostpn-search-active');
              
              // Show all items
              itemsList.find('li').show();
            }
          });
                }
      });

      // Single unified click outside handler for all search wrappers
      $(document).on('click', function(e) {
        var clickedInsideSearch = false;
        var activeSearchInput = null;
        var activeSearchToggle = null;
        var activeSearchWrapper = null;
        var activeList = null;
        var activeListWrapper = null;
        var activeItemsList = null;

        // Check if clicked inside any search wrapper
        Object.keys(hostpn_cpts).forEach(function(cptKey) {
          var searchWrapperSelector = '.hostpn-' + cptKey + '-search-wrapper';
          var searchInputSelector = '.hostpn-' + cptKey + '-search-input';
          var searchToggleSelector = '.hostpn-' + cptKey + '-search-toggle';
          var listSelector = '.hostpn-hostpn_' + cptKey + '-list';
          var listWrapperSelector = '.hostpn-hostpn_' + cptKey + '-list-wrapper';

          if ($(e.target).closest(searchWrapperSelector).length) {
            clickedInsideSearch = true;
          }

          // Find active search input
          var searchInput = $(searchInputSelector + ':not(.hostpn-display-none)');
          if (searchInput.length && !activeSearchInput) {
            activeSearchInput = searchInput;
            activeSearchToggle = searchInput.siblings(searchToggleSelector);
            activeSearchWrapper = searchInput.closest(searchWrapperSelector);
            activeList = searchInput.closest(listSelector);
            activeListWrapper = activeList.find(listWrapperSelector);
            activeItemsList = activeListWrapper.find('ul');
          }
        });

        // Close search if clicked outside
        if (!clickedInsideSearch && activeSearchInput) {
          activeSearchInput.addClass('hostpn-display-none').val('');
          activeSearchToggle.text('search');
          activeSearchWrapper.removeClass('hostpn-search-active');
          
          // Show all items
          activeItemsList.find('li').show();
        }
      });
      
    }
  });

  $(document).on('click', '.hostpn-toggle', function(e) {
    e.preventDefault();
    e.stopPropagation();
    e.stopImmediatePropagation();

    var hostpn_toggle = $(this);

    if (hostpn_toggle.find('i').length) {
      if (hostpn_toggle.siblings('.hostpn-toggle-content').is(':visible')) {
        hostpn_toggle.find('i').text('add');
      }else{
        hostpn_toggle.find('i').text('clear');
      }
    }

    hostpn_toggle.siblings('.hostpn-toggle-content').fadeToggle();
  });
})(jQuery);
