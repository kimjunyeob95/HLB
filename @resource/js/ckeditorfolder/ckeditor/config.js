/**
 * @license Copyright (c) 2003-2019, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see https://ckeditor.com/legal/ckeditor-oss-license
 */

CKEDITOR.editorConfig = function (config) {
    // Define changes to default configuration here.
    // For complete reference see:
    // https://ckeditor.com/docs/ckeditor4/latest/api/CKEDITOR_config.html
  
    // The toolbar groups arrangement, optimized for two toolbar rows.
    
    CKEDITOR.dtd.$removeEmpty.i = false;
    CKEDITOR.dtd.$removeEmpty.hgroup = false;
    CKEDITOR.dtd.$removeEmpty.p = false;
    CKEDITOR.dtd.$removeEmpty.span = 0;
  
    CKEDITOR.config.contentsCss = [
      ["/@resource/css/style.css"],
      ["/@resource/css/common.css"],
      ["/@resource/css/layout.css"],
    ];

    //엔터시 줄바꿈처리
    config.enterMode = CKEDITOR.ENTER_BR;

    // config.docType = '&lt;!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "<a _foo="con_link" href="http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd" target="_blank">http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd</a>"&gt;';
    config.language = "ko";
    config.shiftEnterMode = CKEDITOR.ENTER_P;
    config.toolbarCanCollapse = true;
    config.menu_subMenuDelay = 0;
    config.toolbar = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ], items: [ 'Source', '-', 'Save', 'NewPage', 'Preview', 'Print', '-', 'Templates' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ], items: [ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ], items: [ 'Find', 'Replace', '-', 'SelectAll', '-', 'Scayt' ] },
        { name: 'forms', items: [ 'Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField' ] },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ], items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ], items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote',     'CreateDiv', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock', '-', 'BidiLtr', 'BidiRtl', 'Language' ] },
        { name: 'links', items: [ 'Link', 'Unlink', 'Anchor' ] },
        { name: 'insert', items: [ 'Image', 'Flash', 'Table', 'HorizontalRule', 'Smiley', 'SpecialChar', 'PageBreak', 'Iframe' ] },
        '/',
        { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
        { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
        { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
        { name: 'others', items: [ '-' ] },
        { name: 'about', items: [ 'About' ] }
    ];
    config.toolbarGroups = [
        { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
        { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
        { name: 'editing', groups: [ 'find', 'selection', 'spellchecker' ] },
        { name: 'forms' },
        '/',
        { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
        { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi' ] },
        { name: 'links' },
        { name: 'insert' },
        '/',
        { name: 'styles' },
        { name: 'colors', groups: [ 'Table', 'HorizontalR' ]},
        { name: 'tools' },
		{ name: 'others' , groups: [ 'JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ]},
        { name: 'about' }
    ];
    config.extraPlugins= 'image2,confighelper,uploadimage,colorbutton,colordialog,justify,font';
    config.allowedContent = true;
    config.format_tags = 'p;h1;h2;h3;pre';
    config.filebrowserUploadUrl = "/@proc/ckupload.php";
    config.removeButtons = "Underline,Subscript,Superscript";
    config.removeDialogTabs = "image:advanced;link:advanced";
    config.basicEntities = false;
    config.fillEmptyBlocks = false;
    
  };
  