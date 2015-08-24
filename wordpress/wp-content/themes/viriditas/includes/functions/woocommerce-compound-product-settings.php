<?php
class WC_Settings_Woocommerce_Compound_Settings {

    /**
     * Bootstraps the class and hooks required actions & filters.
     *
     */
    public static function init() {
        add_filter( 'woocommerce_settings_tabs_array', __CLASS__ . '::add_settings_tab', 50 );
        add_action( 'woocommerce_settings_tabs_settings_tab_compound', __CLASS__ . '::settings_tab' );
        add_action( 'woocommerce_update_options_settings_tab_compound', __CLASS__ . '::update_settings' );
    }
    
    
    /**
     * Add a new settings tab to the WooCommerce settings tabs array.
     *
     * @param array $settings_tabs Array of WooCommerce setting tabs & their labels, excluding the Subscription tab.
     * @return array $settings_tabs Array of WooCommerce setting tabs & their labels, including the Subscription tab.
     */
    public static function add_settings_tab( $settings_tabs ) {
        $settings_tabs['settings_tab_compound'] = __( 'Compound settings', 'woocommerce-settings-tab-compound' );
        return $settings_tabs;
    }


    /**
     * Uses the WooCommerce admin fields API to output settings via the @see woocommerce_admin_fields() function.
     *
     * @uses woocommerce_admin_fields()
     * @uses self::get_settings()
     */
    public static function settings_tab() {
        woocommerce_admin_fields( self::get_settings() );
    }

    /**
     * Uses the WooCommerce options API to save settings via the @see woocommerce_update_options() function.
     *
     * @uses woocommerce_update_options()
     * @uses self::get_settings()
     */
    public static function update_settings() {
        woocommerce_update_options( self::get_settings() );
    }


    /**
     * Get all the settings for this plugin for @see woocommerce_admin_fields() function.
     *
     * @return array Array of settings for @see woocommerce_admin_fields() function.
     */
    public static function get_settings() {

        $settings = array(
            'section_title' => array(
                'name'     => __( 'Compound Settings', 'woocommerce-settings-tab-compound' ),
                'type'     => 'title',
                'desc'     => '',
                'id'       => 'wc_settings_tab_compound_section_title'
            ),
            'sizes' => array(
                'name' => __( 'Size/Price/Extra fee/Double Extra fee', 'woocommerce-settings-tab-compound' ),
                'type' => 'textarea',
                'desc' => __( 'Please enter size, price, extra fee, double extra fee in this format (500 mL/60/5/10). For multiple sizes use comma(,) to separate.', 'woocommerce-settings-tab-compound' ),
                'id'   => 'wc_settings_tab_compound_sizes',
				'css' =>'min-width:500px;min-height:100px;'
            ),
			// 'compounding_service_fee' => array(
                // 'name' => __( 'Compounding service fee', 'woocommerce-settings-tab-compound' ),
                // 'type' => 'text',
                // 'desc' => __( 'Please enter compounding service fee.', 'woocommerce-settings-tab-compound' ),
                // 'id'   => 'wc_settings_tab_compound_service_fee',
				// 'css' =>'min-width:500px;min-height:100px;'
            // ),
            'section_end' => array(
                 'type' => 'sectionend',
                 'id' => 'wc_settings_tab_compound_section_end'
            )
        );

        return apply_filters( 'wc_settings_tab_compound_settings', $settings );
    }

}

WC_Settings_Woocommerce_Compound_Settings::init();