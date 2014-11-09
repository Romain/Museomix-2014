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

        <div id="picture-details">
            <div class="row">
                <div class="col-sm-3">
                    <div id="firstname"></div>
                    <div id="museozoom">
                        <img src="<?php echo base_url('assets/img/museozoom.png'); ?>" alt="MuseoZoom" title="MuseoZoom">
                    </div>
                </div>
                <div class="col-sm-1 col-sm-offset-1">
                    <div id="gun">
                        <img src="<?php echo base_url('assets/img/gun.png'); ?>" alt="Pistolet MuseoZoom" title="Pistolet MuseoZoom">
                    </div>
                </div>
                <div class="col-sm-6">
                    <div id="comment">
                        <?php echo $pictures[0]->{'comment'}; ?>
                    </div>
                </div>
                <div class="col-sm-1">
                    <img src="<?php echo base_url('assets/img/logo.png'); ?>" alt="Logo MuseoZoom" title="Logo MuseoZoom">
                </div>
            </div>
        </div>

        <div id="sounds">
            <audio id="sound">
                <source src="<?php echo base_url('assets/sounds/'.$pictures[0]->{'sound'}); ?>" type="audio/mpeg">
                <source src="<?php echo base_url('assets/sounds/'.substr($pictures[0]->{'sound'}), -3)."ogg"; ?>" type="audio/ogg">
                Your browser does not support the audio element.
            </audio>
        </div>

        <?php echo form_open_multipart( base_url('#'), array('id' => 'update-pictures', 'class' => '', 'role' => 'form'), array($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ); ?>
        <?php echo form_close(); ?>

        <?php include('inc/js.php'); ?>
        <script type="text/javascript">
            $(document).ready(function() {

                // Set vars to deal with the update of images
                var numberOfImages = 0;
                var imagesChanged = 0;
                var rotate = "launch";
                var timer = 0;

                // Play the sound associated to the image
                var sound = $("#sounds audio#sound").get(0);
                var firstSound = "<?php echo $pictures[0]->{'sound'} ?>";
                sound.volume = 1;
                if(firstSound != 0) {
                    sound.load();
                    sound.play();
                }

                // Resize the image div
                var screenWidth = window.innerWidth;
                var screenHeight = window.innerHeight;
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
                numberOfImages = images.length;

                // Set the sounds
                var sounds = [<?php 
                    for($i=0; $i<count($pictures); $i++) {
                        if($i != 0)
                            echo ",";

                        echo '"'.$pictures[$i]->{'sound'}.'"';
                    } 
                ?>];

                // Set the comments
                var comments = [<?php 
                    for($i=0; $i<count($pictures); $i++) {
                        if($i != 0)
                            echo ",";

                        echo '"'.$pictures[$i]->{'comment'}.'"';
                    } 
                ?>];

                // Set the firstnames
                var firstnames = [<?php 
                    for($i=0; $i<count($pictures); $i++) {
                        if($i != 0)
                            echo ",";

                        echo '"'.$pictures[$i]->{'name'}.'"';
                    } 
                ?>];

                // Set a pointer to be used with the images array.
                var imagesPointer = 0;
                var imagePosition = "back";

                // Make the image rotate
                var rotation = setInterval(function() {
                    console.log(rotate);
                    if(rotate == "launch") {
                        setNewImage(imagesPointer);

                        // Reset the timer.
                        timer = 0;
                    }
                    else if(rotate == "stop") {
                        window.clearInterval(rotation);
                        rotate = "doNothing";
                    }
                }, 10000);

                // Use the timer to relaunch the rotation after 1 minute
                var timing = setInterval(function() {
                    console.log(timer);
                    if(timer == 60) {
                        rotate = "launch";

                        // Reset the timer.
                        timer = 0;
                    }
                    else {
                        timer++;
                    }
                }, 1000);

                // Update the list of images and sounds
                updatePicturesList();

                // Get the last action
                getAction();


                /**************************************************/
                /************* Additional functions ***************/
                /**************************************************/

                function setNewImage(imageNumber) {
                    // Set the image pointer
                    if(imagesPointer < images.length - 1)
                        imagesPointer++;
                    else
                        imagesPointer = 0;

                    if(imagesChanged == 1) {
                        imagesPointer = 0;
                        imagesChanged = 0;
                    }

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

                    // Display or hide the gun
                    if(comments[imagesPointer] == "")
                        $("#gun").fadeOut();
                    else
                        $("#gun").fadeIn();

                    // Set the other information
                    $("#firstname").empty().text(firstnames[imagesPointer]);
                    $("#comment").empty().text(comments[imagesPointer]);

                    // Pause any sound playing
                    sound.pause();

                    // Set the new sound
                    var selectedSound = sounds[imagesPointer];
                    var selectedSoundOgg = selectedSound.substr(0, selectedSound.length - 3) + "ogg";
                    $("#sounds audio#sound source:first-child").attr("src", "<?php echo base_url('assets/sounds') ?>/"+selectedSound);
                    $("#sounds audio#sound source:last-child").attr("src", "<?php echo base_url('assets/sounds') ?>/"+selectedSoundOgg);

                    // Play the new sound
                    if(selectedSound != 0) {
                        sound.load();
                        sound.play();
                    }
                }



                function updatePicturesList() {
                    imagesChanged = 0;

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
                                var newComments = new Array;
                                var newNames = new Array;
                                
                                for(var i=0; i<obj.pictures.length; i++) {
                                    newImages.push(obj.pictures[i].picture);
                                    newSounds.push(obj.pictures[i].sound);
                                    newComments.push(obj.pictures[i].comment);
                                    newNames.push(obj.pictures[i].name);
                                }

                                if(newImages.length != numberOfImages) {
                                    imagesChanged = 1;
                                    numberOfImages = newImages.length;
                                }
                                
                                images = newImages;
                                sounds = newSounds;
                                comment = newComments;
                                firstnames = newNames;
                            },
                            error: function(data, textStatus, jqXHR){
                                var obj = $.parseJSON(data);
                                console.log(obj);
                            }
                        });
                        
                        updatePicturesList();
                    }, 10000);
                }



                function getAction() {

                    setTimeout(function() {

                        $.ajax({
                            type: 'POST',
                            url: '<?= base_url("action/get") ?>',
                            data: { 
                                '0': $("form#update-pictures input[name='0']").val(), 
                                '1': $("form#update-pictures input[name='1']").val(), 
                                'csrf_museomix_2014': $("form#update-pictures input[name='csrf_museomix_2014']").val()
                            },
                            success: function(data, textStatus, jqXHR){
                                var obj = $.parseJSON(data);
                                
                                if(obj.action != 0)
                                    var action = obj.action.action;

                                if(action == "left") {
                                    imagesPointer -= 2;
                                    setNewImage(imagesPointer);
                                    rotate = "doNoting";
                                    timer = 0;
                                }
                                else if(action == "right") {
                                    setNewImage(imagesPointer);
                                    rotate = "doNoting";
                                    timer = 0;
                                }
                                else if(action == "top") {
                                    rotate = "launch";
                                    timer = 0;
                                }
                            },
                            error: function(data, textStatus, jqXHR){
                                var obj = $.parseJSON(data);
                                console.log(obj);
                            }
                        });
                        
                        getAction();
                    }, 1000);
                }
            });
        </script>
    </body>
</html>