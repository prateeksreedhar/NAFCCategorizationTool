<?php
	require_once "db.php";
        $connect = dbConnect();
        $query = "use alzSolutions_m2oj";
        
        if(!mysqli_query($connect, $query))
        {	echo mysqli_error($connect);
            exit();
        }
	
    $handle = fopen("rater_2500.csv", "w");
    $str = "Problem/Soltuion,Number,hkroger,snehal,eschare,# Category, Category\n";
    fwrite($handle, $str);
    $count = 1;
    $raters = array('hkroger', 'snehal', 'eschare');
    //var_dump($raters);
    

    while($count < 2501)
    {   echo $count ."\n";
        $pCat = array(array(array()));
        for($i = 0; $i < 11; $i++)
        {   $t = $i + 1;
            $query = "Select ID from ProblemSubCat where pcID = ". $t .";";

            if($result = mysqli_query($connect, $query))
            {   while($row = mysqli_fetch_row($result))
                {   $pCat[$raters[0]][$i][$row[0]] = 0;
                    $pCat[$raters[1]][$i][$row[0]] = 0;
                    $pCat[$raters[2]][$i][$row[0]] = 0;
                }
            }
            else
            {   echo mysqli_error($connect);
                exit();
            }

            $pCat[$raters[0]][$i]['Other'] = 0;
            $pCat[$raters[1]][$i]['Other'] = 0;
            $pCat[$raters[2]][$i]['Other'] = 0;
        }

        foreach($raters as $rater)
        {   $query = "select category, subCategory from CategorizedProblems where pID = ". $count ." and username = '". $rater ."';";
            //echo $query;

            if($res = mysqli_query($connect, $query))
            {   if(mysqli_num_rows($res) > 0)
                {   while($row = mysqli_fetch_row($res))
                    {   $pCat[$rater][$row[0] - 1][$row[1]] = 1;
                    }
                }
            }
            else
            {	echo mysqli_error($connect);
                echo $query;
                exit();
            }

            $query = "select pcID from CatProbOther where pID = ". $count ." and username = '". $rater ."';";

            if($res = mysqli_query($connect, $query))
            {   if(mysqli_num_rows($res) > 0)
                {   while($row = mysqli_fetch_row($res))
                    {   $pCat[$rater][$row[0] - 1]['Other'] = 1;
                    }
                }
            }
            else
            {   echo mysqli_error($connect);
                echo $query;
                exit();
            }
        }

        $sum = 0;
        $index = -1;
        $subIndex = '';

        for($i = 0; $i < 11; $i++)
        {   $t = $i + 1;
            $query = "Select ID from ProblemSubCat where pcID = ". $t .";";
                
            if($result = mysqli_query($connect, $query))
            {   while($row = mysqli_fetch_row($result))
                {   $val = $pCat['hkroger'][$i][$row[0]] + $pCat['snehal'][$i][$row[0]] + $pCat['eschare'][$i][$row[0]];

                    if($val == 3)
                    {   $sum = $val;
                        $index = $i;
                        $subIndex = $row[0];
                    }
                    else if($val > $sum)
                    {   $sum = $val;
                        $index = $i;
                        $subIndex = $row[0];
                    }
                }
            }

            $val = $pCat['hkroger'][$i]['Other'] + $pCat['snehal'][$i]['Other'] + $pCat['eschare'][$i]['Other'];

            if($val > $sum)
            {   $sum = $val;
                $index = $i;
                $subIndex = 'Other';
            }
        }

        if($sum <= 1)
        {   $str = "Problem,$count,*,*,*,*,*\n";
            fwrite($handle, $str);
        }
        else
        {   $num = $index + 1;
            $query = "select category from ProblemCat where ID = ". $num .";";

            if($res = mysqli_query($connect, $query))
            {   $row = mysqli_fetch_row($res);
                $str = "Problem,$count,". $pCat[$raters[0]][$index][$subIndex] .",". $pCat[$raters[1]][$index][$subIndex] .",". $pCat[$raters[2]][$index][$subIndex] .",". $num ."". $subIndex .",". $row[0] ."\n";
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

                if($res0 = mysqli_query($connect, $query))
                {   //echo "\n Number of sols: ".mysqli_num_rows($res0);
                    while($row0 = mysqli_fetch_row($res0))
                    {   //echo "\n sol count: ".$solCount;
                        
                        $sCat = array(array(array()));
                        
                        for($i = 0; $i < 9; $i++)
                        {   $t = $i + 1;
                            $query = "Select ID from SolutionSubCat where scID = ". $t .";";

                            if($result = mysqli_query($connect, $query))
                            {   while($row2 = mysqli_fetch_row($result))
                                {   $sCat[$raters[0]][$i][$row2[0]] = 0;
                                    $sCat[$raters[1]][$i][$row2[0]] = 0;
                                    $sCat[$raters[2]][$i][$row2[0]] = 0;
                                }
                            }
                            else
                            {   echo mysqli_error($connect);
                                exit();
                            }

                            $sCat[$raters[0]][$i]['Other'] = 0;
                            $sCat[$raters[1]][$i]['Other'] = 0;
                            $sCat[$raters[2]][$i]['Other'] = 0;
                        }
                        
                        foreach($raters as $rater)
                        {   $query2 = "select category, subCategory from CategorizedSolutions where sID = ". $row0[0] ." and username = '". $rater ."';";

                            if($res2 = mysqli_query($connect, $query2))
                            {   if(mysqli_num_rows($res2) > 0)
                                {   while($row2 = mysqli_fetch_row($res2))
                                    {   $sCat[$rater][$row2[0] - 1][$row2[1]] = 1;
                                    }
                                }
                            }
                            else
                            {	echo mysqli_error($connect);
                                echo $query2;
                                exit();
                            }

                            $query2 = "select scID from CatSolOther where sID = ". $row0[0] ." and username = '". $rater ."';";

                            if($res2 = mysqli_query($connect, $query2))
                            {   if(mysqli_num_rows($res2) > 0)
                                {   while($row2 = mysqli_fetch_row($res2))
                                    {   $sCat[$rater][$row2[0] - 1]['Other'] = 1;
                                    }
                                }
                            }
                            else
                            {   echo mysqli_error($connect);
                                echo $query2;
                                exit();
                            }
                        }

                        $sum = 0;
                        $index = -1;
                        $subIndex = '';
                        
                        for($i = 0; $i < 9; $i++)
                        {   $t = $i + 1;
                            $query = "Select ID from SolutionSubCat where scID = ". $t .";";
                                
                            if($result = mysqli_query($connect, $query))
                            {   while($row0 = mysqli_fetch_row($result))
                                {   $val = $sCat['hkroger'][$i][$row0[0]] + $sCat['snehal'][$i][$row0[0]] + $sCat['eschare'][$i][$row0[0]];

                                    if($val == 3)
                                    {   $sum = $val;
                                        $index = $i;
                                        $subIndex = $row0[0];
                                    }
                                    else if($val > $sum)
                                    {   $sum = $val;
                                        $index = $i;
                                        $subIndex = $row0[0];
                                    }
                                }
                            }

                            $val = $sCat['hkroger'][$i]['Other'] + $sCat['snehal'][$i]['Other'] + $sCat['eschare'][$i]['Other'];

                            if($val > $sum)
                            {   $sum = $val;
                                $index = $i;
                                $subIndex = 'Other';
                            }
                        }

                        if($sum <= 1)
                        {   $str = "Solution,$count.$solCount,*,*,*,*,*\n";
                            fwrite($handle, $str);
                        }
                        else
                        {   $num = $index + 1;
                            $query = "select category from SolutionCat where ID = ". $num .";";

                            if($res3 = mysqli_query($connect, $query))
                            {   $row3 = mysqli_fetch_row($res3);
                                $str = "Solution,$count.$solCount,". $sCat[$raters[0]][$index][$subIndex] .",". $sCat[$raters[1]][$index][$subIndex] .",". $sCat[$raters[2]][$index][$subIndex] .",". $num ."". $subIndex .",". $row3[0] ."\n";
                                fwrite($handle, $str);
                            }
                            else
                                echo mysqli_error($connect);
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