<?php

global $post;
$pitch_stats = PitchPro_Pitch::get_full_stats( get_the_ID() );

 ?>
<div class="entry">
    <a href="http://pitch.pro.dev/app/pitch-create/?c=<?php echo get_the_ID(); ?>">Create Pitch</a>
    <div class="wpb_wrapper">
    <div class="vc_chart vc_round-chart wpb_content_element" data-vc-tooltips="1" data-vc-animation="easeinOutCubic" data-vc-stroke-color="#ffffff" data-vc-stroke-width="2" data-vc-type="doughnut" data-vc-values='[{"value":60,"color":"#5472d2","highlight":"#3c5ecc","label":"One"},{"value":40,"color":"#fe6c61","highlight":"#fe5043","label":"Two"}]'>

    	<div class="wpb_wrapper">
    		<canvas class="vc_round-chart-canvas" width="218" height="218" style="width: 218px; height: 218px;"></canvas>
    	</div>
    </div>
    </div>
<table>
    <thead>
        <tr>
            <th>Type</th>
            <th>Amount</th>
            <th>Budget</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Draft</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Sent</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Opened</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Pitch Viewed</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Accepted</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Declined</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Expired</td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Total</td>
            <td><a href="<?php echo get_permalink( get_page_by_path('pitches', OBJECT, PitchPro_App::POSTTYPE ) ); ?>?campaign=<?php echo $post->post_name; ?>"><?php echo $pitch_stats['total']; ?></a></td>
            <td></td>
        </tr>
    </tbody>
</table>
</div>
