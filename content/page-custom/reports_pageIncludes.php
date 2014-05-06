<?php
    //INITIALIZE ALL VARS (PHP)
    if(db_connection()) {
        $chartParams = array();

        //GET MIN AND MAX DATE ACROSS ALL DATA
        $dateRangeSQL = 'SELECT MIN(T1.call_date), MIN(T2.call_date), MIN(T3.call_date), MAX(T1.call_date), MAX(T2.call_date), MAX(T3.call_date) FROM CTL_DAT_r3_callsDM T1, CTL_DAT_r4a_callsMM T2, CTL_DAT_r4b_callsMM T3;';
        $dateRangeQuery = mysqli_query($db_conn, $dateRangeSQL);
        $dateRangeArray = mysqli_fetch_array($dateRangeQuery);

        $minDate = min($dateRangeArray);
        $maxDate = max($dateRangeArray);

        
        $chartParams['filters'] = array();

        if(!isset($_POST['chartParams'])) { //SET DEFAULTS IF THIS IS THE FIRST ARRIVAL TO THE REPORTS PAGE
            $chartParams['reportType']  = "numCalls";
            $chartParams['chartType']   = "Trend";
            $chartParams['primaryDim']  = "Date";
            $chartParams['secondaryDim']= "None";

            $chartParams['filters']['date'] = array(
                'all'       => true,
                'group'     => 'Month',
                'min'       => $minDate,
                'max'       => $maxDate,
                'start'     => $minDate,
                'end'       => $maxDate
            );
            $chartParams['filters']['timeslot'] = array(
                'all'       => true,
                'selected'  => array()
            );
            $chartParams['filters']['market'] = array(
                'all'       => true,
                'group'     => 'DMA',
                'selected'  => array()
            );
            $chartParams['filters']['ethnicity'] = 1;
            $chartParams['filters']['ageGender'] = 1;
            $chartParams['filters']['media'] = array(
                'all'       => true,
                'selected'  => array()
            );
            $chartParams['filters']['creative'] = array(
                'all'       => true,
                'selected'  => array()
            );
        }
    }
?>

<script type="text/javascript" src="https://www.google.com/jsapi"></script>

<script type="text/javascript">
    google.load("visualization", "1", {packages:["corechart"]});
    google.load('visualization', '1', {packages:['table']});
    google.load('visualization', '1', {packages:['geochart']});
    google.setOnLoadCallback(drawChart);

    //INITIALIZE CHART AND DATA VARS GLOBALLY SO THEY CAN BE USED ANYWHERE
    var firstDraw = true;
    var chart;
    var data;
    var chartParams = <?php echo json_encode($chartParams); ?>;

    function drawChart() {
        /*/CLEAR EXISTING CHART IF IT EXISTS
        if(!firstDraw) {chart.clearChart();}
        firstDraw = false;*/
        
        //UPDATE REPORT TYPE OPTIONS
        chartParams.reportType      = $("#reportSummary-reportType").val();
        chartParams.chartType       = $("#reportSummary-chartType").val();
        chartParams.primaryDim      = $("#reportSummary-primaryDim").val();
        chartParams.secondaryDim    = $("#reportSummary-secondaryDim").val();

        //AJAX CALL TO GET JSON FORMATTED DATA
        var jsonChartData = $.ajax({
            url: "/include/Classes/chartData-JSON.php",
            type: 'POST',
            data: {chartParams: JSON.stringify(chartParams)},
            dataType:"json",
            async: false
        }).responseText;

        //PUT JSON DATA INTO GOOGLE DATATABLE FORMAT
        data = new google.visualization.DataTable(jsonChartData);

        //DRAW SELECTED CHART TYPE
        switch (chartParams.chartType) {
            case 'trend':
                drawAreaChart();
                break;
            case 'map':
                drawGeoChart();
                break;
        }

        //DRAW THE DATA TABLE
        var table = new google.visualization.Table(document.getElementById('reportTableWrapper'));
        table.draw(data);
    }
    
    function drawAreaChart() {
        var options = {
            title: 'Number of Calls by Month',
            hAxis: {title: 'Month',  titleTextStyle: {color: '#333'}},
            vAxis: {minValue: 0}
        };

        //DRAW THE CHART
        var chart = new google.visualization.AreaChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    }

    function drawGeoChart() {
        var options = {
            displayMode:        'regions', 
            region:             'US',
            resolution:         'metros',
            colorAxis:          {minValue: 1,  colors: ['#99CCFF', '#003399']}
        };

        var chart = new google.visualization.GeoChart(document.getElementById('chart_div'));
        chart.draw(data, options);
    };

    function applyFilters() {
        //FIRST STORE ALL VALUES INTO CHARTPARAMS OBJECT OF VARS
        //Get date Filters
        chartParams.filters.date.start      = $("#startDateInput").val();
        chartParams.filters.date.end        = $("#endDateInput").val();

        if ((chartParams.filters.date.start == chartParams.filters.date.min) && (chartParams.filters.date.end == chartParams.filters.date.max)) {
            chartParams.filters.date.all = true;
        }
        else {
            chartParams.filters.date.all = false;
        }
        chartParams.filters.date.group      = $("#filter-date-group-select").val();

        //Get timeslot Filters
        if ($("input#timeslot_all").prop("checked")) {
            chartParams.filters.timeslot.all = true;
        }
        else {
            chartParams.filters.timeslot.all = false;
            chartParams.filters.timeslot.selected.length = 0;

            $("input.timeslot:checked").each(function() {
                chartParams.filters.timeslot.selected.push($(this).val());
            });
        }

        //BUILD GEO FILTER FUNCTIONALITY

        //Get ethnicity filters
        chartParams.filters.ethnicity = $("input.ethnicity:checked").val();

        //Get ageGender filters
        chartParams.filters.ageGender = $("input.ageGender:checked").val();

        //Get media Filters
        if ($("input#media_all").prop("checked")) {
            chartParams.filters.media.all = true;
        }
        else {
            chartParams.filters.media.all = false;
            chartParams.filters.media.selected.length = 0;

            $("input.media:checked").each(function() {
                chartParams.filters.media.selected.push($(this).val());
            });
        }

        //Get creative Filters
        if ($("input#creative_all").prop("checked")) {
            chartParams.filters.creative.all = true;
        }
        else {
            chartParams.filters.creative.all = false;
            chartParams.filters.creative.selected.length = 0;

            $("input.creative:checked").each(function() {
                chartParams.filters.creative.selected.push($(this).val());
            });
        }

        //PRINT VARS TO FILTER SUMMARY TABLE
        //Print date Filters
        var tempChildren = $("#filterSummary-date").children();
        var dateFilterText = chartParams.filters.date.all == true ? "ALL DATES" : (chartParams.filters.date.start + " ~ " + chartParams.filters.date.end);

        $("#filterSummary-date").html(dateFilterText).append(tempChildren);
        $("#filter-date-group-select").val(chartParams.filters.date.group);

        //Print timeslot Filters
        $("#filterSummary-timeslot").empty();
        if(chartParams.filters.timeslot.all) {
            $("#filterSummary-timeslot").html("ALL TIMESLOTS");
        }
        else {
            var tempArray = new Array();
            $.each(chartParams.filters.timeslot.selected,function() {
                tempArray.push(getFilterValFromID("timeslot", this.valueOf()));
            });
            $("#filterSummary-timeslot").html(tempArray.join(", "));
        }

        //BUILD GEO FILTER FUNCTIONALITY

        //Print ethnicity Filters
        if(chartParams.filters.ethnicity == 1) {
            $("#filterSummary-ethnicity").html("ALL ETHNIC GROUPS (Total Market)");
        }
        else {
            $("#filterSummary-ethnicity").html(getFilterValFromID("ethnicity", chartParams.filters.ethnicity, 1));
        }

        //Print ageGender Filters
        if(chartParams.filters.ageGender == 1) {
            $("#filterSummary-ageGender").html("ALL AGE / GENDER GROUPS (Adults 18+)");
        }
        else {
            $("#filterSummary-ageGender").html(getFilterValFromID("ageGender", chartParams.filters.ageGender, 0));
        }

        //Print media Filters
        $("#filterSummary-meda").empty();
        if(chartParams.filters.media.all) {
            $("#filterSummary-media").html("ALL MEDIA TYPES");
        }
        else {
            var tempArray = new Array();
            $.each(chartParams.filters.media.selected,function() {
                tempArray.push(getFilterValFromID("media", this.valueOf()));
            });
            $("#filterSummary-media").html(tempArray.join(", "));
        }

        //Print creative Filters
        $("#filterSummary-creative").empty();
        if(chartParams.filters.creative.all) {
            $("#filterSummary-creative").html("ALL CREATIVES");
        }
        else {
            var tempArray = new Array();
            $.each(chartParams.filters.creative.selected,function() {
                tempArray.push(getFilterValFromID("creative", this.valueOf()));
            });
            $("#filterSummary-creative").html(tempArray.join(", "));
        }
    }

    function getFilterValFromID(filterType, ID, codeOrName) {
        if(typeof(codeOrName) === 'undefined') {codeOrName = 0;}

        return $("#" + filterType + "FilterSelectTable input[value='" + ID + "']").parent().siblings().eq(codeOrName).html();
    }

    $(document).ready(function() {
        $( "#tabs" ).tabs({ active: 0, heightStyle: "fill" });

        $("#startDatePicker").datepicker({
            altField:"#startDateInput",
            altFormat:"yy-mm-dd",
            constrainInput: true,
            dateFormat:"yy-mm-dd",
            maxDate: chartParams.filters.date.max,
            minDate: chartParams.filters.date.min
        });
        $("#startDatePicker").datepicker("setDate",chartParams.filters.date.start);
        $("#endDatePicker").datepicker({
            altField:"#endDateInput",
            altFormat:"yy-mm-dd",
            constrainInput: true,
            dateFormat:"yy-mm-dd",
            maxDate: chartParams.filters.date.max,
            minDate: chartParams.filters.date.min
        });
        $("#endDatePicker").datepicker("setDate",chartParams.filters.date.end);

        $("#slider-range").slider({
            range: true,
            min: 0,
            max: 500,
            values: [ 75, 300 ]
        });

        //SELECT/DESELECT ALL FILTERS IF 'SELECT ALL' CHECKBOX IS CHANGED
        $("input.selectAll").click(function() {
            $(this).parents("table.filterSelectTable").children("tbody").children("tr").children("td").children("input").prop("checked", $(this).prop("checked"));
        });

        //AUTO SET 'SELECT ALL' CHECKBOX IF NEEDED WHEN OTHER FILTER CHECKBOXES ARE CHANGED
        $(".filterSelectTable input[type='checkbox']:not(.selectAll)").click(function() {
            filterType = $(this).attr("name");
            if($(this).prop("checked")) {
                if($("input." + filterType + ":not(:checked)").length == 0) {
                    $("input#" + filterType + "_all").prop("checked", true);
                }
            }
            else {
                $("input#" + filterType + "_all").prop("checked", false);
            }
        });
    });
</script>



