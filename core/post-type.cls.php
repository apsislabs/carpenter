<?php

class PostType {
    protected $slug;
    protected $singular;
    protected $plural;
    protected $options;

    function __construct($name, $args = array()) {
        extract($args, EXTR_SKIP);

        $this->slug      = isset($slug) ? $slug : $name;
        $this->singular  = isset($singular) ? $singular : $this->createLabel($name);
        $this->plural    = isset($plural) ? $plural : $this->createLabel($name);
        $this->menu_icon = isset($menu_icon) ? $menu_icon : null;
        $this->supports  = isset($supports) ? $supports : null;
        $this->labels    = isset($labels) ? $labels : array();

        $options = ( isset($options) && is_array($options) ) ? $options : array();
        $this->options = wp_parse_args($options, $this->getDefaultOptions());

        add_action('init', array(&$this, 'register'));
    }

    public function register() {
        if ( ! post_type_exists($this->slug) ) {
            register_post_type( $this->slug, $this->options);
        }
    }

    protected function getDefaultLabels() {
        return array(
            'name'               => _x( $this->plural, 'post type general name', 'carpenter-wp' ),
            'singular_name'      => _x( $this->singular, 'post type singular name', 'carpenter-wp' ),
            'menu_name'          => _x( $this->plural, 'admin menu', 'carpenter-wp' ),
            'name_admin_bar'     => _x( $this->singular, 'add new on admin bar', 'carpenter-wp' ),
            'add_new'            => _x( "Add New", $this->slug, 'carpenter-wp' ),
            'add_new_item'       => __( "Add New {$this->singular}", 'carpenter-wp' ),
            'new_item'           => __( "New {$this->singular}", 'carpenter-wp' ),
            'edit_item'          => __( "Edit {$this->singular}", 'carpenter-wp' ),
            'view_item'          => __( "View {$this->singular}", 'carpenter-wp' ),
            'all_items'          => __( "All {$this->plural}", 'carpenter-wp' ),
            'search_items'       => __( "Search {$this->plural}", 'carpenter-wp' ),
            'parent_item_colon'  => __( "Parent {$this->plural}:", 'carpenter-wp' ),
            'not_found'          => __( "No {$this->plural} found.", 'carpenter-wp' ),
            'not_found_in_trash' => __( "No {$this->plural} found in Trash.", 'carpenter-wp' )
        );
    }

    protected function getDefaultOptions() {
        return array(
            'labels'    => wp_parse_args($this->labels, $this->getDefaultLabels()),
            'public'    => true,
            'rewrite'   => array( 'slug' => $this->slug ),
        );
    }

    protected function createLabel($name) {
        return ucwords(str_replace('_', ' ', $name));
    }
}