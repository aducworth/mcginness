<h1><?=$_GET['id']?'Edit':'Add' ?> Wood Type</h1>

<form action='/wood_type' id='data-form' method='post' enctype="multipart/form-data">

<? if( $_GET['id'] ): ?>

	<input type='hidden' id='id' name='id' value='<?=$_GET['id'] ?>'/>

<? endif; ?>

<?=$form->textbox( 'name', array( 'label' => 'Name', 'default' => $controller->result['name'], 'class' => 'required' ) ) ?>
<p><label>Image:</label><input type='file' name='image'/></p>
<?=$form->textbox( 'price', array( 'label' => 'Price ( per sqft )', 'default' => $controller->result['price'], 'class' => 'required number' ) ) ?>
<?=$form->checkbox( 'is_active', array( 1 => 'Active?' ), array( 'label' => false, 'default' => array($controller->result['is_active']), 'class' => '' ) ) ?>
<?=$form->select( 'display_order', array(), array( 'label' => 'Display Order', 'default' => $controller->result['display_order'], 'class' => 'required', 'empty' => false, 'range' => array( 'lower' => 0, 'upper' => 50 ) ) ) ?>

<p class='action-buttons'>

	<input type='submit' class="btn pull-right" value='Save'/>

	<? if( $_GET['id'] ): ?>

		<button id='delete-wood_type' class="btn btn-danger pull-left delete-item">Delete</button>
        
    <? else: ?>
    
    	<button id='cancel-wood_type' class="btn btn-danger pull-left cancel-item">Cancel</button>
        
    <? endif; ?>

</p>

</form>