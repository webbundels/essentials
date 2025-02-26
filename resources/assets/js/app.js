const Parchment = Quill.import('parchment');
const customRegistry = new Parchment.Registry();

const toolbarOptions = [
    ['bold', 'italic', 'underline', 'strike'],
    ['link'],

    [{ 'list': 'ordered'}, { 'list': 'bullet' }],
    [{ 'indent': '-1'}, { 'indent': '+1' }],          // outdent/indent
    [{ 'size': ['small', false, 'large', 'huge'] }],

    [{ 'color': [] }],

    ['clean']                                         // remove formatting button
  ];


const SAVE_DELAY_MS = 30 * 1000;

// When we load we check if the page is a form.
// This means we are in a edit or create page, so we should enable auto-saving.
if (document.getElementById("form") != null) {
    if (document.getElementById("documentation_holder") != null) {
        setInterval(saveDocumentationPage, SAVE_DELAY_MS);
    }// else {
       // setInterval(saveChangelogPage, SAVE_DELAY_MS);
    //}

    if (localStorage.getItem("title_input") == "") {
        document.getElementById("recover_session_button").style.display = "none";

    }

}
// When we are in the create/edit page we need to setup the main quill editor
if (document.getElementById('edit') !== null) {

    // ele = element
    const editorEle = document.getElementById('body_text');

    const editor = new Quill(editorEle, {
        customRegistry,
        modules: {
            toolbar: toolbarOptions,
        },
        placeholder: '',
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

if (new URLSearchParams(window.location.search).get('enable_moving') == 1) {
    toggleSequence();
}

// For the edit page we automatically load the subchapters with their bodies.
// But the Quilljs editor arent setup for them yet, so we need to do it now.
var existingSubchapters = document.getElementsByClassName("subchapter-holder");
for (let i=0; i < existingSubchapters.length; i++) {

    var existingSubchapter = existingSubchapters.item(i);

    var newEditorElement = existingSubchapter.getElementsByClassName("subchapter_editor")[1];
    const editorInput = existingSubchapter.getElementsByClassName("subchapter_editor")[0];
    var newToolbarElement = existingSubchapter.getElementsByClassName('sub_toolbar')[0];


    createQuillEditor(newEditorElement, editorInput, newToolbarElement);

    //Update the editors value to the input.
    newEditorElement.querySelector('.ql-editor').innerHTML = editorInput.value;

}

function submitForm() {
    localStorage.clear();


}

function toggleSequence() {
    var form = document.getElementById("change-sequence");

    if (form.innerHTML == "Wijzig Volgorde") {
        var forms = document.getElementsByClassName("move-form")
        for (let i=0; i<forms.length; i++) {
            forms[i].style.display = "inherit";
        }

        document.getElementById("change-sequence").innerHTML = "Klaar";
    } else {


        var forms = document.getElementsByClassName("move-form");
        for (let i=0; i<forms.length; i++) {
            forms[i].style.display = "none";
        }

        document.getElementById("change-sequence").innerHTML = "Wijzig Volgorde";

    }
}

// This is just an Quality of life function to create a new Editor.
function createQuillEditor(editorElement, editorInput, toolbarElement) {

    var newEditor = new Quill(editorElement, {
        customRegistry,
        modules: {
            toolbar: toolbarOptions,
        },
        theme: 'bubble',
        scrollingContainer: document.documentElement
    });

    newEditor.on('text-change', function(delta, oldDelta, source){
        editorInput.value = editorElement.querySelector('.ql-editor').innerHTML;
    });

    newEditor.enable(true);
}

var subchapter_temp_id = 1;

// This manually creates all the html elements with their styles for a subchapter
// It returns the Subchapter Holder DIV
function createNewSubchapterElement() {
    var subchapterHolder = document.createElement('div');
    subchapterHolder.classList.add('subchapter_holder');
    subchapterHolder.id = "subchapter_temp_" + subchapter_temp_id;
    subchapter_temp_id++;

    var subTitle = document.createElement('input');
    subTitle.type = 'text';
    subTitle.name = 'sub_title[]';
    subTitle.placeholder = "Titel";
    subTitle.classList.add("subchapter-title-input");

    subTitle.required = true;

    var labelTitle = document.createElement('label');
    labelTitle.for = "sub_title[]";
    labelTitle.classList.add('styled-label');

    var labelSpan = document.createElement("span");
    labelSpan.innerHTML = "Titel";
    labelTitle.appendChild(labelSpan);

    labelTitle.appendChild(subTitle);
    subchapterHolder.append(labelTitle);

    var subchapterEditor = document.createElement('input');
    subchapterEditor.classList.add('subchapter-editor');
    subchapterEditor.type = 'hidden';
    subchapterEditor.name = 'sub_body[]';

    labelTitle = document.createElement('label');
    labelTitle.for = "sub_body[]";

    labelSpan = document.createElement("span");
    labelSpan.innerHTML = "Inhoud";
    labelTitle.appendChild(labelSpan);

    labelTitle.appendChild(subchapterEditor);
    subchapterHolder.append(labelTitle);

    subchapterEditor.required = true;

    var idInput = document.createElement('input');
    idInput.type = 'hidden';
    idInput.name = "sub_id[]";
    idInput.value = '-1';

    subchapterHolder.appendChild(idInput);

    var subchapterEditorHolder = document.createElement('div');
    subchapterEditorHolder.classList.add('subchapter-editor');

    subchapterHolder.appendChild(subchapterEditorHolder);

    var deleteButton = document.createElement("div");
    deleteButton.classList.add("styled-button");
    deleteButton.classList.add("delete-subchapter-button");
    deleteButton.type = "button";
    deleteButton.innerHTML = "Verwijderen";
    deleteButton.onclick = function() {
        if (confirm("Weet je zeker dat je dit subhoofdstuk wil verwijderen?") == true) {

            subchapterHolder.style.display = "none";
            idInput.value = "-2";
            subTitle.required = false;
            subchapterEditor.required = false;
        }
    };

    subchapterHolder.appendChild(deleteButton);

    var subchaptersHolder = document.getElementById('subchapter_list');
    subchaptersHolder.appendChild(subchapterHolder);

    return subchapterHolder;
}

// Calls the createNewSubchapter and createQuillEditor functions
// And adds the subchapter to the html page.
function addSubChapter() {
    var clone =  createNewSubchapterElement();
    console.log(clone.innerHTML);

    var new_editor_ele = clone.getElementsByClassName("subchapter-editor")[1];
    const editor_input = clone.getElementsByClassName("subchapter-editor")[0];
    var new_toolbar_ele = clone.getElementsByClassName('sub_toolbar')[0];

    createQuillEditor(new_editor_ele, editor_input, new_toolbar_ele);
}

//Saves all the info of the current create/edit page with the use of 'localstorage.set'
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

//Loads the info of the items stored about the changelog page in the localstorage
function loadChangelogPage() {



    if (localStorage.getItem("title_input") == "") {
        console.log("Did not load");
        return;
    }

    document.getElementById("title_input").value = localStorage.getItem("title_input");
    document.getElementById("version_input").value = localStorage.getItem("version_input");
    document.getElementById("date_input").value = localStorage.getItem("date_input");

    document.getElementById('body_text').querySelector('.ql-editor').innerHTML = localStorage.getItem("body_input");
    document.getElementById("body_input").innerHTML = localStorage.getItem("body_input");
    console.log("Loaded Changelog");
}

//Saves all the info of the current create/edit page with the use of 'localstorage.set'
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

//Loads the info of the items stored about the changelog page in the localstorage
function loadDocumentationPage() {


    if (localStorage.getItem("title_input") == "") {
        console.log("Did not load");
        alert("Je vorige sessie was leeg, dus het kan niet geladen worden.");
        return;
    }

    if (confirm(" Weet je zeker dat je het laatste concept wilt laden?") == false) {
        console.log("Did not load");
        return;
    }


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
function formatDate(date) {

    var out_string = "0"
    out_string += date.getUTCDate();
    out_string += "-";

    var month = date.getUTCMonth();

    if (month <= 9) {
        out_string += "0";
    }

    out_string += month + 1;
    out_string += "-";
    out_string += date.getUTCFullYear();

    out_string += " ";
    let hour = date.getUTCHours();
    if (hour <= 9) {
        out_string += "0";
    }
    out_string += hour;
    out_string += ":";


    let min = date.getUTCMinutes();
    if (min <= 9) {
        out_string += "0";
    }
    out_string += min;
    
    return out_string;
}



// Creates a new commit element
// Then adds it to the commit-holder on the html page
function createNewCommitElement(index, sub_index, commiter, message, date, commit_url) {
    var commit_ele = document.createElement("div");
    commit_ele.classList.add("commit");
    commit_ele.style.display = "inherit";


    var title = document.createElement("div");
    title.classList.add("commit-title");
    title.innerHTML = commiter;

    commit_ele.appendChild(title);

    var commit_message = document.createElement("div");
    commit_message.classList.add("commit-message");
    commit_message.innerHTML = message;

    commit_ele.appendChild(commit_message);

    var commit_date = document.createElement("a");
    commit_date.classList.add("date-title");
    commit_date.href = commit_url;
    commit_date.target = '_blank';
    commit_date.innerHTML = formatDate(new Date(date));

    commit_ele.appendChild(commit_date);

    var commit_holder = document.getElementById("commit-holder-" + index + "-" + sub_index);
    commit_holder.appendChild(commit_ele);
}

// iterates through an array of commits
// Then uses createNewCommitElement to add them to the html page
function parseCommits(index, sub_index, commits) {

    for (let i=0; i<commits.length; i++) {
        var commiter = commits[i].commiter;
        var message = commits[i].message;
        var date = commits[i].created_at;
        var url = commits[i].url;

        createNewCommitElement(index, sub_index, commiter, message, date, url);
    }
}

// Hides or shows the commits
// If the commits arent yet loaded it will send an XMLHtppRequest to the server to get the commits.
function toggleCommits(index, sub_index, changelog_id, previous_id, repo_name) {

    var commitHolder = document.getElementById("commit-holder-" + index.toString() + "-" + sub_index.toString());
    var button = document.getElementById("toggle-button-" + index.toString() + "-" + sub_index.toString());

    if (commitHolder.getElementsByClassName("commit").length == 0) {

        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                parseCommits(index, sub_index, JSON.parse(this.response).commits);
            }
        };
        xhttp.open("GET", "/commit?index=" + index + "&changelog_id=" + changelog_id + "&previous_id=" + previous_id + "&repo=" +  repo_name, true);
        xhttp.send();
    }

    if (commitHolder.style.display == "none") {
        commitHolder.style.display = "inherit";
    } else {
        commitHolder.style.display = "none";
    }
}
