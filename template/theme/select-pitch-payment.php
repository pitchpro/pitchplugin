<?php global $pitchpro_filter_payment_current; ?>
<select name="payment_status" data-guid="<?php echo $post->post_name; ?>">
    <option value="">Select a status</option>
    <?php foreach( PitchPro_Pitch::$payment_status as $value => $label ) : ?>
        <option value="<?php echo $value; ?>" <?php selected( $value, $pitchpro_filter_payment_current ); ?>><?php echo $label; ?></option>
    <?php endforeach; ?>
</select>
