<?php

$pitch_guid = PitchPro_Pitch::get_the_guid( $pitch_id );

$send_pitch_url = '/app/send-pitch/?guid=' . $pitch_guid;


?><div class="ubtn-ctn-center">
        <a class="ubtn-link ult-adjust-bottom-margin ubtn-left ubtn-large"
        href="<?php echo $send_pitch_url; ?>" target=""><button class=
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
        style="background-color:"></span><span class="ubtn-data ubtn-text">Send Pitch Now</span></button></a>
    </div>
