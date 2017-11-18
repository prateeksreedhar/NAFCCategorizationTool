<?php
	require_once "db.php";
	$connect = dbConnect();
	$query = "use alzSolutions_m2oj;";
	
	if(!mysqli_query($connect, $query))
	{	echo mysqli_error($connect);
	}
	
	$handle = fopen("reclassification.csv", "r");
	$data = fgetcsv($handle);
	$count = 0;
	$table = "Problem";
	
	while($data = fgetcsv($handle))
	{	//var_dump($data);
		//break
		if(strcasecmp("Solutions", $data[0]) == 0)
		{	$table = "Solution";
			$count = 0;
			continue;
		}
		
		if(strlen($data[0]) > 1 && ctype_digit($data[0][1]))
		{	$count++;
			$query = "insert into ".$table."Cat values ('".$count."', '".addslashes($data[1])."');";
			//echo $query;
			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}
		}
		else if(strlen($data[0]) == 1)
		{	$query = "insert into ".$table."SubCat values('".$data[0]."', '".$count."', '".addslashes($data[1])."');";
	
			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}
		}
	}
?>