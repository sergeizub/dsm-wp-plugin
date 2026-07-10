<?php
namespace DanceStudioManager;

if ( ! defined( 'ABSPATH' ) ) exit;

class App
{
    protected static $api;
    protected static $client;
    protected static $template;
    protected static $error;
    protected static $emailer;

    public function __construct()
    {
        self::$error = new Error();
        self::$api = new Api();
        self::$client = new Client();
        self::$template = new Template();
        self::$emailer = new Emailer();

        add_action('admin_enqueue_scripts', function ($hook) {
                wp_enqueue_style('dsm_admin', plugins_url('../css/admin.css',__FILE__ ));
            });

        add_action('wp_enqueue_scripts', function ($hook) {
                wp_register_style( 'dsm_css_bootstrap', plugins_url('../assets/bootstrap-3.3.7/css/bootstrap.min.css',__FILE__) );
                wp_register_style( 'dsm_datetimepicker', plugins_url('../assets/bootstrap-3.3.7/css/bootstrap-datetimepicker.min.css',__FILE__) );
                wp_register_style( 'dsm_fontawesome', plugins_url('../assets/font-awesome-4.7.0/css/font-awesome.min.css',__FILE__) );
                wp_register_style( 'dsm_style', plugins_url('../assets/css/style.css',__FILE__ ) );
                wp_register_style( 'dsm_style_united', plugins_url('../assets/css/style-united.css',__FILE__ ) );
                wp_register_style( 'dsm_fullcalendar', plugins_url('../assets/fullcalendar-3.9.0/fullcalendar.min.css',__FILE__) );
               
                wp_register_script( 'dsm_js_bootstrap', plugins_url('../assets/bootstrap-3.3.7/js/bootstrap.min.js',__FILE__) , array('jquery'));
                wp_register_script( 'dsm_signature_pad', plugins_url('../assets/js/signature_pad/index.js',__FILE__ ) , array('jquery'), time());
                wp_register_script( 'dsmfunctionjs', plugins_url('../assets/js/functions.js',__FILE__ ) , array('jquery') , time());

                wp_localize_script( 'dsmfunctionjs', 'dsmajax',
                    array(
                        'url' => admin_url('admin-ajax.php')
                    )
                );
                wp_register_script( 'dsm_datetimepicker', plugins_url('../assets/bootstrap-3.3.7/js/bootstrap-datetimepicker.min.js',__FILE__) , array('jquery'));
                wp_register_script( 'dsm_fullcalendar', plugins_url('../assets/fullcalendar-3.9.0/fullcalendar.min.js',__FILE__) , array('jquery'));
                wp_register_script( 'payconex-iframe-lib', 'https://secure.payconex.net/iframe/iframe-lib-1.0.0.js', array(), null, false );
            }, 20);

        add_action( 'widgets_init', function () {
                register_widget( 'DanceStudioManager\GroupclassesWidget' );
                register_widget( 'DanceStudioManager\RegisterWidget' );
                register_widget( 'DanceStudioManager\CalendarWidget' );
            });

        add_shortcode('dsm_classes_list', function ( $atts ) {

            wp_enqueue_style('dsm_css_bootstrap');
            wp_enqueue_style('dsm_datetimepicker');
            wp_enqueue_style('dsm_fontawesome');
            wp_enqueue_style('dsm_style');
            wp_enqueue_style('dsm_style_united');
            wp_enqueue_style('dsm_fullcalendar');
           
            wp_dequeue_script( 'bootstrap' );
            
            wp_enqueue_script('dsm_js_bootstrap');
            wp_enqueue_script('dsm_momentjs','https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js', array(), '2.20.1', true );
            wp_enqueue_script('dsm_signature_pad');
            wp_enqueue_script('dsmfunctionjs');
            wp_enqueue_script('dsm_datetimepicker');
            wp_enqueue_script('dsm_fullcalendar');
            
            $args = array(
                'before_widget' => '<div class="box widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title">',
                'after_title'   => '</div>',
            );

            ob_start();
            the_widget( 'DanceStudioManager\GroupclassesWidget', $atts, $args );
            $output = ob_get_clean();
            return   $output;
        });

        add_shortcode('dsm_calendar', function ( $atts ) {

            wp_enqueue_style('dsm_css_bootstrap');
            wp_enqueue_style('dsm_datetimepicker');
            wp_enqueue_style('dsm_fontawesome');
            wp_enqueue_style('dsm_style');
            wp_enqueue_style('dsm_style_united');
            wp_enqueue_style('dsm_fullcalendar');
            
            wp_dequeue_script( 'bootstrap' );

            wp_enqueue_script('dsm_js_bootstrap');
            wp_enqueue_script( 'dsm_momentjs', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js', array(), '2.20.1', true );
            wp_enqueue_script('dsm_signature_pad');
            wp_enqueue_script('dsmfunctionjs');
            wp_enqueue_script('dsm_datetimepicker');
            wp_enqueue_script('dsm_fullcalendar');
            wp_enqueue_script( 'payconex-iframe-lib' );

            $args = array(
                'before_widget' => '<div class="box widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title">',
                'after_title'   => '</div>',
            );

            ob_start();
            the_widget( 'DanceStudioManager\CalendarWidget', $atts, $args );
            $output = ob_get_clean();
            return   $output;
        });

        add_shortcode('dsm_register', function ( $atts ) {

            wp_enqueue_style('dsm_css_bootstrap');
            wp_enqueue_style('dsm_datetimepicker');
            wp_enqueue_style('dsm_fontawesome');
            wp_enqueue_style('dsm_style');
            wp_enqueue_style('dsm_style_united');
            wp_enqueue_style('dsm_fullcalendar');

            wp_dequeue_script( 'bootstrap' );

            wp_enqueue_script('dsm_js_bootstrap');
            wp_enqueue_script( 'dsm_momentjs', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js', array(), '2.20.1', true );
            wp_enqueue_script('dsm_signature_pad');
            wp_enqueue_script('dsmfunctionjs');
            wp_enqueue_script('dsm_datetimepicker');
            wp_enqueue_script('dsm_fullcalendar');
            wp_enqueue_script( 'payconex-iframe-lib' );

            $args = array(
                'before_widget' => '<div class="box widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title">',
                'after_title'   => '</div>',
            );

            ob_start();
            the_widget( 'DanceStudioManager\RegisterWidget', $atts, $args );
            $output = ob_get_clean();
            return   $output;
        });

        add_shortcode('dsm_client', function ( $atts ) {

            wp_enqueue_style('dsm_css_bootstrap');
            wp_enqueue_style('dsm_datetimepicker');
            wp_enqueue_style('dsm_fontawesome');
            wp_enqueue_style('dsm_style');
            wp_enqueue_style('dsm_style_united');
            wp_enqueue_style('dsm_fullcalendar');

            wp_dequeue_script( 'bootstrap' );

            wp_enqueue_script('dsm_js_bootstrap');
            wp_enqueue_script( 'dsm_momentjs', 'https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.20.1/moment.min.js', array(), '2.20.1', true );
            wp_enqueue_script('dsm_signature_pad');
            wp_enqueue_script('dsmfunctionjs');
            wp_enqueue_script('dsm_datetimepicker');
            wp_enqueue_script('dsm_fullcalendar');
            wp_enqueue_script( 'payconex-iframe-lib' );

            //Redirect to the https page 
            if (!is_ssl() && !empty($_SERVER['HTTP_HOST']) && !empty($_SERVER['REQUEST_URI'])) {
                return '<script>window.location.href = "https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'].'";</script>';
            }
            
            $_SESSION['dsm_client_attrs'] = array('view' => '', 'default_tab' => '');
            if (!empty($atts))
                foreach ($atts as $k_att => $att) {
                    if ($k_att == 'class_genre')
                        $k_att = 'class_name';
                    if (strpos ( $att , '|') !== false)
                        $_SESSION['dsm_client_attrs'][$k_att] = explode('|',$att);
                    else
                        $_SESSION['dsm_client_attrs'][$k_att] = sanitize_text_field($att);
                    
                    if (is_array($_SESSION['dsm_client_attrs'][$k_att]))
                        foreach($_SESSION['dsm_client_attrs'][$k_att] as $k => $v)
                            $_SESSION['dsm_client_attrs'][$k_att][$k] = sanitize_text_field($v);
                }

            ob_start();
            self::$client->Output();
            $output = ob_get_clean();
            return $output;
        });
    }

    public static function GetApi()
    {
        return self::$api;
    }

    public static function GetClient()
    {
        return self::$client;
    }

    public static function GetTemplate()
    {
        return self::$template;
    }

    public static function GetEmailer()
    {
        return self::$emailer;
    }

    public static function GetError()
    {
        return self::$error;
    }
}