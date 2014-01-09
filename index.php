<?

session_start();

//print_r( $_GET );

include( 'admin/db.php' );
include( 'admin/form.php' );
include( 'admin/store.php' );

$action = $_GET['url']?$_GET['url']:'index';

//$secure_pages = array( 'checkout' );
//	
//if( in_array( $action, $secure_pages ) && $_SERVER['HTTPS'] != 'on' ) {
//
//	header( 'Location: https://www.boxworkcabinets.com/' . $action );
//	exit;
//
//}
//
//if( strpos( $_SERVER['SERVER_NAME'], 'www.' ) === false ) {
//
//	header( "HTTP/1.1 301 Moved Permanently" ); 
//	header( "Location: http://www.boxworkcabinets.com/" . (($action=='home')?'':$action) ); 
//	
//}


if( !$_GET['ajax'] ) {
	
	include( 'views/header.php' );
	
}

if( !file_exists( 'views/' . $action . '.php') ) {

	$action = '404';
	
}

include( 'views/' . $action . '.php' );

if( !$_GET['ajax'] ) {

	include( 'views/footer.php' );

}

?>