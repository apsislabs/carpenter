# Carpenter

Carpenter is a simple-to-use plugin for adding custom post-types and taxonomies to your WordPress plugins and themes. To use, simply install the Carpenter plugin, then include a post-type registration file somewhere in your theme or plugin files.

## API

Post types are built using standard PHP arrays. Here's an example file that would create a custom Post Type with its own custom Taxonomy.

```
if ( function_exists('build_post_types') ) {
    $postTypeData = array(
        'slug' => array(
            'singular'   => 'Post Type',
            'plural'     => 'Post Types',
            'options'    => array(),
            'taxonomies' => array(
                'taxonomy_slug' => array(
                    'singular' => 'Taxonomy',
                    'plural'   => 'Taxonomies',
                    'options'  => array(
                        'hierarchical' => true
                    ),
                    'defaults' => array(
                        'term_slug' => array(
                            'name' => 'Default Term',
                            'options' => array(
                                'description' => 'A default term — this cannot be deleted by users.'
                            )
                        ),
                        'child_term_slug' => array(
                            'name' => 'Default Child Term',
                            'options' => array(
                                'parent' => 'term_slug' // parents are assigned by slug
                            )
                        )
                    )
                )
            )
        )
    );

    build_post_types($postTypeData);
}
```

- **slug**: Your post type's slug, unless overriden.
- **singular**: The singular name for your post type. This will be passed through WordPress's i18n functions.
- **plural**: The plural name for your post type. This will be passed through WordPress's i18n functions.
- **options**: For overriding the default options when necessary. This is an array that is identical to the `args` param for the native `register_post_type` function.
- **taxonomies**: If you'd like to include a taxonomy for your post type, create it here.
    + **taxonomy_slug**: The slug for your taxonomy.
    + **singular**: The singular name for your taxonomy. This will be passed through WordPress's i18n functions.
    + **plural**: The plural name for your taxonomy. This will be passed through WordPress's i18n functions.
