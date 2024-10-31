jQuery('#sms_number_field').fadeOut();
jQuery('input#sms_checkbox').change(function(){   
     if (this.checked) {
         jQuery('#sms_number_field').fadeIn();
         jQuery('#sms_number_field input').val('');         
     } else {
         jQuery('#sms_number_field').fadeOut();
         jQuery('#sms_number_field input').val(''); 
     }
});