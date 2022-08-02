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
            <h1>Results page</h1>

            <div>
                <h2>Number of questions</h2>
                <span>{{ numberOfExamQuestions }}</span>

                <h2>Easy answers</h2>
                <span>{{ easyAnswers }}</span>

                <h2>Good answers</h2>
                <span>{{ goodAnswers }}</span>

                <h2>Hard answers</h2>
                <span>{{ hardAnswers }}</span>

                <h2>Time to finish the exam</h2>
                <span>{{ timeToFinishTheExam }}</span>
            </div>
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
    props: ["deck"],

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
            timeToFinishTheExam: "asds",
        };
    },

    computed: {
        currentCard() {
            const [card] = this.studyDeck.cards;
            return card;
        },

        formAction() {
            return `${
                document.querySelector('meta[name="base_url"]').content
            }/cards/${this.currentCard.id}/update_appear_on`;
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
            // this.submitAnswerInterval("easy");
        },

        goodAnswer() {
            this.goodAnswers += 1;
            this.showNextQuestion();
            // this.submitAnswerInterval("good");
        },

        hardAnswer() {
            this.hardAnswers += 1;
            this.showNextQuestion();
            // this.submitAnswerInterval("hard");
        },

        submitAnswerInterval(interval) {
            this.clearScratchPaper();

            const formData = new FormData();
            formData.append("interval", interval);

            this.showNextQuestion();

            // JSONFetchClient(this.formAction, formData, "POST")
            //     .then(() => {
            //         this.showNextQuestion();
            //     })
            //     .catch((error) => {
            //         error.json().then(() => {
            //             Swal.fire(
            //                 "Warning",
            //                 "An error has been encountered. You can still continue, but your session may not be saved properly.",
            //                 "warning"
            //             ).then(() => {
            //                 this.showNextQuestion();
            //             });
            //         });
            //     });
        },

        showNextQuestion() {
            if (this.studyDeck.cards.length === 1) {
                Swal.fire(
                    "You have finished all the questions for this session.",
                    "",
                    "success"
                ).then(() => {
                    this.isShowingResults = true;
                    this.isShowingExam = false;
                    // window.location.href = `${
                    //     document.querySelector('meta[name="base_url"]').content
                    // }/decks`;
                });

                return;
            }

            this.studyDeck.cards.shift();
            this.isShowingAnswer = false;
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
    },
};
</script>
