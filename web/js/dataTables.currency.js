jQuery.fn.dataTableExt.oSort['currency-asc'] = function(a,b) {
	var x = a;
	var y = b;

        /* Remove any commas (assumes tx = a.replace( /./g, "" );hat if present all strings will have a fixed number of d.p) */
	x = x.replace('.','');
        y = y.replace('.','');

	/* Remove the currency sign */
	x = x.substring( 1 );
	y = y.substring( 1 );

	/* Parse and return */
	x = parseFloat( x );
	y = parseFloat( y );
	return x - y;
};

jQuery.fn.dataTableExt.oSort['currency-desc'] = function(a,b) {
	var x = a;
	var y = b;

        /* Remove any commas (assumes tx = a.replace( /./g, "" );hat if present all strings will have a fixed number of d.p) */
	x = x.replace('.','');
        y = y.replace('.','');

	/* Remove the currency sign */
	x = x.substring( 1 );
	y = y.substring( 1 );

	/* Parse and return */
	x = parseFloat( x );
	y = parseFloat( y );
	return y - x;
};