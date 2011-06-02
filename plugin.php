<?php
add_plugin_hook('install', 'DocsViewerPlugin::install');
add_plugin_hook('uninstall', 'DocsViewerPlugin::uninstall');
add_plugin_hook('config_form', 'DocsViewerPlugin::configForm');
add_plugin_hook('config', 'DocsViewerPlugin::config');
add_plugin_hook('admin_append_to_items_show_primary', 'DocsViewerPlugin::append');
add_plugin_hook('public_append_to_items_show', 'DocsViewerPlugin::append');

class DocsViewerPlugin
{
    const API_URL = 'http://docs.google.com/viewer';
    const DEFAULT_VIEWER_EMBED = 1;
    const DEFAULT_VIEWER_WIDTH = 500;
    const DEFAULT_VIEWER_HEIGHT = 600;
    
    private $_supportedFiles = array('pdf', 'doc', 'ppt', 'tif', 'tiff');
    
    public static function install()
    {
        set_option('docsviewer_embed_admin', DocsViewerPlugin::DEFAULT_VIEWER_EMBED);
        set_option('docsviewer_width_admin', DocsViewerPlugin::DEFAULT_VIEWER_WIDTH);
        set_option('docsviewer_height_admin', DocsViewerPlugin::DEFAULT_VIEWER_HEIGHT);
        set_option('docsviewer_embed_public', DocsViewerPlugin::DEFAULT_VIEWER_EMBED);
        set_option('docsviewer_width_public', DocsViewerPlugin::DEFAULT_VIEWER_WIDTH);
        set_option('docsviewer_height_public', DocsViewerPlugin::DEFAULT_VIEWER_HEIGHT);
    }
    
    public static function uninstall()
    {
        delete_option('docsviewer_width');
        delete_option('docsviewer_height');
    } 
    
    public static function configForm()
    {
        include 'config_form.php';
    }
    
    public static function config($post)
    {
        if (!is_numeric($post['docsviewer_width_admin']) || 
            !is_numeric($post['docsviewer_height_admin']) || 
            !is_numeric($post['docsviewer_width_public']) || 
            !is_numeric($post['docsviewer_height_public'])) {
            throw new Omeka_Validator_Exception('The width and height must be numeric.');
        }
        set_option('docsviewer_embed_admin', (int) (boolean) $post['docsviewer_embed_admin']);
        set_option('docsviewer_width_admin', $post['docsviewer_width_admin']);
        set_option('docsviewer_height_admin', $post['docsviewer_height_admin']);
        set_option('docsviewer_embed_public', (int) (boolean) $post['docsviewer_embed_public']);
        set_option('docsviewer_width_public', $post['docsviewer_width_public']);
        set_option('docsviewer_height_public', $post['docsviewer_height_public']);
    }
    
    public static function append()
    {
        // Embed viewer only if configured to do so.
        if ((is_admin_theme() && !get_option('docsviewer_embed_admin')) || 
            (!is_admin_theme() && !get_option('docsviewer_embed_public'))) {
            return;
        }
        $docsViewer = new DocsViewerPlugin;
        $docsViewer->embed();
    }
    
    public function embed()
    {
        // Get valid documents.
        $docs = array();
        foreach (__v()->item->Files as $file) {
            $extension = pathinfo($file->archive_filename, PATHINFO_EXTENSION);
            if (!in_array($extension, $this->_supportedFiles)) {
                continue;
            }
            $docs[] = $file;
        }
?>
<?php if (!empty($docs)): ?>
<script type="text/javascript">
jQuery(document).ready(function () {
    var docviewer = jQuery('#docsviewer_plugin_docviewer');
    
    // Set the default docviewer.
    docviewer.append(
    '<h2>Viewing: ' + <?php echo js_escape($docs[0]->original_filename); ?> + '</h2>' 
  + '<iframe src="' + <?php echo js_escape($this->_getUrl($docs[0])); ?> 
  + '" width="' + <?php echo is_admin_theme() ? js_escape(get_option('docsviewer_width_admin')) : js_escape(get_option('docsviewer_width_public')); ?> 
  + '" height="' + <?php echo is_admin_theme() ? js_escape(get_option('docsviewer_height_admin')) : js_escape(get_option('docsviewer_height_public')); ?> 
  + '" style="border: none;"></iframe>');
    
    // Handle the document click event.
    jQuery('.docsviewer_plugin_docs').click(function(event) {
        event.preventDefault();
        
        // Reset the docviewer.
        docviewer.empty();
        docviewer.append(
        '<h2>Viewing: ' + jQuery(this).text() + '</h2>' 
      + '<iframe src="' + this.href 
      + '" width="' + <?php echo is_admin_theme() ? js_escape(get_option('docsviewer_width_admin')) : js_escape(get_option('docsviewer_width_public')); ?> 
      + '" height="' + <?php echo is_admin_theme() ? js_escape(get_option('docsviewer_height_admin')) : js_escape(get_option('docsviewer_height_public')); ?> 
      + '" style="border: none;"></iframe>');
    });
});
</script>
<div>
    <h2>Document Viewer</h2>
    <p>Click below to view a document.</p>
    <ul>
        <?php foreach($docs as $doc): ?>
        <li><a href="<?php echo html_escape($this->_getUrl($doc)); ?>" class="docsviewer_plugin_docs"><?php echo html_escape($doc->original_filename); ?></a></li>
        <?php endforeach; ?>
    </ul>
</div>
<div id="docsviewer_plugin_docviewer"></div>
<?php endif; ?>
<?php
    }
    
    private function _getUrl(File $file)
    {
        require_once 'Zend/Uri.php';
        $uri = Zend_Uri::factory(self::API_URL);
        $uri->setQuery(array('url'      => $file->getWebPath('archive'), 
                             'embedded' => 'true'));
        return $uri->getUri();
    }
}