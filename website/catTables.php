<?php
	if(($handle = fopen("NAFCCategories.csv", "r")) !== FALSE)
	{	$connect  = mysqli_connect("mysql1.cs.clemson.edu", "alzSltns_7ndz", "alzSolution520!^");

		if($connect)
		{	$query = "use alzSolutions_m2oj";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "Drop table NAFCCat;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

      $query = "Drop table NAFCSubCat;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "Drop table NAFCSubSubCat;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "select 1 from NAFCCat limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table NAFCCat (ID int not null primary key, category varchar(255) not null);";

				if(!mysqli_query($connect, $query))
				{	echo mysqli_error($connect);
				}
			}

      $query = "select 1 from NAFCSubCat limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table NAFCSubCat (ID char(3) not null, cID int not null, subCategory text not null, primary key(ID, cID));";

				if(!mysqli_query($connect, $query))
				{	echo mysqli_error($connect);
				}
			}

			$query = "select 1 from NAFCSubSubCat limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table NAFCSubSubCat (ID char(3) not null, scID char(3) not null, cID int not null, subsubCategory text not null, primary key(ID, cID, scID));";

				if(!mysqli_query($connect, $query))
				{	echo mysqli_error($connect);
				}
			}

			$cCount = 0;
      $scCount = 'a';
			$sscCount = 'A';
			$data = fgetcsv($handle);

			while($data = fgetcsv($handle))
			{	if(!empty($data[0]))
				{	$cCount++;
					$scCount = 'a';
					$sscCount = 'A';
                    $str = mysqli_real_escape_string($connect, $data[0]);
                    $query = "insert into NAFCCat values ('$cCount', '$str');";

                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                        echo $query;
                        echo "\nCategory Count: $cCount";
                        exit();
                    }

										if(!empty($data[1])){
	                    $str = mysqli_real_escape_string($connect, $data[1]);
	                    $query = "insert into NAFCSubCat values ('$scCount', '$cCount', '$str');";

	                    if(!mysqli_query($connect, $query))
	                    {	echo mysqli_error($connect);
	                        echo $query;
	                        echo "\nSubCategory Count: $scCount";
	                        exit();
	                    }
										}

										if(!empty($data[2])){
											$str = mysqli_real_escape_string($connect, $data[2]);
	                    $query = "insert into NAFCSubSubCat values ('$sscCount', '$scCount', '$cCount', '$str');";

	                    if(!mysqli_query($connect, $query))
	                    {	echo mysqli_error($connect);
	                        echo $query;
	                        echo "\nSubSubCategory Count: $sscCount";
	                        exit();
	                    }
									  }
                }
								//if the sub-category is present but not the category in the same row of csv file
                else if(empty($data[0]) && !empty($data[1]))
                {   $scCount++;
										$sscCount = 'A';
										$str = mysqli_real_escape_string($connect, $data[1]);
                    $query = "insert into NAFCSubCat values ('$scCount', '$cCount', '$str');";

                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                        echo $query;
                        echo "\nSubCategory Count: $scCount";
                        exit();
                    }
										if(!empty($data[2])){

											$str = mysqli_real_escape_string($connect, $data[2]);
											$query = "insert into NAFCSubSubCat values ('$sscCount', '$scCount', '$cCount', '$str');";
											if(!mysqli_query($connect, $query))
	                    {	echo mysqli_error($connect);
	                        echo $query;
	                        echo "\nSubSubCategory Count: $scCount";
	                        exit();
	                    }
										}
                }
								else{
									$sscCount++;
									$str = mysqli_real_escape_string($connect, $data[2]);
									$query = "insert into NAFCSubSubCat values ('$sscCount', '$scCount', '$cCount', '$str');";

									if(!mysqli_query($connect, $query))
									{	echo mysqli_error($connect);
											echo $query;
											echo "\nSubSubCategory Count: $scCount";
											exit();
									}
								}

			}
		}
		else
			echo mysqli_error($connect);
	}

/*    if(($handle = fopen("sCat.csv", "r")) !== FALSE)
	{	$connect  = mysqli_connect("mysql1.cs.clemson.edu", "alzSltns_7ndz", "alzSolution520!^");

		if($connect)
		{	$query = "use alzSolutions_m2oj";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "Drop table SolutionCat;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

            $query = "Drop table SolutionSubCat;";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
			}

			$query = "select 1 from SolutionCat limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table SolutionCat (ID int not null primary key, category varchar(255) not null);";

				if(!mysqli_query($connect, $query))
				{	echo mysqli_error($connect);
				}
			}

            $query = "select 1 from SolutionSubCat limit 1;";

			if(!mysqli_query($connect, $query))
			{	$query = "create table SolutionSubCat (ID char(2) not null, scID int not null, subCategory text not null, primary key(ID, scID));";

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
                    $query = "insert into SolutionCat values ('$qCount', '$str');";

                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                        echo $query;
                        echo "\nQ Count: $qCount";
                        exit();
                    }

                    $aCount = 'a';
                }
                else if(!empty($data[1]))
                {   $str = mysqli_real_escape_string($connect, $data[2]);
                    $query = "insert into SolutionSubCat values ('$aCount', '$qCount', '$str');";

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
	}*/

?>
