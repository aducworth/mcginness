<?

session_start();

//print_r( $_GET );

include( 'admin/db.php' );
include( 'admin/form.php' );
include( 'admin/store.php' );

$action = $_GET['url']?$_GET['url']:'index';

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