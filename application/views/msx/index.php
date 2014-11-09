<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php include('inc/meta.php'); ?>
        <?php include('inc/css.php'); ?>
    </head>
    <body id="index-body">
        <a href="<?php echo base_url('picture/add') ?>" title="Partagez une photo"></a>

        <?php include('inc/js.php'); ?>
        <script type="text/javascript">
        $(document).ready(function() {
                
            // Resize the image div
            var screenWidth = window.innerWidth;
            var screenHeight = window.innerHeight;
            $("body").width(screenWidth);
            $("body").height(screenHeight);
        });
        </script>
    </body>
</html>