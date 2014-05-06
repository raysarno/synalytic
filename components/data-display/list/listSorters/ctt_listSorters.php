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
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'ctt_l_name,ctt_f_name' ? $sortDir : 'None') ;?>.png" sortField="ctt_l_name,ctt_f_name"  /> Name /
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'ctt_raw_cmp' ? $sortDir : 'None') ;?>.png" sortField="ctt_raw_cmp"  /> Company / 
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'ctt_raw_lcn' ? $sortDir : 'None') ;?>.png" sortField="ctt_raw_lcn" /> Location
	</div>
	<div class="infoColumn">
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'ctt_role' ? $sortDir : 'None') ;?>.png" sortField="ctt_role" /> Role / 
		<img class="listSorter" src="img/sort<?php echo ($sortField == 'ctt_title' ? $sortDir : 'None') ;?>.png" sortField="ctt_title" /> Title
	</div>	
	<div class="infoColumn small">
		Email / 
		Tel #
	</div>		
	<div class="icons">
	</div>	
</div>