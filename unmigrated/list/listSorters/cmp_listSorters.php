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
	<div class="infoColumn">
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'cmp_name' ? $sortDir : 'None') ;?>.png" sortField="cmp_name" /> Company Name / URL
	</div>
	<div class="infoColumn">
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'cmp_linkedin_contact_count' ? $sortDir : 'None') ;?>.png" sortField="cmp_linkedin_contact_count" /> LinkedIn Contacts / CRM Contacts / CRM Locations
	</div>		
	<div class="icons">
	</div>	
</div>