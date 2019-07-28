import axios from 'axios';

Array.prototype.sample = function () {
    return this[Math.floor(Math.random() * this.length)];
}

const DEFAULT_COMPLAIN_BUTTON = 'Euuuh bah si lol ...';
const ENTER = 13;

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
            exercise: null,
            goodRun: false,
            remarks: [],
            currentCorrection: null,
            correctionStatus: 'Oki :)',
            conclusionHeader: '',
            conclusionBody: '',
            conclusionFooter: '',
            state: STATE.IDDLE,
            complainSent: false,
            exerciseType: null,
            possiblePropositions: null,
            selectedProposition: null,
            rightAnswer: null,
        },
        methods: {
            getRandomSuccessMessage() {
                return ['Oki :)', 'Yes maggle !', 'Bien ouej kiki', 'C bn sa'].sample()
            },
            getRandomFailureMessage () {
                return ['Tropa :(', 'Nope.', 'Euh ...', 'Tacru'].sample()
            },
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

                if (undefined !== data.exercise) {
                    this.exercise = data.exercise;
                }

                if (undefined !== data.remarks) {
                    this.remarks = data.remarks;
                }

                if (undefined !== data.correctionStatus) {
                    this.correctionStatus = data.correctionStatus;
                }
            },
            sendProposition() {
                if (this.doingTranslation()) {
                    this.sendTranslationProposition();
                }
                if (this.doingQuestion()) {
                    this.sendQuestionAnswer();
                }
            },
            sendTranslationProposition() {
                this.blockPropositionInput = true

                axios.post(
                    Routing.generate('api_study_answer_translation'),
                    {
                        text: this.proposition,
                    }
                )
                .then((response) => {
                    this.currentCorrection = response.data;
                    this.showCorrection();
                })
                .catch((error) => { console.error(error) })
            },
            sendQuestionAnswer() {
                axios.post(
                    Routing.generate('api_study_answer_question'),
                    {
                        answer: this.selectedProposition.id,
                    }
                )
                .then((response) => {
                    this.currentCorrection = response.data;
                    this.rightAnswer = response.data.rightAnswer;
                    this.showCorrection();
                })
                .catch((error) => { console.error(error) })
            },
            showCorrection() {
                this.refreshLessonView(this.currentCorrection);
                this.state = STATE.READING_REMARKS;
                $('#correction-status').fadeIn();

                if (this.currentCorrection.isOk) {
                    this.correctionStatus = this.getRandomSuccessMessage();
                }
                else {
                    this.correctionStatus = this.getRandomFailureMessage()

                    setTimeout(function() {
                        $('#complain-button').fadeIn();
                    }, 150);
                }
            },
            startNextExercise() {
                this.selectedProposition = null;
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
                if (this.doingTranslation()) {
                    this.$nextTick(() => this.$refs.proposition.focus);
                }
            },
            doingTranslation(){
                return this.exercise && this.exercise.type === 'translation';
            },
            doingQuestion(){
                return this.exercise && this.exercise.type === 'question';
            },
            readingRemarks() {
                return this.state === STATE.READING_REMARKS;
            },
            selectProposition(proposition) {
                if (this.selectProposition == proposition) {
                    this.selectedProposition = null;
                    return;
                }

                this.selectedProposition = proposition;
            }
        },
        mounted() {
            axios.get(Routing.generate('api_study_start', {lesson: lessonId}))
            .then((response) => {
                console.log(response.data);
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

