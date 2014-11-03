<?php

class Carpenter {
    protected $postTypes = array();
    protected $taxonomies = array();
    protected $taxonomyData = array();
    protected $postTypeData = array();

    function __construct( Array $postTypeData ) {
        $this->postTypeData = $postTypeData;
        $this->acfCheck();
        $this->setupPostTypes();
        $this->setupTaxonomies();
    }

    function setupPostTypes() {
        foreach ($this->postTypeData as $postType => $args) {
            $this->postTypes[] = new PostType($postType, $args);

            if ( isset($args['taxonomies']) && is_array($args['taxonomies']) ) {
                foreach ( $args['taxonomies'] as $taxonomy => $taxArgs ) {
                    $this->taxonomyData[$taxonomy]['postTypes'][] = $postType;
                    $this->taxonomyData[$taxonomy]['taxArgs'] = $taxArgs;
                }
            }
        }
    }

    function setupTaxonomies() {
        foreach ($this->taxonomyData as $taxonomy => $args) {
            $this->taxonomies[] = new Taxonomy($taxonomy, $args['postTypes'], $args['taxArgs']);
        }
    }

    function acfCheck() {
        if ( ! function_exists("register_field_group") ) {
            add_action( 'admin_notices', array($this, 'acfNotice'));
        }
    }

    function acfNotice() {
        printf('<div class="error"><p>%s</p></div>', esc_html( 'Your custom post types are activated, but will not work properly unless you install and activate Advanced Custom Fields.', 'carpenter-wp' ));
    }
}