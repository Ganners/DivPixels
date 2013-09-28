<?php

/**
 * A little expirement to see how well we can draw an image using a load of
 * divs.
 *
 * I had some issues with memory as we're storing a lot of html here. Got
 * around that by setting a magic '_maxPixels' variable. The image will resize
 * as closely as possible to reach this. It's quite interesting to change it
 * and see the difference it takes to load.
 *
 * @author Mark Gannaway <mark@ganners.co.uk>
 */
namespace experiment;

class ImagePixelDrawer {
     
    protected $_imagePath,
              $_maxPixels = 10000;
    
    /**
     * Construct - Just pass it a darn image file path!
     *
     * @param string $imagePath - The physical path to the image
     *
     * @throws Exception if image doesn't exist
     */
    public function __construct($imagePath) {
        
        if(!file_exists($imagePath))
            throw new Exception('Image path does not exist');

        $this->_imagePath = $imagePath;

    }

    /**
     * This executes the magic - will return a string containing a lot of
     * HTML which prints out an image
     *
     * @return string
     */
    public function getPixelHTML() {

        // Grab the image resource
        $imageRes = $this->_getImageResource();
        $this->_shrinkImageToMaxPixels($imageRes);

        $width = imagesx($imageRes);
        $height = imagesy($imageRes); 

        $html = '';

        for($y = 0;$y < $height; ++$y) {
            // Start of row
            $html .= '<div style="overflow: hidden;">'.PHP_EOL;

            for($x = 0;$x < $width; ++$x) {
                // Column (Pixel)
                $pixel = imagecolorat($imageRes, $x, $y);
                $r = ($pixel >> 16) & 0xFF;
                $g = ($pixel >> 8) & 0xFF;
                $b = $pixel & 0xFF;

                $html .= '<div style="
                    height: 1px;
                    width: 1px;
                    float: left;
                    background: rgb('.$r.','.$g.','.$b.');">'.PHP_EOL;

                $html .= '</div>'.PHP_EOL;
            }
            
            $html .= '</div>';
            // End of row
        }
        
        return $html;

    }
    
    /**
     * Grabs an image resource from the image path sent to the obkect
     *
     * @returns Image Resource (gdlib)
     *
     * @throws Exception if file is not jpg/gif/png
     */
    protected function _getImageResource() {

        $size = getimagesize($this->_imagePath); 

        switch ($size['mime']) { 
        case "image/gif": 
            return imagecreatefromgif($this->_imagePath);
            break; 
        case "image/jpeg": 
            return imagecreatefromjpeg($this->_imagePath);
            break; 
        case "image/png": 
            return imagecreatefrompng($this->_imagePath);
            break; 
        default: 
            throw new Exception(
                'File is not a valid image, must be jpg/png/gif!');
            break; 
        }  

    }
    
    /**
     * Will calculate and resize an image to the magic maxPixels number
     * The image resource here is passed by reference
     *
     * @param Resource $imageRes - Image resource passed by reference
     */
    protected function _shrinkImageToMaxPixels(&$imageRes) {

        // The max pixels can be determined by:
        $currentWidth = imagesx($imageRes);
        $currentHeight = imagesy($imageRes); 
        
        $currentPixelCount = $currentWidth * $currentHeight;

        // If the pixel count is smaller than our limit, then just return
        if($currentPixelCount < $this->_maxPixels)
            return;
        
        $reductionNumber =
            sqrt($currentPixelCount / $this->_maxPixels);

        $newWidth = floor($currentWidth / $reductionNumber);
        $newHeight = floor($currentHeight / $reductionNumber);
        
        $tempImage = imagecreatetruecolor($newWidth, $newHeight);
        imagecopyresampled($tempImage, $imageRes,
            0, 0, 0, 0, $newWidth, $newHeight, $currentWidth, $currentHeight);

        imagedestroy($imageRes);
        $imageRes = $tempImage;

    }


}
