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
        <form method="post" action="cat2.php">
            <input type="text" name="user" value="">
            <input type="submit" value="Login">
        </form>
    </body>
</html>