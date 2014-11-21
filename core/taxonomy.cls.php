<?php

class Taxonomy {

    protected $slug;
    protected $singular;
    protected $plural;
    protected $postType;
    protected $options;

    function __construct($name, $postType, $args = array()) {
        extract($args, EXTR_SKIP);

        $this->postType = $postType;
        $this->slug     = isset($slug) ? $slug : $name;
        $this->singular = isset($singular) ? $singular : $this->createLabel($name);
        $this->plural   = isset($plural) ? $plural : $this->createLabel($name);

        $options = ( isset($options) && is_array($options) ) ? $options : array();
        $this->options = wp_parse_args($options, $this->getDefaultOptions());

        add_action('init', array(&$this, 'register'), 0);
    }

    public function register() {
        if ( ! taxonomy_exists($this->slug) ) {
            register_taxonomy( $this->slug, $this->postType, $this->options );
        }
    }

    protected function getLabels() {
        return  array(
            'name'              => _x( $this->plural, 'post type general name', 'carpenter-wp' ),
            'singular_name'     => _x( $this->singular, 'post type singular name', 'carpenter-wp' ),
            'search_items'      => __( "Search {$this->plural}", 'carpenter-wp' ),
            'all_items'         => __( "All {$this->plural}", 'carpenter-wp' ),
            'parent_item_colon' => __( "Parent {$this->plural}", 'carpenter-wp' ),
            'parent_item_colon' => __( "Parent {$this->plural}:", 'carpenter-wp' ),
            'edit_item'         => __( "Edit {$this->singular}", 'carpenter-wp' ),
            'update_item'       => __( "Update {$this->singular}", 'carpenter-wp' ),
            'add_new_item'      => __( "Add New {$this->singular}", 'carpenter-wp' ),
            'new_item_name'     => __( "New {$this->singular} Name", 'carpenter-wp' ),
            'menu_name'         => _x( $this->plural, 'admin menu', 'carpenter-wp' ),
        );
    }

    protected function getDefaultOptions() {
        return array(
            'hierarchical'      => true,
            'labels'            => $this->getLabels(),
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => $this->slug ),
        );
    }

    protected function createLabel($name) {
        return ucwords(str_replace('_', ' ', $name));
    }
}