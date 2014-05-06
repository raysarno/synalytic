<?php

	if ($listType == 'ctt') {
		$filterList = array(
			array(
				'field' 		=> $listType . '_f_name',
				'placeholder' 	=> 'First Name'
			),
			array(
				'field' 		=> $listType . '_l_name',
				'placeholder' 	=> 'Last Name'
			),
			array( //UPDATE ON DB IMPROVE
				'field' 		=> $listType . '_raw_cmp',
				'placeholder' 	=> 'Company'
			),
			array(
				'field' 		=> $listType . '_title',
				'placeholder' 	=> 'Title'
			),
			array( //UPDATE ON DB IMPROVE
				'field' 		=> $listType . '_raw_lcn',
				'placeholder' 	=> 'Location'
			),
			array(
				'field' 		=> $listType . '_role',
				'placeholder' 	=> 'Role'
			)
		);
	}
	else if ($listType == 'cmp') {
		$filterList = array(
			array(
				'field' 		=> $listType . '_name',
				'placeholder'	=> 'Company Name'
			)
		);
	}
	else if ($listType == 'lcn') {
		$filterList = array(
			array(
				'field' 		=> $listType . '_name',
				'placeholder'	=> 'Location Name'
			)
		);
	}
?>

<div id="<?php echo $listType; ?>_filterBar" class="filterBar <?php echo $listSize; ?>">
	<!--form id="<?php //echo $listType; ?>" class="filterForm" method="POST" action="<?php //echo $_SERVER['PHP_SELF']; ?>"-->
		<?php
			foreach ($filterList as $filter) {
				echo '<input class="' . $listType . '-filterField" id="' . $filter['field'] . '_filter" type="text" name="' . $filter['field'] . '" size="20" placeholder = "' . $filter['placeholder'] . '" value="' . (isset($_SESSION['filters'][$listType][$filter['field']]) ? $_SESSION['filters'][$listType][$filter['field']] : '') . '" />';
			}
			//echo '<input id="formType" type="text" name="formType" value="filter" style="display:none;" />';
			echo '<input id="formSubject" type="text" name="formSubject" value="' . $listType . '" style="display:none;" />';
			echo '<button class="applyFilters" id="' . $listType . '_applyFilters" onclick="applyFilters(\'' . $listType . '\');">Apply Filters</button>';
			echo '<button class="clearFilters" id="' . $listType . '_clearFilters" onclick="applyFilters(\'' . $listType . '\');">Clear Filters</button>';
		?>
	<!--/form-->
</div>
