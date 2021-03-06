$(document).ready(function(){
  $('#progressBar').removeAttr('hidden');
  // uuid = $('#progressBar').data('progress_bar_uuid');
  // form submission
  $('form').submit(function(){
    // Prevent multiple submits
    // if ($.data(this, 'submitted')) return false;
    // Append X-Progress-ID uuid form action
    // this.action += (this.action.indexOf('?') == -1 ? '?' : '&') + 'X-Progress-ID=' + uuid;
    // Update progress bar
    function update_progress_info() {
      // $('#progressBar').removeAttr('hidden');
      $.getJSON(upload_progress_url, function(data){
        //console.log(data);
        if(data){
          $('#progressBar').removeAttr('hidden');  // show progress bar if there are datas
          var progress = parseInt(data.uploaded_s3, 10)/parseInt(data.length_s3, 10)*100;
          $('#progressBar').attr('value', progress);
        }
        else {
          $('#progressBar').attr('hidden', '');  // hide progress bar if no datas
        }
        window.setTimeout(update_progress_info, 10000);
      });
    }
    window.setTimeout(update_progress_info, 10000);
    $.data(this, 'submitted', true); // mark form as submitted.
    return true;
  });
});