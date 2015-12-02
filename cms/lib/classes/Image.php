<?php

namespace CMS;
use abeautifulsite\SimpleImage;
use Exception;

/** NOTE:
 * Make sure your $upload_folder and /thumbnails
 * has permissions set to at least 775
 */

class Image
{

    /* Properties */
    public  $public_folder;
    public  $upload_folder;
    public  $the_thumbnail;
    public  $the_thumbnails = array();
    public  $the_image;
    public  $the_images = array();

    /* Construct */
    public function __construct() {
        $this->public_folder = CMS_MEDIA_DIR;
        $this->upload_folder = ROOT_DIR . '/' . PUBLIC_ROOT . '/' . $this->public_folder;
    }

    /* Methods */

    // Upload image
    public function upload_image($image, $properties) {

        // Filename
        $random_string = $properties['filename'] . '-' . str_replace(' ', '_', $image['name']);

        // Initialise SimpleImage
        $img = new SimpleImage($image['tmp_name']);

        // Get Properties
        $max_width = $properties['max_width'];

        // Check for min width
        if ( isset($properties['min_width']) ) {
            if ( $img->get_width() < $properties['min_width'] ) {
                return false;
            }
        }

        // Resize image
        try {

            $img->fit_to_width($max_width);
            $img->save($this->upload_folder . '/' . $random_string);

            // Save the image to the_image
            $this->the_image = $this->public_folder . '/' . $random_string;

        } catch (Exception $e) {

            echo $e->getMessage();

            // In a production app, the error should be logged or
            // sent to your own error handler/alert system and not
            // echoed out.
        }

        // Check to see if we need to generate thumbnail
        if (isset($properties['thumbnail'])) {

            // Get Properties
            $thumb_width = $properties['thumbnail']['width'];
            $thumb_height = $properties['thumbnail']['height'];

            // Generate thumbnail
            try {

                $img->adaptive_resize($thumb_width, $thumb_height);
                $img->save($this->upload_folder . '/thumbnails/' . $random_string);

                // Save the image to the_thumbnail
                $this->the_thumbnail = $this->public_folder . '/thumbnails/' . $random_string;

            } catch (Exception $e) {

                echo $e->getMessage();

            }

        }

    }

    // Upload multiple images
    public function upload_images($images, $properties) {

        $image_count = count($images['name']);
        $upload_images = array();

        for ($i = 0; $i < $image_count; $i++) {

            $upload_images[$i]['name'] = $images['name'][$i];
            $upload_images[$i]['type'] = $images['type'][$i];
            $upload_images[$i]['tmp_name'] = $images['tmp_name'][$i];
            $upload_images[$i]['error'] = $images['error'][$i];
            $upload_images[$i]['size'] = $images['size'][$i];

        }

        $i = 0;
        foreach ($upload_images as $image) {

            // Pass to the upload_image() method
            $this->upload_image($image, $properties);

            // Get image & thumbnail and save it the_images/the_thumbnails array
            $this->the_images[$i] = $this->get_image();
            $this->the_thumbnails[$i] = $this->get_thumbnail();
            $i++;

        }

    }

    /* Utility Methods */
    public function get_image() {
        return $this->the_image;
    }

    public function get_images() {
        return $this->the_images;
    }

    public function get_thumbnail() {
        return $this->the_thumbnail;
    }

    public function get_thumbnails() {
        return $this->the_thumbnails;
    }

}

?>
