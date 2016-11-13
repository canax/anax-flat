<?php
/**
 * Add routes to the router, processed in the same order they are added.
 * The variabel $app relates to $this.
 */

// Support theme selector by adding class to html element
if ($app->session->has("theme")) {
    $theme = $app->session->get("theme");

    if (isset($theme["class"])) {
        $app->theme->appendToVariable("htmlClass", $theme["class"]);
    }

    if (isset($theme["stylesheets"])) {
        $stylesheets = $theme["stylesheets"];
        if (is_string($stylesheets)) {
            $app->theme->addStylesheet($stylesheets);
        } elseif (is_array($stylesheets)) {
            foreach ($stylesheets as $stylesheet) {
                $app->theme->addStylesheet($stylesheet);
            }
        }
    }
} else {
    $app->theme->appendToVariable("htmlClass", "default");
}



/**
 * Add your own custom route
 */
$app->router->add("theme-selector", function () use ($app) {
    $app->theme->setTitle("Set theme");
    $app->views->add("theme-selector/index");
});
