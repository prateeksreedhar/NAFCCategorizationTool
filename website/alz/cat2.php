<html>
    <head>
        <title>
            Alz Categorization Tool | Clemson University | CUCWD
        </title>
        <style>
            .lable
            {   text-align: right;
                font-weight: 700;
            }

            .catAccP, .catAccS
            {   border: 1px solid black;
                border-radius: 4px;
                padding: 2px 0 2px 2px;
            }

            div.subCatsP, div.subCatsS
            {   padding: 5px 0 10px 15px;
                display: none;
                border: 1px solid grey;
            }

            div.show
            {   display: block;
            }

            .centered
            {   text-align: center;
            }

            #submit
            {   margin-top: 20px;
                border: 1px solid black;
                border-radius: 4px;
                padding: 2px 2px 2px 2px;
                width: 100px;
                text-align: center;
            }
        </style>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
    </head>
    <body>
        <?php
            function in_arrayM($v, $haystack)
            {   //echo "<h4>HERE" . count($haystack) . "</h3>";
                foreach($haystack as $a)
                {   if(count($a) > 0)
                        if(in_array($v, $a))
                            return true;
                }
                return false;
            }

            require_once "db.php";
            $connect = dbConnect();
            $query = "use alzSolutions_m2oj";

			if(!mysqli_query($connect, $query))
			{	echo mysqli_error($connect);
                exit();
			}


            $showPage = FALSE;

            //$user = $_SERVER['REMOTE_USER'];
            $user = "";

            if(isset($_POST['user']))
            {   $user = $_POST['user'];

                $query = "select * from Users where users='". $user ."';";
                $res = mysqli_query($connect, $query);

                if(mysqli_num_rows($res) > 0)
                {   $row = mysqli_fetch_row($res);

                    if(!is_null($row[0]))
                        $showPage = TRUE;
                }
            }

            if(!$showPage)
            {   echo "<h1> You don't have access to this tool!</h1>";
            }

            echo "<div class=centered><h2>ALZ Categorization Tool</h2>User Login: $user</div>";

            if($connect && $showPage)
            {   /*$query = "insert ignore into Users values('$user');";
                if(!mysqli_query($connect, $query))
                {	echo mysqli_error($connect);
                }*/

                $last = 1;
                $exists = FALSE;
				$poExists = false;
                $solExists = array();
				$soExists = array();
                $psc = array();
                $pscOther = array(array());
                $pcEmotion;
                $ssc = array(array());
                $sscOther = array(array(array()));
                $scEmotion = array();

                if(isset($_POST['fetch']))
                {   $last = $_POST['fetch'];
                    $query = "select * from CategorizedProblems where username = '$user' and pID = ". $_POST['fetch'] .";";
                    $res = mysqli_query($connect, $query);

                    if(mysqli_num_rows($res) > 0)
                    {   $exists = true;

						while($row = mysqli_fetch_row($res))
                        {   $psc[] = (string)$row[2].$row[3];
                        }
                    }

                    $query = "select * from CatProbOther where username = '$user' and pID = ". $_POST['fetch'] .";";
                    $res = mysqli_query($connect, $query);

                    if(mysqli_num_rows($res) > 0)
                    {   $poExists = true;

						while($row = mysqli_fetch_row($res))
                        {   $pscOther[$row[2]] = array($row[2], $row[3]);
                        }
                    }

                    if($exists || $poExists)
                    {   $query = "select emotion from CatProbEmotion where username = '$user' and pID = ". $_POST['fetch'] .";";
                        $pcEmotion = mysqli_fetch_row(mysqli_query($connect, $query))[0];

                        $query = "select solCount from Problems where ID = ". $_POST['fetch'] .";";
                        $c = 0;
                        if($res = mysqli_query($connect, $query))
                        {   if(mysqli_num_rows($res) > 0)
                                $c = intval(mysqli_fetch_row($res)[0]);
                        }
                        else
                        {   echo mysqli_error($connect);
                        }

                        if($c > 0)
                        {   $index = 0;
                            $query = "select ID from Solutions where pID = ". $_POST['fetch'] ." order by 1;";

                            if($res = mysqli_query($connect, $query))
                            {   while($row = mysqli_fetch_row($res))
                                {   $solExists[$index] = false;
									$soExists[$index] = false;

									$query2 = "select * from CategorizedSolutions where username = '$user' and sID = $row[0];";
                                    //echo $query2;
                                    $res2 = mysqli_query($connect, $query2);

                                    if(mysqli_num_rows($res2) > 0)
                                    {   $solExists[$index] = true;

										while($row2 = mysqli_fetch_row($res2))
                                        {   $ssc[$index][] = $row2[2] . $row2[3];
                                        }
                                    }

                                    $query2 = "select * from CatSolOther where username = '$user' and sID = $row[0];";
                                    $res2 = mysqli_query($connect, $query2);

                                    if(mysqli_num_rows($res2) > 0)
                                    {   $soExists[$index] = true;

										while($row2 = mysqli_fetch_row($res2))
                                        {   $sscOther[$index][$row2[2]] = array($row2[2], $row2[3]);
                                        }
                                    }

                                    if($solExists[$index] || $soExists[$index])
                                    {   $query2 = "select emotion from CatSolEmotion where username = '$user' and sID = $row[0];";
                                        $scEmotion[] = mysqli_fetch_row(mysqli_query($connect, $query2))[0];
                                    }

                                    $index++;
                                }
                            }
                            else
                            {   echo mysqli_error($connect);
                            }
                        }
                    }
                }
                else
                {   $query = "select max(pID) from CategorizedProblems where username = '$user';";
                    $res = mysqli_query($connect, $query);

                    if(mysqli_num_rows($res) > 0)
                    {   $row = mysqli_fetch_row($res);

                        if(!is_null($row[0]) && $last < 2999)
                            $last = $row[0] + 1;
                    }
                }

                $pCat = array();
                $pSubCat = array();
                $query = "select * from ProblemCat;";

                if($res = mysqli_query($connect, $query))
                {   while($row = mysqli_fetch_row($res))
                    {   $pCat[] = array($row[0], $row[1]);
                    }
                }
                else
                {   echo mysqli_error($connect);
                }

                $query2 = "select * from ProblemSubCat;";

                if($res2 = mysqli_query($connect, $query2))
                {   while($row2 = mysqli_fetch_row($res2))
                    {   $pSubCat[] = array($row2[0], $row2[1], $row2[2]);
                    }
                }
                else
                {   echo mysqli_error($connect);
                }

                $sCat = array();
                $sSubCat = array();
                $query = "select * from SolutionCat;";

                if($res = mysqli_query($connect, $query))
                {   while($row = mysqli_fetch_row($res))
                    {   $sCat[] = array($row[0], $row[1]);
                    }
                }
                else
                {   echo mysqli_error($connect);
                }
                //var_dump($sCat);
                $query2 = "select * from SolutionSubCat;";

                if($res2 = mysqli_query($connect, $query2))
                {   while($row2 = mysqli_fetch_row($res2))
                    {   $sSubCat[] = array($row2[0], $row2[1], $row2[2]);
                    }
                }
                else
                {   echo mysqli_error($connect);
                }

                /*echo count($pscOther);
                var_dump($pscOther);
                echo count($sscOther);
                var_dump($sscOther);*/

                $query = "select * from Problems where ID = $last";
                if($res = mysqli_query($connect, $query))
                {   $row = mysqli_fetch_row($res);
                    echo '<form id="catForm" action="submit.php" method="post">
                        <input type="hidden" name="user" value="' . $user . '">
                        <table>
                            <tr>
                                <td class="lable"><input type="hidden" name="pID" value="' . $row[0] . '">Number:</td><td>' . $row[0] . '</td>
                            </tr>
                            <tr>
                                <td class="lable">Timestamp:</td><td>' . $row[1] . '</td>
                            </tr>
                            <tr>
                                <td class="lable">Username:</td><td>' . $row[3] . '</td>
                            </tr>
                            <tr>
                                <td class="lable">Category:</td><td>' . $row[2] . '</td>
                            </tr>
                            <tr>
                                <td class="lable">Question:</td><td>' . $row[5] . '</td>
                            </tr>
                            <tr>
                                <td class="lable"><input type="hidden" name="solCount" value="' . $row[4] . '">Solutions offered:</td><td>' . $row[4] . '</td>
                            </tr>
                            <tr>
                                <td colspan=2>';
                                    foreach($pCat as $pc)
                                    {   echo '<div class="catAccP">' . $pc[0] . ' - ' . $pc[1] . '</div>
                                        <div class="subCatsP">';

                                        foreach($pSubCat as $pSub)
                                        {   if($pSub[1] == $pc[0])
                                            {   echo '<input type="checkbox" name="pCat[]" value="' . $pSub[1] . $pSub[0] . '"';
                                                $val = (string)$pSub[1].$pSub[0];

                                                if($exists)
                                                {   if(in_array($val, $psc))
                                                        echo 'checked';
                                                }

                                                echo '>' . $pSub[1] . $pSub[0] . ' - ' . $pSub[2] . '<br>';
                                            }
                                        }

                                        if($poExists)
                                        {   if(in_arrayM($pc[0], $pscOther))
                                               echo '<input type="checkbox" name="pCat[]" value="other" checked>Other: <input type="text" name="pOtherVal[]" value="'. $pscOther[$pc[0]][1] .'"><br>';
                                            else
                                                echo '<input type="checkbox" name="pCat[]" value="other">Other: <input type="text" name="pOtherVal[]" value=""><br>';
                                        }
                                        else
                                        {   echo '<input type="checkbox" name="pCat[]" value="other">Other: <input type="text" name="pOtherVal[]" value=""><br>';
                                        }

                                        echo '</div>';
                                    }

                                    echo '<br>Emotion Rating:<br>1<input type="range" min="1" max ="10" name="pEmotion" ';

                                    if($exists)
                                       echo 'value="'. $pcEmotion .'">10';
                                    else
                                        echo '>10';

                                echo '</td>
                            </tr>';

                            $count = 0;
                            if(intval($row[4]) > 0)
                            {   $query = "select * from NAFCSolutions where pID = $last order by 1";
                                if($res = mysqli_query($connect, $query))
                                {   while($row = mysqli_fetch_row($res))
                                    {   $count++;
                                        echo '<tr>
                                                <td colspan=2><input type="hidden" name="sID[]" value="' . $row[0] . '"><hr></td>
                                        <tr>
                                        <tr>
                                            <td class="lable">Timestamp:</td><td>' . $row[2] . '</td>
                                        </tr>
                                        <tr>
                                            <td class="lable">Username:</td><td>' . $row[4] . '</td>
                                        </tr>
                                        <tr>
                                            <td class="lable">Category:</td><td>' . $row[3] . '</td>
                                        </tr>
                                        <tr>
                                            <td class="lable">Solution:</td><td>' . $row[5] . '</td>
                                        </tr>
                                        <tr>
                                            <td colspan=2>';
                                                $index = $count - 1;

                                                foreach($sCat as $sc)
                                                {   echo '<div class="catAccS">' . $sc[0] . ' - ' . $sc[1] . '</div>
                                                    <div class="subCatsS">';

                                                    foreach($sSubCat as $sSub)
                                                    {   if($sSub[1] == $sc[0])
                                                        {   echo '<input class="sol' . $count . '" type="checkbox" name="sCat['. $index .'][]" value="' . $sSub[1] . $sSub[0] . '"';

                                                            if($exists && $solExists[$index])
                                                            {   $val = (string)$sSub[1].$sSub[0];

                                                                if(in_array($val, $ssc[$index]))
                                                                    echo 'checked';
                                                            }

                                                            echo '>' . $sSub[1] . $sSub[0] . ' - ' . $sSub[2] . '<br>';
                                                        }
                                                    }

                                                    if($exists && $soExists[$index])
                                                    {   if(in_arrayM($sc[0], $sscOther[$index]))
                                                            echo '<input class="sol' . $count . '" type="checkbox" name="sCat['. $index .'][]" value="other" checked>Other: <input type="text" name="sOtherVal['. $index .'][]" value="'. $sscOther[$index][$sc[0]][1] .'"><br>';
                                                        else
                                                            echo '<input class="sol' . $count . '" type="checkbox" name="sCat['. $index .'][]" value="other">Other: <input type="text" name="sOtherVal['. $index .'][]" value=""><br>';
                                                    }
                                                    else
                                                        echo '<input class="sol' . $count . '" type="checkbox" name="sCat['. $index .'][]" value="other">Other: <input type="text" name="sOtherVal['. $index .'][]" value=""><br>';

                                                    echo '</div>';
                                                }

                                                echo '<br>Emotion Rating:<br>1<input type="range" min="1" max ="10" name="sEmotion[]" value="';

                                                if($exists)
                                                    echo $scEmotion[$index] .'">10';
                                                else
                                                    echo '1">10';

                                            echo '</td>
                                        </tr>';
                                    }
                                }
                                else
                                {   echo mysqli_error($connect);
                                }
                            }
                        }

                        echo '</table>
                        <div id="submit">Submit</div>
                    </form>
                    <form action="cat2.php" method="post">
                        <input type="hidden" name="user" value="'. $user .'">
                        <input type="submit" value="Load Problem: ">
                        <input type="number" name="fetch" min="1" max="3000" value="'. $last .'">
                    </form>
                    <script type="text/javascript">
                        var solCount = ' . $count . ';
                    </script>';
                }
                else if($showPage)
                {   echo mysqli_error($connect);
                }
        ?>
        <script type="text/javascript">
            $(document).ready(function () {
                $("#submit").click(function() {

                    var checked = new Array(solCount + 1);
                    checked[0] = $('input[name=pCat\\[\\]]:checked').length;

                    if(!checked[0])
                    {   alert("You must check at least one checkbox for the question.");
                        return false;
                    }

                    if(solCount > 0)
                    {   var i = 1;

                        for(i = 1; i <= solCount; i++)
                        {   checked[i] = $("input[class=sol" + i + "]:checked").length;

                            if(!checked[i]) {
                                alert("You must check at least one checkbox for solution " + i);
                                return false;
                            }
                        }
                    }

                    $("#catForm").submit();
                });
            });

            var acc = document.getElementsByClassName("catAccP");
            var i;

            for (i = 0; i < acc.length; i++) {
                acc[i].onclick = function(){
                    this.nextElementSibling.classList.toggle("show");
                }
            }

            var acc2 = document.getElementsByClassName("catAccS");
            var i;

            for (i = 0; i < acc2.length; i++) {
                acc2[i].onclick = function(){
                    this.nextElementSibling.classList.toggle("show");
                }
            }
        </script>
    </body>
</html>
