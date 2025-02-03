var toolbarOptions = [
    ['bold', 'italic'],
    ['blockquote'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'color': [] }],        
    ['clean']
];


if (document.getElementById('edit') !== null) {

    const editorEle = document.getElementById('body_text');

    const editor = new Quill(editorEle, {
        modules: {
            toolbar: toolbarOptions
        },
        placeholder: 'Inhoud...',
        theme: 'bubble',
        scrollingContainer: document.documentElement
    });

    const bodyInput = document.getElementById('body_input');

    editor.on('text-change', function(delta, oldDelta, source){
        bodyInput.value = editorEle.querySelector('.ql-editor').innerHTML;
    });

    editor.enable(true);

    window.deleteChapter = function(data) {
        var r = confirm("Weet je zeker dat je dit hoofdstuk wilt verwijderen?");
        if (r == true) {
            window.location.href = data.href;
        }
    }

    window.cancel = function(data) {
        var r = confirm("Weet je zeker dat je terug wilt gaan zonder op te slaan?");
        if (r == true) {
            window.location.href = data.href;
        }
    }
}
