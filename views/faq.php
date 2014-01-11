<?

	$db = new DB;
		
	$db->table = 'faqs';
	
	$faqs = $db->retrieve('all','*',' where is_active=1 order by display_order');
		
?>	
<div class='welcome'>
			
	<h1>Frequently Asked Questions</h1>
	
	<hr>
	
	<h2>Answers to Commonly Asked Questions</h2>

</div>

<section class='build-graphics'>

	<div class='container'>

		<? if( count( $faqs ) ): ?>
		
		<ul class='faq-list'>
		
			<? foreach( $faqs as $faq ): ?>
			
			<li>
				<span class='question'><?=$faq['question'] ?></span>
				<span class='answer'><?=$faq['answer'] ?></span>
			</li>
			
			<? endforeach; ?>
			
		</ul>
		
		<? else: ?>
		
			<p>No faqs have been added yet.</p>
		
		<? endif; ?>
		
	</div>

</section>