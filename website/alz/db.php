<?php
    function dbConnect()
    {   $connect  = mysqli_connect("mysql1.cs.clemson.edu", "xyz", "******");

        if($connect)
        {   return $connect;
        }

        return false;
    }
?>