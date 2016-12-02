jQuery(document).ready(function($) {

    jQuery('.pitchpro_pitch').on('change','select[name=payment_status]',function(event){
        var $this = jQuery(this);

        var data = {
            'action': 'pitchpro_pitch_mark_paid',
            'guid': $this.data('guid'),
            'status': $this.val()
        }, confirmAction = confirm("Are you sure you want to change the payment status?");;

        if( confirmAction ){

            jQuery.post(pitchpro.ajax_url, data, function( r ) {
                alert( r.message );
            }, 'json');

        }

    });

});
