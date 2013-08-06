<h1><?=$_GET['id']?'Edit':'Add' ?> Discount</h1>

<form action='/discount' id='data-form' method='post' enctype="multipart/form-data">

<? if( $_GET['id'] ): ?>

	<input type='hidden' id='id' name='id' value='<?=$_GET['id'] ?>'/>

<? endif; ?>

<?=$form->textbox( 'code', array( 'label' => 'Code', 'default' => $controller->result['code'], 'class' => 'required' ) ) ?>
<?=$form->select( 'type', array( 'flat' => 'Flat', 'percentage' => 'Percentage' ), array( 'label' => 'Type', 'default' => $controller->result['type'], 'class' => 'required' ) ) ?>
<?=$form->textbox( 'amount', array( 'label' => 'Amount', 'default' => $controller->result['amount'], 'class' => 'required number' ) ) ?>
<?=$form->checkbox( 'is_active', array( 1 => 'Active?' ), array( 'label' => false, 'default' => array($controller->result['is_active']), 'class' => '' ) ) ?>
<?=$form->textarea( 'notes', array( 'label' => 'Notes', 'default' => $controller->result['notes'], 'class' => '' ) ) ?>
<p class='action-buttons'>

	<input type='submit' class="btn pull-right" value='Save'/>

	<? if( $_GET['id'] ): ?>

		<button id='delete-discount' class="btn btn-danger pull-left delete-item">Delete</button>
        
    <? else: ?>
    
    	<button id='cancel-discount' class="btn btn-danger pull-left cancel-item">Cancel</button>
        
    <? endif; ?>

</p>

</form>