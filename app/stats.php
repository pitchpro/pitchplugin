<?php
if( !class_exists('PitchPro_Stats') ){
    class PitchPro_Stats {
        public static function pitch_full_stats( $campaign_id = null ){
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
                $stats['sent'] = self::get_all_campaign_pitches_payment_by_status($campaign_id, 'sent');
                $stats['accept'] = self::get_all_campaign_pitches_payment_by_status($campaign_id, 'accept');
                $stats['decline'] = self::get_all_campaign_pitches_payment_by_status($campaign_id, 'decline');
                $stats['expire'] = self::get_all_campaign_pitches_payment_by_status($campaign_id, 'expire');
                $stats['paid'] = self::get_all_campaign_pitches_payment_paid($campaign_id);
                $stats['viewed'] = self::get_all_campaign_pitches_viewed($campaign_id);
                $stats['opened'] = self::get_all_campaign_pitches_opened($campaign_id);
                $stats['total']['count'] = $query_pitches->post_count;

            }
            return $stats;
        }

        private static function get_all_campaign_pitches_payment_by_status( $campaign_id = null, $status = 'any' ){
            $args = array(
                'is_stats_query' => true,
                'post_type' => PitchPro_Pitch::POSTTYPE,
                'post_status' => is_array($status) ? $status : array($status),
                'meta_query' => array(
            		array(
            			'key'     => 'associated_campaign',
            			'value'   => $campaign_id,
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

        private static function get_all_campaign_pitches_viewed( $campaign_id = null ){
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
            			'key'     => 'pitch_viewed',
            			'value'   => '0',
            			'compare' => '>'
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

        private static function get_all_campaign_pitches_opened( $campaign_id = null ){
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
            			'key'     => 'pitch_opened',
            			'value'   => '1',
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

        private function get_all_campaign_pitches_payment_paid( $campaign_id = null ){
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
            			'value'   => 'paid',
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
