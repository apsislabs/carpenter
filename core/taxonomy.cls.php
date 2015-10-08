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
        $this->defaults = $defaults;

        $options = ( isset($options) && is_array($options) ) ? $options : array();

        $this->options = wp_parse_args($options, $this->getDefaultOptions());

        add_action('init', array(&$this, 'register'), 0);

        // Make default Taxonomies Permanent
        add_action('delete_term_taxonomy', array(&$this, 'delete'), 10, 1);
        add_action( "{$this->slug}_pre_edit_form", array(&$this, 'preEditForm'), 10, 2);

        // Add Filters for Changing Taxonomy Display
        add_filter( "term_name", array(&$this, 'defaultTermName'), 10, 2);
        add_filter( "{$this->slug}_row_actions", array(&$this, 'defaultActionsRow'), 10, 2);
        add_filter( "{$this->slug}_edit_form", array(&$this, 'defaultEditForm'), 10, 2);
    }

    public function register() {
        if ( ! taxonomy_exists($this->slug) ) {
            register_taxonomy( $this->slug, $this->postType, $this->options );

            if ( $this->defaults ) {
                $this->registerDefaults();
            }
        }
    }

    public function delete($arrIds) {
        $ids = is_array($arrIds) ? $arrIds : array($arrIds);

        foreach ($ids as $id) {
            $term = get_term_by('id', $id, $this->slug);
            $defaults = array_keys($this->defaults);

            // If this is a default term, refuse to delete it
            $termIsDefault   = ($term && in_array($term->slug, $defaults));

            if ( $termIsDefault ) {
                wp_die($this->getTermError(), '', array(
                    'back_link' => true
                ));
            }
        }
    }

    public function preEditForm($term, $taxonomy) {
        if ( in_array($term->slug, array_keys($this->defaults)) ) {
            $this->defaultTermNotice();
        }
    }

    public function defaultEditForm($term, $taxonomy) {
        if ( in_array($term->slug, array_keys($this->defaults)) ) {
            echo '<script>document.forms.edittag.slug.disabled = true;</script>';
            echo '<script>document.forms.edittag.parent.disabled = true;</script>';
        }
    }

    public function defaultTermName($tagName, $termObj) {
        if ( is_object($termObj) ) {
            $term = get_term($termObj->term_id, $this->slug, OBJECT, 'edit');

            $termIsDefault = ($term && in_array($term->slug, array_keys($this->defaults)));

            if ( $termIsDefault ) {
                $tagName .= " &ndash; " . __('Default Term', 'carpenter-wp');
            }
        }

        return $tagName;
    }

    public function defaultActionsRow($actions, $term) {
        if ( in_array($term->slug, array_keys($this->defaults)) ) {
            $actions = array(
                'view' => $actions['view']
            );
        }

        return $actions;
    }

    protected function registerDefaults() {
        foreach ($this->defaults as $slug => $term) {
            if ( !get_term_by('slug', $slug, $this->slug) ) {

                $options = isset($term['options']) ? wp_parse_args($term['options'], array(
                    'description' => null,
                    'slug' => $slug,
                    'parent' => 0
                )) : null;

                if ( is_string($options['parent']) ) {
                    $parent = get_term_by('slug', $options['parent'], $this->slug);
                    $options['parent'] = $parent ? $parent->term_id : 0;
                }

                // Format the Name
                $name = isset($term['name']) ? (string) $term['name'] : $this->createLabel($slug);
                wp_insert_term($name, $this->slug, $options);
            }
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

    protected function getTermError() {
        return new WP_Error('default_tax_delete', __("Cannot edit default terms for $this->singular taxonomy", 'carpenter-wp'));
    }

    public function defaultTermNotice() {
        printf('<div class="update-nag"><p>%s</p></div>', __( 'Some fields cannot be edited for default terms.', 'carpenter-wp' ));
    }
}
