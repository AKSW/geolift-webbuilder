<?php
/*
 * Copyright 2014 Alrik Hausdorf
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
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
        <div class="container">
            <div class="row">
                <div class="col-xs-12">
                    <h1 id="home">GeoLift <small>Web-Builder</small></h1>
                    <p>
GeoLift is a spatial mapping component aims to enrich RDF datasets with geo-spatial information. To achieve this goal, GeoLit relies on several modules and operators. GeoLift is implemented in Java as an open-source project.

                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-xs-12">

                    <h1 id="MyFiles">My Datasets <small>All Uploaded and linked datasets</small></h1>


                    <p class="loggedin" style="display:none;">Your logged in with:
                        
                        <strong>test@gurkware.de</strong> 
                        <button class="btn btn-xs ">logout</button>
                    </p>

                    <div class="form-group notloggedin">
                        
                        <div class="col-xs-12" style="margin-bottom: 15px;" >
                            Please first enter your email-adress. <br>
                            Your email is used to store your files on the Server, and to inform you on completed Querys.                            
                        </div>
                        
                        <div class="col-sm-5 col-xs-12">
                            <input type="text" id="email" class="col-sm-6 form-control" placeholder="user@provider.de">
                        </div>
                        <div class="col-sm-3 col-xs-12">
                            <button class="btn " id="loginbtn">submit</button>
                        </div>
                    </div>
                    <div class="col-xs-12">
                        <hr>
                    </div>
                    <div class="panel-group hidden" id="blocks">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                    <h3 class="panel-title clearfix">Add a new Dataset</h3>
                            </div>
                            <div id="adddataset">
                                <div class="panel-body wrapper" >
                                    <form>
                                        <div class="form-group col-xs-12 col-sm-5">
                                            <label for="urlInput">Dataset from URL</label>
                                            <input type="text" id="urlInput" class="form-control" placeholder="a URL">
                                            <span class="space-2  hidden-xs"></span>
                                            <label for="urlInput">Dataset endpoint</label>
                                            <input type="text" id="endPointInput" class="form-control" placeholder="an endpoint URL">
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
                               
                                <h3 class="panel-title clearfix"> Your Datasets and Querys<button id="refreshBtn" class=" btn btn-xs pull-right"><i class="fa fa-refresh"></i> Refresh</button></h3>
                                

                            </div>
                            <div id="datasets" >

                                <div class="panel-body wrapper" >
                                    <p>Please login first</p>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bootstrap core JavaScript
        ================================================== -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
        <script>
            if (typeof jQuery === typeof undefined) {
                document.write(unescape("%3Cscript src='js/jquery.min.js' type='text/javascript'%3E%3C/script%3E"));
            }
        </script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/Storage.js"></script>   
        <script src="js/upload.js"></script>    
        <script src="js/main.js"></script>    
        <script>
            new Upload();
        </script>
    </body>
</html>
