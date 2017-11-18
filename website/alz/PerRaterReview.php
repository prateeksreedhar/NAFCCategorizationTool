<?php
	require_once "db.php";
        $connect = dbConnect();
        $query = "use alzSolutions_m2oj";
        
        if(!mysqli_query($connect, $query))
        {	echo mysqli_error($connect);
            exit();
        }
	
    $handle = fopen("rater.csv", "w");
    $str = "Problem/Soltuion,Number,# Category, Category\n";
    fwrite($handle, $str);
    $count = 1;
    $raters = array('hkroger', 'snehal', 'eschare');
    //var_dump($raters);
    

    while($count < 251)
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
        fwrite($handle, $str);

        for($i = 1; $i < 11; $i++)
        {   $str = ",,". $pCat['hkroger'][$i] .",". $pCat['snehal'][$i] .",". $pCat['eschare'][$i] ."\n";
            fwrite($handle, $str);
        }*/

        $sum = 0;
        $index = -1;

        for($i = 0; $i < 11; $i++)
        {   $val = $pCat['hkroger'][$i] + $pCat['snehal'][$i] + $pCat['eschare'][$i];

            if($val == 3)
            {   $sum = $val;
                $index = $i;
                break;
            }
            else if($val > $sum)
            {   $index = $i;
                $sum = $val;
            }
        }

        if($sum <= 1)
        {   $str = "Problem,$count,*,*\n";
            fwrite($handle, $str);
        }
        else
        {   $num = $index + 1;
            $query = "select category from ProblemCat where ID = ". $num .";";

            if($res = mysqli_query($connect, $query))
            {   $row = mysqli_fetch_row($res);
                $str = "Problem,$count,". $num .",". $row[0] ."\n";
                fwrite($handle, $str);
            }
            else
                echo mysqli_error($connect);
        }

        $query = "select solCount from Problems where ID = ". $count .";";

        if($res = mysqli_query($connect, $query))
        {   $row = mysqli_fetch_row($res);

            if(intval($row[0]) > 0)
            {   $query = "select * from Solutions where pID = ". $count .";";
                $solCount = 1;

                if($res = mysqli_query($connect, $query))
                {   while($row = mysqli_fetch_row($res))
                    {   $sCat = array(array());
                        $sCat[$raters[0]] = array_fill(0, 15, 0);
                        $sCat[$raters[1]] = array_fill(0, 15, 0);
                        $sCat[$raters[2]] = array_fill(0, 15, 0);
                        
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
                                    {   $sCat[$rater][$row2[0] - 1] = 1;
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
                        fwrite($handle, $str);

                        for($i = 1; $i < 15; $i++)
                        {   $str = ",,". $sCat['hkroger'][$i] .",". $sCat['snehal'][$i] .",". $sCat['eschare'][$i] ."\n";
                            fwrite($handle, $str);
                        }*/

                        $sum = 0;
                        $index = -1;

                        for($i = 0; $i < 15; $i++)
                        {   $val = $sCat['hkroger'][$i] + $sCat['snehal'][$i] + $sCat['eschare'][$i];

                            if($val == 3)
                            {   $sum = $val;
                                $index = $i;
                                break;
                            }
                            else if($val > $sum)
                            {   $index = $i;
                                $sum = $val;
                            }
                        }

                        if($sum <= 1)
                        {   $str = "Solution,$count.$solCount,*,*\n";
                            fwrite($handle, $str);
                        }
                        else
                        {   $num = $index + 1;
                            $query = "select category from SolutionCat where ID = ". $num .";";

                            if($res = mysqli_query($connect, $query))
                            {   $row = mysqli_fetch_row($res);
                                $str = "Solution,$count.$solCount,". $num .",". $row[0] ."\n";
                                fwrite($handle, $str);
                            }
                            else
                                mysqli_error($connect);
                        }

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