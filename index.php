<html>
	<head>Select and enter values for reporting:</head>
	<body>
		<script type="text/javascript" >
		
				function validateForm() {
					//Basic form validation - ensures any input matches YYYY-MM-DD format
					//Ultimately will implement date picker
					var date_start = document.forms["sensehatreporter"]["date_start"].value;
					var date_end = document.forms["sensehatreporter"]["date_end"].value;

					if (date_start != "") {
						
						if(isValidDate(date_start) == false)
							{
							alert("Please enter a valid start date YYYY-MM-DD");
							}	
							return false
						else
							{
								return true
							}	
							
					}
					
					if (date_end != "") {
						
						if(isValidDate(date_end) == false)
							{
							alert("Please enter a valid end date YYYY-MM-DD");
							}	
							return false
						else
							{
							return true
							}
							
					}
				}
		
		
				function isValidDate(dateString) {
					
					//Uses regular expression to test the input against a YYYY-MM-DD format date
					var regEx = /^\d{4}-\d{2}-\d{2}$/;
					if(!dateString.match(regEx))
						return false;  // Invalid format
						var d;
					if(!((d = new Date(dateString))|0))
						return false; // Invalid date (or this could be epoch)
					return d.toISOString().slice(0,10) == dateString;
				}
		
		</script>
		
		
		<form name="sensehatreporter" action="report.php" method="get" onsubmit="return validateForm()">
			<br />
			Report Type:
			<br /><br />
			<select name="report">
				<option value="temperature">Temperature</option>
				<option value="pressure">Pressure</option>
				<option value="humidity">Humidity</option>
			</select>
			<br /><br />
			Date Range (YYYY-MM-DD): 
			<input type="text" name="date_start" /> <input type="text" name="date_end" />
			<br /><br />
			<input type="submit">
		</form>
		
	</body>

</html>
