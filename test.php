<?php
include_once 'sprhide.php';
?>
<html>
    <head>
        <title>SprHide</title>
        <script src="http://www.google.com/jsapi"></script>
    </head>
    <body>
        <?php $kitchen = new Sprhide("p1", "kitchen.jpg", 20);?>

        <script>
	        // Load jQuery
	        google.load("jquery", "1.2.6");

	        google.setOnLoadCallback(function() {
      	        <?php $kitchen->sprhide_image();?>
	        });
        </script>
    </body>
</html>
