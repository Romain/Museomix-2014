<!DOCTYPE html>
<html lang="fr">
    <head>
        <?php include('inc/meta.php'); ?>
        <?php include('inc/css.php'); ?>
        <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    </head>
    <body id="add-body">
        <div class="wrapper">
            <a href="#" title="Accéder à l'aide" data-toggle="modal" data-target="#helpModal">
                <div id="help">
                    <i class="fa fa-question"></i>
                </div>
            </a>

            <div class="text-center">
                <h1 class="text-center">
                    <img src="<?php echo base_url('assets/img/museozoom-mobile.png'); ?>" alt="MuseoZoom" title="MuseoZoom">
                </h1>

                <div id="gun" class="text-center">
                    <img src="<?php echo base_url('assets/img/squared-gun.png'); ?>" alt="Gun" title="Gun">
                </div>

                <?php if(isset($message)) : ?>
                    <div class="alert alert-<?php echo $message_type ?>"><?php echo $message; ?></div>
                <?php endif; ?>

                <?php echo form_open_multipart( base_url('picture/add'), array('id' => 'add-picture', 'class' => '', 'role' => 'form'), array($this->security->get_csrf_token_name(), $this->security->get_csrf_hash()) ); ?>

                    <div class="form-group">
                        <div id="picture-container">
                            <?php echo form_upload( array('name' => 'picture', 'placeholder' => 'Votre photo', 'class' => 'form-control', 'id' => 'picture') ); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="picture">Choisissez un son</label>
                        <?php echo form_dropdown('sound', array('0' => 'Nos sons d\'ambiance', 'sound-1.mp3' => 'sound-1.mp3', 'sound-2.mp3' => 'sound-2.mp3', 'sound-3.mp3' => 'sound-3.mp3'), '0', 'class="form-control"'); ?>
                    </div>

                    <div class="form-group">
                        <label for="picture">Associez un commentaire</label>
                        <?php echo form_textarea(array('name' => 'comment', 'placeholder' => 'Votre commentaire en 140 caractères', 'class' => 'form-control', 'rows' => 4)); ?>
                    </div>

                    <div class="form-group">
                        <label for="picture">Votre prénom ou surnom</label>
                        <?php echo form_input(array('name' => 'name', 'placeholder' => 'Ex. : Bobby', 'class' => 'form-control')); ?>
                    </div>

                    <div class="text-center">
                        <?php echo form_submit(array('name' => 'submit', 'value' => 'Partager', 'class' => 'btn btn-default margin-top')); ?>
                    </div>

                <?php echo form_close(); ?>
            </div>
        </div>

        <div id="sounds">
            <audio id="sound">
                <source src="<?php echo base_url('sounds/sound-1.mp3') ?>" type="audio/mpeg">
                Your browser does not support the audio element.
            </audio>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="helpModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel">Aide</h4>
                    </div>
                    <div class="modal-body">
                        <p>
                            Retrouvez toute l'aide nécessaire à l'utilisation de MuseoZoom dans cette section.
                        </p>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Fermer</button>
                    </div>
                </div>
            </div>
        </div>

        <?php include('inc/js.php'); ?>
        <script src="<?php echo base_url('assets/js/validation/dist/jquery.validate.min.js') ?>"></script>
        <script type="text/javascript">
            $(document).ready(function() {

                // Form validation
                $("#add-picture").validate({
                    rules: {
                        picture: "required",
                        comment: {
                            maxlength: 140
                        },
                        name: "required"
                    },
                    messages: {
                        picture: "Veuillez choisir une image.",
                        comment: {
                            maxlength: "Votre commentaire ne peut pas faire plus de 140 caractères."
                        },
                        name: "Veuillez nous donner votre prénom ou surnom."
                    },
                    errorPlacement: function(error, element) {
                        error.insertBefore(element);
                    }
                });

                // Play sound when the user changes the menu
                var sound = $("#sounds audio#sound").get(0);
                sound.volume = 1;

                $("#add-picture select[name='sound']").change(function() {
                    // Pause any sound playing
                    sound.pause();

                    // Rewind to the beginning of the sound
                    sound.currentTime = 0;

                    // Set the new sound
                    var selectedSound = $(this).val();
                    $("#sounds audio#sound source").attr("src", "<?php echo base_url('sounds') ?>/"+selectedSound);

                    // Play the new sound
                    sound.load();
                    sound.play();
                });
            });
        </script>
    </body>
</html>