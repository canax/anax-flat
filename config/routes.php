<?php

$app->router->add("*", function () use ($app) {

    $app->content->useCache(false);
    $content = $app->content->contentForRoute();

    $app->views->add("default/article", [
        "content" => $content->text,
    ]);

    $app->theme->addFrontmatter($content->frontmatter);

});
