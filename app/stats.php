<?php
if( !class_exists('PitchPro_Stats') ){
    class PitchPro_Stats {
        public function pitch_full_stats( $campaign_id = null ){
            $stats = array(
                'draft' => array( 'count' => 0, 'money' => '0' ),
                'sent' => array( 'count' => 0, 'money' => '0' ),
                'opened' => array( 'count' => 0, 'money' => '0' ),
                'viewed' => array( 'count' => 0, 'money' => '0' ),
                'accept' => array( 'count' => 0, 'money' => '0' ),
                'decline' => array( 'count' => 0, 'money' => '0' ),
                'expire' => array( 'count' => 0, 'money' => '0' ),
                'paid' => array( 'count' => 0, 'money' => '0' ),
                'total' => array( 'count' => 0, 'money' => '0' )
            );
            if( !is_null( $campaign_id ) ){
                $query_pitches = new WP_Query(array(
                    'post_type' => PitchPro_Pitch::POSTTYPE,
                    'meta_key' => 'associated_campaign',
                    'meta_value' => $campaign_id
                ));
                $stats['paid'] = self::pitch_payment_paid($campaign_id);
                $stats['total']['count'] = $query_pitches->post_count;

            }
            return $stats;
        }

        private function pitch_payment_paid( $campaign_id = null ){
            $args = array(
                'post_type' => PitchPro_Pitch::POSTTYPE,
                'meta_query' => array(
            		'relation' => 'AND',
            		array(
            			'key'     => 'associated_campaign',
            			'value'   => $campaign_id,
            			'compare' => '='
            		),
            		array(
            			'key'     => 'payment_status',
            			'value'   => 'sent',
            			'compare' => '='
            		)
                )
            );
            $query_pitches = new WP_Query( $args );
            $money = 0;
            foreach( $query_pitches->posts as $pitch ){
                $money += (int) get_post_meta( 'payout_amount', $pitch->ID, true );
            }
            return array( 'count' => $query_pitches->post_count, 'money' => $money);
        }

    }
}
