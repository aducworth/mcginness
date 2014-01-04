<h1><?=$_GET['id']?'Edit':'Add' ?> Order</h1>

<form action='/order' id='data-form' method='post' enctype="multipart/form-data">

<? if( $_GET['id'] ): ?>

	<input type='hidden' id='id' name='id' value='<?=$_GET['id'] ?>'/>

<? endif; ?>

<?=$form->select( 'status', $controller->order_statuses, array( 'label' => 'Status', 'default' => $controller->result['status'], 'class' => 'required' ) ) ?>
<?=$form->textbox( 'tracking_number', array( 'label' => 'Tracking Number', 'default' => $controller->result['tracking_number'], 'class' => '' ) ) ?>
<?=$form->textarea( 'notes', array( 'label' => 'Notes', 'default' => $controller->result['notes'], 'class' => '' ) ) ?>
<p class='action-buttons'>

	<input type='submit' class="btn pull-right" value='Save'/>

	<? if( $_GET['id'] ): ?>

		<button id='delete-order' class="btn btn-danger pull-left delete-item">Delete</button>
        
    <? else: ?>
    
    	<button id='cancel-order' class="btn btn-danger pull-left cancel-item">Cancel</button>
        
    <? endif; ?>

</p>

</form>