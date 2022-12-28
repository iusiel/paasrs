<template>
    <div>
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
                @click="againAnswer"
                class="btn btn-primary me-3 mb-3 mb-md-0 px-3 fs-4"
            >
                Again
            </button>
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
            <button
                @click="showMarkModal"
                class="btn btn-primary me-3 mb-3 mb-md-0 px-3 fs-4"
            >
                Mark this card
            </button>
        </div>
        <div class="mt-3">
            Remaining questions: {{ studyDeck.cards.length }}
        </div>

        <div v-if="isShowingMarkModal">
            <!-- Button trigger modal -->
            <button
                ref="showmodalbutton"
                type="button"
                class="d-none"
                data-bs-toggle="modal"
                data-bs-target="#markCardModal"
            >
                Launch modal
            </button>

            <!-- Modal -->
            <div
                class="modal fade"
                id="markCardModal"
                tabindex="-1"
                aria-labelledby="markCardModalLabel"
                aria-hidden="true"
            >
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="markCardModalLabel">
                                Why do you want to mark this card?
                            </h5>
                            <button
                                type="button"
                                class="btn-close"
                                data-bs-dismiss="modal"
                                aria-label="Close"
                                ref="markModalClose"
                            ></button>
                        </div>
                        <div class="modal-body">
                            <form id="markForm" @submit="submitMarkForm">
                                <textarea
                                    class="form-control"
                                    rows="5"
                                    name="marked_message"
                                    v-model="markedMessage"
                                    required
                                ></textarea>

                                <div
                                    class="d-flex align-content-end align-items-end justify-content-end py-3"
                                >
                                    <button
                                        type="button"
                                        class="btn btn-secondary me-3"
                                        data-bs-dismiss="modal"
                                    >
                                        Close
                                    </button>
                                    <button
                                        type="submit"
                                        class="btn btn-primary"
                                    >
                                        Submit
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2";
import JSONFetchClient from "../modules/JSONFetchClient.js";

const MarkdownIt = require("markdown-it");

import("../../plugins/prism/prism.js");

export default {
    name: "DeckStudy",
    props: ["deck"],

    data() {
        const deck = JSON.parse(atob(this.deck));
        if (deck.randomize_order_of_questions) {
            deck.cards.sort(() => Math.random() - 0.5); // randomize cards array if randomize setting is on
        }

        return {
            studyDeck: deck,
            formMethod: "POST",
            csrfToken: document.querySelector('meta[name="csrf-token"]')
                .content,
            isShowingAnswer: false,
            isShowingMarkModal: false,
            markedMessage: "",
            scratchPaper: "",
            easyAnswers: 0,
            goodAnswers: 0,
            hardAnswers: 0,
            totalCards: 0,
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

        easyPercentage() {
            return (this.easyAnswers / this.totalCards).toFixed(2) * 100;
        },

        goodPercentage() {
            return (this.goodAnswers / this.totalCards).toFixed(2) * 100;
        },

        hardPercentage() {
            return (this.hardAnswers / this.totalCards).toFixed(2) * 100;
        },
    },

    watch: {
        isShowingAnswer() {
            setTimeout(() => {
                window.Prism.highlightAll();
            }, 100);
        },
    },

    mounted() {
        const totalCards = this.studyDeck.cards.length;
        this.totalCards = totalCards;
    },

    methods: {
        showAnswer() {
            this.isShowingAnswer = true;
        },

        clearScratchPaper() {
            this.scratchPaper = "";
        },

        againAnswer() {
            const currentCard = this.studyDeck.cards.shift();
            currentCard.retake = true;
            this.studyDeck.cards.push(currentCard);
            this.isShowingAnswer = false;
            this.clearScratchPaper();
        },

        easyAnswer() {
            this.easyAnswers += 1;
            this.submitAnswerInterval("easy");
        },

        goodAnswer() {
            this.goodAnswers += 1;
            this.submitAnswerInterval("good");
        },

        hardAnswer() {
            this.hardAnswers += 1;
            this.submitAnswerInterval("hard");
        },

        submitAnswerInterval(interval) {
            this.clearScratchPaper();

            const formData = new FormData();
            formData.append("interval", interval);

            JSONFetchClient(this.formAction, formData, "POST")
                .then(() => {
                    this.showNextQuestion();
                })
                .catch((error) => {
                    error.json().then(() => {
                        Swal.fire(
                            "Warning",
                            "An error has been encountered. You can still continue, but your session may not be saved properly.",
                            "warning"
                        ).then(() => {
                            this.showNextQuestion();
                        });
                    });
                });
        },

        showNextQuestion() {
            if (this.studyDeck.cards.length === 1) {
                Swal.fire(
                    "You have finished all the questions for this session.",
                    `
                    Results: <br/>
                    Easy: ${this.easyAnswers}/${this.totalCards} (<b>${this.easyPercentage}%</b>) <br/>
                    Good: ${this.goodAnswers}/${this.totalCards} (<b>${this.goodPercentage}%</b>) <br/>
                    Hard: ${this.hardAnswers}/${this.totalCards} (<b>${this.hardPercentage}%</b>)
                    `,
                    "success"
                ).then(() => {
                    window.location.href = `${
                        document.querySelector('meta[name="base_url"]').content
                    }/decks`;
                });

                return;
            }

            this.studyDeck.cards.shift();
            this.isShowingAnswer = false;
        },

        showMarkModal() {
            this.isShowingMarkModal = true;
            setTimeout(() => {
                this.$refs.showmodalbutton.click();
                this.markedMessage = this.currentCard.marked_message;
            }, 100);
        },

        submitMarkForm(event) {
            event.preventDefault();
            const formData = new FormData();
            formData.append("marked_message", this.markedMessage);

            const markMessageURL = `${
                document.querySelector('meta[name="base_url"]').content
            }/cards/${this.currentCard.id}/mark`;
            JSONFetchClient(markMessageURL, formData, "POST")
                .then((response) => {
                    Swal.fire("", response.message, "success").then(() => {
                        this.hideMarkModal();
                        this.currentCard.marked_message = this.markedMessage;
                    });
                })
                .catch((error) => {
                    error.json().then(() => {
                        Swal.fire(
                            "Error",
                            "An error has been encountered. Please try marking this card again.",
                            "error"
                        ).then(() => {
                            this.hideMarkModal();
                        });
                    });
                });
        },

        hideMarkModal() {
            this.$refs.markModalClose.click();
        },

        convertMarkdownToHTML(markdown) {
            const md = new MarkdownIt({
                html: true,
                linkify: true,
                breaks: true,
            });
            return md.render(markdown);
        },
    },
};
</script>
