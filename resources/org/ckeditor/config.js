/**
 * @license Copyright (c) 2003-2017, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';

	//ckfinder setting about img read
	config.filebrowserBrowseUrl = 'resources/org/ckeditor/kcfinder/browse.php?opener=ckeditor&type=files';
 
    config.filebrowserImageBrowseUrl = 'resources/org/ckeditor/kcfinder/browse.php?opener=ckeditor&type=images';
 
    // config.filebrowserFlashBrowseUrl = 'resources/org/ckeditor/kcfinder/browse.php?opener=ckeditor&type=flash';
 
    config.filebrowserUploadUrl = 'resources/org/ckeditor/kcfinder/upload.php?opener=ckeditor&type=files';
 
    config.filebrowserImageUploadUrl = 'resources/org/ckeditor/kcfinder/upload.php?opener=ckeditor&type=images';
 
    // config.filebrowserFlashUploadUrl = 'resources/org/ckeditor/kcfinder/upload.php?opener=ckeditor&type=flash';


    //base setting
	config.font_names = "新細明體;標楷體;微軟正黑體;" +config.font_names ;
    config.height = 300;
    config.width = 1000;
};
