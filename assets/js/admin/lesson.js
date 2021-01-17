import axios from 'axios';
let questions, translations;

$(document).ready(function(e) {
    questions = $('ul.question-list');
    addAddButtonToQuestionList();

    questions.find('ul.proposition-list').each(function() {
        addAddButtonToPropositions($(this));
    });

    translations = $('ul.translation-list');
    addAddButtonToTranslationList();

    translations.find('ul.answer-list').each(function() {
        addAddButtonToAnswers($(this));
    });
});

function addAddButtonToQuestionList() {
    let newQuestionButton = $('<button>+</button>');
    let newQuestionLi = $('<li></li>').append(newQuestionButton);
    questions.append(newQuestionLi);
    questions.data('index', questions.find('ul.proposition-list').length);

    newQuestionButton.on('click', addQuestionEntry);
}

function addAddButtonToTranslationList() {
    let newTranslationButton = $('<button>+</button>');
    let newTranslationLi = $('<li></li>').append(newTranslationButton);
    translations.append(newTranslationLi);
    translations.data('index', translations.find('ul.proposition-list').length);

    newTranslationButton.on('click', addTranslationEntry);
}

function addQuestionEntry() {
    let newLi = addFormEntry(questions);
    let newPropositions = newLi.find('ul.proposition-list');
    addAddButtonToPropositions(newPropositions);
}

function addTranslationEntry() {
    let newLi = addFormEntry(translations);
    let newAnswers = newLi.find('ul.answer-list');
    addAddButtonToAnswers(newAnswers);
}

function addFormEntry(collection, formNamePlaceholder = /__name__/g) {
    let newEntry = collection.data('prototype');
    let index = collection.find('> li').length - 1;
    newEntry = $(newEntry.replace(formNamePlaceholder, index));
    let lastEntry = collection.find('> li').last();
    lastEntry.before(newEntry);

    return newEntry;
}

function addAddButtonToPropositions(propositions) {
    let newPropositionButton = $('<button>+</button>');
    let newPropositionLi = $('<li></li>').append(newPropositionButton);

    propositions.append(newPropositionLi);
    propositions.data('index', propositions.find('li').length);

    newPropositionButton.on('click', () => addPropositionEntry(propositions));
}

function addAddButtonToAnswers(answerm) {
    let newAnswerButton = $('<button>+</button>');
    let newAnswerLi = $('<li></li>').append(newAnswerButton);

    answers.append(newAnswerLi);
    answers.data('index', answers.find('li').length);

    newAnswerButton.on('click', () => addAnswerEntry(answers));
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
