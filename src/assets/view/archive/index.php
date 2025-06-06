<style>
</style>
<?php
    if( have_posts() ){

        // loop
        while ( have_posts() ) {
            the_post(); global $post;
            echo View::partial("archive/item", [ "post" => $post] );
        }
    } else {
        echo View::partial("archive/notfound", [  ] );

    }

?>