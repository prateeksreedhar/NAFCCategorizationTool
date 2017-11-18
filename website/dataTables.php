<?php
/*	$q = array();
	$temp = array();

	for($i = 1; $i < 5560; $i++)
	{	$temp[] = $i;
	}

	shuffle($temp);

	for($i = 0; $i < 3000; $i++)
	{	$q[] = $temp[$i];
	}

	echo array_search(0, $q);
	echo array_search(0, $temp);
*/
	if(($handle = fopen("RandomNAFCPosts.csv", "r")) !== FALSE)
	{	$connect  = mysqli_connect("mysql1.cs.clemson.edu", "alzSltns_7ndz", "alzSolution520!^");

		if($connect)
		{	$query = "use alzSolutions_m2oj";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "Drop table NAFCQuestions;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "Drop table NAFCSolutions;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "select 1 from NAFCQuestions limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table NAFCQuestions (ID int not null primary key, dateTime varchar(255) not null, username varchar(255) not null, question text not null);";

				if(!mysqli_query($connect, $query))
				{	echo mysqli_error($connect);
				}
			}

			$query = "select 1 from NAFCSolutions limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table NAFCSolutions (ID int not null, qID int not null, dateTime varchar(255) not null, username varchar(255) not null, question text not null, primary key(ID, qID));";

				if(!mysqli_query($connect, $query))
				{	echo mysqli_error($connect);
				}
			}

			$qCount = 0;
			$aCount = 0;
			$data = fgetcsv($handle);

			while($data = fgetcsv($handle))
			{
				if(strcmp($data[2], "YES") == 0){
					$qCount++;
					$aCount = 0;
					//$user_upvotes = mysqli_real_escape_string($connect, $data[0]);
					$time_stamp = mysqli_real_escape_string($connect, $data[3]);
					$user = mysqli_real_escape_string($connect, $data[4]);
					$post = mysqli_real_escape_string($connect, $data[5]);
					//$thread_name = mysqli_real_escape_string($connect, $data[8]);
					$query = "insert into NAFCQuestions values ('$qCount', '$time_stamp', '$user', '$post');";

					if(!mysqli_query($connect, $query))
					{	echo mysqli_error($connect);
							echo $query;
							echo "\nQuestion Count: $qCount";
							exit();
					}
				}else{
					$aCount++;
					$time_stamp = mysqli_real_escape_string($connect, $data[3]);
					$user = mysqli_real_escape_string($connect, $data[4]);
					$post = mysqli_real_escape_string($connect, $data[5]);
					//$thread_name = mysqli_real_escape_string($connect, $data[8]);
					$query = "insert into NAFCSolutions values ('$aCount','$qCount', '$time_stamp', '$user', '$post');";

					if(!mysqli_query($connect, $query))
					{	echo mysqli_error($connect);
							echo $query;
							echo "\nSolution Count: $aCount";
							exit();
					}
				}
			}
}
/*			$count = 0;
			$qCount = 0;
			$solCount = 0;
			$totalSols = 0;
			$data = fgetcsv($handle);

			while($data = fgetcsv($handle))
			{	if(strcmp($data[3], "Question") == 0)
				{	$count++;

					if(array_search($count, $q))
					{	$qCount++;
						$query = "insert into Questions values ('$qCount',";
						$data[2] = date("Y-m-d H:i:s", strtotime($data[2]));
						$data[0] = mysqli_real_escape_string($connect, $data[0]);
						$data[1] = mysqli_real_escape_string($connect, $data[1]);
						$data[4] = intval($data[4]);
						$query = "$query '$data[2]', '$data[0]', '$data[1]', '$data[4]'";
						$question = trim(mysqli_real_escape_string($connect, "$data[5] $data[6] $data[7] $data[8]"));
						$query = "$query, '$question');";

						if(!mysqli_query($connect, $query))
						{	echo mysqli_error($connect);
							echo $query;
							echo "\nQ Count: $qCount";
							exit();
						}

						$sol = intval($data[4]);
						$totalSols += $sol;

						if($sol > 0)
						{	$inserted = 0;
							$query = "select 1 from Solutions limit 1;";

							if(!mysqli_query($connect, $query))
							{	$query = "create table Solutions (ID int not null primary key, qID int not null, dateTime timestamp not null, category varchar(255), username varchar(255) not null, solution text not null);";

								if(!mysqli_query($connect, $query))
								{	echo mysqli_error($connect);
									echo $query;
									exit();
								}
							}

							while($sol > 0 && $inserted < $sol)
							{	$solCount++;
								$inserted++;
								$query = "insert into Solutions values ('$solCount', '$qCount',";
								$sData = fgetcsv($handle);
								//echo "\n Question $qCount: $sData[3] $solCount.";
								$sData[2] = date("Y-m-d H:i:s", strtotime($sData[2]));
								$sData[0] = mysqli_real_escape_string($connect, $sData[0]);
								$sData[1] = mysqli_real_escape_string($connect, $sData[1]);
								$query = "$query '$sData[2]', '$sData[0]', '$sData[1]'";
								$solution = trim(mysqli_real_escape_string($connect, "$sData[5] $sData[6] $sData[7] $sData[8]"));
								$query = "$query, '$solution');";

								if(!mysqli_query($connect, $query))
								{	echo mysqli_error($connect);
									echo $query;
									echo "\nS Count: $solCount";
									exit();
								}
							}
						}

						if($qCount == 3000)
						{	break;
						}
					}
				}
			}

			echo "\nQuestion Count: $qCount at $count";
			echo "\nSolution Count: $totalSols - $solCount";
		}
		else
			echo mysqli_error($connect);*/
	}
?>
