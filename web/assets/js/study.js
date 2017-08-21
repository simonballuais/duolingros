$(document).ready(function() {
    const ENTER = 13;

    var $proposition = $('input#proposition');

    var lessonApp = new Vue({
        el: 'div#lesson',
        delimiters: ['${', '}'],
        data: {
            lessonTitle: '...',
            progress: 80,
            exerciseText: '...'
        }
    });

    var refreshLessonView = function(data) {
        if (undefined !== data.progress) {
            lessonApp.progress = data.progress;
        }

        if (undefined !== data.lessonTitle) {
            lessonApp.lessonTitle = data.lessonTitle;
        }

        if (undefined !== data.exerciseText) {
            lessonApp.exerciseText = data.exerciseText;
        }
    };

    var startLesson = function(lessonId) {
        $.ajax({
            type        : 'GET',
            url         : Routing.generate('api_study_start', {lesson: lessonId}),
            dataType    : 'json',
            success     : function(data) {
                refreshLessonView(data);
                $proposition.focus();
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
                console.log(data);
                refreshLessonView(data);
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
});
