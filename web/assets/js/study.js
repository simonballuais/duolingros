$(document).ready(function() {
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

    var $proposition = $('input#proposition');

    var lessonApp = new Vue({
        el: 'div#lesson',
        delimiters: ['${', '}'],
        data: {
            selectedLessonId: 1,
            proposition: '',
            lessonTitle: '...',
            progress: 0,
            exerciseText: '...',
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
            lessonApp.progress = data.progress;
        }

        if (undefined !== data.lessonTitle) {
            lessonApp.lessonTitle = data.lessonTitle;
        }

        if (undefined !== data.exerciseText) {
            lessonApp.exerciseText = data.exerciseText;
        }

        if (undefined !== data.remarks) {
            lessonApp.remarks = data.remarks;
        }

        if (undefined !== data.correctionStatus) {
            lessonApp.correctionStatus = data.correctionStatus;
        }
    };

    var hidePlayground = function() {
        $('#playground').fadeOut(function() {
            $('#caca').fadeIn();
        });

        $('#lesson-list').animate({
            'width': '25%',
            'padding': '5px',
        });

        $('#dynamic-content').animate({
            'width': '75%',
        });
    };

    var showPlayground = function() {
        var $proposition = $('input#proposition');

        $('#caca').fadeOut(function() {
            $('#playground').fadeIn(function() {
                $proposition.focus();
            })
        });

        $('#lesson-list').animate({
            'width': '0',
            'padding': '0px',
        });

        $('#dynamic-content').animate({
            'width': '100%',
        });
    };

    var showConclusion = function() {
        $('#lesson-conclusion-modal').modal("show");
    };

    var hideConclusion = function() {
        $('#lesson-conclusion-modal').modal("hide");
    };

    var finishStudy = function(data) {
        state = STATE.READING_STUDY_CONCLUSION;
        lessonApp.conclusionHeader = 'Leçon terminée :)';
        lessonApp.conclusionBody = 'Score de ' + data.successPercentage + '%';
        lessonApp.conclusionFooter = 'Maitrise de cette leçon : ' + data.mastery;
        showConclusion();
    };

    var closeStudy = function() {
        state = STATE.IDDLE;
        lessonApp.proposition = '';
        hideConclusion();
        hidePlayground();
    };

    var startLesson = function(lessonId) {
        var $proposition = $('input#proposition');

        showPlayground();

        $.ajax({
            type        : 'GET',
            url         : Routing.generate('api_study_start', {lesson: lessonId}),
            dataType    : 'json',
            success     : function(data) {
                refreshLessonView(data);
                $proposition.focus();
                lessonApp.proposition = '';
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
        var $proposition = $('input#proposition');
        $proposition.attr('readonly', true);

        $.ajax({
            type        : 'POST',
            url         : Routing.generate('api_study_proposition_send'),
            data        : { text: $proposition.val() },
            dataType    : 'json',
            success     : function(data) {
                refreshLessonView(data);
                state = STATE.READING_REMARKS;
                $proposition.focus();
                $('#correction-status').fadeIn();

                if (data.isOk) {
                    lessonApp.correctionStatus = 'Oki :)';
                    lessonApp.remarksBg = BG_GREEN;
                    lessonApp.remarksFg = FG_GREEN;
                }
                else {
                    lessonApp.correctionStatus = 'Tropa :(';
                    lessonApp.remarksBg = BG_RED;
                    lessonApp.remarksFg = FG_RED;
                }
            },
            error       : function(XMLHttpRequest, textStatus, errorThrown) {
                error(XMLHttpRequest, textStatus, errorThrown);
            }
        });
    };

    var startNextExercise = function() {
        var $proposition = $('input#proposition');
        $('#correction-status').fadeOut();

        $.ajax({
            type        : 'GET',
            url         : Routing.generate('api_study_get_new_exercise'),
            dataType    : 'json',
            success     : function(data) {
                if (data.studyOver) {
                    return finishStudy(data);
                }

                $proposition.attr('readonly', false);
                lessonApp.remarks = [];
                lessonApp.proposition = '';
                refreshLessonView(data);
                state = STATE.WRITING_PROPOSITION;
            },
            error       : function(XMLHttpRequest, textStatus, errorThrown) {
                error(XMLHttpRequest, textStatus, errorThrown);
            }
        });
    };

    $('.start-lesson').click(function(e) {
        $this = $(this);
        var lessonId = $this.attr('lesson-id');

        startLesson(lessonId);
    });

    $('body').keypress(function(event) {
        if (event.which === 115) {
            if (state == STATE.IDDLE) {

                return startLesson(lessonApp.selectedLessonId);
            }
        }

        if (event.which === ENTER) {
            if (state == STATE.IDDLE) {
                return startLesson(lessonApp.selectedLessonId);
            }
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

    $('body').keydown(function(event) {
        if (event.which == ARROW.DOWN) {
            lessonApp.selectedLessonId ++;
        }
        if (event.which == ARROW.UP) {
            lessonApp.selectedLessonId --;
        }
    });
});
