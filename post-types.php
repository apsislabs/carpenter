<?php

if ( function_exists('build_post_types') ) {
    $postTypeData = array(
        'post_type_slug' => array(
            'singular'   => 'Post Type',
            'plural'     => 'Post Types',
            'options'    => array(),
            'taxonomies' => array(
                'taxonomy_slug' => array(
                    'singular' => 'Taxonomy',
                    'plural'   => 'Taxonomies'
                )
            )
        )
    );

    build_post_types($postTypeData);
}