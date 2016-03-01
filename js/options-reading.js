/* global jQuery, ccp_front_page_field */

jQuery( document ).ready( function( $ ) {

	$( 'input[name="show_on_front"]' ).first().closest( 'p' ).after( ccp_front_page_field );

} );
