<?php
/**
 * Theme chooser in the design course.
 */

// These are the valid themes and their configuration
$separator = "------------------------------------------------";
$themes = [
    "separator0" => $separator,
    "base"      => [
        "title"      => "Minimal style, only the plain base",
        "class"      => "base",
        "stylesheets" => []
    ],
    "default"   => [
        "title"      => "Your own selected default theme",
        "class"      => "default",
        "stylesheets" => ["moped", "mask"]
    ],
    "light"     =>  [
        "title"      => "Very light theme, white, black and nuances of grey",
        "class"      => "light",
        "stylesheets" => []
    ],
    "color"     => [
        "title"      => "Enhance the light theme by adding a tiny bit of color",
        "class"      => "color",
        "stylesheets" => []
    ],
    "dark"      => [
        "title"      => "Dark background and light text",
        "class"      => "dark",
        "stylesheets" => []
    ],
    "colorful"  => [
        "title"      => "Make a very colorful theme",
        "class"      => "colorful",
        "stylesheets" => []
    ],
    "typography" => [
        "title"      => "A theme where the typography really stands out",
        "class"      => "light",
        "stylesheets" => []
    ],
    "separator1" => $separator,
    "fun"       => [
        "title"      => "All fun, test and play, make it stand out!",
        "class"      => "fun",
        "stylesheets" => []
    ],
];



// Check if form was posted with a valid theme
$output = null;
if (isset($_POST["theme"]) && array_key_exists($_POST["theme"], $themes)) {
    $this->di->session->set(
        "theme-message",
        "<p>Setting theme to "
            . $_POST["theme"]
            . ".<p>Theme details are:<br><pre>"
            . print_r($themes[$_POST["theme"]], 1)
            . "</pre>"
    );
    $theme = $themes[$_POST["theme"]];
    $theme["key"] = $_POST["theme"];
    $this->di->session->set("theme", $theme);
    $this->di->response->redirect($this->di->request->getCurrentUrl());
}


// Get current theme
$currentTheme = $this->di->session->get("theme");

// Message to display when theme is changed
$message = $this->di->session->readOnce("theme-message");


?><article>
<h1>Theme selector</h1>

<form method="post">
    <fieldset>
        <legend>Select a theme</legend>
        <select name="theme" onchange="form.submit();">
            <option value="-1" disabled="disabled">Select a theme...</option>
            <?php foreach ($themes as $key => $value) :
                $selected = $key == $currentTheme["key"]
                    ? "selected"
                    : null;
                $separate = $value === $separator
                    ? "disabled=\"disabled\""
                    : null;
                $value = $separate
                    ? $separator
                    : "$key - " . $value["title"];
            ?>
                <option value="<?= $key ?>" <?= $selected ?> <?= $separate ?>>
                    <?= $value ?>
                </option>
            <?php endforeach; ?>
        </select>
        
        <output>
            <?php if ($message) : ?>
                <p><?= $message ?></p>
            <?php endif; ?>
        </output>
    </fieldset>
</form>

<p>Here you can select a theme. By selecting a theme, the theme details are stored in the session and applied to the template when rendering the resulting page.</p>

<p>Basically, the theme you select will add its name as a class to the html-element. If you edit the settings to this file, <code>view/theme-selector/index.tpl.php</code>, you can extend this to more classes and even additional stylesheets.</p>

<p>The code that applies the details from session, to the template during rendering, is <code>config/routes/custom.php</code>.</p>

</article>
