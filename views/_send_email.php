<? 

	$db = new DB;
	$store = new Store( $db );

	if( $_POST['email'] ) {
		
		// send confirmation emails
		$to = 'chris@mcwfurniture.com';
		$subject = 'Newsletter Signup';
		$body = 'This email has signed up for your newsletter: ' . $_POST['email'];
		$store->send_mail( $to, $body, $subject, 'store@boxworkcabinets.com', 'Boxwork Store' );
		
		echo( 'success' );

	} else {
		
		echo( 'error' );
		
	}
		
?>