<div id="listSorters" class="<?php echo $listSize; ?>" listtype="<?php echo $listType; ?>">
	<div class="selector">
	</div>
	<div class="QA_Wrapper">
	</div>
	<div class="score">
	</div>
	<div class="badges">
	</div>
	<div class="mainImage">
	</div>
	<div class="infoColumn small">
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'lcn_name' ? $sortDir : 'None') ;?>.png" sortField="lcn_name" /> Office Name / Tel#
	</div>
	<div class="infoColumn">
		Address Line 1 / Line 2 / Line 3
	</div>		
	<div class="infoColumn small">
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'lcn_locality' ? $sortDir : 'None') ;?>.png" sortField="lcn_locality" /> City / 
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'lcn_region' ? $sortDir : 'None') ;?>.png" sortField="lcn_region" /> Region / 
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'lcn_country' ? $sortDir : 'None') ;?>.png" sortField="lcn_country" /> Country / 
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'lcn_postcode' ? $sortDir : 'None') ;?>.png" sortField="lcn_postcode" /> Post (Zip) Code
	</div>
</div>