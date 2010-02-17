<?php
add_plugin_hook('config_form', 'DocsViewerPlugin::configForm');
add_plugin_hook('config', 'DocsViewerPlugin::config');
add_plugin_hook('admin_append_to_items_show_primary', 'DocsViewerPlugin::embed');

class DocsViewerPlugin
{
    const API_URL = 'http://docs.google.com/viewer';
    
    public static function configForm()
    {
?>
<label for="docsviewer_width">The width of the Docs Viewer, in pixels:</label>
<p><input type="text" name="docsviewer_width" value="<?php echo get_option('docsviewer_width'); ?>" size="5" /></p>
<label for="docsviewer_height">The height of the Docs Viewer, in pixels:</label>
<p><input type="text" name="docsviewer_height" value="<?php echo get_option('docsviewer_height'); ?>" size="5" /></p>
<?php
    }
    
    public static function config($post)
    {
        if (!is_numeric($post['docsviewer_width']) || !is_numeric($post['docsviewer_height'])) {
            throw new Exception('The width and height must be numeric.');
        }
        set_option('docsviewer_width', $post['docsviewer_width']);
        set_option('docsviewer_height', $post['docsviewer_height']);
    }
    
    public static function embed()
    {
        foreach (__v()->item->Files as $file) {
?>
<div>
    <iframe src="<?php echo DocsViewerPlugin::_getUrl($file);; ?>" 
            style="width:<?php echo get_option('docsviewer_width'); ?>px; height:<?php echo get_option('docsviewer_height'); ?>px;"
            frameborder="0"></iframe>
</div>
<?php
        }
    }
    
    private static function _getUrl(File $file)
    {
        require_once 'Zend/Uri.php';
        $uri = Zend_Uri::factory(self::API_URL);
        $uri->setQuery(array('url'      => WEB_ARCHIVE . '/' . $file->archive_filename, 
                             'embedded' => 'true'));
        return $uri->getUri();
    }
}