const DEFAULT_COMPLAINT_BUTTON = 'Euuuh bah si lol ...';
const ENTER = 13;

const BG_RED = '#e22d2d';
const BG_GREEN = '#2de230';
const FG_RED = '#440d0d';
const FG_GREEN = '#0d440e';

const ARROW = {
    UP: 38,
    DOWN: 40,
};

const STATE = {
    IDDLE: 'iddle',
    WRITING_PROPOSITION: 'writing proposition',
    WAITING_CORRECTION: 'waiting for correction',
    READING_REMARKS: 'reading remarks',
    READING_STUDY_CONCLUSION: 'reading study conclusion',
};

var state = STATE.IDDLE;
var complaintSent = false;

var $proposition = $('textarea#proposition');

var playgroundApp = new Vue({
    el: 'div#playground',
    delimiters: ['${', '}'],
    data: {
        proposition: '',
        lessonTitle: '...',
        progress: 0,
        exerciseText: '...',
        goodRun: false,
        remarks: [],
        remarksFg: "red",
        remarksBg: "red",
        correctionStatus: 'Oki :)',
        conclusionHeader: '',
        conclusionBody: '',
        conclusionFooter: '',
    }
});

var refreshLessonView = function(data) {
    console.log(data);
    if (undefined !== data.progress) {
        playgroundApp.progress = data.progress;
    }

    if (undefined !== data.lessonTitle) {
        playgroundApp.lessonTitle = data.lessonTitle;
    }

    if (undefined !== data.exerciseText) {
        playgroundApp.exerciseText = data.exerciseText;
    }

    if (undefined !== data.remarks) {
        playgroundApp.remarks = data.remarks;
    }

    if (undefined !== data.correctionStatus) {
        playgroundApp.correctionStatus = data.correctionStatus;
    }
};

var hidePlayground = function() {
    $('#playground').fadeOut(function() {
        $('#caca').fadeIn();
    });
};

var showPlayground = function() {
    var $proposition = $('textarea#proposition');

    $('#caca').fadeOut(function() {
        $('#playground').fadeIn(function() {
            $proposition.focus();
        })
    });
};

var showConclusion = function(callback) {
    $('#lesson-conclusion-modal').modal("show");
};

var hideConclusion = function() {
    $('#lesson-conclusion-modal').modal("hide");
};

var finishStudy = function(data) {
    state = STATE.READING_STUDY_CONCLUSION;
    playgroundApp.conclusionHeader = 'Leçon terminée :)';
    playgroundApp.conclusionBody = 'Score de ' + data.successPercentage + '%';
    playgroundApp.conclusionFooter = 'Maitrise de cette leçon : ' + data.mastery;
    playgroundApp.goodRun = data.goodRun;
    showConclusion();
};

var closeStudy = function() {
    state = STATE.IDDLE;
    playgroundApp.proposition = '';
    hideConclusion();
    hidePlayground();
    goBackToLobby();
};

startLesson = function(lessonId) {
    var $proposition = $('textarea#proposition');
    window.scrollTo(0, 0);

    showPlayground();

    $.ajax({
        type        : 'GET',
        url         : Routing.generate('api_study_start', {lesson: lessonId}),
        dataType    : 'json',
        success     : function(data) {
            refreshLessonView(data);
            $proposition.focus();
            playgroundApp.proposition = '';
            $proposition.attr('readonly', false);
            lessonStarted = true;
            state = STATE.WRITING_PROPOSITION;
        },
        error       : function(XMLHttpRequest, textStatus, errorThrown) {
            error(XMLHttpRequest, textStatus, errorThrown);
        }
    });
};

var sendProposition = function() {
    var $proposition = $('textarea#proposition');
    $proposition.attr('readonly', true);

    $.ajax({
        type        : 'POST',
        url         : Routing.generate('api_study_proposition_send'),
        data        : { text: $proposition.val() },
        dataType    : 'json',
        success     : function(data) {
            console.log(data);
            refreshLessonView(data);
            state = STATE.READING_REMARKS;
            $proposition.focus();
            $('#correction-status').fadeIn();

            if (data.isOk) {
                playgroundApp.correctionStatus = 'Oki :)';
                playgroundApp.remarksBg = BG_GREEN;
                playgroundApp.remarksFg = FG_GREEN;
            }
            else {
                playgroundApp.correctionStatus = 'Tropa :(';
                playgroundApp.remarksBg = BG_RED;
                playgroundApp.remarksFg = FG_RED;
                setTimeout(function() {
                    $('#complaint-button').fadeIn();
                }, 150);
            }
        },
        error       : function(XMLHttpRequest, textStatus, errorThrown) {
            console.log(XMLHttpRequest);
            error(XMLHttpRequest, textStatus, errorThrown);
        }
    });
};

var startNextExercise = function() {
    var $proposition = $('textarea#proposition');
    $('#correction-status').fadeOut();
    resetComplaintButton();

    $.ajax({
        type        : 'GET',
        url         : Routing.generate('api_study_get_new_exercise'),
        dataType    : 'json',
        success     : function(data) {
            if (data.studyOver) {
                return finishStudy(data);
            }

            $proposition.attr('readonly', false);
            playgroundApp.remarks = [];
            playgroundApp.proposition = '';
            refreshLessonView(data);
            state = STATE.WRITING_PROPOSITION;
        },
        error       : function(XMLHttpRequest, textStatus, errorThrown) {
            error(XMLHttpRequest, textStatus, errorThrown);
        }
    });
};

var complaint = function() {
    if (complaintSent) {
        return;
    }

    $('#complaint-button').html('<i class="fa fa-cog fa-spin fa-2x fa-fw"></i>');
    complaintSent = true;

    $.ajax({
        type        : 'PUT',
        url         : Routing.generate('api_study_complaint'),
        dataType    : 'json',
        success     : function(data) {
            $('#complaint-button').html('<i class="fa fa-check-circle fa-fw"></i>' + data.message);
        },
        error       : function(XMLHttpRequest, textStatus, errorThrown) {
            $('#complaint-button').html('<i class="fa fa-times-circle fa-fw"></i>');
            error(XMLHttpRequest, textStatus, errorThrown);
        }
    });
};

var goBackToLobby = function() {
    window.location.replace(Routing.generate('homepage'));
};

var resetComplaintButton = function() {
    setTimeout(function() {
        $('#complaint-button').html(DEFAULT_COMPLAINT_BUTTON);
    }, 1500);

    $('#complaint-button').fadeOut();
    complaintSent = false;
};

$('#complaint-button').click(complaint);

$('body').keypress(function(event) {
    if (event.which === ENTER) {
        if (state == STATE.WRITING_PROPOSITION) {
            return sendProposition();
        }
        if (state == STATE.READING_REMARKS) {
            return startNextExercise();
        }
        if (state == STATE.READING_STUDY_CONCLUSION) {
            return closeStudy();
        }
    }
});
