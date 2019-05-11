let questionList;

$(document).ready(function(e) {
    let newQuestionButton = $('<button>+<button>');
    let newQuestionLi = $('<li></li>').append(newQuestionButton);
    questionList = $('ul.question-list');
    questionList.append(newQuestionLi);
    questionList.data('index', questionList.find('ul.proposition-list').length);

    newQuestionButton.on('click', function(e) {
        addFormEntry(questionList, newQuestionLi);
    });

    questionList.find('ul.proposition-list').each(function(questionContainer) {
        let newPropositionButton = $('<button>+<button>');
        let newPropositionLi = $('<li></li>').append(newPropositionButton);
        let propositionList = $(this);
        propositionList.append(newPropositionLi);
        propositionList.data('index', propositionList.find('li').length);

        newPropositionButton.on('click', function(e) {
            addFormEntry(propositionList, newPropositionLi);
        });
    });
});

function addFormEntry(collection, entry) {
    let prototype = collection.data('prototype');
    let index = collection.data('index');
    let newEntry = prototype;
    newEntry = newEntry.replace(/__name__/g, index);
    collection.data('index', index + 1);
    let newLi = $('<li></li>').append(newEntry);
    entry.before(newLi);
}
