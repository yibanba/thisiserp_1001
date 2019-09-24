<?php

    echo '<h5>http://thisiserp/</h5>';

    if ( $handle = opendir('.') ) {

        echo '<ol>';

        while ( false !== ( $file = readdir($handle) )) {
            if ( ! preg_match( '/^\./', $file ) && is_dir( $file ) ) {
                printf('<li><a href="%s">%s</a>, &nbsp; DB_NANE: nb_%s', $file, $file, $file );
                if( is_dir( $file . '/wp-admin') ) {
                    printf('<li><a href="%s/wp-login.php">%s/wp-login.php</a>', $file, $file );
                }
            }
        }

        echo '</ol>';

        closedir( $handle );
    }
