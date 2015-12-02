<?php

namespace CMS;
use Exception;

class Media_Object {

    # Properties
    public $ID;
    public $post;
    public $date;
    public $modified;
    public $filename;
    public $type;
    public $status;
    public $meta;

    /**
     * Construct
     */
    public function __construct( $media, $meta = true ) {

        # Check data
        if ( !is_object($media) && !is_array($media) ) {
            throw new Exception('Media_Object data must be object or array');
        }

        # Save data
        foreach ( $media as $property => $value ) {
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
            'sf_media',
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
