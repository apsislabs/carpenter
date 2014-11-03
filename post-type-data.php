<?php

if ( class_exists('Carpenter') ) {
    $postTypeData = array(
        'slug' => array(
            'singular'   => 'Post Type',
            'plural'     => 'Post Types',
            'options'    => array(),
            'taxonomies' => array(
                'taxonomy' => array(
                    'singular' => 'Taxonomy',
                    'plural'   => 'Taxonomies'
                )
            )
        )
    );
}