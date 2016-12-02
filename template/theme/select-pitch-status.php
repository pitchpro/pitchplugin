<?php global $pitchpro_filter_status_current; ?>
<select name="status" data-guid="<?php echo $post->post_name; ?>">
    <option value="">Select status</option>
    <?php foreach (PitchPro_Pitch::$pitch_status as $status => $label ) : ?>
    <option value="<?php echo $status; ?>" <?php selected( $status, $pitchpro_filter_status_current ); ?>><?php echo $label; ?></option>
    <?php endforeach; ?>
</select>
