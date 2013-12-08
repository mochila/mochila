<!DOCTYPE HTML>
<html>
     <head>
          <title>View your DAGR</title>
          <?php include("header.inc"); ?>
          <link rel="stylesheet" href="css/readdagr.css" type="text/css">
          <script type="text/javascript" src="js/dagrform.js"></script>
     </head>
     <body>
          <?php include("navbar.inc"); ?>
          <div class="row">
               <div id="dagr-metadata" class="col-md-1">
                    <div id="metadata-container" class="navbar navbar-default navbar-left" role="navigation">
                         <form role="form">
                              <div class="form-group">
                                   <input id="dagr-title" class="form-control" type="text" placeholder="File title">
                                   <div class="dagr-metadata text">
                                        <div><span class="text-left"><b>Author:</b></span><span class="text-right">Author goes here</span></div>
                                        <div><span class="text-left"><b>Date:</b></span><span>Date goes here</span></div>
                                        <div><span class="text-left"><b>Size:</b></span><span>Size goes here</span></div>
                                        <div><span class="text-left"><b>Type</b></span><span>Type goes here</span></div>
                                        <div><span class="text-left"><b>Parent:</b></span><span>Parent goes here</span></div>
                                   </div>
                                   <div class="row">
                                        <label class="col-sm-2" for="dagr-tags">Tags</label>
                                        <div class="col-sm-10">
                                             <input type="text" class="form-control" id="dagr-tags">     
                                        </div>  
                                   </div>
                                   
                              </div>
                         </form>
                    </div>
               </div>
               
               <div id="dagr-viewer" class="col-md-11">
                    stuff inside
                    
               </div>
          </div>
          
          <?php include("footer.inc"); ?>
     </body>
     
</html>