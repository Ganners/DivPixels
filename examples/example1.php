<!doctype HTML>
<html>
    <head>
        <title>HTML Pixels</title>
    </head>

    <body>
    <?php

    // Grab our class
    require_once(realpath('../src/ImagePixelDrawer.php'));

    // Construct, passing the location of our image
    $pixelDrawer = new \experiment\ImagePixelDrawer(realpath('images/test.jpg'));

    // Prints out the image in HTML
    echo $pixelDrawer->getPixelHTML();

    ?>
    </body>
</html>
