<h2>Admin Theme</h2>

<div class="field">
    <div id="docsviewer_embed_admin_label" class="two columns alpha">
        <label for="docsviewer_embed_admin">Embed viewer in admin item show pages?</label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formCheckbox('docsviewer_embed_admin', null, 
        array('checked' => (bool) get_option('docsviewer_embed_admin'))); ?>
    </div>
</div>
<div class="field">
    <div id="docsviewer_width_admin_label" class="two columns alpha">
        <label for="docsviewer_width_admin">Viewer width, in pixels</label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formText('docsviewer_width_admin', get_option('docsviewer_width_admin')); ?>
    </div>
</div>
<div class="field">
    <div id="docsviewer_height_admin_label" class="two columns alpha">
        <label for="docsviewer_height_admin">Viewer height, in pixels</label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formText('docsviewer_height_admin', get_option('docsviewer_height_admin')); ?>
    </div>
</div>

<h2>Public Theme</h2>

<div class="field">
    <div id="docsviewer_embed_public_label" class="two columns alpha">
        <label for="docsviewer_embed_public">Embed viewer in public item show pages?</label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formCheckbox('docsviewer_embed_public', null, 
        array('checked' => (bool) get_option('docsviewer_embed_public'))); ?>
    </div>
</div>
<div class="field">
    <div id="docsviewer_width_public_label" class="two columns alpha">
        <label for="docsviewer_width_public">Viewer width, in pixels</label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formText('docsviewer_width_public', get_option('docsviewer_width_public')); ?>
    </div>
</div>
<div class="field">
    <div id="docsviewer_height_public_label" class="two columns alpha">
        <label for="docsviewer_height_public">Viewer height, in pixels</label>
    </div>
    <div class="inputs five columns omega">
        <?php echo $this->formText('docsviewer_height_public', get_option('docsviewer_height_public')); ?>
    </div>
</div>
<p>By using this service you acknowledge that you have read and agreed to the 
<a href="http://www.google.com/intl/en/policies/terms/">Google Docs Viewer Terms 
of Service</a>.</p>
