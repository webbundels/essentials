
var toolbarOptions = [
    ['bold', 'italic'],
    ['blockquote'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'color': [] }],
    ['clean'],
];

const Parchment = Quill.import('parchment');
const customRegistry = new Parchment.Registry();


if (document.getElementById('edit') !== null) {

    const editorEle = document.getElementById('body_text');

    const editor = new Quill(editorEle, {
        customRegistry,
        modules: {
            toolbar: '#toolbar'
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

var existingSubchapters = document.getElementsByClassName("subchapter_holder");


for (let i=0; i < existingSubchapters.length; i++) {

    var existingSubchapter = existingSubchapters.item(i);

    var newEditorElement = existingSubchapter.getElementsByClassName("subchapter_editor")[1];
    const editorInput = existingSubchapter.getElementsByClassName("subchapter_editor")[0];
    var newToolbarElement = existingSubchapter.getElementsByClassName('sub_toolbar')[0];


    createQuillEditor(newEditorElement, editorInput, newToolbarElement);

    newEditorElement.querySelector('.ql-editor').innerHTML = editorInput.value;

}

function createQuillEditor(editorElement, editorInput, toolbarElement) {

    var newEditor = new Quill(editorElement, {
        customRegistry,
        modules: {
            toolbar: toolbarElement
        },
        placeholder: 'Inhoud...',
        theme: 'bubble',
        scrollingContainer: document.documentElement
    });

    newEditor.on('text-change', function(delta, oldDelta, source){
        editorInput.value = editorElement.querySelector('.ql-editor').innerHTML;
    });

    newEditor.enable(true);
}

function createNewSubchapterElement() {
    var subchapterHolder = document.createElement('div');
    subchapterHolder.classList.add('subchapter_holder');
    subchapterHolder.style.marginTop = '20vh';
/////

    var subchapterToolbar = document.createElement('div');
    subchapterToolbar.classList.add('sub_toolbar');

    var qlSize = document.createElement('select');
    subchapterToolbar.classList.add('ql-size');

    var smallOption = document.createElement('option');
    smallOption.value = 'small';

    var normalOption = document.createElement('option');
    normalOption.value = 'normal';
    normalOption.selected = true;

    var largeOption = document.createElement('option');
    largeOption.value = 'large';


    qlSize.appendChild(smallOption);
    qlSize.appendChild(normalOption);
    qlSize.appendChild(largeOption);

    subchapterToolbar.appendChild(qlSize);
    subchapterHolder.appendChild(subchapterToolbar);

    var subTitle = document.createElement('input');
    subTitle.type = 'text';
    subTitle.name = 'sub_title[]';
    subTitle.placeholder = 'SubChapter title';
    subTitle.style.marginTop = '2vh';
    subTitle.style.marginLeft = '2vw';

    subTitle.required = true;

    subchapterHolder.appendChild(subTitle);

    var subchapterEditor = document.createElement('input');
    subchapterEditor.classList.add('subchapter_editor');
    subchapterEditor.type = 'hidden';
    subchapterEditor.name = 'sub_body[]';
    subchapterEditor.placeholder = 'desc...';
    subTitle.style.marginTop = '2vh';
    subTitle.style.marginLeft = '2vw';
    subchapterHolder.appendChild(subchapterEditor);

    subchapterEditor.required = true;

    var idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = "sub_id[]";
    idInput.value = '-1';

    subchapterHolder.appendChild(idInput);

    var subchapterEditorHolder = document.createElement('div');
    subchapterEditorHolder.classList.add('subchapter_editor');

    subchapterHolder.appendChild(subchapterEditorHolder);

    var subchaptersHolder = document.getElementById('subchapter_list');
    subchaptersHolder.appendChild(subchapterHolder);

    return subchapterHolder;
}

function addSubChapter() {
    var clone =  createNewSubchapterElement();
    console.log(clone.innerHTML);

    var new_editor_ele = clone.getElementsByClassName("subchapter_editor")[1];
    const editor_input = clone.getElementsByClassName("subchapter_editor")[0];
    var new_toolbar_ele = clone.getElementsByClassName('sub_toolbar')[0];

    createQuillEditor(new_editor_ele, editor_input, new_toolbar_ele);
}
