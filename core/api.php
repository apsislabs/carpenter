<?php

function build_post_types( array $post_type_data ) {
    global $carpenter;

    $carpenter = new Carpenter($post_type_data);

    return $carpenter;
}