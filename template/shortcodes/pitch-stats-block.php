<div class="entry">
    <div class="ubtn-ctn-center">
            <a class="ubtn-link ult-adjust-bottom-margin ubtn-left ubtn-large"
            href="/app/pitch-create/?c=<?php echo get_the_ID(); ?>" target=""><button class=
            "ubtn ult-adjust-bottom-margin ult-responsive ubtn-large ubtn-no-hover-bg none ubtn-sep-icon ubtn-sep-icon-at-left ubtn-left tooltip-57d8b32f31ce0"
            data-bg="#25a249" data-border-color="" data-border-hover="" data-hover=
            "" data-hover-bg="" data-responsive-json-new=
            "{&quot;font-size&quot;:&quot;&quot;,&quot;line-height&quot;:&quot;&quot;}"
            data-shadow="" data-shadow-click="none" data-shadow-hover=""
            data-shd-shadow="" data-ultimate-target="#ubtn-3191" id="ubtn-3191"
            style=
            "font-weight:normal;border:none;background: #25a249;color: #ffffff;"
            type="button"><span class="ubtn-data ubtn-icon"><i class=
            "Defaults-sign-in" style=
            "font-size:32px;color:#ffffff;"></i></span><span class="ubtn-hover"
            style="background-color:"></span><span class="ubtn-data ubtn-text">Create Pitch</span></button></a>
        </div>
    <!-- <div class="wpb_wrapper">
    <div class="vc_chart vc_round-chart wpb_content_element" data-vc-tooltips="1" data-vc-animation="easeinOutCubic" data-vc-stroke-color="#ffffff" data-vc-stroke-width="2" data-vc-type="doughnut" data-vc-values='[{"value":60,"color":"#5472d2","highlight":"#3c5ecc","label":"One"},{"value":40,"color":"#fe6c61","highlight":"#fe5043","label":"Two"}]'>

    	<div class="wpb_wrapper">
    		<canvas class="vc_round-chart-canvas" width="218" height="218" style="width: 218px; height: 218px;"></canvas>
    	</div>
    </div>
    </div> -->
<table>
    <thead>
        <tr>
            <th>Status</th>
            <th>Amount</th>
            <th>Budget</th>
        </tr>
    </thead>
    <tbody><?php

        foreach( $stat_list as $key => $stat ) {

            if( $stat['link'] != null ) {
                printf('<tr><td><a href="%s">%s</a></td><td><a href="%s">%s</a></td><td>%s</td></tr>', $stat['link'], $stat['label'], $stat['link'],  $pitch_stats[ $key ]['count'], '$' . money_format('%i', $pitch_stats[ $key ]['money'] ) );
            } else {
                printf('<tr><td>%s</td><td>%s</td><td>%s</td></tr>', $stat['label'], $pitch_stats[ $key ]['count'], '$' . money_format('%i', $pitch_stats[ $key ]['money'] ) );
            }

        }

    ?></tbody>
</table>
</div>
