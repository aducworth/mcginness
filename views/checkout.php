<? $form = new Form; ?>

<section>

	<div class='container'>

		<h1>Your Order</h1>
		
		<h3>Order Summary</h3>
		
		<form action='/checkout' method='post'>
		
		<div class='cart-contents'>
		
			<p>Loading...</p>
			
		
		</div>
		
		<div id='order-input'>
		
			<div class='discount-container'>
			
				<h3>Discount Codes</h3>
		
				<input type='text' id='discount-code' placeholder='Code'>
				
				<button id='apply-discount' class='red-button'>Apply</button>
				
				<p>* If you have a discount code, enter it above and click Apply.</p>
			
			</div>

		    <div id='billing-address'>
		    
		        <h3>Billing Address</h3>
		        
		        <p>        
		        	<?=$form->textbox( 'billing_name', array( 'label' => 'Billing Name', 'default' => $_POST['billing_name'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>  
		        	<?=$form->textbox( 'billing_address1', array( 'label' => 'Address 1', 'default' => $_POST['billing_address1'], 'class' => 'required' ) ) ?></p>
		        <p>
		        	<?=$form->textbox( 'billing_address2', array( 'label' => 'Address 2', 'default' => $_POST['billing_address2'] ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'billing_city', array( 'label' => 'City', 'default' => $_POST['billing_city'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>
		        	<?=$form->select( 'billing_state', $form->states, array( 'label' => 'State', 'empty' => ' ', 'default' => $_POST['billing_state'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'billing_zipcode', array( 'label' => 'Zipcode', 'default' => $_POST['billing_zipcode'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'billing_phone', array( 'label' => 'Phone', 'default' => $_POST['billing_phone'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'billing_email', array( 'label' => 'Email', 'default' => $_POST['billing_email'], 'class' => 'required' ) ) ?>
		        </p>
		    
		    </div>
		    
		    <div id='shipping-address'>
    
		        <h3>Shipping Address</h3>
		        
		        <div id='billing-checkbox'>
		        	<input type='checkbox' id='same_as_billing' name='same_as_billing' onclick='use_billing()' style='width: auto; height: auto;' <?=$_POST['same_as_billing']?'checked':'' ?>/> Same as Billing
		        </div>
		        		        
		        <div id='shipping-fields'>
		        
		        <p>
		        	<?=$form->textbox( 'shipping_name', array( 'label' => 'Shipping Name', 'default' => $_POST['shipping_name'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'shipping_address1', array( 'label' => 'Address 1', 'default' => $_POST['shipping_address1'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'shipping_address2', array( 'label' => 'Address 2', 'default' => $_POST['shipping_address2'] ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'shipping_city', array( 'label' => 'City', 'default' => $_POST['shipping_city'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>
		         	<?=$form->select( 'shipping_state', $form->states, array( 'label' => 'State', 'empty' => ' ', 'default' => $_POST['shipping_state'], 'class' => 'required', 'onchange' => 'shipping_and_taxes()' ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'shipping_zipcode', array( 'label' => 'Zipcode', 'default' => $_POST['shipping_zipcode'], 'class' => 'required' ) ) ?>
		        </p>
		        <p>
		        	<?=$form->textbox( 'shipping_phone', array( 'label' => 'Phone', 'default' => $_POST['shipping_phone'], 'class' => 'required' ) ) ?>
		        </p>
	        
	        </div>
	    
	    </div>
	    
	    <div id='payment-information'>
    
	        <h3>Payment Information</h3>
	        
	        <p>
	         	<?=$form->select( 'card_type', array( 'Visa', 'Mastercard', 'Discover' ), array( 'label' => 'Card', 'default' => $_POST['card_type'], 'class' => 'required' ) ) ?>
	        </p>
	              
	        <p>  
	        	<?=$form->textbox( 'card_no', array( 'label' => 'Card No' . ($_COOKIE['in_store_rate']?" <span style='font-size: 8px;'>( In Store )</span>":''), 'default' => $_POST['card_no'], 'class' => 'required' ) ) ?>
	        </p>
	                
	        <p>
	            <label>Exp Date</label>
	            
	            <select id='exp_mo-id' name='exp_mo' style='width: auto; height: auto;'>
	                <? foreach( $form->months as $mo ): ?>
	                    <option value='<?=$mo ?>'><?=$mo ?></option>
	                <? endforeach; ?>
	            </select> / 
	            
	            <select id='exp_year-id' name='exp_year' style='margin-left: 0px; width: auto; height: auto;'>
	                <? for( $i=date('Y');$i<2030;$i++ ): ?>
	                    <option value='<?=$i ?>'><?=$i ?></option>
	                <? endfor; ?>
	            </select><br />
	        </p>
	        
	        <p>  
	        	<?=$form->textarea( 'comments', array( 'label' => 'Comments', 'default' => $_POST['comments'], 'rows' => 5, 'cols' => 20 ) ) ?>
	        </p>
	                
	        <p>
	        
	        	<input type='submit' id='submit' name='submit' value='Submit Order'/>
	            
	        </p>  
	               
	    
	    </div>
	    
		</div> 
		
		</form>
		
	</div>

</section>