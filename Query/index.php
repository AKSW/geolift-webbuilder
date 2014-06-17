<?php
/*
 * Copyright (c) 2012-2014, Gurkware Solutions GbR  All rights reserved. 
 */
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>GeoLift Web-Builder</title>

        <!-- Bootstrap -->
        <link href="../css/bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" href="../css/font-awesome.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="../css/query.css">
    </head> 
    <body>

        <div class="sidebar">
            <div class="sidebar-element container" style="top:0px; ">
                <div class="input-group">
                    <input id="jobName" type="text" placeholder="newQuery" data-toggle="tooltip1" data-placement="bottom" class="form-control" title="Query name">
                    <span class="input-group-btn">
                        <button class="btn btn-success " data-toggle="tooltip1" data-placement="bottom" title="Save"><i class="fa fa-save"></i></button>
                        <button class="btn btn-danger " data-toggle="tooltip1" data-placement="bottom" title="discard & close"><i class="fa fa-reply"></i></button>
                    </span>
                </div>
            </div>

            <div class="sidebar-element" style="top:50%;">

            </div>
            <div class="vdragbar" style="text-align: center;">
                <hr class="pull-left" style=" margin: 3px; height: 4px; width: 100%;">
            </div>
        </div>
        <div class="viewpoint"></div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>
            if (typeof jQuery === typeof undefined) {
                document.write(unescape("%3Cscript src='../js/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
            }
        </script>
        <script src="../js/bootstrap.min.js"></script>
        <script src="../js/jquery-ui-1.10.4.custom.min.js"></script>
        <script src="../js/query.js"></script>    

    </body>
</html>