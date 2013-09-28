<!doctype HTML>
<html>
    <head>
        <title>HTML Pixels</title>
        <style>
            body {
                zoom: 1;
                text-align: center;
            }
            .container {
                margin: 0 auto;
                display: inline-block;
                width: auto;
            }
        </style>
    </head>

    <body>
        <div class="container">
            <?php

            // Grab our class
            require_once(realpath('../src/ImagePixelDrawer.php'));

            // Construct, passing the location of our image
            $pixelDrawer = new \experiment\ImagePixelDrawer(realpath('images/test.jpg'));

            // Prints out the image in HTML
            echo $pixelDrawer->getPixelHTML();

            ?>
        </div>
    </body>
</html>
