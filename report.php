<!doctype html >
<html lang="en" >
<head>
<title> Temperature recordings from SenseHat </title>
<meta charset="utf-8" >
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js" > </script>
<!-- Load the AJAX API -->
<script type="text/javascript" src="https://www.google.com/jsapi" > </script>
<script type="text/javascript" >
    
	function BackToReports()
	{
		window.location = 'index.php';   
	}
    
    function getQueryString(field, url) {
		
			// Gets the querystring
			var href = url ? url : window.location.href;
			var reg = new RegExp( '[?&]' + field + '=([^&#]*)', 'i' );
			var string = reg.exec(href);
			return string ? string[1] : null;	
	}
    
    
    function getData() {
		
		var report = getQueryString('report');
		var date_start = getQueryString('date_start');
		var date_end = getQueryString('date_end');
		
		//Set up jQuery ajax call to generic php page to handle MySQL data access and return data object
		//Passes in parameters so it knows what SQl query to run
        jQuery.ajax({
            url: 'report_ajax_handler.php',
            data: 
            { 
				"report": report,
				"start_date": date_start, 
				"end_date": date_end, 
			},
            type: 'GET',
            dataType: 'json',
            success: function( data, jqXHR ) {
                if( data == "null" ) {
                    alert("There is no data!");
                } else {
					//alert( data );
                    drawGraph( data );
                }
            },
            error: function( textStatus ) {
                console.log(" error. damm. ");
            }
        });
    }
    
    //Set up google Charts API
    google.load( "visualization" , "1", { packages: [ "corechart" ] });
    google.setOnLoadCallback( getData );
    
 
    function drawGraph( data ) {
		
		//Sets up Google Line Chart and sets values for titles etc. based on parameters passed to page 
		var report = getQueryString('report');
		var date_start = getQueryString('date_start');
		var date_end = getQueryString('date_end');
		
		var chart_title = report + " report. [Date Range: " + date_start + " : " + date_end + " ]";
		
        for( var i = data.length; i > 0; i-- ) {
            data[i] = data[i - 1];
        }
        data[0] = [ 'Date', report ];
        console.log( data );
        var chartData = google.visualization.arrayToDataTable( data );

        var options = {
            title: chart_title
        };

        var chart = new google.visualization.LineChart( document.getElementById( 'chart_div' ) );

        chart.draw( chartData , options );
        
    }
</script>
</head>
<body>
    <div class="container" >
        <h3>
			<script type="text/javascript" >
				//Some inline JavaScript to change the heading depending on report parameter - inelegant
				var report = getQueryString('report');
				document.write("Line chart showing " + report + " values from SenseHat instruments");
			</script>
        
        </h3>
        
        <!-- Div that will hold the line chart -->
        <div id="chart_div" > </div>
    </div>
    <br /><br />
    <a onclick="javascript:BackToReports()" href="javascript:void(0)">Reports</a>
</body>
</html>
