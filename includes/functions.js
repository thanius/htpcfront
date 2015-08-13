$(document).ready(function() {
  var PAGE, button_cnt, current_btn, total_btns;
  var removed_buttons = [];

  // Disable right-clicking
  document.oncontextmenu = function() {return false;};

  // Reset Google search form when backing in browser
  $(window).bind('pageshow', function() {
    var form = $('form');
    form[0].reset();
  });

  // -- BEGIN CONFIGURATION PAGE --

  // Insert localized title
  $('#config_title').append(CONFIGURATION);

  $(document).on('blur', ':input.config_input', function() {
    var button_id = $(this).attr('id').replace('_url', '');
    $('#' + button_id).val($('#' + button_id + '_image').attr('src').split('/').pop() + ';' + $('#' + button_id + '_url').val());
    $('#save_button').removeClass('fade-out').addClass('fade-in').css('display', 'block');
  });

  // Upload image when file selection dialog is closed
  $(document).on('change', ':file', function() {
    var filename = $(this).val().split('\\').pop(),
    filesize=Math.floor(this.files[0].size/1024),
    extension = filename.replace(/^.*\./, '').toLowerCase(),
    valid_extensions = ['jpg', 'jpeg', 'png', 'gif'],
    ERROR = 0;

    // Report and cancel is anything is bogus
    if ($.inArray(extension, valid_extensions) === -1) {
      ERROR = 1;
      alert(UPLOAD_ERROR_FILETYPE);
    } else if (filesize>2000) {
      ERROR = 1;
      alert(UPLOAD_ERROR_FILESIZE);
    } 

    if (ERROR != 1) {
      var button_id = $(this).attr('id').replace('_input', '');

      // Create our iframe for uploading asynchronously
      $('#main').append("<iframe id='upload_frame' name='upload_frame' src='' style='display: none;'></iframe>");

      // Begin upload
      this.form.submit();
              
      // When upload is complete, change button image to new
      $('#upload_frame').load(function(){
        $('#' + button_id + '_image').attr('src', 'buttons/' + filename);
        $('#' + button_id).val(filename + ';' + $('#' + button_id + '_url').val());
        $('#upload_frame').remove();
      });
    }

    $('#save_button').removeClass('fade-out').addClass('fade-in').css('display', 'block');
  });

  // Load all buttons from configuration file and inject into placeholders
  var conf_file = 'includes/buttons.txt';
  $('<div />').load(conf_file, function(data){
    if(data.length > 0) {
      var line = data.split('\n'),
      config_lines = line.length -1,
      filename = '';
  
      for( i=0; i <= config_lines; i++ ){
        if (line[i].length > 0) {
          filename = line[i].split(';')[0];
          button_url = line[i].split(';')[1];

          total_btns = i+1;
          button_cnt = total_btns;

          $('#button' + button_cnt + '_cell').css('display','inline-block');
          $('#button' + button_cnt + '_image').attr('src','buttons/' + filename);
          $('#button' + button_cnt + '_url').attr('value',button_url);
          $('#button' + button_cnt).attr('value',filename + ';' + button_url);
        }
      }

    } else { total_btns = 0 }

    // Check if total buttons are 20, if not, append addchannel-button
    if ( total_btns < 20 ) {
      $('#configbutton_cell_container').append('\
	  <li class="configbutton_cell unsortable" id="add_channel_cell" style="margin-left: 1%; cursor: pointer;">\
	    <div class="bkg" style="border: 2px dotted #555; background: none;">\
	      <div class="wrapper">\
	        <img src="images/add_channel.png" id="add_channel" alt="">\
	      </div>\
	    </div>\
	  </li>\
      ');
    }

    // Add placeholder, make addchannel button not sortable and change cursor when dragging
    $('#configbutton_cell_container').sortable({ placeholder: 'ui-sortable-placeholder', items: 'li:not(.unsortable)', cursor: "move" });

    // Disable dragging of images
    $('#configbutton_cell_container').disableSelection();
    $('img').on('dragstart', function(e) { e.preventDefault(); });

    // Highlight add channel button when mouse over
    $(document).on('mouseenter', '#add_channel_cell', function() {
      $('#add_channel').css('opacity','1');
      $('#add_channel').css('-webkit-transform', 'translateZ(0%)');
      $('#add_channel').css('-webkit-transition', 'all 0.5s ease-in');
    });

    $(document).on('mouseleave', '#add_channel_cell', function() {
      $('#add_channel').css('opacity','0.25');
      $('#add_channel').css('-webkit-transform', 'translateZ(0%)');
      $('#add_channel').css('-webkit-transition', 'all 0.5s ease-out');
    });
  
    // Detach add channel button, keeping data in DOM, then reattach
    var addChannelCell = $('#add_channel_cell').detach();
    if(total_btns < 20) { $('#configbutton_cell_container').append(addChannelCell); }

    // ADD CHANNEL BUTTON CLICKED
    $(document).on('click', '#add_channel_cell', function() {
      total_btns = total_btns + 1;

      // If a button was removed, reuse that button and remove from array. If not, display next one on list
      button_added = removed_buttons.shift();
      if (typeof button_added === 'undefined') { button_added = 'button' + total_btns + '_cell' }

      $('#configbutton_cell_container').find('li#' + button_added).appendTo('#configbutton_cell_container');
      $('#' + button_added).addClass('fade-in');
      $('#' + button_added).css('display','inline-block');

      $('#add_channel_cell').detach();

      setTimeout(function() {
        $('#' + button_added).removeClass('fade-in');
      }, 550);

      if(total_btns < 20) { $('#configbutton_cell_container').append(addChannelCell); }
    });

    // REMOVE CHANNEL BUTTON CLICKED
    $(document).on('click', '.remove_channel', function() {
      total_btns = total_btns - 1;

      // Add removed button to array
      button_removed = $(this).parentsUntil('#configbutton_cell_container').closest('li').attr('id');
      removed_buttons.push(button_removed);

      // Move removed buttons to end of list
      $('#configbutton_cell_container').find('li#' + button_removed).appendTo('#configbutton_cell_container');

      // Clear button
      $('#' + button_removed).find('input:text').val('');
      $('#' + button_removed).find('input:hidden').val('');
      $('#' + button_removed).find('.addbutton').attr('src','images/add_button.png');
      $('#' + button_removed).css('display','none');

      if(total_btns < 20) {  $('#configbutton_cell_container').append(addChannelCell); }

      if(total_btns < 1) {
        $('#save_button').removeClass('fade-in').addClass('fade-out');

        setTimeout(function() {
          $('#save_button').css('display','none');
        },550);
      }

    });

    // Cancel when clicking config button
    $(document).on('click', '#config_button', function() {
      PAGE = '#channels'
      $('.flipper.powerconfig').removeClass('flipped');
      $('#config_frame').addClass('fade-out');

      setTimeout(function() {
        $('#channels').removeClass('fade-out').css('display','block');
        $('#google_button').removeClass('fade-out').css('display','block');
        $('#config_frame').css('display','none');
        $('#channels').removeClass('fade-out').addClass('fade-in');
      }, 550);
    });
    
    // Save data and order of buttons when clicking save button
    $(document).on('click', '#save_button', function() {

      // Purge any empty URL and file inputs
      $(':input[value=""]').attr('disabled', 'disabled');
      $('.button_input').each(function(){
        if ($(this).val().indexOf('add_button.png') === 0) { $(this).attr('disabled', 'disabled'); }
    });

    // Clean up the string data and serialize into variable, then create iframe to push config to
    var button_data = $('#configbutton_cell_container :input.button_input').serialize().replace(/&/g, '+');
    $('body').append('<iframe id="write_config_frame" name="write_config_frame" src="includes/write_config.php?config=' + button_data + '" style="display:none;"></iframe>');

    // Fade in flashbang
    $('#main').removeClass('fade-in').addClass('fade-out');

        // Reload page after saving and animation is complete
        setTimeout(function() {
          window.location.reload();
        }, 2000);

      });

  // -- END CONFIGURATION PAGE --


  // -- BEGIN MAIN PAGE --

  // POWEROFF/CONFIG BUTTONS
  $('#powerbutton').click(function() {
    $('#shutdown_container').css('display', 'block');
  });

  $('#powerbutton').mousedown(function(e){ 
    if(e.button === 2 && PAGE != '#config_frame') {
      PAGE = '#config_frame'
      $('#channels').addClass('fade-out');
      $('#google_button').addClass('fade-out');

      if (total_btns > 0) {
        $('#save_button').removeClass('fade-out').addClass('fade-in').css('display', 'block');
      }

      setTimeout(function() {
        $('#channels').css('display','none');
        $('#google_button').css('display','none');
        $('#config_frame').removeClass('fade-out').css('display','table-cell');
      }, 550);

      $('.flipper.powerconfig').addClass('flipped');
      return false; 
    } 

    return true; 
  });

  // PAGE SWITCH BUTTON (GOOGLE/CHANNELS)
  $('#google_button').click(function() {
    PAGE = '#google_frame';
    $('#channels').addClass('fade-out');

    setTimeout(function() {
      $('#channels').css('display','none');
      $('#google_frame').removeClass('fade-out').css('display','block');
    }, 550);

   $('.flipper.google').addClass('flipped');
   $('.powerconfig').css('display','none');
  });

  $('#home_button').click(function() {
    PAGE = '#channels';
    $('#google_frame').addClass('fade-out');

    setTimeout(function() {
      $('#google_frame').css('display','none');
      $('#channels').removeClass('fade-out').css('display','block');
      $('.powerconfig').css('display','block');
    }, 550);

    $('.flipper.google').removeClass('flipped');
  });

  // Check for config file on start
  $.ajax({
    url:'includes/buttons.txt',
    type:'HEAD',
     
    // Config file missing, redirect to config page
    error: function() { PAGE = '#config_frame'; },

    // Config file found, load buttons
    success: function() {

      // Check if config file is empty
      $.get('includes/buttons.txt', function(data) {
        if(data.length > 0){
           PAGE = '#channels'

          // Add small delay so that we can fade out the page when clicking a button
          $(document.body).on('click', 'a' ,function(e){
            e.preventDefault();
            var goTo = this.getAttribute('href');
            $('#flashbang').css('display','none');
            $('#main').removeClass('fade-in').addClass('fade-out');
 
            setTimeout(function(){
              window.location = goTo;
            },3000);
          });
        } else {
          $('.flipper.powerconfig').removeClass('flipped');
          $('#powerbutton').css('opacity','1');
          $('#google_button').css('display','none');
          PAGE = '#config_frame'
        }
      });
    }
  });

  // Fade-in first page
  setTimeout(function() {
    $(PAGE).removeClass('fade-out').css('display','block');

    // When channels (or config) are loaded, disable clickkiller
    setTimeout(function() {
      $('#clickkiller').css('display','none');
    }, 1500);
  }, 1000);
  });

  // -- BEGIN SHUTDOWN PAGE --
  $('#shutdown_dialog').append('\
	<div class="subpage_title">'+ SHUTDOWN +'</div><br>\
	<span id="shutdown_dialog_ok" class="shutdown_dialog_button">'+ OK +'</span><br>\
	<span id="shutdown_dialog_cancel" class="shutdown_dialog_button">'+ CANCEL +'</span><br>\
  ');

  // When clicking 'OK', fade out to black and then run shutdown script through PHP
  $(document).on('click', '#shutdown_dialog_ok', function() {
    $('#clickkiller').css('display','block');
    $('#flashbang').css('display','none');
    $('#shutdown_container').addClass('fade-out');
      
    setTimeout(function() {
      $('#main').addClass('fade-out');
      $('#shutdown_container').css('display','none').removeClass('fade-out');
    }, 500);

    setTimeout(function() {
      $('#main').css('display','none');
      window.location = 'includes/shutdown.php';
    }, 1500);
  });

  // Cancel shutdown when clicking 'CANCEL'
  $(document).on('click', '#shutdown_dialog_cancel', function() {
    $('#shutdown_container').addClass('fade-out');
      
    setTimeout(function() {
      $('#shutdown_container').css('display','none').removeClass('fade-out');
    }, 550);
  });
});
