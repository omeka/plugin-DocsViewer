<?php
add_plugin_hook('install', 'DocsViewerPlugin::install');
add_plugin_hook('uninstall', 'DocsViewerPlugin::uninstall');
add_plugin_hook('config_form', 'DocsViewerPlugin::configForm');
add_plugin_hook('config', 'DocsViewerPlugin::config');
add_plugin_hook('admin_append_to_items_show_primary', 'DocsViewerPlugin::embed');

class DocsViewerPlugin
{
    const API_URL = 'http://docs.google.com/viewer';
    const VIEWER_WIDTH = 500;
    const VIEWER_HEIGHT = 600;
    
    private $_supportedFiles = array('pdf', 'doc', 'ppt', 'tif', 'tiff');
    
    public static function install()
    {
        set_option('docsviewer_width', DocsViewerPlugin::VIEWER_WIDTH);
        set_option('docsviewer_height', DocsViewerPlugin::VIEWER_HEIGHT);
    }
    
    public static function uninstall()
    {
        delete_option('docsviewer_width');
        delete_option('docsviewer_height');
    } 
    
    public static function configForm()
    {
?>
<label for="docsviewer_width">The width of the Docs Viewer, in pixels:</label>
<p><input type="text" name="docsviewer_width" value="<?php echo get_option('docsviewer_width'); ?>" size="5" /></p>
<label for="docsviewer_height">The height of the Docs Viewer, in pixels:</label>
<p><input type="text" name="docsviewer_height" value="<?php echo get_option('docsviewer_height'); ?>" size="5" /></p>
<p>By using this service you acknowledge that you have read and agreed to the <a href="http://docs.google.com/viewer/TOS?hl=en">Google Docs Viewer Terms of Service</a>.</p>
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
        $docsViewer = new DocsViewerPlugin;
        $docsViewer->_embed();
    }
    
    private function _embed()
    {
        foreach (__v()->item->Files as $file) {
            $extension = pathinfo($file->archive_filename, PATHINFO_EXTENSION);
            if (!in_array($extension, $this->_supportedFiles)) {
                continue;
            }
?>
<div>
    <h2>File: <?php echo $file->original_filename; ?></h2>
    <iframe src="<?php echo $this->_getUrl($file); ?>" 
            width="<?php echo get_option('docsviewer_width'); ?>" 
            height="<?php echo get_option('docsviewer_height'); ?>" 
            style="border: none;"></iframe>
</div>
<?php
        }
    }
    
    private function _getUrl(File $file)
    {
        require_once 'Zend/Uri.php';
        $uri = Zend_Uri::factory(self::API_URL);
        $uri->setQuery(array('url'      => WEB_FILES . '/' . $file->archive_filename, 
                             'embedded' => 'true'));
        return $uri->getUri();
    }
}