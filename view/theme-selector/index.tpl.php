<?php
/**
 * Theme chooser in the design course.
 */

// These are the valid themes
$separator = "------------------------------------------------";
$themes = [
    "separator0" => $separator,
    "base"      => "Minimal style, only the plain base",
    "default"   => "Your own selected default theme",
    "light"     => "Very light theme, white, black and nuances of grey",
    "color"     => "Enhance the light theme by adding a tiny bit of color",
    "dark"      => "Dark background and light text",
    "colorful"  => "Make a very colorful theme",
    "typography" => "A theme where the typography really stands out",
    "separator1" => $separator,
    "fun"       => "All fun, test and play, make it stand out!",
];



// Check if form was posted with a valid theme
$output = null;
if (isset($_POST["theme"]) && array_key_exists($_POST["theme"], $themes)) {
    $this->di->session->set("theme-message", "Setting theme to " . $_POST["theme"] . ".");
    $this->di->session->set("theme", $_POST["theme"]);
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
            <?php foreach ($themes as $theme => $description) :
                $selected = $theme == $currentTheme ? "selected" : null;
                $separate = $description == $separator ? "disabled=\"disabled\"" : null;
                $value = $separate ? $separator : "$theme - $description";
            ?>
                <option value="<?= $theme ?>" <?= $selected ?> <?= $separate ?>>
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

<p>Here you can select a theme. By selecting a theme, the theme "key" is saved in the session and applied to the template when rendering the resulting page. Basically, the theme you  select will add its name as a class to the html-element.</p>

<p>This means that you can style your theme using a CSS class that you know will be attached to the HTML element.</p>

<p>Like this, for a hallowen theme:</p>

<pre>
html.hallowen {
    background-image: url("pumpkin.png");
}
</pre>
</article>
