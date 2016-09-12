<?php

// These are the valid themes
$themes = ["base", "light", "colorful", "dark"];

// Check if form was posted with a valid theme
$output = null;
if (isset($_POST["theme"]) && in_array($_POST["theme"], $themes)) {
    $this->di->session->set("theme-message","Setting theme to " . $_POST["theme"] . ".");
    $this->di->session->set("theme", $_POST["theme"]);
    $this->di->response->redirect($this->di->request->getCurrentUrl());
}

$currentTheme = $this->di->session->get("theme");
// replace with readOnce
$message = $this->di->session->get("theme-message");
$this->di->session->set("theme-message", null);

?><article>
<h1>Theme selector</h1>

<form method="post">
    <fieldset>
        <legend>Select a theme</legend>
        <select name="theme" onchange="form.submit();">
            <option value="-1">Select a theme...</option>
            <?php foreach ($themes as $theme) : 
                $selected = $theme == $currentTheme ? "selected" : null;
            ?>
                <option value="<?= $theme ?>" <?= $selected ?>>
                    <?= $theme ?>
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

<p>Here you can select a theme. By selecting a theme, that theme is saved in the session and applied to the template when rendering the resulting page. Basically, the theme you  select will add its name as a class to the html-element.</p>

<p>This means that you can style your theme using a CSS class that you know will be attached to the HTML element.</p>

</article>
