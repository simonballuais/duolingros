import axios from 'axios';

const DEFAULT_COMPLAIN_BUTTON = 'Euuuh bah si lol ...';
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


export function startLesson(lessonId) {
    var $proposition = $('textarea#proposition');
    window.scrollTo(0, 0);

    playgroundApp = new Vue({
        el: 'div#playground',
        delimiters: ['${', '}'],
        data: {
            proposition: '',
            blockPropositionInput: false,
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
            state: STATE.IDDLE,
            complainSent: false,
        },
        methods: {
            getPropositionInput() {
                return document.getElementById('proposition');
            },
            refreshLessonView(data) {
                if (undefined !== data.progress) {
                    this.progress = data.progress;
                }

                if (undefined !== data.lessonTitle) {
                    this.lessonTitle = data.lessonTitle;
                }

                if (undefined !== data.exerciseText) {
                    this.exerciseText = data.exerciseText;
                }

                if (undefined !== data.remarks) {
                    this.remarks = data.remarks;
                }

                if (undefined !== data.correctionStatus) {
                    this.correctionStatus = data.correctionStatus;
                }
            },
            sendProposition() {
                this.blockPropositionInput = true

                axios.post(
                    Routing.generate('api_study_proposition_send'),
                    { text: this.proposition }
                )
                .then((response) => {
                    this.refreshLessonView(response.data);
                    this.state = STATE.READING_REMARKS;
                    this.focusInput();
                    $('#correction-status').fadeIn();

                    if (response.data.isOk) {
                        this.correctionStatus = 'Oki :)';
                        this.remarksBg = BG_GREEN;
                        this.remarksFg = FG_GREEN;
                    }
                    else {
                        this.correctionStatus = 'Tropa :(';
                        this.remarksBg = BG_RED;
                        this.remarksFg = FG_RED;
                        setTimeout(function() {
                            $('#complain-button').fadeIn();
                        }, 150);
                    }
                })
                .catch((error) => { console.error(error) })
            },
            startNextExercise() {
                $('#correction-status').fadeOut();
                this.hideComplainButton();

                axios.get(Routing.generate('api_study_get_new_exercise'))
                .then((response) => {
                    if (response.data.studyOver) {
                        return this.finishStudy(response.data);
                    }

                    this.blockPropositionInput = false;
                    this.remarks = [];
                    this.proposition = '';
                    this.refreshLessonView(response.data);
                    this.state = STATE.WRITING_PROPOSITION;
                })
                .catch((error) => { console.error(error) })
            },
            closeStudy() {
                this.state = STATE.IDDLE;
                this.proposition = '';
                this.hideConclusion();
                this.hidePlayground();
                this.goBackToLobby();
            },
            finishStudy(data) {
                this.state = STATE.READING_STUDY_CONCLUSION;
                this.conclusionHeader = 'Leçon terminée :)';
                this.conclusionBody = 'Score de ' + data.successPercentage + '%';
                this.conclusionFooter = 'Maitrise de cette leçon : ' + data.mastery;
                this.goodRun = data.goodRun;
                this.showConclusion();
            },
            showConclusion() {
                $('#lesson-conclusion-modal').modal("show");
            },
            hideConclusion() {
                $('#lesson-conclusion-modal').modal("hide");
            },
            complain() {
                if (this.complainSent) {
                    return;
                }

                this.makeComplainButtonThink()
                this.complainSent = true;

                $.ajax({
                    type        : 'PUT',
                    url         : Routing.generate('api_study_complaint'),
                    dataType    : 'json',
                    success     : function(data) {
                        $('#complain-button').html('<i class="fa fa-check-circle fa-fw"></i>' + data.message);
                    },
                    error       : function(XMLHttpRequest, textStatus, errorThrown) {
                        $('#complain-button').html('<i class="fa fa-times-circle fa-fw"></i>');
                        error(XMLHttpRequest, textStatus, errorThrown);
                    }
                });
            },
            hideComplainButton() {
                setTimeout(this.resetComplainButton, 1500)
                $('#complain-button').fadeOut();
                this.complainSent = false;
            },
            makeComplainButtonThink() {
                $('#complain-button').html('<i class="fa fa-cog fa-spin fa-2x fa-fw"></i>')
            },
            resetComplainButton() {
                $('#complain-button').html(DEFAULT_COMPLAIN_BUTTON)
            },
            hidePlayground() {
                $('#playground').fadeOut(function() {
                    $('#caca').fadeIn();
                });
            },
            showPlayground() {
                var $proposition = $('textarea#proposition');

                $('#caca').fadeOut(function() {
                    $('#playground').fadeIn(function() {
                        this.focusInput();
                    })
                });
            },
            goBackToLobby() {
                window.location.replace(Routing.generate('homepage'));
            },
            focusInput() {
                this.$nextTick(() => this.$refs.proposition.focus);
            }
        },
        mounted() {
            axios.get(Routing.generate('api_study_start', {lesson: lessonId}))
            .then((response) => {
                this.refreshLessonView(response.data);
                this.proposition = '';
                this.blockPropositionInput = false;
                this.focusInput();
                this.state = STATE.WRITING_PROPOSITION;
            })
            .catch((error) => { console.error(error) })

            this.showPlayground();
        }
    });

    registerEvents();
};

function registerEvents() {
    $('body').keypress(function(event) {
        if (event.which === ENTER) {
            if (playgroundApp.state == STATE.WRITING_PROPOSITION) {
                return playgroundApp.sendProposition();
            }
            if (playgroundApp.state == STATE.READING_REMARKS) {
                return playgroundApp.startNextExercise();
            }
            if (playgroundApp.state == STATE.READING_STUDY_CONCLUSION) {
                return playgroundApp.closeStudy();
            }
        }
    });
}

