let questionList;

$(document).ready(function(e) {
    questionList = $('ul.question-list');
    addAddButtonToQuestionList();

    questionList.find('ul.proposition-list').each(function() {
        addAddButtonToPropositionList($(this));
    });
});

function addAddButtonToQuestionList() {
    let newQuestionButton = $('<button>+<button>');
    let newQuestionLi = $('<li></li>').append(newQuestionButton);
    questionList.append(newQuestionLi);
    questionList.data('index', questionList.find('ul.proposition-list').length);

    newQuestionButton.on('click', addQuestionEntry);
}

function addQuestionEntry() {
    let newLi = addFormEntry(questionList);
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

function addPropositionEntry(collection) {
    addFormEntry(collection);
}

$(document).ready(function(e) {
    $('.remove-question').on('click', function (e) {
        $(this).parent().parent().parent().parent().remove();
    });

    $('.remove-proposition').on('click', function (e) {
        $(this).parent().parent().parent().parent().remove();
    });
});
