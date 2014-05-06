<?php

	/*

	REPORT 1a - Media Buy Detail (Mass Media)
		Expected File Extension: 	 .xlsx
		Expected Number of Fields:   15
		Data to Read:                worksheet('Raw')

	REPORT 2  - Media Buy Detail (Drop Report) (Direct Mail)
		Expected File Extension: 	 .xls
		Expected Number of Fields:   inconsistent across worksheets
		Data to Read:  worksheets likely to change, maybe build the duplicate data checker first? Ask user if they want to keep new data?

	REPORT 3 - Call Volume (Direct Mail)
		Expected File Extension: 	 .xlsx
		Expected Number of Fields:   4
		Data to Read:  worksheet(0)

	REPORT 4a - Call Volume (Mass Media) (Filename contains 'L_CTL')
		Expected File Extension: 	 .csv
		Expected Number of Fields:   7
		Data to Read:  N/A

	REPORT 4b - Call Volume (Mass Media) (Filename contains 'LQ_')
		Expected File Extension: 	 .xlsx
		Expected Number of Fields:   13
		Data to Read:  worksheet(0)
	
	REPORT 5 - Creative TFN Lookup
		Expected File Extension: 	 .xlsx
		Expected Number of Fields:   8
		Data to Read:  worksheet(0)
	*/

$dataRules = array(
	"r1a" => array( 								//MASS MEDIA MEDIA BUY (ACTUAL)
		//Don't have example data set yet
	),
	"r1b" => array( 								//MASS MEDIA MEDIA BUY (PLANNED)
		"expFileExt" 		=> "XLSX",				//Expected File Extension
		"expNumCols" 		=> 15,					//Expected Number of columns
		"worksheetToRead" 	=> 1,					//Should be the index of the 'Raw' Worksheet
		"tableNameSuffix"   => "mediaBuyMM",
		"checkDuplMethod"   => "normal",	
		"sourceCols" 		=> array(
			1 => array(
				"sourceField"	=> "Month",
				"AmetrixField"  => "",
				"dataType"  	=> "date",		
				"use" 			=> false,
				"checkDupl" 	=> false
			),
			2 => array(
				"sourceField"	=> "Media_Type",
				"AmetrixField"  => "media_type",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			3 => array(
				"sourceField"	=> "Product_Name",
				"AmetrixField"  => "product_name",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			4 => array(
				"sourceField"	=> "Market_Name",
				"AmetrixField"  => "raw_market",
				"dataType"  	=> "DMA_Nielsen",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			5 => array(
				"sourceField"	=> "Broadcast_Week / Insertion date",		//Data source is excel, Ex. 41281
				"AmetrixField"  => "start_date",
				"dataType"  	=> "date",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			6 => array(
				"sourceField"	=> "Daypart_Code",
				"AmetrixField"  => "daypart_code",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			7 => array(
				"sourceField"	=> "Spot_Length",
				"AmetrixField"  => "spot_length",
				"dataType"  	=> "int",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			8 => array(
				"sourceField"	=> "Program_Name",
				"AmetrixField"  => "program_name",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			9 => array(
				"sourceField"	=> "Days_of_Week",
				"AmetrixField"  => "days_of_week",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true	
				),
			10 => array(
				"sourceField"	=> "Station_Name",
				"AmetrixField"  => "station_name",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			11 => array(
				"sourceField"	=> "Start_Time",	//AM.PM format, Ex. 6:00AM
				"AmetrixField"  => "start_time",
				"dataType"  	=> "time",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			12 => array(
				"sourceField"	=> "End_Time",	//AM.PM format, Ex. 6:00AM
				"AmetrixField"  => "end_time",
				"dataType"  	=> "time",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			13 => array(
				"sourceField"	=> "Net_Cost",
				"AmetrixField"  => "net_cost",
				"dataType"  	=> "decimal",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			14 => array(
				"sourceField"	=> "Purchased_Spot_Count",
				"AmetrixField"  => "purchased_spot_count",
				"dataType"  	=> "int",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			15 => array(
				"sourceField"	=> "Purchased_RTG_GRPs",
				"AmetrixField"  => "purchased_rtg_grps",
				"dataType"  	=> "decimal",
				"use" 			=> true,
				"checkDupl" 	=> true	
			)
		)
	),
	"r2" => array( 									//DIRECT MAIL DROP REPORT (MEDIA BUY)
		"expFileExt" 		=> "XLS",
		"expNumCols" 		=> 11,        			//LIKELY TO CHANGE ONCE FORMAT IS AGREED UPON
		"worksheetToRead" 	=> 1,	//LIKELY TO CHANGE ONCE FORMAT IS AGREED UPON
		"tableNameSuffix"   => "mediaBuyDM",
		"checkDuplMethod"   => "normal",
		"sourceCols" 		=> array(
			1 => array(
				"sourceField"	=> "Keycode",
				"AmetrixField"  => "ISCI",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			2 => array(
				"sourceField"	=> "Drop date",	//Source is Excel Ex. 41425
				"AmetrixField"  => "drop_date",
				"dataType"  	=> "date",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			3 => array(
				"sourceField"	=> "Legacy",
				"AmetrixField"  => "legacy",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> false
			),
			4 => array(
				"sourceField"	=> "Campaign/Target",
				"AmetrixField"  => "campaign_target",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> false
			),
			5 => array(
				"sourceField"	=> "Version #",
				"AmetrixField"  => "version_number",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			6 => array(
				"sourceField"	=> "Quantity",
				"AmetrixField"  => "quantity",
				"dataType"  	=> "int",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			7 => array(
				"sourceField"	=> "Cost per Piece",
				"AmetrixField"  => "cost_per_piece",
				"dataType"  	=> "decimal",
				"use" 			=> true,				
				"checkDupl" 	=> false
			),
			8 => array(
				"sourceField"	=> "Cost",
				"AmetrixField"  => "total_cost",
				"dataType"  	=> "int",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			9 => array(
				"sourceField"	=> "Media Description",
				"AmetrixField"  => "raw_media",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			10 => array(
				"sourceField"	=> "Market DMA",
				"AmetrixField"  => "raw_market",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> false
			),
			11 => array(
				"sourceField"	=> "Ethnicity",
				"AmetrixField"  => "raw_ethnicity",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> false
			)
		)
	),
	"r3" => array( 								//DIRECT MAIL CALL VOLUME
		"expFileExt" 		=> "XLSX",
		"expNumCols" 		=> 4,        		
		"worksheetToRead" 	=> 0,		
		"tableNameSuffix"   => "callsDM",
		"checkDuplMethod"   => "date",		
		"sourceCols" 		=> array(
			1 => array(
				"sourceField"	=> "TF_NUM",
				"AmetrixField"  => "TFN",
				"dataType"  	=> "TFN",
				"use" 			=> true,
				"checkDupl" 	=> false
			),
			2 => array(
				"sourceField"	=> "KEYCODE",
				"AmetrixField"  => "ISCI",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> false
			),
			3 => array(
				"sourceField"	=> "Call_DATE",	//Source is Excel Ex. 41418
				"AmetrixField"  => "call_date",
				"dataType"  	=> "date",
				"use" 			=> true,				
				"checkDupl" 	=> false
			),
			4 => array(
				"sourceField"	=> "CALLS",
				"AmetrixField"  => "call_count",
				"dataType"  	=> "int",
				"use" 			=> true,
				"checkDupl" 	=> false
			)
		)
	),
	"r4a" => array( 							//MASS MEDIA CALL VOLUME (Filename contains 'L_CTL')
		"expFileExt" 		=> "CSV",
		"expNumCols" 		=> 7,        		
		"worksheetToRead" 	=> 0,		
		"tableNameSuffix"   => "callsMM",
		"checkDuplMethod"   => "normal",		
		"sourceCols" 		=> array(
			1 => array(
				"sourceField"	=> "CALL_DATE",	//Source is sql, Ex. 20130705
				"AmetrixField"  => "call_date",
				"dataType"  	=> "date",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			2 => array(
				"sourceField"	=> "CALL_TIME",	//SQL format Ex. 20:38:25:000
				"AmetrixField"  => "call_time",
				"dataType"  	=> "time",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			3 => array(
				"sourceField"	=> "NUMBER_DIALED",
				"AmetrixField"  => "TFN",
				"dataType"  	=> "TFN",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			4 => array(
				"sourceField"	=> "ANI",
				"AmetrixField"  => "ANI",
				"dataType"  	=> "DMA_ANI",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			5 => array(
				"sourceField"	=> "COUNT_CALLS",
				"AmetrixField"  => "call_count",
				"dataType"  	=> "int",
				"use" 			=> true,
				"checkDupl" 	=> false
			),
			6 => array(
				"sourceField"	=> "MARKET",
				"AmetrixField"  => "raw_market",
				"dataType"  	=> "DMA_Nielsen",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			7 => array(
				"sourceField"	=> "TACTIC_MEDIUM",
				"AmetrixField"  => "raw_media",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			)
		)
	),
	"r4b" => array( 							//MASS MEDIA CALL VOLUME (Filename contains 'L_CTL')
		"expFileExt" 		=> "XLSX",
		"expNumCols" 		=> 13,        		
		"worksheetToRead" 	=> 0,		
		"tableNameSuffix"   => "callsMM",
		"checkDuplMethod"   => "normal",		
		"sourceCols" 		=> array(
			1 => array(
				"sourceField"	=> "IDR_CALLORGDT",	//Source is Excel, Ex. 41267
				"AmetrixField"  => "call_date",
				"dataType"  	=> "date",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			2 => array(
				"sourceField"	=> "IDR_CALLORGMONTH",
				"AmetrixField"  => "",
				"dataType"  	=> "date",
				"use" 			=> false,
				"checkDupl" 	=> false
			),
			3 => array(
				"sourceField"	=> "IDR_CALLORGWEEK",
				"AmetrixField"  => "",
				"dataType"  	=> "date",
				"use" 			=> false,
				"checkDupl" 	=> false
			),
			4 => array(
				"sourceField"	=> "IDR_CALLORGTM",	//Format is Ex. 0.332326389
				"AmetrixField"  => "call_time",
				"dataType"  	=> "time",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			5 => array(
				"sourceField"	=> "IDR_CALLORGHR",
				"AmetrixField"  => "",
				"dataType"  	=> "time",
				"use" 			=> false,
				"checkDupl" 	=> false
			),
			6 => array(
				"sourceField"	=> "IDR_CALLORGMN",
				"AmetrixField"  => "",
				"dataType"  	=> "time",
				"use" 			=> false,
				"checkDupl" 	=> false
			),
			7 => array(
				"sourceField"	=> "idr_tollfreenumber",
				"AmetrixField"  => "TFN",
				"dataType"  	=> "TFN",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			8 => array(
				"sourceField"	=> "IDR_CBSA",
				"AmetrixField"  => "raw_market",
				"dataType"  	=> "DMA_CBSA",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			9 => array(
				"sourceField"	=> "FC_BUSUNIT",
				"AmetrixField"  => "",
				"dataType"  	=> "string",
				"use" 			=> false,
				"checkDupl" 	=> false
			),
			10 => array(
				"sourceField"	=> "IDR_STATE_CD",
				"AmetrixField"  => "state",
				"dataType"  	=> "DMA_state",
				"use" 			=> true,
				"checkDupl" 	=> false
			),
			11 => array(
				"sourceField"	=> "mass_dma",
				"AmetrixField"  => "mass_dma",
				"dataType"  	=> "DMA_Nielsen",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			12 => array(								//NOT USING THIS ONE BECUASE THE PREVIOUS COLUMN IS THE SAME BUT SLIGHTLY BETTER
				"sourceField"	=> "IDR_DMA_NAME",
				"AmetrixField"  => "",
				"dataType"  	=> "DMA_Nielsen",
				"use" 			=> false,
				"checkDupl" 	=> false
			),
			13 => array(
				"sourceField"	=> "calls",
				"AmetrixField"  => "call_count",
				"dataType"  	=> "int",
				"use" 			=> true,
				"checkDupl" 	=> false
			)
		)
	),
	"r5" => array( 							//MASS MEDIA CALL VOLUME (Filename contains 'L_CTL')
		"expFileExt" 		=> "XLSX",
		"expNumCols" 		=> 8,        		
		"worksheetToRead" 	=> 0,	
		"tableNameSuffix"   => "TFNLookup",	
		"checkDuplMethod"   => "normal",		
		"sourceCols" 		=> array(
			1 => array(
				"sourceField"	=> "TFN",
				"AmetrixField"  => "TFN",
				"dataType"  	=> "TFN",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			2 => array(
				"sourceField"	=> "ISCI /KeyCode",
				"AmetrixField"  => "ISCI",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			3 => array(
				"sourceField"	=> "Alternate ISCI/code",
				"AmetrixField"  => "alt_ISCI",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			4 => array(
				"sourceField"	=> "Creative Name",
				"AmetrixField"  => "creative_name",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			5 => array(
				"sourceField"	=> "Legacy",
				"AmetrixField"  => "legacy",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			6 => array(
				"sourceField"	=> "Media",
				"AmetrixField"  => "media_type",
				"dataType"  	=> "string",
				"use" 			=> true,
				"checkDupl" 	=> true
			),
			7 => array(
				"sourceField"	=> "Start Date",	//Source is Excel, Ex. 41274
				"AmetrixField"  => "start_date",
				"dataType"  	=> "date",
				"use" 			=> true,				
				"checkDupl" 	=> true
			),
			8 => array(
				"sourceField"	=> "End Date", 		//Source is Excel, Ex. 41274
				"AmetrixField"  => "end_date",
				"dataType"  	=> "date",
				"use" 			=> true,				
				"checkDupl" 	=> true
			)
		)
	)
);

?>