var $ = jQuery;
$(document).ready(function(){
    $('#wadap_date_range').daterangepicker({
        autoUpdateInput: false,
        // minDate: moment().startOf('day').add(1, 'day'),
        minDate: moment().startOf('day'),
        locale: {
            cancelLabel: 'Clear',
            format: 'MM/DD/YYYY'
        }
    });
    $('#wadap_background, #wadap_text_color').wpColorPicker(); 
    $('#wadap_date_range').on('apply.daterangepicker', function(ev, picker) {
        $(this).val(picker.startDate.format('MM/DD/YYYY') + ' - ' + picker.endDate.format('MM/DD/YYYY'));
    });
  
    $('#wadap_date_range').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
    });

    $(document).on('submit','#save-wadap',function(e){
        e.preventDefault();
        var $error = false;
        $('.field-error').removeClass('.field-required');
        $('.save-res-msg').remove();
        $(this).find(".field-required").each(function(){
            if($(this).val() == '')
            {
                $(this).addClass('field-error');
                $error = true;
            }

            if($(this).attr('name') == 'wadap_header_message')
            {
                if($(this).val() == '')
                {
                    $('#wp-wadap_header_message-wrap').addClass('field-error');
                    $error = true;
                }
            }
        });
        if($error)
        {
            return;
        }
        var data = new FormData(this);
        $.ajax({
            type: 'POST',
            url:  localize_object.ajax_url,
            data: data,
            processData: false,
            contentType: false,
            beforeSend: function() {
                $('#save-wadap').addClass('processing');
            },
            success: function(response){
                $('#save-wadap').before("<h3 class='save-res-msg'>"+response.msg+"</h3/>")
                $('#save-wadap').removeClass('processing');
                $('.save-res-msg').delay(2000).fadeOut();
            },
            error: function (response) {
                alert('Something went wrong. Please try again letter');
                $('#save-wadap').removeClass('processing');
            }
        });
    });
  
})