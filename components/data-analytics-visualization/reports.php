<?php include $_SERVER['DOCUMENT_ROOT'] . '/include/header.php'; ?>
<div id="content">
	<div id="filterWrapper">
		<div id="filterSummary">
			<table class="filterSummaryTable" style="border-bottom:1px solid #39F;">
				<tr>
					<td colspan="2">
						<h3 class="filterTitle">Report Summary</h3>
						<button id="buildChart" type="button" class="filterButton" onclick="drawChart();">Build Chart</button>
					</td>
				</tr>
				<tr>
					<td>Report Type</td>
					<td>
						<select id="reportSummary-reportType" class="chart-select">
							<option value="numCalls">Number of Calls</option>
							<option value="numImps">Number of Impressions</option>
							<option value="spend">Dollars Spent</option>
							<option value="CPC">Cost Per Call (CPC)</option>
							<option value="responseRate">Response Rate (Calls/Impressions)</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Visualization Type</td>
					<td>
						<select id="reportSummary-chartType" class="chart-select">
							<option value="trend">Trend</option>
							<option value="area">Area/Line Graph</option>
							<option value="map">Map</option>
							<option value="column">Column Chart</option>
							<option value="pie" disabled>Pie Chart</option>						
						</select>
					</td>
				</tr>
				<tr>
					<td>Primary Dimension</td>
					<td>
						<select id="reportSummary-primaryDim" class="chart-select">
							<option value="date">Date</option>
							<option value="timeslot">Timeslot</option>
							<option value="market">Market (DMA)</option>
							<option value="state">Market (State)</option>
							<option value="media">Media Type</option>
							<option value="creative">Creative</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Secondary Dimension</td>
					<td>
						<select id="reportSummary-secondaryDim" class="chart-select">
							<option value="none">None</option>
							<option value="timeslot">Timeslot</option>
							<option value="market">Market (DMA)</option>
							<option value="state">Market (State)</option>
							<option value="media">Media Type</option>
							<option value="creative">Creative</option>
						</select>
					</td>
				</tr>
			</table>
			<table class="filterSummaryTable">
				<tr>
					<td colspan="2">
						<h3 class="filterTitle">Filter Summary</h3>
						<button id="applyFilters" type="button" class="filterButton" onclick="applyFilters();">Apply Filters</button>
					</td>
				</tr>
				<tr>
					<td>
						Date Range (Grouping)
					</td>
					<td id="filterSummary-date">
						<?php echo $chartParams['filters']['date']['all'] ? 'ALL DATES' : ($chartParams['filters']['date']['start'] . ' ~ ' . $chartParams['filters']['date']['end']); ?>
						<select id="filter-date-group-select" class="filter-select">
							<option id="filter-date-group-month" value="Month" <?php echo $chartParams['filters']['date']['group'] == 'Month' ? 'selected="selected"' : '';?>>By Month</option>
							<option id="filter-date-group-week" value="Week" <?php echo $chartParams['filters']['date']['group'] == 'Week' ? 'selected="selected"' : '';?>>By Week</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Timeslot(s)</td>
					<td id="filterSummary-timeslot">
						<?php echo $chartParams['filters']['timeslot']['all'] ? 'ALL TIMESLOTS' : 'build selected timeslot filter display'; ?>
					</td>
				</tr>
				<tr>
					<td>Geography</td>
					<td id="filterSummary-market">
						<?php echo $chartParams['filters']['market']['all'] ? 'ALL MARKETS' : 'build selected market filter display'; ?>
						<select class="filter-select">
							<option id="filter-market-group-DMA" <?php echo $chartParams['filters']['market']['group'] == 'DMA' ? 'selected="selected"' : '';?>>By DMA</option>
							<option id="filter-market-group-state" <?php echo $chartParams['filters']['market']['group'] == 'State' ? 'selected="selected"' : '';?>>By State</option>
						</select>
					</td>
				</tr>
				<tr>
					<td>Ethnic Group</td>
					<td id="filterSummary-ethnicity">
						<?php echo $chartParams['filters']['ethnicity'] == 1 ? 'ALL ETHNIC GROUPS (Total Market)' : $chartParams['filters']['ethnicity']; ?>
					</td>
				</tr>
				<tr>
					<td>Gender / Age Group</td>
					<td id="filterSummary-ageGender">
						<?php echo $chartParams['filters']['ageGender'] == 1 ? 'ALL AGE / GENDER GROUPS (Adults 18+)' : $chartParams['filters']['ageGender']; ?>
					</td>
				</tr>
				<tr>
					<td>Media Type(s)</td>
					<td id="filterSummary-media">
						<?php echo $chartParams['filters']['media']['all'] ? 'ALL MEDIA TYPES' : 'build selected media filter display'; ?>
					</td>
				</tr>
				<tr>
					<td>Creative(s)</td>
					<td id="filterSummary-creative">
						<?php echo $chartParams['filters']['creative']['all'] ? 'ALL CREATIVES' : 'build selected creative filter display'; ?>
					</td>
				</tr>
			</table>
		</div>
		<div id="filterSettings">
			<h3 class="filterTitle" style="margin:0px 2px; width: 396px; padding-left:3px; box-sizing:border-box;">Select Filters</h3>
			<div id="tabs">
				<ul>
					<li><a href="#tab-date">Date</a></li>
					<li><a href="#tab-timeslot">Timeslot</a></li>
					<li><a href="#tab-geography">Geography</a></li>
					<li><a href="#tab-ethnicity">Ethnic Group</a></li>
					<li><a href="#tab-ageGender">Gender / Age Group</a></li>
					<li><a href="#tab-media">Media Type(s)</a></li>
					<li><a href="#tab-creative">Creative(s)</a></li>
				</ul>

				<div id="tab-date">
					<u>Select Date Range Filter</u> &nbsp;Date Format: (YYYY-MM-DD)

					<table id="dateFilterTable">
						<tr>
							<td>Start Date:</td>
							<td>End Date:</td>
						</tr>
						<tr>
							<td><input id="startDateInput" form="reportData" type="text" placeholder="YYYY-MM-DD"></input></td>
							<td><input id="endDateInput" form="reportData" type="text" placeholder="YYYY-MM-DD"></input></td>
						</tr>
						<tr>
							<td><div id="startDatePicker"></div></td>
							<td><div id="endDatePicker"></div></td>
						</tr>
						<!-- tr> //THE SLIDER IS OUT FOR NOW!
							<td colspan="2"><div id="slider-range"></div></td>
						</tr -->
					</table>
				</div>
				<div id="tab-timeslot" filter="timeslot">
					<u>Select Timeslot Filter</u>
					<?php buildFilterSelectTable('timeslot', 'LKP_timeslot', array('timeslot_code', 'timeslot_name'), "checkbox"); ?>
				</div>
				<div id="tab-geography">
					<u>Select Geographic Filter</u>
				</div>
				<div id="tab-ethnicity">
					<u>Select Ethnic Group Filters</u>
					<?php buildFilterSelectTable('ethnicity', 'LKP_ethnicity', array('ethnicity_code', 'ethnicity_name'), "radio"); ?>
				</div>
				<div id="tab-ageGender">
					<u>Select Gender / Age Group Filter</u>
					<?php buildFilterSelectTable('ageGender', 'LKP_ageGender', array('ageGender_name'), "radio"); ?>
				</div>
				<div id="tab-media">
					<u>Filter by Media Type(s)</u>
					<?php buildFilterSelectTable('media', 'LKP_media', array('media_code', 'media_name'), "checkbox"); ?>
				</div>
				<div id="tab-creative">
					<u>Filter by Creative(s)</u>
					<?php buildFilterSelectTable('creative', 'CTL_DAT_r5_TFNLookup', array('ISCI'), "checkbox"); ?>
				</div>
			</div>
		</div>
	</div>

	<div id="chartWrapper">
		<div id="chart_div" style="width: 100%; height: 500px;">
		</div>
	</div>

	<div id="reportTableWrapper">
	</div>
</div>
<?php include ('include/footer.php'); ?>





