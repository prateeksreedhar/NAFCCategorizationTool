<?php
	require_once "db.php";
        $connect = dbConnect();
        $query = "use alzSolutions_m2oj";
        
        if(!mysqli_query($connect, $query))
        {	echo mysqli_error($connect);
            exit();
        }
	
    $handle = fopen("Problems.csv", "w");
    $str = "Problem,Hunter(hkroger),Snehal,Emma(eschare)\n";
    fwrite($handle, $str);
	$handle2 = fopen("Solutions.csv", "w");
    $str = "Soltuion,Hunter(hkroger),Snehal,Emma(eschare)\n";
    fwrite($handle2, $str);
    $count = 1;
    $raters = array('hkroger', 'snehal', 'eschare');
    //var_dump($raters);
    

    while($count < 2501)
    {   $pCat = array(array());
		$pCat[$raters[0]] = array_fill(0, 11, 0);
		$pCat[$raters[1]] = array_fill(0, 11, 0);
		$pCat[$raters[2]] = array_fill(0, 11, 0);

		foreach($raters as $rater)
        {   $query = "select distinct category from CategorizedProblems where pID = ". $count ." and username = '". $rater ."';";
            //echo $query;

            if($res = mysqli_query($connect, $query))
            {   if(mysqli_num_rows($res) > 0)
                {   while($row = mysqli_fetch_row($res))
                    {   $pCat[$rater][$row[0] - 1] = 1;
                    }
                }
            }
            else
            {	echo mysqli_error($connect);
                echo $query;
                exit();
            }

            /*echo "\n\n $rater: ";
            foreach($pCat[$rater] as $val)
            {   echo "$val\n";
            }*/

            $query = "select pcID from CatProbOther where pID = ". $count ." and username = '". $rater ."';";

            if($res = mysqli_query($connect, $query))
            {   if(mysqli_num_rows($res) > 0)
                {   while($row = mysqli_fetch_row($res))
                    {   $pCat[$rater][$row[0] - 1] = 1;
                    }
                }
            }
            else
            {   echo mysqli_error($connect);
                echo $query;
                exit();
            }
        }

        /*$str = "Problem,$count,". $pCat['hkroger'][0] .",". $pCat['snehal'][0] .",". $pCat['eschare'][0] ."\n";
        fwrite($handle, $str);*/
		
		$val = array(0, 0, 0);
		$chk = array(false, false, false);
		
        for($i = 0; $i < 11; $i++)
        {   if(!$chk[0])
			{	if($pCat['hkroger'][$i] > 0)
				{	$val[0] = $i + 1;
					$chk[0] = true;
					//echo "hunter: ".$i."\n";
				}
			}
			
			if(!$chk[1])
			{	if($pCat['snehal'][$i] > 0)
				{	$val[1] = $i + 1;
					$chk[1] = true;
					//echo "snehal: ".$i."\n";
				}
			}
			
			if(!$chk[2])
			{	if($pCat['eschare'][$i] > 0)
				{	$val[2] = $i + 1;
					$chk[2] = true;
					//echo "emma: ".$i."\n";
				}
			}
        }
		
		$str = $count.",".$val[0].",".$val[1].",".$val[2]."\n";
        fwrite($handle, $str);

        $query = "select solCount from Problems where ID = ". $count .";";

        if($res = mysqli_query($connect, $query))
        {   $row = mysqli_fetch_row($res);

            if(intval($row[0]) > 0)
            {   $query = "select * from Solutions where pID = ". $count .";";
                $solCount = 1;

                if($res = mysqli_query($connect, $query))
                {   while($row = mysqli_fetch_row($res))
                    {   $sCat = array(array());
                        $sCat[$raters[0]] = array_fill(0, 9, 0);
                        $sCat[$raters[1]] = array_fill(0, 9, 0);
                        $sCat[$raters[2]] = array_fill(0, 9, 0);
                        
                        foreach($raters as $rater)
                        {   $query2 = "select distinct category from CategorizedSolutions where sID = ". $row[0] ." and username = '". $rater ."';";

                            if($res2 = mysqli_query($connect, $query2))
                            {   if(mysqli_num_rows($res2) > 0)
                                {   while($row2 = mysqli_fetch_row($res2))
                                    {   $sCat[$rater][$row2[0] - 1] = 1;
                                    }
                                }
                            }
                            else
                            {	echo mysqli_error($connect);
                                echo $query2;
                                exit();
                            }

                            $query2 = "select scID from CatSolOther where sID = ". $row[0] ." and username = '". $rater ."';";

                            if($res2 = mysqli_query($connect, $query2))
                            {   if(mysqli_num_rows($res2) > 0)
                                {   while($row2 = mysqli_fetch_row($res2))
                                    {   $sCat[$rater][$row2[0] - 1] = 2;
                                    }
                                }
                            }
                            else
                            {   echo mysqli_error($connect);
                                echo $query2;
                                exit();
                            }
                        }

                        /*$str = "Solution,$count.$solCount,". $sCat['hkroger'][0] .",". $sCat['snehal'][0] .",". $sCat['eschare'][0] ."\n";
                        fwrite($handle, $str);*/

						$val = array(0, 0, 0);
						$chk = array(false, false, false);
						
                        for($i = 0; $i < 9; $i++)
						{   if(!$chk[0])
							{	if($sCat['hkroger'][$i] > 0)
								{	$val[0] = $i + 1;
									$chk[0] = true;
								}
							}
							
							if(!$chk[1])
							{	if($sCat['snehal'][$i] > 0)
								{	$val[1] = $i + 1;
									$chk[1] = true;
								}
							}
							
							if(!$chk[2])
							{	if($sCat['eschare'][$i] > 0)
								{	$val[2] = $i + 1;
									$chk[2] = true;
								}
							}
						}
						
						$str = $count.".".$solCount.",".$val[0].",".$val[1].",".$val[2]."\n";
						fwrite($handle2, $str);

                        $solCount++;
                    }
                }
                else
                {   mysqli_error($connect);
                    echo $query;
                    exit();
                }
            }
        }
        else
        {   echo mysqli_error($connect);
            echo $query;
            exit();
        }

        $count++;
    }

    fclose($handle);
?>