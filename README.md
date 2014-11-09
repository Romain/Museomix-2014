Procédure de démarrage
==================

Matériel
==================

- Plexiglasse et vinyl pour la signalétique et la communication
- La suite Adobe pour l'ensemble du travail
- Le code est développé en PHP, Javascript, HTML, CSS, MySQL. Code Limiter a permis de développer en PHP, JQuery pour le Javascript, Twitter Bootstrap pour la mise en forme, et Abode Air pour la Kinect
- Enfin, Premiere Pro pour la vidéo

## Emprunté à la tech-shop

- Un videoprojecteur pour projeter sur les murs
- Un spot de lumière bleue pour créer une ambiance
- Une Kinect détecteur de mouvement pour jouer avec les images
- Des bruitages qui sont associés aux images par les visiteurs et restitués grâce à une enceinte unidirectionnelle
- Un clavier et une souris


Procédure d'installation
==================

L'application se compose de 2 éléments :
- un site web permettant de gérer la partie mobile pour la capture des photos, ainsi que le rendu sous forme de slideshow
- une appli flash qui permet de gérer les mouvements des visiteurs captés par la Kinect

Pour installer l'application il faut :
1. Installez le site sur un serveur de type LAMP. Pour la démo réalisée dans le cadre de Museomix 2014 à Saint-Etienne, ce site est d'ores et déjà installé sur le serveur hébergeant museomix.org, et il utilise le nom de domaine museozoom.com.
Pour installer le site sur un serveur, uploadez les fichier via un logiciel FTP type Filezilla, renommez le htaccess-prod.txt en .htaccess et en faisant les modifications nécessaires. Installez manuellement la table sessions grâce au fichier sql présent dans le dossier install. Lancez l'URL http://votredomaine.com/private/migrate pour installer toutes les autres tables.
2. Générez éventuellement un QR code vers votre URL (ex : http://www.museozoom.com) et partagez le.
3. La partie Kinect est difficilement réplicable car il n'existe plus de librairies OpenNI pour mac pour faire tourner l'application flash nécessaire. Pour la démo Museomix, nous avons dû trouver un ordinateur avec ces librairies déjà installées, ce qui fut compliqué.
4. Branchez un ordinateur à un écran ou un vidéo projecteur. Assurez-vous que cet ordinateur soit connecté à internet. Ouvrez un navigateur (de préférence Chrome), puis lancez l'URL suivante : http://votredomaine.com/show. Passez le navigateur en plein écran.