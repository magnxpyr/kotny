<?php
/**
 * @copyright   2006 - 2015 Magnxpyr Network
 * @license     New BSD License; see LICENSE
 * @link        http://www.magnxpyr.com
 * @author      Stefan Chiriac <stefan@magnxpyr.com>
 */

namespace Admin\Controllers;

use Engine\Mvc\AdminController;

class AdminFileManagerController extends AdminController {

    public function initialize() {

        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/theme.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/common.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/dialog.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/toolbar.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/navbar.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/statusbar.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/contextmenu.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/cwd.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/quicklook.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/commands.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/css/fonts.css");
        $this->assets->collection('header-css')->addCss("vendor/elFinder/jquery/ui-themes/smoothness/jquery-ui-1.10.1.custom.css");


        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/elFinder.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/elFinder.version.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/jquery.elfinder.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/elFinder.resources.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/elFinder.options.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/elFinder.history.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/elFinder.command.js");

        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/overlay.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/workzone.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/navbar.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/dialog.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/tree.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/cwd.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/toolbar.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/button.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/uploadButton.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/viewbutton.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/searchbutton.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/sortbutton.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/panel.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/contextmenu.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/path.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/stat.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/ui/places.js");

        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/back.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/forward.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/reload.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/up.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/home.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/copy.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/cut.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/paste.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/open.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/rm.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/info.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/duplicate.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/rename.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/help.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/getfile.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/mkdir.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/mkfile.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/upload.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/download.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/edit.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/quicklook.js");
	    $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/quicklook.plugins.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/extract.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/archive.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/search.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/view.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/resize.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/sort.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/commands/netmount.js");

        /*
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.ar.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.bg.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.ca.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.cs.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.de.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.el.js");
        */
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.en.js");
        /*
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.es.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.fa.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.fr.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.hu.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.it.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.jp.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.ko.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.nl.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.no.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.pl.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.pt_BR.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.ru.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.sl.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.sv.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.tr.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.zh_CN.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.zh_TW.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/i18n/elfinder.vi.js");
        */

        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/jquery.dialogelfinder.js");
        $this->assets->collection('header-js')->addJs("vendor/elFinder/js/proxy/elFinderSupportVer1.js");

        parent::initialize();
    }

    public function indexAction() {
        $this->setTitle('File Manager');
        $this->view->setVar("connector", 'vendor/elFinder/php/connector.minimal.php');
    }
}