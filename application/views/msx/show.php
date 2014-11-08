<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php include('inc/meta.php'); ?>
        <?php include('inc/css.php'); ?>
        
        <style type="text/css">
            body {
                padding: 0;
            }
            #image-back {
                background: url(<?php echo $pictures[0]->{'picture'} ?>) no-repeat;
                background-size: cover;
                background-position: center center;
            }
        </style>
    </head>
    <body>
        <div id="image-back">
            <div id="image-top"></div>
        </div>

        <?php include('inc/js.php'); ?>
        <script type="text/javascript">
            $(document).ready(function() {

                // Resize the image div
                var screenWidth = screen.width;
                var screenHeight = screen.height;
                $("#image-back").width(screenWidth);
                $("#image-back").height(screenHeight);

                // Set the images
                var images = [<?php 
                    for($i=0; $i<count($pictures); $i++) {
                        if($i != 0)
                            echo ",";

                        echo '"'.$pictures[$i]->{'picture'}.'"';
                    } 
                ?>];

                // Set a pointer to be used with the images array.
                var imagesPointer = 0;
                var imagePosition = "back";

                // Make the image rotate
                setNewImage();


                /**************************************************/
                /************* Additional functions ***************/
                /**************************************************/

                function setNewImage() {
                    setTimeout(function() {
                        if(imagesPointer < images.length - 1)
                            imagesPointer++;
                        else
                            imagesPointer = 0;

                        if(imagePosition == "back")
                            imagePosition = "top";
                        else
                            imagePosition = "back";

                        $("#image-"+imagePosition).css("background", "url("+images[imagesPointer]+") no-repeat");
                        $("#image-"+imagePosition).css("backgroundSize", "cover");
                        $("#image-"+imagePosition).css("backgroundPosition", "center center");

                        if(imagePosition == "back") {
                            $("#image-top").animate({
                                opacity: 0
                            }, 500);
                        }
                        else {
                            $("#image-top").animate({
                                opacity: 1
                            }, 500);   
                        }

                        setNewImage();
                    }, 5000);
                }
            });
        </script>
    </body>
</html>