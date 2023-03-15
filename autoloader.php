<?php

spl_autoload_register( 'dsm_namespace_autoload' );

function dsm_namespace_autoload( $class_name )
{
    if ( false === strpos( $class_name, 'DanceStudioManager' ))
        return;
    $file_parts = explode( '\\', $class_name );
    $namespace = '';
    for ( $i = count( $file_parts ) - 1; $i > 0; $i-- ) {
        $current = $file_parts[ $i ] ;
        $current = str_ireplace( 'DanceStudioManager', '', $current );
        if ( count( $file_parts ) - 1 === $i ) {
            if ( false !== strpos( $current, 'Widget' ))
                $file_name = "classes/widgets/$current.php";
            else if ( false !== strpos( $current, 'Controller' ))
                $file_name = "classes/controllers/$current.php";
            else
                $file_name = "classes/$current.php";
        } else {
            $namespace = '/' . $current . $namespace;
        }
    }

    $filepath  = trailingslashit( dirname( __FILE__ ) . $namespace );
    $filepath .= $file_name;
  
    if ( file_exists( $filepath ) ) {
        include_once( $filepath );
    } else {
        wp_die(
            esc_html( "The file attempting to be loaded at $filepath does not exist." )
        );
    }
}