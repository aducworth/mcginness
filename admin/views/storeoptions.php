<h1>Store Options</h1>

<form action='/storeoptions' method='post'>

<? foreach( $controller->store_options as $so ): ?>

    <input type='hidden' name='id[]' value='<?=$so['id'] ?>'/>
    <?=$form->textbox( 'name[]', array( 'label' => $so['option_name'], 'default' => $so['option_value'] ) ) ?><br>

<? endforeach; ?>

<input type='submit' class="btn pull-left" value='Save'/>

</form>