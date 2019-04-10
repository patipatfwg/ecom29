CKEDITOR.editorConfig = function( config ) {
 config.toolbarGroups = [
  { name: 'document', groups: [ 'mode', 'document', 'doctools' ] },
  { name: 'clipboard', groups: [ 'clipboard', 'undo' ] },
  { name: 'editing', groups: [ 'find', 'selection', 'spellchecker', 'editing' ] },
  { name: 'forms', groups: [ 'forms' ] },
  { name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
  { name: 'paragraph', groups: [ 'list', 'indent', 'blocks', 'align', 'bidi', 'paragraph' ] },
  { name: 'links', groups: [ 'links' ] },
  { name: 'insert', groups: [ 'insert' ] },
  { name: 'styles', groups: [ 'styles' ] },
  { name: 'colors', groups: [ 'colors' ] },
  { name: 'tools', groups: [ 'tools' ] },
  { name: 'others', groups: [ 'others' ] },
  { name: 'about', groups: [ 'about' ] },
  { name: 'source', groups: [ 'source' ] },
 ];
 config.filebrowserUploadUrl = '/uploadfile/images';
 config.removeButtons = 'Save,Templates,NewPage,Preview,Print,Cut,Undo,Copy,Redo,Paste,PasteText,PasteFromWord,Find,Replace,SelectAll,Scayt,Form,Radio,Checkbox,TextField,Textarea,Select,Button,ImageButton,HiddenField,Subscript,Superscript,CopyFormatting,Strike,CreateDiv,BidiLtr,BidiRtl,Language,Flash,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe,Styles,Format,Font,ShowBlocks,About';
};

$(function() {

  $('body').on('click', '.cke_dialog_tab_selected', function() {
    $('div.cke_dialog_ui_file label.cke_dialog_ui_labeled_label').html('Only .jpg or .png allowed');
  });

  $('body').on('click', '.cke_dialog_ui_fileButton', function() {
    var inputIframe = $('iframe.cke_dialog_ui_input_file').contents().find('input').val();
    if (inputIframe != '') {
      $(this).addClass('loading');
    }
  });
});
