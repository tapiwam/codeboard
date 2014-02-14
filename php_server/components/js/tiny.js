tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink autosave link image lists charmap print preview hr anchor pagebreak spellchecker",
        "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
        "table contextmenu directionality emoticons template textcolor paste textcolor"
    ],
    toolbar1: "newdocument fullpage | bold italic underline strikethrough | alignleft aligncenter alignright alignjustify | styleselect formatselect fontselect fontsizeselect",
    toolbar2: "cut copy paste | searchreplace | bullist numlist | outdent indent blockquote | undo redo | link unlink anchor image media code | inserttime preview | forecolor backcolor",
    toolbar3: "table | hr removeformat | subscript superscript | charmap emoticons | print fullscreen | ltr rtl | spellchecker | visualchars visualblocks nonbreaking template pagebreak restoredraft",
    //menubar: false,
    toolbar_items_size: 'small',
    style_formats: [
        {title: 'Code', inline: 'code'},
        {title: 'Bold text', inline: 'b'},
        {title: 'Italic text', inline: 'em'},
        {title: 'Red text', inline: 'span', styles: {color: '#ff0000'}},
    ],
    templates: [
        {title: 'Test template 1', content: 'Test 1'},
        {title: 'Test template 2', content: 'Test 2'}
    ]

});