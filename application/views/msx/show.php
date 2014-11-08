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

        <div id="sounds">
            <audio id="sound">
                <source src="<?php echo base_url('sounds/'.$pictures[0]->{'sound'}); ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>

        <?php include('inc/js.php'); ?>
        <script type="text/javascript">
            $(document).ready(function() {

                // Play the sound associated to the image
                var sound = $("#sounds audio#sound").get(0);
                var firstSound = "<?php echo $pictures[0]->{'picture'} ?>";
                sound.volume = 1;
                if(firstSound != "") {
                    sound.load();
                    sound.play();
                }

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

                // Set the sounds
                var sounds = [<?php 
                    for($i=0; $i<count($pictures); $i++) {
                        if($i != 0)
                            echo ",";

                        echo '"'.$pictures[$i]->{'sound'}.'"';
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
                        // Set the image pointer
                        if(imagesPointer < images.length - 1)
                            imagesPointer++;
                        else
                            imagesPointer = 0;

                        // Set the which div will receive the image
                        if(imagePosition == "back")
                            imagePosition = "top";
                        else
                            imagePosition = "back";

                        // Set the image in the div
                        $("#image-"+imagePosition).css("background", "url("+images[imagesPointer]+") no-repeat");
                        $("#image-"+imagePosition).css("backgroundSize", "cover");
                        $("#image-"+imagePosition).css("backgroundPosition", "center center");

                        // Animate the divs
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

                        // Pause any sound playing
                        sound.pause();

                        // Rewind to the beginning of the sound
                        sound.currentTime = 0;

                        // Set the new sound
                        var selectedSound = sounds[imagesPointer];
                        $("#sounds audio#sound source").attr("src", "<?php echo base_url('sounds') ?>/"+selectedSound);

                        // Play the new sound
                        if(selectedSound != "") {
                            sound.load();
                            sound.play();
                        }

                        setNewImage();
                    }, 5000);
                }
            });
        </script>
    </body>
</html>