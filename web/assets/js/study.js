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

    var lessonMenuApp = new Vue({
        el: 'div#lesson-list',
        delimiters: ['${', '}'],
        data: {
            lessons: [],
            selectedLessonId: 1,
        }
    });

    var playgroundApp = new Vue({
        el: 'div#playground',
        delimiters: ['${', '}'],
        data: {
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
        var $proposition = $('input#proposition');

        $('#caca').fadeOut(function() {
            $('#playground').fadeIn(function() {
                $proposition.focus();
            })
        });
    };

    var showConclusion = function(callback) {
        $('#lesson-conclusion-modal').modal("show");

        if (callback) {
            callback();
        }
    };

    var hideConclusion = function() {
        $('#lesson-conclusion-modal').modal("hide");
    };

    var finishStudy = function(data) {
        state = STATE.READING_STUDY_CONCLUSION;
        playgroundApp.conclusionHeader = 'Leçon terminée :)';
        playgroundApp.conclusionBody = 'Score de ' + data.successPercentage + '%';
        playgroundApp.conclusionFooter = 'Maitrise de cette leçon : ' + data.mastery;
        showConclusion(loadLessonMenu);
    };

    var closeStudy = function() {
        state = STATE.IDDLE;
        playgroundApp.proposition = '';
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
                    playgroundApp.correctionStatus = 'Oki :)';
                    playgroundApp.remarksBg = BG_GREEN;
                    playgroundApp.remarksFg = FG_GREEN;
                }
                else {
                    playgroundApp.correctionStatus = 'Tropa :(';
                    playgroundApp.remarksBg = BG_RED;
                    playgroundApp.remarksFg = FG_RED;
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

    var loadLessonMenu = function() {
        $.ajax({
            type        : 'GET',
            url         : Routing.generate('api_study_get_lesson_menu'),
            dataType    : 'json',
            success     : function(data) {
                lessonMenuApp.lessons = data;
                console.log(lessonMenuApp.lessons);
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
                return startLesson(lessonMenuApp.selectedLessonId);
            }
        }

        if (event.which === ENTER) {
            if (state == STATE.IDDLE) {
                return startLesson(lessonMenuApp.selectedLessonId);
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
            lessonMenuApp.selectedLessonId ++;
        }
        if (event.which == ARROW.UP) {
            lessonMenuApp.selectedLessonId --;
        }
    });

    loadLessonMenu();
});
