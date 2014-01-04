<!-- Main hero unit for a primary marketing message or call to action -->
<div class="hero-unit">
	<h1>Hello, <?=$_SESSION['logged_in_user']['fname'] ?> <?=$_SESSION['logged_in_user']['lname'] ?>!</h1>
</div>

<!-- Example row of columns -->
<div class="row">
<div class="span6">
  <h2>Recently Added Products</h2>
  <? if( count( $controller->recently_added_products ) ): ?>

        <table id='default-table' class="table table-striped table-condensed">
        
            <thead>
                <tr>
                    <th>Name</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            
            <tbody>
            
                <? foreach( $controller->recently_added_products as $p ): ?>
                        
                <tr>
                    <td>
						<a href='/product?id=<?=$p['id'] ?>'>
							<?=$p['name'] ?>
                        </a>
                    </td>
                    <td>Added <?=date( 'm/d/y g:ia', strtotime( $p['created'] ) ) ?></td>
                </td>
                
                <? endforeach; ?>
                
            </tbody>
        
        </table>
        
	<? else: ?>
    
        <p>No products have been added.</p>
        
    <? endif; ?>
</div>
<div class="span6">
  <h2>Recently Added Orders</h2>
  <? if( count( $controller->recently_added_orders ) ): ?>

        <table id='default-table' class="table table-striped table-condensed">
        
            <thead>
                <tr>
                    <th>Name</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            
            <tbody>
            
                <? foreach( $controller->recently_added_orders as $p ): ?>
                        
                <tr>
                    <td>
						<a href='/order?id=<?=$p['id'] ?>'>
							<?=$p['billing_name'] ?>
                        </a>
                    </td>
                    <td>Created <?=date( 'm/d/y g:ia', strtotime( $p['created'] ) ) ?></td>
                </td>
                
                <? endforeach; ?>
                
            </tbody>
        
        </table>
        
	<? else: ?>
    
        <p>No orders have been added.</p>
        
    <? endif; ?>
</div>
</div>