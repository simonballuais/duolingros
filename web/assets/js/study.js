$(document).ready(function() {
    const ENTER = 13;

    const BG_RED = '#e22d2d';
    const BG_GREEN = '#2de230';
    const FG_RED = '#440d0d';
    const FG_GREEN = '#0d440e';

    const STATE = {
        IDDLE: 'iddle',
        WRITING_PROPOSITION: 'writing proposition',
        READING_REMARKS: 'reading remarks',
        READING_STUDY_CONCLUSION: 'reading study conclusion',
    };

    var state = STATE.IDDLE;

    var $proposition = $('input#proposition');

    var lessonApp = new Vue({
        el: 'div#lesson',
        delimiters: ['${', '}'],
        data: {
            proposition: '',
            lessonTitle: '...',
            progress: 80,
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

    var finishStudy = function(data) {
        state = STATE.READING_STUDY_CONCLUSION;
        lessonApp.conclusionHeader = 'Leçon terminée :)';
        lessonApp.conclusionBody = 'Score de ' + data.successPercentage + '%';
        lessonApp.conclusionFooter = 'Maitrise de cette leçon : ' + data.mastery;
        $('#lesson-conclusion-modal').modal("show");
    };

    var closeStudy = function() {
        state = STATE.IDDLE;
        lessonApp.proposition = '';
        $('#lesson-conclusion-modal').modal("hide");
    };

    var startLesson = function(lessonId) {
        var $proposition = $('input#proposition');

        $.ajax({
            type        : 'GET',
            url         : Routing.generate('api_study_start', {lesson: lessonId}),
            dataType    : 'json',
            success     : function(data) {
                refreshLessonView(data);
                $proposition.focus();
                lessonApp.proposition = '';
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
        $proposition.attr('disabled', true);

        $.ajax({
            type        : 'POST',
            url         : Routing.generate('api_study_proposition_send'),
            data        : { text: $proposition.val() },
            dataType    : 'json',
            success     : function(data) {
                refreshLessonView(data);
                state = STATE.READING_REMARKS;
                $proposition.attr('disabled', false);
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
                nothungHappenedYet = false;
                startLesson(1);
            }
        }

        if (event.which === ENTER) {
            if (state == STATE.WRITING_PROPOSITION) {
                sendProposition();
                return;
            }
            if (state == STATE.READING_REMARKS) {
                startNextExercise();
                return;
            }
            if (state == STATE.READING_STUDY_CONCLUSION) {
                closeStudy();
                return;
            }
        }
    });
});
