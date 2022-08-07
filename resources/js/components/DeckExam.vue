<template>
    <div>
        <section v-if="!isShowingExam && isExamStart">
            <form @submit="startExam" method="GET">
                <div class="mb-3">
                    <label
                        for="question"
                        class="form-label form-label__required"
                        >How many questions for this exam?</label
                    >
                    <input
                        v-model="numberOfExamQuestions"
                        type="text"
                        class="form-control"
                        maxlength="3"
                        inputmode="numeric"
                        placeholder="e.g. 10, 20, 0 for all questions"
                        required
                    />
                    <div class="form__error-message"></div>
                </div>
                <button type="submit" class="btn btn-primary">
                    Start Exam
                </button>

                <a href="/" class="btn btn-primary ms-3">Cancel Exam</a>
            </form>
        </section>
        <section v-if="isShowingExam">
            <div class="fst-italic h6">{{ currentCard.tags }}</div>
            <div v-html="studyQuestion" class="mb-3 fs-3"></div>
            <div
                v-if="isShowingAnswer"
                v-html="studyAnswer"
                class="border-top border-bottom border-dark py-4 fs-4"
            ></div>
            <div
                v-if="isShowingAnswer && currentCard.extra_information"
                class="my-3"
            >
                <div
                    v-html="studyExtraInformation"
                    class="alert alert-info mb-0 fs-6 text-break"
                ></div>
            </div>
            <textarea
                v-model="scratchPaper"
                class="form-control mb-3"
                rows="3"
                placeholder="You can use this as your scratch paper."
            ></textarea>
            <button
                v-if="!isShowingAnswer"
                @click="showAnswer"
                class="btn btn-primary me-3 px-3 fs-4"
            >
                Show answer
            </button>
            <div class="mt-5 study__buttons-container" v-if="isShowingAnswer">
                <button
                    @click="hardAnswer"
                    class="btn btn-primary me-3 mb-3 mb-md-0 px-3 fs-4"
                >
                    Hard
                </button>
                <button
                    @click="goodAnswer"
                    class="btn btn-primary me-3 mb-3 mb-md-0 px-3 fs-4"
                    v-if="!currentCard.retake"
                >
                    Good
                </button>
                <button
                    @click="easyAnswer"
                    class="btn btn-primary me-3 mb-3 mb-md-0 px-3 fs-4"
                    v-if="!currentCard.retake"
                >
                    Easy
                </button>
            </div>
            <div class="mt-3">
                Remaining questions: {{ studyDeck.cards.length }}
            </div>
        </section>
        <section v-if="isShowingResults">
            <h1 class="mb-5">Results page</h1>

            <div>
                <h2>Number of questions</h2>
                <span class="mb-3 d-block">{{ numberOfExamQuestions }}</span>

                <h2>Easy answers</h2>
                <span class="mb-3 d-block">{{ easyAnswers }}</span>

                <h2>Good answers</h2>
                <span class="mb-3 d-block">{{ goodAnswers }}</span>

                <h2>Hard answers</h2>
                <span class="mb-3 d-block">{{ hardAnswers }}</span>

                <h2>Time to finish the exam</h2>
                <span class="mb-3 d-block">{{ timeToFinishTheExam }}</span>
            </div>

            <button
                @click="retakeExam"
                type="button"
                class="btn btn-primary mt-3"
            >
                Retake exam
            </button>
            <a href="/" class="btn btn-primary ms-3 mt-3"
                >Go back to decks dashboard</a
            >
        </section>
    </div>
</template>

<script>
import Swal from "sweetalert2";
// eslint-disable-next-line
import JSONFetchClient from "../modules/JSONFetchClient.js";

const MarkdownIt = require("markdown-it");

import("../../plugins/prism/prism.js");

export default {
    name: "DeckExam",
    props: ["deck", "formAction"],

    data() {
        return {
            studyDeck: JSON.parse(atob(this.deck)),
            formMethod: "POST",
            csrfToken: document.querySelector('meta[name="csrf-token"]')
                .content,
            isShowingAnswer: false,
            scratchPaper: "",
            isShowingExam: false,
            isExamStart: true,
            numberOfExamQuestions: "",
            isShowingResults: false,
            easyAnswers: 0,
            goodAnswers: 0,
            hardAnswers: 0,
            timeToFinishTheExam: "",
        };
    },

    computed: {
        currentCard() {
            const [card] = this.studyDeck.cards;
            return card;
        },

        studyQuestion() {
            return this.convertMarkdownToHTML(this.currentCard.question);
        },

        studyAnswer() {
            return this.convertMarkdownToHTML(this.currentCard.answer);
        },

        studyExtraInformation() {
            return this.convertMarkdownToHTML(
                this.currentCard.extra_information
            );
        },
    },

    watch: {
        isShowingAnswer() {
            setTimeout(() => {
                window.Prism.highlightAll();
            }, 100);
        },
    },

    methods: {
        showAnswer() {
            this.isShowingAnswer = true;
        },

        clearScratchPaper() {
            this.scratchPaper = "";
        },

        easyAnswer() {
            this.easyAnswers += 1;
            this.showNextQuestion();
            this.clearScratchPaper();
        },

        goodAnswer() {
            this.goodAnswers += 1;
            this.showNextQuestion();
            this.clearScratchPaper();
        },

        hardAnswer() {
            this.hardAnswers += 1;
            this.showNextQuestion();
            this.clearScratchPaper();
        },

        showNextQuestion() {
            if (this.studyDeck.cards.length === 1) {
                Swal.fire(
                    "You have finished all the questions for this exam session.",
                    "",
                    "success"
                ).then(() => {
                    this.processExam();
                });

                return;
            }

            this.studyDeck.cards.shift();
            this.isShowingAnswer = false;
        },

        processExam() {
            const formData = new FormData();
            formData.append("deck_name", this.studyDeck.id);
            formData.append("number_of_questions", this.numberOfExamQuestions);
            formData.append("easy_answers", this.easyAnswers);
            formData.append("good_answers", this.goodAnswers);
            formData.append("hard_answers", this.hardAnswers);

            JSONFetchClient(this.formAction, formData, "POST")
                .then((response) => {
                    this.timeToFinishTheExam = response.time_elapsed;
                    this.isShowingResults = true;
                    this.isShowingExam = false;
                })
                .catch((error) => {
                    error.json().then(() => {
                        Swal.fire(
                            "Warning",
                            "An error has been encountered. You can still continue, but your session may not be saved properly.",
                            "warning"
                        ).then(() => {
                            this.isShowingResults = true;
                            this.isShowingExam = false;
                        });
                    });
                });
        },

        convertMarkdownToHTML(markdown) {
            const md = new MarkdownIt({
                html: true,
                linkify: true,
                breaks: true,
            });
            return md.render(markdown);
        },

        startExam() {
            this.isShowingExam = true;
            this.isExamStart = false;
            this.studyDeck.cards = this.studyDeck.cards.slice(
                0,
                this.numberOfExamQuestions
            );
        },

        retakeExam() {
            window.location.reload();
        },
    },
};
</script>
