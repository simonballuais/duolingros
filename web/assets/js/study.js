$(document).ready(function() {
    const ENTER = 13;

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

        $.ajax({
            type        : 'POST',
            url         : Routing.generate('api_study_proposition_send'),
            data        : { text: $proposition.val() },
            dataType    : 'json',
            success     : function(data) {
                refreshLessonView(data);
                state = STATE.READING_REMARKS;
            },
            error       : function(XMLHttpRequest, textStatus, errorThrown) {
                error(XMLHttpRequest, textStatus, errorThrown);
            }
        });
    };

    var startNextExercise = function() {
        var $proposition = $('input#proposition');

        $.ajax({
            type        : 'GET',
            url         : Routing.generate('api_study_get_new_exercise'),
            dataType    : 'json',
            success     : function(data) {
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

    $('input#proposition').keypress(function(event) {
        if (event.which === ENTER) {
            sendProposition();
        }
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
            }

            if (state == STATE.READING_REMARKS) {
                startNextExercise();
            }
        }
    });
});
