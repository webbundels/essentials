
var toolbarOptions = [
    ['bold', 'italic'],
    ['blockquote'],
    [{ 'header': 1 }, { 'header': 2 }],
    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'color': [] }],
    ['clean'],
];


const SAVE_DELAY_MS = 30 * 1000;
const Parchment = Quill.import('parchment');
const customRegistry = new Parchment.Registry();


if (document.getElementById("form") != null) {
    if (document.getElementById("documentation_holder") != null) {
        setInterval(saveDocumentationPage, SAVE_DELAY_MS);
    } else {
        setInterval(saveChangelogPage, SAVE_DELAY_MS);
    }
}
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

    subchapterToolbar.innerHTML = document.getElementById("toolbar").innerHTML;

    subchapterHolder.appendChild(subchapterToolbar);

    var subTitle = document.createElement('input');
    subTitle.type = 'text';
    subTitle.name = 'sub_title[]';
    subTitle.classList.add("subchapter_title_input");
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

function saveChangelogPage() {

    if (document.getElementById("body_input").value === "") {
        console.log("Did not save");
        return;
    }

    localStorage.setItem("title_input", document.getElementById("title_input").value);
    localStorage.setItem("version_input", document.getElementById("version_input").value);
    localStorage.setItem("date_input", document.getElementById("date_input").value);

    localStorage.setItem("body_input", document.getElementById('body_text').querySelector('.ql-editor').innerHTML);


    console.log("Saved Changelogs");
}

function loadChangelogPage() {

    document.getElementById("title_input").value = localStorage.getItem("title_input");
    document.getElementById("version_input").value = localStorage.getItem("version_input");
    document.getElementById("date_input").value = localStorage.getItem("date_input");

    document.getElementById('body_text').querySelector('.ql-editor').innerHTML = localStorage.getItem("body_input");
    document.getElementById("body_input").innerHTML = localStorage.getItem("body_input");
    console.log("Loaded Changelog");
}

function saveDocumentationPage() {

    if (document.getElementById("body_input").value === "") {
        console.log("Did not save");
        return;
    }


    localStorage.setItem("title_input", document.getElementById("title_input").value);
    localStorage.setItem("body_input", document.getElementById('body_text').querySelector('.ql-editor').innerHTML);

    var subchapter_titles = document.getElementsByClassName("subchapter_title_input");

    for (i=0; i<subchapter_titles.length; i++) {
        subchapter_titles[i].defaultValue = subchapter_titles[i].value;
    }

    localStorage.setItem("subchapters", document.getElementById("subchapter_list").innerHTML);

    console.log("Saved Documentation");
}

function loadDocumentationPage() {

    document.getElementById("title_input").value = localStorage.getItem("title_input");

    document.getElementById('body_text').querySelector('.ql-editor').innerHTML = localStorage.getItem("body_input");
    document.getElementById("body_input").innerHTML = localStorage.getItem("body_input");

    document.getElementById("subchapter_list").innerHTML = localStorage.getItem("subchapters");

    var subchapters = document.getElementsByClassName("subchapter_holder");

    for (i=0; i<subchapters.length; i++) {
        var existingSubchapter = subchapters[i];

        var newEditorElement = existingSubchapter.getElementsByClassName("subchapter_editor")[1];
        const editorInput = existingSubchapter.getElementsByClassName("subchapter_editor")[0];
        var newToolbarElement = existingSubchapter.getElementsByClassName('sub_toolbar')[0];


        createQuillEditor(newEditorElement, editorInput, newToolbarElement);

        newEditorElement.querySelector('.ql-editor').innerHTML = editorInput.value;
    }

    console.log("Loaded Documentation");
}


function toggleCommits(index) {

    var commitHolder = document.getElementById("commit-holder-" + index.toString());
    var button = document.getElementById("toggle-button-" + index.toString());


    if (commitHolder.style.display == "none") {
        commitHolder.style.display = "inherit";
        button.innerHTML = "hide";
    } else {
        commitHolder.style.display = "none";
        button.innerHTML = "show";
    }
}
