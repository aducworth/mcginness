<?

	$db = new DB;
	
	$db->table = 'wood_types';
	
	$wood_types = $db->retrieve('all','*',' where is_active=1 order by display_order');
	
	$db->table = 'colors';
	
	$colors = $db->retrieve('all','*',' where is_active=1 order by display_order');
	
	$db->table = 'profiles';
	
	$profiles = $db->retrieve('all','*',' where is_active=1 order by display_order');
	
	$db->table = 'products';
	
	$base_cabinets = $db->retrieve('all','*',' where is_active=1 and product_type = 1 order by display_order');
	
	$db->table = 'products';
	
	$upper_cabinets = $db->retrieve('all','*',' where is_active=1 and product_type = 2 order by display_order');
	
	$db->table = 'products';
	
	$specialty_cabinets = $db->retrieve('all','*',' where is_active=1 and product_type = 3 order by display_order');
	
	$db->table = 'products';
	
	$accessories = $db->retrieve('all','*',' where is_active=1 and product_type = 4 order by display_order');
	
	$db->table = 'color_images';
	
	$color_images = $db->retrieve('all','*',' order by wood_type');
	
	$color_images_by_wood = array();
	
	foreach( $color_images as $ci ) {
		
		$color_images_by_wood[ $ci['wood_type'] ][ $ci['color'] ] = $ci['image'];
		
	}
	
?>	
	
		<div class='welcome'>
			
			<h1>Welcome to Our Cabinet Customizer</h1>
			
			<hr>
			
			<h2>Customizing Your Kitchen Cabinets Has Never Been Easier. Get Started.</h2>
		
		</div>
		
	<section class='build-1 build-graphics'>
	
		<div class='container'>
			
			<div class='build-steps step-1'>
			
				<div class='build-header'><span>1</span>Select Your Style</div>
				
				<div class='option-selector'>
				
					<h3>Select Door and Drawer Profile</h3>
				
					<a href='#' class='prev'>&lt;</a>
					
					<ul>
						<? foreach( $profiles as $p ): ?>
							<li class='visible-option'><a href='#' class='door-and-drawer <?=($_SESSION['drawer-and-door'] == $p['id'])?'selected':'' ?>' data-value='<?=$p['id'] ?>'><img src='<?=$p['image']?('/images/uploads/thumbnails/'.$p['image']):'/images/base-cabinet.jpg' ?>'/><span><?=$p['name'] ?></span></a></li>
						<? endforeach; ?>
					</ul>
					
					<a href='#' class='next'>&gt;</a>
			
				</div>
				
				<div class='option-selector'>
				
					<h3>Select Wood Type</h3>
				
					<a href='#' class='prev'>&lt;</a>
					
					<ul>
						<? foreach( $wood_types as $wt ): ?>
							<li class='visible-option'><a href='#' class='wood-type <?=($_SESSION['wood-type'] == $wt['id'])?'selected':'' ?>' data-value='<?=$wt['id'] ?>'><div class='image-holder' style="background-image: url('<?=$wt['image']?('/images/uploads/thumbnails/'.$wt['image']):'/images/wood-grain-1.jpg' ?>');"></div><span><?=$wt['name'] ?></span></a></li>
						<? endforeach; ?>
					</ul>
					
					<a href='#' class='next'>&gt;</a>
			
				</div>
				
				<div class='option-selector'>
				
					<h3>Select Stain Color</h3>
				
					<a href='#' class='prev'>&lt;</a>
					
					<ul>
						<? foreach( $colors as $c ): ?>
						
							<? 
								if( $_SESSION['wood-type'] ) {
									
									$image = $color_images_by_wood[ $_SESSION['wood-type'] ][ $c['id'] ];
									
								} else {
									
									$image = $c['image'];
									
								}
								
							?>
							
							<li class='visible-option'><a href='#' class='stain-color <?=($_SESSION['stain-color'] == $c['id'])?'selected':'' ?>' data-value='<?=$c['id'] ?>'><div class='image-holder' style='<?=$image?('background-image: url(/images/uploads/thumbnails/'.$image.');'):('background-color: ' . $c['hex_index']) ?>'></div><span><?=$c['name'] ?></span></a></li>
						<? endforeach; ?>
					</ul>
					
					<a href='#' class='next'>&gt;</a>
			
				</div>
			
			</div>
			
		</div>
		
	</section>
	
	<section class='build-2'>
	
		<div class='container'>
			
			<div class='build-steps step-2'>
			
				<div class='build-header'><span>2</span>Select Your Specs <a href='#' class='back-to-top'>Back to Top</a></div>
				
				<div class='side-panel'>
				
					<h2>Your Current Cabinet</h2>
					
					<div class='selected-option-one selected-door-and-drawer'>
					
						Loading...
											
					</div>
					
					<div class='selected-options selected-wood-type'>
					
						Loading...
					
					</div>
					
					<div class='selected-options selected-stain-color'>
					
						Loading...
											
					</div>
					
					<div class='side-panel-cart'>
					
						Loading...
					
					</div>
				
				</div>
				
				<div class='option-selector'>
				
					<h3>Select Base Cabinets</h3>
				
					<a href='#' class='prev'>&lt;</a>
					
					<ul>
						<? foreach( $base_cabinets as $bc ): ?>
							<li class='visible-option'><a href='/select_cabinet?ajax=true&product=<?=$bc['id'] ?>' class='colorbox'>
							
								<? if( $bc['image'] ): ?>
								
									<img src='/images/uploads/resize/<?=$bc['image'] ?>'/>
									
								<? else: ?>
								
									<img src='/images/base-cabinet.jpg'/>
								
								<? endif; ?>
								
								<span><?=$bc['name'] ?></span></a></li>
						<? endforeach; ?>
					</ul>
					
					<a href='#' class='next'>&gt;</a>
			
				</div>
				
				<div class='option-selector'>
				
					<h3>Select Upper Cabinets</h3>
				
					<a href='#' class='prev'>&lt;</a>
					
					<ul>
						<? foreach( $upper_cabinets as $uc ): ?>
							<li class='visible-option'><a href='/select_cabinet?ajax=true&product=<?=$uc['id'] ?>' class='colorbox'>
								<? if( $uc['image'] ): ?>
								
									<img src='/images/uploads/resize/<?=$uc['image'] ?>'/>
									
								<? else: ?>
								
									<img src='/images/base-cabinet.jpg'/>
								
								<? endif; ?>
								
								<span><?=$uc['name'] ?></span></a></li>
						<? endforeach; ?>
					</ul>
					
					<a href='#' class='next'>&gt;</a>
			
				</div>
				
				<div class='option-selector'>
				
					<h3>Select Specialty Cabinets</h3>
				
					<a href='#' class='prev'>&lt;</a>
					
					<ul>
						<? foreach( $specialty_cabinets as $sc ): ?>
							<li class='visible-option'><a href='/select_cabinet?ajax=true&product=<?=$sc['id'] ?>' class='colorbox'>
							
								<? if( $sc['image'] ): ?>
								
									<img src='/images/uploads/resize/<?=$sc['image'] ?>'/>
									
								<? else: ?>
								
									<img src='/images/base-cabinet.jpg'/>
								
								<? endif; ?>
								
								<span><?=$sc['name'] ?></span></a></li>
						<? endforeach; ?>
					</ul>
					
					<a href='#' class='next'>&gt;</a>
			
				</div>
				
			</div>
			
		</div>
		
	</section>
	
	<section class='build-3'>
	
		<div class='container'>
			
			<div class='build-steps step-3'>
			
				<div class='build-header'><span>3</span>Select Accessories <a href='#' class='back-to-top'>Back to Top</a></div>
				
				<div class='option-selector'>
				
					<h3>Select Accessories for Your Cabinets</h3>
				
					<a href='#' class='prev'>&lt;</a>
					
					<ul>
						<? foreach( $accessories as $a ): ?>
							<li class='visible-option'><a href='/select_cabinet?ajax=true&product=<?=$a['id'] ?>' class='colorbox'>
							
							<? if( $a['image'] ): ?>
								
								<img src='/images/uploads/resize/<?=$a['image'] ?>'/>
								
							<? else: ?>
							
								<img src='/images/base-cabinet.jpg'/>
							
							<? endif; ?>
							
							<span><?=$a['name'] ?></span></a></li>
						<? endforeach; ?>
					</ul>
					
					<a href='#' class='next'>&gt;</a>
			
				</div>
			
			</div>
			
		</div>
		
	</section>