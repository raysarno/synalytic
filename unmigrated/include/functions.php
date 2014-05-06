<?php
function getBestData($type, $ID, $field, $vars) {

	if($type == 'ctt') {
		if($field == 'cmp') {
			if($vars['cmp_ID'] == 0) {
				$dataQuality = 'bad';
				$dataTitle = 'Data Quality: POOR.  This contact is not associated with a company in the database.  The text displayed is the scraped company name.';
				$dataValue = $vars['ctt_raw_cmp'];
			}
			else {
				$dataQuality = 'good';
				$dataTitle = 'Data Quality: GOOD.  This contact is associated with a company in the database.  The text displayed is the company\'s name from the database.';
				$dataValue = getDataFromID('CRM_cmp','cmp_name',$vars['cmp_ID']);

				if(strpos($vars['ctt_raw_cmp'], $dataValue) === false) {
					$dataQuality = 'warn';
					$dataTitle = 'Data Quality: WARNING.  This contact is associated with a company in the database but it may be incorrect because the company name (' . $dataValue . ') does not match the contact\'s scraped company name(' . $vars['ctt_raw_cmp'] . ').';
				}
			}
		}
        else if($field == 'lcn') {
            if($vars['lcn_ID'] == 0) {
                $dataQuality = 'warn';
                $dataTitle = 'Data Quality: POOR.  This contact is not associated with a location in the database.  The text displayed is the cleaned version of the scraped location name.';
                $dataValue = $vars['ctt_clean_lcn'];
            }
            else {
                $dataQuality = 'good';
                $dataTitle = 'Data Quality: GOOD.  This contact is associated with a location in the database.  The text displayed is the location\'s name from the database.';
                $dataValue = getDataFromID('CRM_lcn','lcn_name',$vars['lcn_ID']);
            }
        }
        else if($field == 'tel' && db_connection()) {
            $dataQuality = 'bad';
            $dataTitle = 'Data Quality: POOR.  There are no phone numbers for this contact or their location in the database.';
            $dataValue = '<span style="color:#555;">No Telephone Numbers Found</span>';

            $tel_SQL = 'SELECT tel_number FROM CRM_tel WHERE ctt = ' . $ID . ' LIMIT 0,1;';
            $tel_QRY = db_query($tel_SQL);

            if(mysqli_num_rows($tel_QRY)) {
                $dataQuality = 'good';
                $dataTitle = 'Data Quality: GOOD.  This telephone number is associated with this contact.';
                
                $tel_ARR = mysqli_fetch_assoc($tel_QRY);
                $dataValue = $tel_ARR['tel_number'];
            }
            else if ($vars['lcn_ID']) {
                $tel_SQL = 'SELECT tel_number FROM CRM_tel WHERE lcn = ' . $vars['lcn_ID'] . ' LIMIT 0,1;';
                $tel_QRY = db_query($tel_SQL);

                if(mysqli_num_rows($tel_QRY)) {
                    $dataQuality = 'warn';
                    $dataTitle = 'Data Quality: AVERAGE.  This telephone number is associated with this contact\'s location.';
                    
                    $tel_ARR = mysqli_fetch_assoc($tel_QRY);
                    $dataValue = $tel_ARR['tel_number'];
                }
            }
        }
	}

	return ('<div class="data-quality ' . $dataQuality . '" title="' . $dataTitle . '" ID="DQ-ctt-' . $ID . '-' . $field . '"></div>' . $dataValue);
}


function string_compare($str_a, $str_b)
{
    $length = strlen($str_a);
    $length_b = strlen($str_b);
 
	$i = 0;
    $segmentcount = 0;
    $segmentsinfo = array();
    $segment = '';
    while ($i < $length)
    {
        $char = substr($str_a, $i, 1);
        if (strpos($str_b, $char) !== FALSE)
        {
            $segment = $segment.$char;
            if (strpos($str_b, $segment) !== FALSE)
            {
                $segmentpos_a = $i - strlen($segment) + 1;
                $segmentpos_b = strpos($str_b, $segment);
                $positiondiff = abs($segmentpos_a - $segmentpos_b);
                $posfactor = ($length - $positiondiff) / $length_b; // <-- ?
                $lengthfactor = strlen($segment)/$length;
                $segmentsinfo[$segmentcount] = array( 'segment' => $segment, 'score' => ($posfactor * $lengthfactor));
            }
            else
            {
                 $segment = '';
                 $i--;
                 $segmentcount++;
             }
         }
         else
         {
             $segment = '';
            $segmentcount++;
         }
         $i++;
     }
 
     // PHP 5.3 lambda in array_map
     $totalscore = array_sum(array_map(function($v) { return $v['score'];  }, $segmentsinfo));
     return $totalscore;
}
function makeSelectRow($formID, $type, $name, $classes, $val, $text, $text_right, $checked) {
	if(!isset($checked)) {$checked = false;}
	$checked = $checked ? ' checked="checked" ' : '';

	echo '<tr><td>';
	echo '<input form="' . $formID . '" type="' . $type . '" class="' . $classes . '" name="' . $name . '" value="' . $val . '" title="' . $val . '" ' . $checked . '/>';
    if($type != 'submit') {
	   echo ' ' . $text . ' <span style="float:right">' . $text_right . '</span>';
    }
	echo '</td></tr>';
}
?>