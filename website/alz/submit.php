<html>
    <head>
        <title>
            NAFC Categorization Tool | Clemson University | IMPACT Project
        </title>
    </head>
    <body>
        <h2>Storing your analysis!<br>You will be redirected in 5 seconds.</h2>
        <?php
        session_start();
            require_once "db.php";
            $connect = dbConnect();
            $query = "use alzSolutions_m2oj;";

            if(!$res = mysqli_query($connect, $query))
            {   echo mysqli_error($connect);
            }

            $showPage = FALSE;

            //$user = $_SERVER['REMOTE_USER'];
            $user = "";

            echo "<div class=centered><h2>NAFC Categorization Tool</h2></div>";

            if(isset($_POST['user']))
            {   $user = $_POST['user'];

                $query = "select * from Users where users='". $user ."';";
                if($res = mysqli_query($connect, $query))
                {   if(mysqli_num_rows($res) > 0)
                    {   $row = mysqli_fetch_row($res);

                        if(!is_null($row[0]))
                            $showPage = TRUE;
                    }
                }
                else
                {   echo mysqli_error($connect);
                }
            }
            else
            {   echo "<h1> You don't have access to this tool!</h1>";
            }

            if($showPage)
            {   if(!mysqli_query($connect, $query))
                {	echo mysqli_error($connect);
                }

                $query = "delete from NAFCCategorizedProblems where username='". $_POST['user'] ."' and pID=". $_POST['pID'] .";";
                if(!mysqli_query($connect, $query))
                {	echo mysqli_error($connect);
                }
                /*$query = "delete from CatProbEmotion where username='". $_POST['user'] ."' and pID=". $_POST['pID'] .";";
                if(!mysqli_query($connect, $query))
                {	echo mysqli_error($connect);
                }
                $query = "delete from CatProbOther where username='". $_POST['user'] ."' and pID=". $_POST['pID'] .";";
                if(!mysqli_query($connect, $query))
                {	echo mysqli_error($connect);
                }*/

                //for($i = 0; $i < $_POST['solCount']; $i++)
                /*$count = 0;
                $query = "select count(*) from NAFCSolutions where qID = ". $_POST['last'] .";";
                if($res = mysqli_query($connect, $query))
                {   while($row = mysqli_fetch_row($res))
                    {   $count++;
                    }
                }*/

                $count = $_SESSION['count'];
                for($i = 0; $i < $count; $i++)
                {
                    // $query = "delete from NAFCCategorizedSolutions where username='". $_POST['user'] ."' and sID=". $_POST['sID'][$i] .";";
                    $query = "delete from NAFCCategorizedSolutions where username='". $_POST['user'] ."' and pID=". $_POST['pID'] ."' and sID=". $_POST['sID'][$i] . ";";
                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                    }

                    /*$query = "delete from CatSolEmotion where username='". $_POST['user'] ."' and sID=". $_POST['sID'][$i] .";";
                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                    }

                    $query = "delete from CatSolOther where username='". $_POST['user'] ."' and sID=". $_POST['sID'][$i] .";";
                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                    }*/
                }

                //echo $query;


                foreach($_POST['pCat'] as $pc)
                {   /*if(strcmp($pc, 'other') == 0)
                    {   continue;
                    }*/
                    //echo $pc;

                    $pc = preg_split('/(?<=[0-9])(?=[a-z]*)(?=[A-Z])/i', $pc);
                    $cat = $pc[0];
                    $chars = $pc[1];
                    if($cat == 6 || $cat == 7){
                      $subCat = null;
                      $subsubCat = null;
                    }else if(strlen($chars) >= 2 && ctype_upper($chars[strlen($chars)-1]) ){
                      $subsubCat = substr($chars, -1);
                      $subCat = substr($chars, 0, strlen($chars)-1);
                    }else{
                      $subCat = $chars;
                      $subsubCat = null;
                    }
                    /*if(ctype_upper($subCat[count($subCat)])){
                        unset($subCat[count($subCat)-1]);
                    }*/
                    // $subCat = preg_split('/(?=[A-Z]*)/i', $subCat);
                     //$subsubCat = $pc[1][count($pc[1])];
                    $query = "insert into NAFCCategorizedProblems values ('" . $_POST['user'] . "', '" . $_POST['pID'] . "', '" . $cat . "', '" . $subCat . "', '" . $subsubCat . "');";
                    //echo $query;

                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                    }
                }

                foreach($_POST['statCat'] as $pc)
                {   /*if(strcmp($pc, 'other') == 0)
                    {   continue;
                    }*/
                    //echo $pc;

                    $pc = preg_split('/(?<=[0-9])(?=[a-z]*)(?=[A-Z])/i', $pc);
                    $cat = $pc[0];
                    $prob = $pc[1];
                    /*if(ctype_upper($subCat[count($subCat)])){
                        unset($subCat[count($subCat)-1]);
                    }*/
                    // $subCat = preg_split('/(?=[A-Z]*)/i', $subCat);
                     //$subsubCat = $pc[1][count($pc[1])];
                    $query = "insert into NAFCCategorizedChars values ('" . $_POST['user'] . "', '" . $_POST['pID'] . "', '" . $cat . "', '" . $prob . "');";
                    //echo $query;

                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                    }
                }

                /*$query = "insert into CatProbEmotion values ('" . $_POST['user'] . "', '" . $_POST['pID'] . "', '" . $_POST['pEmotion'] . "');";


                if(!mysqli_query($connect, $query))
                {	echo mysqli_error($connect);
                }

                $count = 0;

                foreach($_POST['pOtherVal'] as $other)
                {   $count++;

                    if(!empty($other))
                    {   $query = "insert into CatProbOther values ('" . $_POST['user'] . "', '" . $_POST['pID'] . "', '" . $count . "','" . $other . "');";
                        //echo $query;

                        if(!mysqli_query($connect, $query))
                        {	echo mysqli_error($connect);
                        }
                    }
                }*/

                $index = 0;
                //for($i = 0; $i < $_POST['solCount']; $i++)
                for($i = 0; $i < $count; $i++)
                {   //foreach($_POST['pCat'][$i] as $sc)
                    /*{   if(strcmp($sc, 'other') == 0)
                        {   continue;
                        }


                        $sc = preg_split('/(?<=[0-9])(?=[a-z]+)/i', $sc);
                        $cat = $sc[0];
                        $subCat = $sc[1];
                        $subsubCat = $sc[2];*/
                        $sc =  $_POST['sCat'][$i];
                        $sc = preg_split('/(?<=[0-9])(?=[a-z]*)(?=[A-Z])/i', $sc);
                        $cat = $sc[0];
                        $chars = $sc[1];

                        if($cat == 6 || $cat == 7){
                          $subCat = null;
                          $subsubCat = null;
                        }else if(strlen($chars) >= 2 && ctype_upper($chars[strlen($chars)-1]) ){
                          $subsubCat = substr($chars, -1);
                          $subCat = substr($chars, 0, strlen($chars)-1);
                        }else{
                          $subCat = $chars;
                          $subsubCat = null;
                        }

                        $query = "insert into NAFCCategorizedSolutions values ('" . $_POST['user'] . "', '" . $_POST['pID'] . "', '" . $_POST['sID'][$i] . "', '" . $cat . "', '" . $subCat . "', '" . $subsubCat . "');";
                        $index++;
                        if(!mysqli_query($connect, $query))
                        {	echo mysqli_error($connect);
                        }
                    //}

                    /*$query = "insert into CatSolEmotion values ('" . $_POST['user'] . "', '" . $_POST['sID'][$i] . "', '" . $_POST['sEmotion'][$i] . "');";


                    if(!mysqli_query($connect, $query))
                    {	echo mysqli_error($connect);
                    }

                    $count2 = 0;

                    foreach($_POST['sOtherVal'][$i] as $other)
                    {   $count2++;

                        if(!empty($other))
                        {   $query = "insert into CatSolOther values ('" . $_POST['user'] . "', '" . $_POST['sID'][$i] . "', '" . $count2 . "','" . $other . "');";
                            //echo $query;

                            if(!mysqli_query($connect, $query))
                            {	echo mysqli_error($connect);
                            }
                        }
                    }*/
                }

                echo '<form id="return" action="cat.php" method="post">
                    <input type="hidden" name="user" value="'. $user .'">
                </form>';
            }
        ?>
        <script>
            setTimeout(function() {
                document.getElementById("return").submit();
            }, 5000);
        </script>
    </body>
</html>
