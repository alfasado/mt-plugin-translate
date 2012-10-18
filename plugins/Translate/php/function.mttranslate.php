<?php
function smarty_function_mttranslate ( $args, &$ctx ) {
    $app = $ctx->stash( 'bootstrapper' );
    $cookie_name = $args[ 'use_cookie' ];
    if ( $cookie_name ) {
        $lang = $_COOKIE[ $cookie_name ];
        $language = $_COOKIE[ $cookie_name ];
    } else {
        $user = $app->user;
        $headers = getallheaders();
        $lang = $headers[ 'Accept-Language' ];
        if ( preg_match( '/^j[a|p]/', $lang ) ) {
            $lang = 'ja';
        }
        $str = $args[ 'phrase' ];
        $params = $args[ 'params' ];
        if (! $lang ) {
            $lang = $app->config( 'DefaultLanguage' );
        }
        $lang = strtr( $lang, '-', '_' );
        if ( $lang === 'en_us' ) {
            $lang = 'en';
        }
        if ( $user ) {
            $language = $user->preferred_language;
            $language = strtr( $language, '-', '_' );
            if ( $language === 'en_us' ) {
                $language = 'en';
            }
        } else {
            $language = $lang;
        }
    }
    $plugin_path = dirname( __File__ ) . DIRECTORY_SEPARATOR;
    if (! file_exists( $plugin_path . DIRECTORY_SEPARATOR . 'l10n' . DIRECTORY_SEPARATOR . 'l10n_' . $language . '.php' ) ) {
        $lang = 'en';
        $language = 'en';
    }
    if ( $args[ 'debug' ] ) {
        return $language;
    }
    $Lexicon_lang = 'Lexicon_' . $lang;
    // if ( $lang === $language ) {
    //     global $$Lexicon_lang;
    // } else {
         require_once( $plugin_path . DIRECTORY_SEPARATOR . 'l10n' . DIRECTORY_SEPARATOR . 'l10n_' . $language . '.php' );
         $Lexicon_lang = 'Lexicon_' . $language;
         global $$Lexicon_lang;
    //}
    $l10n_str = isset( ${$Lexicon_lang}[ $str ] ) ? ${$Lexicon_lang}[ $str ] : ( isset( $Lexicon[ $str ] ) ? $Lexicon[ $str ] : $str );
    if ( extension_loaded( 'mbstring' ) ) {
        $str = mb_convert_encoding( $l10n_str, mb_internal_encoding(), "UTF-8" );
    } else {
        $str = $l10n_str;
    }
    if ( $params ) {
        $params = str_getcsv( $params, ':' );
        if (! is_array( $params ) ) {
            $params = array( $params );
        }
    }
    if ( is_array( $params ) && ( strpos( $str, '[_' ) !== FALSE ) ) {
        for ( $i = 1; $i <= count( $params ); $i++ ) {
            $str = preg_replace( "/\\[_$i\\]/", $params[ $i - 1 ], $str );
        }
    }
    return $str;
}
?>