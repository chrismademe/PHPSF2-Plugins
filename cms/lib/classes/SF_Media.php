<?php

namespace CMS;
use Exception;

class Media {

    # Properties
    private $db;
    private $config;
    private $properties;
    private $required;

    # Last Insert ID
    public $ID;

    /**
     * Construct
     */
    public function __construct( array $config = null ) {

        # Database Object
        $this->db = medoo();

        # Default Config
        $this->config = array(
            'table'         => 'sf_media'
        );

        # Custom Config
        if ( !is_null($config) ) {
            foreach ($config as $key => $value) {
                if ( array_key_exists($key, $this->config) ) {
                    $this->config[$key] = $value;
                }
            }
        }

        # Required properties
        $this->required = array(
            'post',
            'filename'
        );

        # Available properties
        $this->properties = array(
            'ID',
            'post',
            'date',
            'modified',
            'filename',
            'type',
            'status'
        );

    }

    /**
     * Get
     *
     * Returns either a single
     * or array of Objects
     *
     * @hooks: get_posts
     */
    public function get( array $args = null ) {

        # Defaults
        $order['ORDER'] = 'date DESC';

        # Set input arguments
        if ( !is_null($args) ) {
            foreach ( $args as $arg => $value ) {
                switch (true) {

                    # Where Properties
                    case $this->is_valid_property($arg):
                        $options[$arg] = $value;
                    break;

                    # Order
                    case $arg === 'order':
                        $order['ORDER'] = $value;
                    break;

                }
            }
        }

        # Build Where Statement
        switch (true) {

            # No arguments
            case !isset($options):
                $where = false;
            break;

            # More than 1 arguments
            case count($options) > 1:
                $where = array(
                    'AND' => $options
                );
            break;

            # 1 argument
            default:
                $where = $options;
            break;

        }

        # Merge Order & Where
        if ( is_array($where) ) {
            $where = array_merge($where, $order);
        }

        # Query
        $query = $this->db->select(
            $this->config['table'],
            '*',
            $where
        );

        # If empty, return false
        if ( !$query ) {
            return false;
        }

        # Create SF_Post objects
        foreach ( $query as $post ) {
            $raw_posts[] = new Media_Object($post);
        }

        # Apply Filters
        foreach ( $raw_posts as $post ) {
            $the_posts[] = apply_filters( 'get_media', $post );
        }

        # Return Objects
        if ( count($the_posts) > 1 ) {
            return $the_posts;
        }

        # For single object, keep
        # the ID
        $this->current_post = $the_posts[0]->ID;

        # Return Object
        return $the_posts[0];

    }

    /**
     * Create
     *
     * Create a new object
     */
    public function create( array $fields ) {

        # Check for required fields
        if ( !$this->has_required_property($fields) ) {
            throw new Exception('Missing required fields');
        }

        # Query
        if ( ! $this->ID = $this->db->insert($this->config['table'], $fields) ) {
            return false;
        }

        return true;

    }

    /**
     * Update
     *
     * Update existing post
     */
    public function update( $ID, array $fields ) {

        # Typecast ID
        if ( !is_int($ID) && is_numeric($ID) ) {
            $ID = (int) $ID;
        }

        # Check object exists
        if ( !$this->post_exists($ID) ) {
            throw new Exception('Media does not exist');
        }

        # If $ID is a string, get the ID
        if ( is_string($ID) && !is_numeric($ID) ) {
            $ID = $this->get_ID($ID);
        }

        # Insert date modified
        $fields['modified'] = date('Y-m-d H:i:s');

        # Update Snippet
        if ( !array_key_exists( 'snippet', $fields ) ) {
            $fields['snippet'] = $this->generate_snippet($fields['content']);
        }

        # Query
        return $this->db->update(
            $this->config['table'],
            $fields,
            array( 'ID' => $ID )
        );

    }

    /**
     * Publish
     *
     * Set status to 1
     */
    public function publish( $ID ) {
        return $this->update( $ID, array('status' => 1) );
    }

    /**
     * Unpublish
     *
     * Set status to 0
     */
    public function unpublish( $ID ) {
        return $this->update( $ID, array('status' => 0) );
    }

    /**
     * Delete
     *
     * Delete existing object
     */
    public function delete( $ID ) {
        return $this->update( $ID, array('status' => -1) );
    }

    /**
     * Remove
     *
     * Actually deletes the object from
     * the database.
     *
     * @NOTE: Use with caution, this cannot
     * be undone!
     */
    public function remove( $ID ) {

        # Check object exists
        if ( !$this->post_exists($ID) ) {
            throw new Exception('Media does not exist');
        }

        return $this->db->delete(
            $this->config['table'],
            array( 'ID' => $ID )
        );

    }

    #########################################
    ### Helpers                           ###
    #########################################

    /**
     * Exists
     *
     * Check to see whether an object
     * exists
     */
    public function post_exists( $ID ) {

        # Check ID is int or string
        if ( !is_int($ID) && !is_string($ID) ) {
            throw new Exception('Invalid input, ID must be Integer or String');
        }

        # Check for ID or alias
        if ( is_int($ID) || is_numeric($ID) ) {
            $modifier = 'ID';
        } else {
            $modifier = 'alias';
        }

        # Query database
        return $this->db->has(
            $this->config['table'],
            array( $modifier => $ID )
        );

    }

    /**
     * Get ID
     *
     * Get object ID from
     * alias.
     */
    public function get_ID( $alias ) {

        # Query
        $query = $this->db->select(
            $this->config['table'],
            array( 'ID' ),
            array( 'alias' => $alias )
        );

        if ( !$query ) {
            return false;
        }

        return $query[0]['ID'];

    }

    /**
     * Required Data
     */
    private function has_required_property( array $input ) {

        # Check $input is an array
        if ( !is_array($input) ) {
            return false;
        }

        # Check $input for required fields
        foreach ( $this->required as $required ) {
            if ( !array_key_exists($required, $input) ) {
                return false;
            }
        }

        return true;

    }

    /**
     * Is Valid Field
     *
     * Array of valid fields to
     * query in get/select functions
     */
    private function is_valid_property( $field ) {
        return in_array( $field, $this->properties );
    }

}
