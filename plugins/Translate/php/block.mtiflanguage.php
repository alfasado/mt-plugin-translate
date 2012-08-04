<?php
function smarty_block_mtiflanguage ( $args, $content, &$ctx, &$repeat ) {
    $args[ 'debug' ] = 1;
    require_once( 'function.mttranslate.php' );
    $lang = smarty_function_mttranslate( $args, $ctx );
    if ( $lang === $args[ 'language' ] ) {
        return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, TRUE );
    } else {
        return $ctx->_hdlr_if( $args, $content, $ctx, $repeat, FALSE );
    }
}
?>