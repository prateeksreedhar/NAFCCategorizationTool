<?php
	require_once "db.php";
	$connect = dbConnect();
	$query = "use alzSolutions_m2oj";
	
	if(!mysqli_query($connect, $query))
	{	echo mysqli_error($connect);
		exit();
	}
	$handle = fopen("Problems.csv", "w");
	$query = "select question from Problems";
	
	if($res = mysqli_query($connect, $query))
	{	while($row = mysqli_fetch_row($res))
		{	$str = array($row[0] ."\n");
			fputcsv($handle, $str);
		}
		
	}
?>