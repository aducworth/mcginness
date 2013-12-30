<h1><?=$_GET['id']?'Edit':'Add' ?> Product</h1>

<form action='/product' id='data-form' method='post' enctype="multipart/form-data">

<? if( $_GET['id'] ): ?>

	<input type='hidden' id='id' name='id' value='<?=$_GET['id'] ?>'/>

<? endif; ?>

<? if( $controller->result['image'] ): ?>
	<img src='<?=$controller->site_url ?>/images/uploads/resize/<?=$controller->result['image'] ?>' class='pull-right'/>
<? endif; ?>

<?=$form->textbox( 'name', array( 'label' => 'Name', 'default' => htmlspecialchars( $controller->result['name'] ), 'class' => 'required' ) ) ?>
<p><label>Image ( 284px x 370px ):</label><input type='file' name='image'/></p>
<?=$form->select( 'product_type', $controller->product_types, array( 'label' => 'Product Type', 'default' => $controller->result['product_type'], 'class' => 'required', 'empty' => ' ( Choose ) ' ) ) ?>
<?=$form->select( 'product_subcategory', $controller->result['product_subcategory']?$controller->product_subcategories[ $controller->result['product_type'] ]:array(), array( 'label' => 'Product Subcategory', 'default' => $controller->result['product_subcategory'], 'class' => 'required', 'empty' => ' ( Choose Product Type ) ' ) ) ?>
<?=$form->textbox( 'base_price', array( 'label' => 'Base Price', 'default' => $controller->result['base_price'], 'class' => 'required number' ) ) ?>
<?=$form->textbox( 'price', array( 'label' => 'Price ( sqft or cuft )', 'default' => $controller->result['price'], 'class' => 'required number' ) ) ?>
<?=$form->textbox( 'lb_per_sqft', array( 'label' => 'Weight ( lb / sqft )', 'default' => $controller->result['lb_per_sqft'], 'class' => 'required number' ) ) ?>
<?=$form->textbox( 'product_width', array( 'label' => 'Width ( for molding )', 'default' => ($controller->result['product_width']?$controller->result['product_width']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'product_height', array( 'label' => 'Height ( for shelves )', 'default' => ($controller->result['product_height']?$controller->result['product_height']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'width_lower', array( 'label' => 'Width Lower', 'default' => ($controller->result['width_lower']?$controller->result['width_lower']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'height_lower', array( 'label' => 'Height Lower', 'default' => ($controller->result['height_lower']?$controller->result['height_lower']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'depth_lower', array( 'label' => 'Depth Lower', 'default' => ($controller->result['depth_lower']?$controller->result['depth_lower']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'length_lower', array( 'label' => 'Length Lower', 'default' => ($controller->result['length_lower']?$controller->result['length_lower']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'width_limit', array( 'label' => 'Width Max', 'default' => ($controller->result['width_limit']?$controller->result['width_limit']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'height_limit', array( 'label' => 'Height Max', 'default' => ($controller->result['height_limit']?$controller->result['height_limit']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'depth_limit', array( 'label' => 'Depth Max', 'default' => ($controller->result['depth_limit']?$controller->result['depth_limit']:0), 'class' => 'number' ) ) ?>
<?=$form->textbox( 'length_limit', array( 'label' => 'Length Max', 'default' => ($controller->result['length_limit']?$controller->result['length_limit']:0), 'class' => 'number' ) ) ?>
<?=$form->checkbox( 'choose_dimensions', array( 1 => 'Choose Dimensions?' ), array( 'label' => false, 'default' => array($controller->result['choose_dimensions']), 'class' => '' ) ) ?>
<?=$form->checkbox( 'choose_side_panels', array( 1 => 'Choose End Panel?' ), array( 'label' => false, 'default' => array($controller->result['choose_side_panels']), 'class' => '' ) ) ?>
<?=$form->checkbox( 'choose_door_side', array( 1 => 'Choose Door Side?' ), array( 'label' => false, 'default' => array($controller->result['choose_door_side']), 'class' => '' ) ) ?>
<?=$form->checkbox( 'choose_shelves', array( 1 => 'Choose to Add Shelves?' ), array( 'label' => false, 'default' => array($controller->result['choose_shelves']), 'class' => '' ) ) ?>
<?=$form->checkbox( 'choose_length', array( 1 => 'Choose Length?' ), array( 'label' => false, 'default' => array($controller->result['choose_length']), 'class' => '' ) ) ?>
<?=$form->checkbox( 'is_active', array( 1 => 'Active?' ), array( 'label' => false, 'default' => array($controller->result['is_active']), 'class' => '' ) ) ?>
<?=$form->select( 'display_order', array(), array( 'label' => 'Display Order', 'default' => $controller->result['display_order'], 'class' => 'required', 'empty' => false, 'range' => array( 'lower' => 0, 'upper' => 50 ) ) ) ?>

<p class='action-buttons'>

	<input type='submit' class="btn pull-right" value='Save'/>

	<? if( $_GET['id'] ): ?>

		<button id='delete-product' class="btn btn-danger pull-left delete-item">Delete</button>
        
    <? else: ?>
    
    	<button id='cancel-product' class="btn btn-danger pull-left cancel-item">Cancel</button>
        
    <? endif; ?>

</p>

</form>