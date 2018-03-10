<?php
/**
 * @package      Magicgallery
 * @subpackage   Modules
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2016 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

// no direct access
defined('_JEXEC') or die;

jimport('Prism.init');
jimport('Magicgallery.init');

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'));

$galleryId = (int)$params->get('gallery_id');
$options  = array(
    'load_resources'   => true,
    'resource_state'   => Prism\Constants::PUBLISHED
);

$gallery  = new Magicgallery\Gallery\Gallery(JFactory::getDbo());
$gallery->load($galleryId, $options);

// Prepare the gallery.
if ($gallery->getId()) {
    $componentParams = JComponentHelper::getParams('com_magicgallery');
    /** @var  $componentParams Joomla\Registry\Registry */

    // Prepare the parameters of the galleries.
    $filesystemHelper = new Prism\Filesystem\Helper($componentParams);
    $pathHelper       = new Magicgallery\Helper\Path($filesystemHelper);

    // Prepare media URI.
    $mediaUri = $pathHelper->getMediaUri($gallery);
    $gallery->setMediaUri($mediaUri);

    // Prepare the gallery.
    $gallery = new Magicgallery\Gallery\SlideGallery($gallery, $params);

    $js = $gallery
        ->setSelector($params->get('selector'))
        ->prepareScriptDeclaration();

    $document = JFactory::getDocument();
    $document->addScriptDeclaration($js);

    require JModuleHelper::getLayoutPath('mod_magicgalleryslidegallery', $params->get('layout', 'default'));
}
