<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
<link rel="stylesheet" type="text/css" href="styles.css">
<title>
Information on the date you entered
</title>
</head>
<body style="color: rgb(205, 92, 92); background-color: rgb(180, 213, 251);" alink="rgb(205, 92, 92)" link="rgb(205, 92, 92)" vlink="rgb(205, 92, 92)">

<div id="main">
	<img style="position: relative; left:-120; width: 362px; height: 398px;" alt="CA_clipped" src="CA_border_clipped_blue.png">

	<?php
	
	function displayMonth($aMonth, $aYear)
	{
	// Display a calendar for the specified month
	
		if (($aYear <= 1752) || (($aYear == 1752) && ($aMonth < 9)))
		{
			$calendar = 2;
			$JDate = JulianToJD($aMonth, 1, $aYear);
		}
		else
		{
			$calendar = 1;
			$JDate = GregorianToJD($aMonth, 1, $aYear);
		}
	
		$daysInMonth = cal_days_in_month($calendar, $aMonth, $aYear);
	
	
		print "<p style=\"position: relative; top: -235; left: 60;\">" . JDMonthName($JDate, $calendar) . "</p>";
	
		print "<p><table border=\"1\" width=\"98%\" style=\"position: relative; top:-250; left: 60;\">";
		print "<tr><td>Sun</td><td>Mon</td><td>Tue</td><td>Wed</td><td>Thur</td><td>Fri</td><td>Sat</td></tr>";
	
		for($k=0; $k<JDDayOfWeek($JDate, 0); $k++)
		{
			print "<td width=\"14%\"></td>";
		}			
	
		$newLine=JDDayOfWeek($JDate, 0);
	
		for ($i=0; $i<$daysInMonth; $i++)
		{
			if ( (($i+1 == 25) && $aMonth == 12) || ((($i+$newLine) % 7) == 0) )
			{
				print "<td width=\"14%\"><font color=\"#FF0000\">" . ($i+1) . "</font></td>";
			}
			else if ((($i+1+$newLine) % 7) == 0)
			{
				print "<td width=\"14%\"><font color=\"#FF0000\">" . ($i+1) . "</font></td></tr><tr>";
			}
			else
			{
				print "<td width=\"14%\">" . ($i+1) . "</td>";
			}		
		}
		print "</tr></table></p>";
	}
	
	function numDaysInMonth($monthVal, $yearVal)
	{
	// Return the number of days in the specified month
	// for either the Julian or Gregorian calendars
	
		if (($yearVal <= 1752) || (($yearVal == 1752) && ($monthVal < 9)))
		{
			$calendar = 2;
			$JDate = JulianToJD($monthVal, 1, $yearVal);
		}
		else
		{
			$calendar = 1;
			$JDate = GregorianToJD($monthVal, 1, $yearVal);
		}
	
		return $monthVal==0?0:cal_days_in_month($calendar, $monthVal, $yearVal);
	}
	
	
	
	// MAIN SUB-ROUTINE
	
	
	$months = array("january" => 1, "february" => 2, "march" => 3, "april" => 4, "may" => 5, "june" => 6, "july" => 7, "august" => 8, "september" => 9, "october" => 10, "november" => 11, "december" => 12);
	$shortMonths = array("jan" => 1, "feb" => 2, "mar" => 3, "apr" => 4, "may" => 5, "jun" => 6, "jul" => 7, "aug" => 8, "sep" => 9, "oct" => 10, "nov" => 11, "dec" => 12);
	
	if (isset($_REQUEST['inDate'])) {
	  $inDate = $_REQUEST['inDate'];
	}
	
	if (isset($_REQUEST['monthName'])) {
	  $monthName = $_REQUEST['monthName'];
	}
	
	$day = strtok($inDate, "/");
	$month = strtok("/");
	$year = strtok("/");
	
	if ($day == FALSE)
	{
		print "<p style=\"position: relative; top: -235; left: 60;\">Please enter a date.</p>";
	}
	else if ($month == FALSE)
	{
		if ($monthName == "---")
		{
			$year = $day;
			$month = 0;
		}
		else
		{
			$year = $day;
			$month = $months[strtolower($monthName)];
		}
		$day = 0;
	}
	else if ($year == FALSE)
	{
		if ($monthName == "---")
		{
			$year = $month;
			$month = $day;
			$day = 0;
		}
		else
		{
			$year = $month;
			$month = $months[strtolower($monthName)];
			$inDate = strtok($inDate, "/") . "/" . $monthName . "/" . strtok("/");
		}
	}
	
	// Check to see if month has been specified as a word, convert to number if it has
	if (!is_numeric($month))
	{
		for ($j=0; $j<12; $j++)
		{
			if ($months[strtolower($month)] == ($j+1))
			{
				$month = ($j+1);
			}
		}
		for ($j=0; $j<12; $j++)
		{
			if ($shortMonths[strtolower($month)] == ($j+1))
			{
				$month = ($j+1);
			}
		}
	}
	
	if (!is_numeric($year) || !is_numeric($month) || !is_numeric($day))
	{
		print "<p style=\"position: relative; top: -235; left: 60;\">Syntax error!</p>";
	}
	else if (($month > 12) || ($month < 0) || ($day > numDaysInMonth($month, $year)) || ($day < 0) || ($year > 9999) || ($year < 1))
	{
		print "<p style=\"position: relative; top: -235; left: 60;\">Invalid input!</p>";
	}
	else
	{
		if ($day != 0) // A full date has been given
		{
		
			// Calculate a Julian Day count for the entered date
			// that will represent it whether it is Julian OR Gregorian.
		    // Assumes that any date entered which is prior
			// to 14/9/1752 will be using the Julian calendar.
			if ($year <= 1752 || ($year == 1752 && ($month < 9 || ($month == 9 && $day < 14))))
			{
				$JDCount = JulianToJD($month, $day, $year);
			}
			else
			{
				$JDCount = GregorianToJD($month, $day, $year);
			}
		
		
			$dayOfWeek = JDDayOfWeek($JDCount, 1);
		
			print "<p style=\"position: relative; top: -235; left: 60;\">" . $inDate . " is a " . $dayOfWeek . "</p>";
			
		}	
		else if ($month != 0) // A month and a year have been given
		{
			displayMonth($month, $year);
		}
		else if ($year != 0) // Just a year has been given
		{
			for ($iterMonth=0; $iterMonth<12; $iterMonth++)
			{
				displayMonth(($iterMonth+1), $year);
			}
		}
	}
	
	?>

	<p><a href="date_fac.html" style="position: relative; top:-240; left: 50"><u>Back</u></a></p>
</div>
</body>
</html>
