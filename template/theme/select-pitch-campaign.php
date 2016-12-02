<?php global $pitchpro_filter_campaign_current; ?>
<select name="campaign" data-guid="<?php echo $post->post_name; ?>">
    <option value="">Select campaign</option>
    <?php foreach (PitchPro_Campaign::get_all_campaigns() as $campaign ) : ?>
    <option value="<?php echo $campaign->post_name; ?>" <?php selected( $campaign->post_name, $pitchpro_filter_campaign_current ); ?>><?php echo $campaign->post_title; ?></option>
    <?php endforeach; ?>
</select>
