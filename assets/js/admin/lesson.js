import axios from 'axios';
let questionList, translationList;

$(document).ready(function(e) {
    questionList = $('ul.question-list');
    addAddButtonToQuestionList();

    questionList.find('ul.proposition-list').each(function() {
        addAddButtonToPropositionList($(this));
    });

    translationList = $('ul.translation-list');
    addAddButtonToTranslationList();

    translationList.find('ul.answer-list').each(function() {
        addAddButtonToAnswerList($(this));
    });
});

function addAddButtonToQuestionList() {
    let newQuestionButton = $('<button>+</button>');
    let newQuestionLi = $('<li></li>').append(newQuestionButton);
    questionList.append(newQuestionLi);
    questionList.data('index', questionList.find('ul.proposition-list').length);

    newQuestionButton.on('click', addQuestionEntry);
}

function addAddButtonToTranslationList() {
    let newTranslationButton = $('<button>+</button>');
    let newTranslationLi = $('<li></li>').append(newTranslationButton);
    translationList.append(newTranslationLi);
    translationList.data('index', translationList.find('ul.proposition-list').length);

    newTranslationButton.on('click', addTranslationEntry);
}

function addQuestionEntry() {
    let newLi = addFormEntry(questionList);
    let newPropositionList = newLi.find('ul.proposition-list');
    addAddButtonToPropositionList(newPropositionList);
}

function addTranslationEntry() {
    let newLi = addFormEntry(translationList);
    let newAnswerList = newLi.find('ul.answer-list');
    addAddButtonToAnswerList(newAnswerList);
}

function addFormEntry(collection, formNamePlaceholder = /__name__/g) {
    let newEntry = collection.data('prototype');
    let index = collection.find('> li').length - 1;
    newEntry = $(newEntry.replace(formNamePlaceholder, index));
    let lastEntry = collection.find('> li').last();
    lastEntry.before(newEntry);

    return newEntry;
}

function addAddButtonToPropositionList(propositionList) {
    let newPropositionButton = $('<button>+</button>');
    let newPropositionLi = $('<li></li>').append(newPropositionButton);

    propositionList.append(newPropositionLi);
    propositionList.data('index', propositionList.find('li').length);

    newPropositionButton.on('click', () => addPropositionEntry(propositionList));
}

function addAddButtonToAnswerList(answerList) {
    let newAnswerButton = $('<button>+</button>');
    let newAnswerLi = $('<li></li>').append(newAnswerButton);

    answerList.append(newAnswerLi);
    answerList.data('index', answerList.find('li').length);

    newAnswerButton.on('click', () => addAnswerEntry(answerList));
}

function addPropositionEntry(collection) {
    addFormEntry(collection, /__proposition_name__/g);
}

function addAnswerEntry(collection) {
    addFormEntry(collection, /__answer_name__/g);
}

$(document).ready(function(e) {
    let removeParentLi = function () { $(this).parent().parent().parent().parent().remove() };

    $(document).on('click', '.remove-question', removeParentLi);
    $(document).on('click', '.remove-proposition', removeParentLi);
    $(document).on('click', '.remove-translation', removeParentLi);
    $(document).on('click', '.remove-answer', removeParentLi);
});

$(document).on('paste', '.proposition-file-input', function(e) {
    e.preventDefault();
    let propositionId = e.target.getAttribute('proposition-id');

    if (!propositionId) {
        return;
    }

    let imageContent = e.originalEvent.clipboardData.getData('text');

    axios.post(
        Routing.generate('api_post_proposition_image', {proposition: propositionId}),
        {
            imageContent: imageContent,
        }
    ).then((response) => {
        $(e.target).parent().parent().parent().find('img').attr('src', response.data);
    });
});

$(document).on('click', '.copy-image', function (e) {
    let el = document.createElement('textarea');
    el.value = $(e.target).parent().parent().parent().find('img').attr('src');
    document.body.appendChild(el);
    el.select();
    document.execCommand('copy');
    document.body.removeChild(el);
});
