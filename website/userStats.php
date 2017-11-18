<?php
if(($handle = fopen("UserStatistics.csv", "r")) !== FALSE)
	{	$connect  = mysqli_connect("mysql1.cs.clemson.edu", "alzSltns_7ndz", "alzSolution520!^");

		if($connect)
		{	$query = "use alzSolutions_m2oj";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "Drop table NAFCUserStatsCat;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

      $query = "Drop table NAFCUserStatsProb;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "select 1 from NAFCUserStatsCat limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table NAFCUserStatsCat (ID int not null primary key, category varchar(255) not null);";

				if(!mysqli_query($connect, $query))
				{	echo mysqli_error($connect);
				}
			}

      $query = "select 1 from NAFCUserStatsProb limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table NAFCUserStatsProb (ID char(2) not null, cID int not null, problem text not null, primary key(ID, cID));";

				if(!mysqli_query($connect, $query))
				{	echo mysqli_error($connect);
				}
			}

			$qCount = 0;
            $aCount = 'a';

			while($data = fgetcsv($handle))
			{	if(!empty($data[0]))
				{	$qCount++;
                    $str = mysqli_real_escape_string($connect, $data[0]);
                    $query = "insert into NAFCUserStatsCat values ('$qCount', '$str');";

                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                        echo $query;
                        echo "\nQ Count: $qCount";
                        exit();
                    }

                    $aCount = 'a';
                }
                if(!empty($data[1]))
                {   $str = mysqli_real_escape_string($connect, $data[1]);
                    $query = "insert into NAFCUserStatsProb values ('$aCount', '$qCount', '$str');";

                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                        echo $query;
                        echo "\nA Count: $aCount";
                        exit();
                    }

                    $aCount++;
                }
			}
		}
		else
			echo mysqli_error($connect);
	}
  ?>
