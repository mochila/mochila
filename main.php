<!DOCTYPE html>
<html>
    <head>
        <title>Mochila</title>
        <?php include("header.inc"); ?>
        <script>
            var $dagr_info = <?php echo json_encode($dagr_list); ?>;
            console.log($dagr_info);
        </script>
    </head>
    <body>
        
        <?php include("navbar.inc"); ?>
        
        <?php include("html/dagrlist.html"); ?>   
        
        <?php include("html/add-tag-modal.html"); ?>
        <?php include("html/delete-dagr-modal.html"); ?>
        <?php include("html/dagrListTemplate.html"); ?>
        <?php include("html/affectedDagrsTemplate.html") ?>
        
        <?php include("footer.inc"); ?>
        
        
        
    </body>
</html>