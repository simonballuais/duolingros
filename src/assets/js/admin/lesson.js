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
    let newQuestionButton = $('<button>+<button>');
    let newQuestionLi = $('<li></li>').append(newQuestionButton);
    questionList.append(newQuestionLi);
    questionList.data('index', questionList.find('ul.proposition-list').length);

    newQuestionButton.on('click', addQuestionEntry);
}

function addAddButtonToTranslationList() {
    let newTranslationButton = $('<button>+<button>');
    let newTranslationLi = $('<li></li>').append(newTranslationButton);
    translationList.append(newTranslationLi);
    translationList.data('index', translationList.find('ul.proposition-list').length);

    newTranslationButton.on('click', addTranslationEntry);
}

function addQuestionEntry() {
    let newLi = addFormEntry(questionList);
    let newPropositionList = newLi.find('ul.proposition-list');
    console.log(newLi);
    console.log(newPropositionList);
    addAddButtonToPropositionList(newPropositionList);
}

function addTranslationEntry() {
    let newLi = addFormEntry(translationList);
    let newPropositionList = newLi.find('ul.proposition-list');
    console.log(newLi);
    console.log(newPropositionList);
    addAddButtonToPropositionList(newPropositionList);
}

function addFormEntry(collection) {
    let prototype = collection.data('prototype');
    let index = collection.data('index');
    let newEntry = prototype;
    newEntry = newEntry.replace(/__name__/g, index);
    collection.data('index', index + 1);
    let newLi = $('<li></li>').append(newEntry);
    let lastEntry = collection.find('> li').last();
    lastEntry.before(newLi);

    return newLi;
}

function addAddButtonToPropositionList(propositionList) {
    let newPropositionButton = $('<button>+<button>');
    let newPropositionLi = $('<li></li>').append(newPropositionButton);

    propositionList.append(newPropositionLi);
    propositionList.data('index', propositionList.find('li').length);

    newPropositionButton.on('click', () => addPropositionEntry(propositionList));
}

function addAddButtonToAnswerList(answerList) {
    let newAnswerButton = $('<button>+<button>');
    let newAnswerLi = $('<li></li>').append(newAnswerButton);

    answerList.append(newAnswerLi);
    answerList.data('index', answerList.find('li').length);

    newAnswerButton.on('click', () => addAnswerEntry(answerList));
}

function addPropositionEntry(collection) {
    addFormEntry(collection);
}

function addAnswerEntry(collection) {
    addFormEntry(collection);
}

$(document).ready(function(e) {
    $('.remove-question').on('click', function (e) {
        $(this).parent().parent().parent().parent().remove();
    });

    $('.remove-proposition').on('click', function (e) {
        $(this).parent().parent().parent().parent().remove();
    });

    $('.remove-translation').on('click', function (e) {
        $(this).parent().parent().parent().parent().remove();
    });

    $('.remove-answer').on('click', function (e) {
        $(this).parent().parent().parent().parent().remove();
    });
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
