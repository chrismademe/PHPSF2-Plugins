<?php

namespace CMS;
use Exception;

class Post {

    # Properties
    public $ID;
    public $author;
    public $alias;
    public $type;
    public $title;
    public $date;
    public $modified;
    public $snippet;
    public $content;
    public $parent;
    public $status;
    public $meta;
    public $media;

    /**
     * Construct
     */
    public function __construct( $post, $meta = true ) {

        # Check data
        if ( !is_object($post) && !is_array($post) ) {
            throw new Exception('Post data must be object or array');
        }

        # Save data
        foreach ( $post as $property => $value ) {
            $this->$property = $value;
        }

        # Load Meta
        if ( $meta ) {
            $this->meta = medoo()->select(
                'sf_meta',
                '*',
                array( 'post' => $this->ID )
            );
        }

        # Load Cover Image
        if ( $this->has_cover_image() ) {
            $this->cover_image = $this->get_cover_image();
        } else {
            $this->cover_image = CMS_MEDIA_DIR . '/default.jpg';
        }

    }

    /**
     * Has Meta
     *
     * Checks to see whether this post
     * has meta attached to it.
     */
    public function has_meta() {

        /**
         * Run Query
         */
        return medoo()->has(
            'sf_meta',
            array( 'post' => $this->ID )
        );

    }

    /**
     * Has Media
     *
     * Check to see if there are any
     * posts with the parent ID of
     * this post
     */
    public function has_media( $type = false ) {

        # What to search for
        $where = array( 'post' => $this->ID );

        # Specific type
        if ( $type ) {
            $where = array(
                'AND' => array(
                    'post' => $this->ID,
                    'type' => $type
                )
            );
        }

        /**
         * Run Query
         */
        return medoo()->has(
            'sf_media',
            $where
        );

    }

    /**
     * Get Media
     */
    public function get_media() {

        $media = medoo()->select(
            'sf_media',
            '*',
            array(
                'AND' => array(
                    'post' => $this->ID,
                    'type[!]' => 'cover_image'
                )
            )
        );

        return $media;

    }

    /**
     * Has Cover Image
     *
     * Check to see if post has
     * media with type 'cover_image'
     */
    public function has_cover_image() {
        return $this->has_media('cover_image');
    }

    /**
     * Get Cover Image
     */
    public function get_cover_image() {

        $image = medoo()->select(
            'sf_media',
            'filename',
            array(
                'AND' => array(
                    'type' => 'cover_image',
                    'post' => $this->ID
                ),
                'ORDER' => 'date DESC',
                'LIMIT' => 1
            )
        );

        if ( $image ) {
            return $image[0];
        }

    }

    /**
     * Has Children
     *
     * Check to see if there are any
     * posts with the parent ID of
     * this post
     */
    public function has_children() {

        /**
         * Run Query
         */
        return medoo()->has(
            'sf_posts',
            array( 'parent' => $this->ID )
        );

    }

    /**
     * Is Child
     *
     * Check to see whether
     * this object has a parent
     */
    public function is_child() {
        return $this->parent > 0;
    }

    /**
     * Is Published
     */
    public function is_published() {
        return $this->status === 1;
    }

    /**
     * Is Deleted
     */
    public function is_deleted() {
        return $this->status === -1;
    }

    /**
     * Is Modified
     */
    public function is_modified() {
        return !empty($this->modified);
    }

    /**
     * Is Owner
     *
     * Checks to see whether specified
     * user ID is the owner of this object
     */
    public function is_owner( $userID ) {
        return $this->owner === $userID;
    }

}
