<?php 
if($listType == 'ctt') {$listTypeFullName = "contacts";}
if($listType == 'cmp') {$listTypeFullName = "companies";}
if($listType == 'lcn') {$listTypeFullName = "offices";}

$entPrPgArr = array ( 50, 100, 150, 200, 250 );
?>
<div id="<?php echo $listType; ?>_pagination" class="pagination <?php echo $listSize; ?>" listtype="<?php echo $listType; ?>">
	<div id="entPrPg">
		Show 
		<select class="countSubmit" id="countSubmit">
			<?php foreach ( $entPrPgArr as $ent ) : $isselect = ($ent == $entPrPg) ? "selected" : " "; //Loop to create entries per page selector?> 
				<option name="pager" value="<?php echo $ent; ?>"<?php echo $isselect; ?>><?php echo $ent; ?></option>
			<?php endforeach; ?>
		</select> 
		entries per page. 
		<span class="totalEntries">Total <?php echo $listTypeFullName . ": " . number_format($totalEntryCount); ?></span>
	</div>

	<div id="pgScroller">
		<?php if ( $curPg > 1 ) : ?>
			<a class='pgScroll' data-val='1'>&lt;&lt;</a>
			&nbsp;&nbsp;
			<a class='pageScroll' data-val='<?php echo ( $curPg - 1 ); ?>'>&lt;</a>
			&nbsp;&nbsp;
		<?php
			endif;
			$sPg = $curPg - 4; //Set starting page of page scroll to be current page minus 4
			if ( $sPg < 1 ) {
				$sPg = 1;
			}
			$ePg = $curPg + 4; //Set ending page of page scroll to be current page plus 4
			if ( $ePg > $numPgs ) {
				$ePg = $numPgs;
			}
			if ( $sPg > 1 ) :
		?>
			..&nbsp;&nbsp;
		<?php
			endif;
			for ( $p = $sPg; $p <= $ePg; $p++ ) :
				if ( $p == $curPg ) :
		?>
			<span><?php echo $p; ?></span>&nbsp;&nbsp;
		<?php else : ?>
			<a class='pgScroll' data-val='<?php echo $p; ?>'><? echo $p; ?></a>&nbsp;&nbsp;
		<?php
			endif;
			endfor;
			if ( $ePg < $numPgs ) :
		?>
			..&nbsp;&nbsp;
		<?php
			endif;
			if ( $curPg < $numPgs ) :
		?>
			<a class='pgScroll' data-val='<?php echo ( $curPg + 1 ); ?>'>&gt;</a>&nbsp;&nbsp;<a class='pgScroll' data-val='<?php echo $numPgs; ?>'>&gt;&gt;</a>
		<?php endif; ?>	
	</div>

	<div id="pgSubmit">
		Page 
		<input class="pgSubmit" type="text" name="page" size="2" value="<?php echo $curPg; ?>" placeholder="<?php echo $curPg; ?>"/> 
		of 
		<span class="numPgs"><?php echo $numPgs; ?></span>
	</div>

</div>
			