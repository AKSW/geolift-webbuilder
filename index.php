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
        <link href="css/bootstrap.min.css" rel="stylesheet">

        <link rel="stylesheet" href="css/font-awesome.min.css">

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <link rel="stylesheet" href="css/home.css">
        <link rel="stylesheet" href="css/upload.css">

    </head> 
    <body>
        <div class="navbar navbar-default navbar-fixed-top">
            <div class="container">
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#home">GeoLift</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav navbar-right">
                        <li class="active"><a href="#home">Start</a></li>
                        <li><a href="#MyFiles">My Datasets</a></li>
                        <li><a href="#contact">Contact</a></li>
                    </ul>

                </div><!--/.nav-collapse -->
            </div>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 id="home">GeoLift <small>Web-Builder</small></h1>
                    <p>
                        text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test 
                        text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test 
                        text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test 
                        text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test 
                        text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test 
                        text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test text test 
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">

                    <h1 id="MyFiles">My Datasets <small>All Uploaded and linked datasets</small></h1>


                    <p class="loggedin" style="display:none;">Your logged in with:
                        <br>
                        <strong>test@gurkware.de</strong> 
                        <button class="btn btn-xs ">logout</button>
                    </p>

                    <div class="form-group notloggedin">
                        <p>
                            Please enter your email-adress. <br>
                            Your email is used to store your files on the Server, and to inform you on completed Querys.<br>
                            If you have already an Account, look for the email we send you and use the link in it, to identify.<br>
                        </p>

                        <div class="col-sm-5 col-xs-12">
                            <input type="text" id="email" class="col-sm-6 form-control" placeholder="user@provider.de">
                        </div>
                        <div class="col-sm-3 col-xs-12">
                            <button class="btn ">submit</button>
                        </div>


                    </div>


                    <div class="col-xs-12">
                        <hr>
                    </div>
                    <div class="panel-group" id="blocks">
                        <div class="panel panel-default">

                            <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#blocks" href="#adddataset">
                                    <h3 class="panel-title clearfix">Add a new Dataset</h3>
                                </a>

                            </div>
                            <div id="adddataset" class="panel-collapse collapse in">
                                <div class="panel-body wrapper" >
                                    <form>
                                        <div class="form-group col-xs-12 col-sm-5">
                                            <label for="urlInput">a new URL to dataset</label>
                                            <input type="text" id="urlInput" class="form-control" placeholder="a URL">
                                            <span class="space-2  hidden-xs"></span>
                                            <a class="btn btn-default col-xs-12" id="submit">go on with this dataset</a>
                                        </div>
                                        <div class="col-sm-1 hidden-xs" style="height: 161px;">
                                            <table style="height: 100%; width: 100%;">
                                                <tr>
                                                    <td style="width: 50%;" class="right-border"></td>
                                                    <td style="width: 50%;"></td>
                                                </tr>
                                                <tr style=" text-align: center;">
                                                    <td colspan="2">OR</td>
                                                </tr>
                                                <tr>
                                                    <td class="right-border"></td>
                                                    <td></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-xs-12 visible-xs">
                                            <table style="height: 100%;width: 100%;">
                                                <tr>
                                                    <td style="width: 45%;"><hr></td>
                                                    <td style="width: 10%; text-align: center;">OR</td>
                                                    <td style="width: 45%;"><hr></td>
                                                </tr>
                                            </table>
                                        </div>
                                        <div class="col-sm-6">
                                            <label class="file-upload-label" id="fileDropper" data-title="Drop file here or click to select file">
                                                <span class="file-upload-file">
                                                    <i class="fa fa-cloud-upload fa-5x"></i>  
                                                </span>

                                                <input type="file" name="files[]" multiple id="files[]">
                                            </label>
                                            <div class="progress progress-striped active">
                                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                                    0%
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a data-toggle="collapse" data-parent="#blocks" href="#datasets">
                                    <h3 class="panel-title clearfix"> Your Datasets and Querys</h3>
                                </a>

                            </div>
                            <div id="datasets" class="panel-collapse collapse">

                                <div class="panel-body wrapper" >
                                    <p>You dont have any Datasets or Querys stored</p>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--div class="site-wrapper">
            
            <div class="site-wrapper-inner">
                
                <div class="cover-container">
                    
                    <div class="masthead clearfix">
                        <div class="inner">
                            <h3 class="masthead-brand">GeoLift</h3>
                            <ul class="nav masthead-nav">
                                <li class="active"><a href="./">Home</a></li>
                                <li><a href="Features/">Features</a></li>
                                <li><a href="Contact/">Contact / Statistics</a></li>
                            </ul>
                        </div>
                    </div>
                        
                    <div class="inner cover">
                        <div class="lead">
                            <label class="file-upload-label" id="fileDropper" data-title="Drop ps-file here or click to select file">
                                <span class="file-upload-file">
                                    <i class="fa fa-cloud-upload fa-5x"></i>  
                                </span>
                                        
                                <input type="file" name="files[]" multiple id="files[]">
                            </label>
                            <div class="progress progress-striped active">
                                <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%;">
                                    0%
                                </div>
                            </div>  
                            <h2>Latest Uploads <a id="cleanHistory" href="#"><i  class="fa fa-trash-o"></i></a></h2>
                            <div class="file-history list-group">
                                
                                <div class="file-container list-group-item">
                                    No Recent Uploads
                                </div>
                            </div> 
                        </div>
                    </div>
                        
                    <div class="mastfoot">
                        <p>&copy; Gurkware Solutions GbR, Daniel Gerighausen and Alrik Hausdorf</p>
                    </div>
                        
                </div>
                    
            </div>
                
        </div-->

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>
            if (typeof jQuery === typeof undefined) {
                document.write(unescape("%3Cscript src='js/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
            }
        </script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/filehistory.js"></script>   
        <script src="js/upload.js"></script>    
        <script>
            new Upload();
        </script>
    </body>
</html>