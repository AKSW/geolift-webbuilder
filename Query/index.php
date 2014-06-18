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
                <div class="input-group" style="padding-top: 5px;">
                    <input id="jobName" type="text" placeholder="newQuery" data-container="body" data-toggle="tooltip1" data-placement="bottom" class="form-control" title="Query name">
                    <span class="input-group-btn">
                        <button class="btn btn-success " data-toggle="tooltip1" data-container="body" data-placement="bottom" title="Save"><i class="fa fa-save"></i></button>
                        <button class="btn btn-danger " data-toggle="tooltip1" data-container="body" data-placement="bottom" title="discard & close"><i class="fa fa-reply"></i></button>
                    </span>
                </div>
                <hr>

            </div>

            <div class="sidebar-element" style="top:50%; padding: 5px;">
                <h5>Module: test1</h5>
                <div class="form-group" data-toggle="tooltip1" data-container="body" data-placement="right" title="long Description of this setting" >
                    <label for="setting1">foxInput</label>
                    <input type="text" class="form-control" id="setting1" >
                </div>
                <div class="form-group" data-toggle="tooltip1" data-container="body" data-placement="right" title="long Description of this setting" >
                    <label for="setting2">foxOutput</label>
                    <select class="form-control" id="setting2">
                        <option>TURTLE</option>             
                        <option>JSONLD</option>             
                        <option>N3</option>             
                        <option>N-TRIPLE</option>             
                        <option>RDF/JSON</option>             
                        <option>RDF/XML</option>             
                        <option>RDF/XML-ABBREV</option>
                    </select>
                </div>
                <div class="form-group" data-toggle="tooltip1" data-container="body" data-placement="right" title="long Description of this setting">
                    <label for="setting3">foxUseNif</label>
                    <div class="onoffswitch">
                        <input type="checkbox" name="onoffswitch" class="onoffswitch-checkbox" id="setting3">
                        <label class="onoffswitch-label" for="setting3">
                            <span class="onoffswitch-inner"></span>
                            <span class="onoffswitch-switch"></span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="vdragbar" style="text-align: center;">
                <hr class="pull-left" style=" margin: 3px; height: 4px; width: 100%;">
            </div>
        </div>
        <div class="viewpoint">
            <div style="left: 30px; width: 100px;" class="queryblock startendBlock"><div class="text">START</div></div>
            <div style="left: 130px; width: 100px;" class="queryblock arrow"><i class="fa fa-5x fa-arrow-right"></i></div>
            <div style="left: 230px; width: 100px;" class="queryblock startendBlock"><div class="text">END</div></div>
            
            
            
        </div>

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