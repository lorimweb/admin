/*
Copyright (c) 2003-2013, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/
CKEDITOR.editorConfig = function(config) {
    var url = '/admin/assets/js/ckeditor/ckfinder/';
    // Define changes to default configuration here. For example:
    // config.language = 'fr';
    // config.uiColor = '#AADC6E';
    config.language = 'pt-br';
    config.skin = 'BootstrapCK-Skin';
    config.filebrowserBrowseUrl = url + 'ckfinder.html';
    config.filebrowserImageBrowseUrl = url + 'ckfinder.html?Type=Images';
    config.filebrowserFlashBrowseUrl = url + 'ckfinder.html?Type=Flash';
    config.filebrowserUploadUrl = url + 'core/connector/php/connector.php?command=QuickUpload&type=Files';
    config.filebrowserImageUploadUrl = url + 'core/connector/php/connector.php?command=QuickUpload&type=Images';
    config.filebrowserFlashUploadUrl = url + 'core/connector/php/connector.php?command=QuickUpload&type=Flash';
};