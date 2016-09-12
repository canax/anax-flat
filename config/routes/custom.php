<?php
/**
 * Add routes to the router, processed in the same order they are added.
 * The variabel $app relates to $this.
 */



/**
 * Add your own custom route
 */
$app->router->add("theme-selector", function () use ($app) {
    $app->theme->setTitle("Set theme");
    //$app->theme->setVariable("htmlClass", "moped");
    $app->views->add("theme-selector/index");
    
    //die("theme");
    
    // Create content
    // Add to view
});
