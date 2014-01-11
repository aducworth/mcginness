<h1><?=$_GET['id']?'Edit':'Add' ?> Slide</h1>

<form action='/slide' id='data-form' method='post' enctype="multipart/form-data">

<? if( $_GET['id'] ): ?>

	<input type='hidden' id='id' name='id' value='<?=$_GET['id'] ?>'/>

<? endif; ?>

<?=$form->textbox( 'title', array( 'label' => 'Title', 'default' => $controller->result['title'], 'class' => 'required' ) ) ?>
<p><label>Image ( 984px x 610px ):</label><input type='file' name='image'/></p>
<?=$form->textarea( 'teaser', array( 'label' => 'Teaser', 'default' => $controller->result['teaser'], 'class' => 'required' ) ) ?>
<?=$form->textarea( 'bullet_points', array( 'label' => 'Bullet Points ( One per line )', 'default' => $controller->result['bullet_points'], 'class' => 'required' ) ) ?>
<?=$form->textarea( 'description', array( 'label' => 'Description', 'default' => $controller->result['description'], 'class' => 'required' ) ) ?>
<?=$form->checkbox( 'is_active', array( 1 => 'Active?' ), array( 'label' => false, 'default' => array($controller->result['is_active']), 'class' => '' ) ) ?>
<?=$form->select( 'display_order', array(), array( 'label' => 'Display Order', 'default' => $controller->result['display_order'], 'class' => 'required', 'empty' => false, 'range' => array( 'lower' => 0, 'upper' => 50 ) ) ) ?>

<p class='action-buttons'>

	<input type='submit' class="btn pull-right" value='Save'/>

	<? if( $_GET['id'] ): ?>

		<button id='delete-slide' class="btn btn-danger pull-left delete-item">Delete</button>
        
    <? else: ?>
    
    	<button id='cancel-slide' class="btn btn-danger pull-left cancel-item">Cancel</button>
        
    <? endif; ?>

</p>

</form>