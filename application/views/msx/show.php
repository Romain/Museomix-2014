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

        <?php echo form_open_multipart( base_url('#'), array('id' => 'update-pictures', 'class' => '', 'role' => 'form'), array($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ); ?>
        <?php echo form_close(); ?>

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

                console.log(images);

                // Set a pointer to be used with the images array.
                var imagesPointer = 0;
                var imagePosition = "back";

                // Make the image rotate
                setNewImage();

                // Update the list of images and sounds
                updatePicturesList()


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



                function updatePicturesList() {
                    setTimeout(function() {

                        $.ajax({
                            type: 'POST',
                            url: '<?= base_url("pictures/update_list") ?>',
                            data: { 
                                '0': $("form#update-pictures input[name='0']").val(), 
                                '1': $("form#update-pictures input[name='1']").val(), 
                                'csrf_museomix_2014': $("form#update-pictures input[name='csrf_museomix_2014']").val()
                            },
                            success: function(data, textStatus, jqXHR){
                                var obj = $.parseJSON(data);
                                var newImages = new Array;
                                var newSounds = new Array;
                                
                                for(var i=0; i<obj.pictures.length; i++) {
                                    newImages.push(obj.pictures[i].picture);
                                    newSounds.push(obj.pictures[i].sound);
                                }
                                
                                images = newImages;
                                sounds = newSounds;
                            },
                            error: function(data, textStatus, jqXHR){
                                var obj = $.parseJSON(data);
                                console.log(obj);
                            }
                        });
                        
                        updatePicturesList();
                    }, 10000);
                }
            });
        </script>
    </body>
</html>