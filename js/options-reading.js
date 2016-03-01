/* global jQuery, ccp_front_page_vars */

jQuery( document ).ready( function( $ ) {

	var checked = ( 'ccp' === ccp_front_page_vars.show_on_front ) ? 'checked="checked"' : '',
	    markup  = '<p><label><input name="show_on_front" type="radio" value="ccp" class="tog" ' + checked + ' /> ' + ccp_front_page_vars.label + '</label></p>';

	$( 'input[name="show_on_front"]' ).first().closest( 'p' ).after( markup );

} );
