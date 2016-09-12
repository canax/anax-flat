<?php

var_dump($_SESSION);
var_dump($_POST);

$themeHtmlClass = $this->di->session->get("theme-html-class");
var_dump($themeHtmlClass);

$id = $this->di->session->get("id");
$id++;
$this->di->session->set("id", $id);
var_dump($id);

?><article>
<h1>Theme selector</h1>
<p>Select theme.</p>


<form method="post">
    <fieldset>
        <legend>Select a theme</legend>
        <select name="theme" onchange="form.submit();">
            <option value="-1">Select a theme...</option>
            <option value="volvo">Volvo</option>
            <option value="saab">Saab</option>
            <option value="mercedes">Mercedes</option>
            <option value="audi">Audi</option>
        </select>
    </fieldset>
</form>

</article>
