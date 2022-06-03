<template>
    <div>
        <div class="fst-italic h6">{{ currentCard.tags }}</div>
        <div class="mb-3">
            <pre class="mb-0 white-space__pre-wrap fs-3">{{
                currentCard.question
            }}</pre>
        </div>
        <div
            v-if="isShowingAnswer"
            class="border-top border-bottom border-dark py-4"
        >
            <pre class="mb-0 white-space__pre-wrap fs-4">{{
                currentCard.answer
            }}</pre>
        </div>
        <div
            v-if="isShowingAnswer && currentCard.extra_information"
            class="my-3"
        >
            <div class="alert alert-info mb-0">
                <pre class="mb-0 white-space__pre-wrap fs-6">{{
                    currentCard.extra_information
                }}</pre>
            </div>
        </div>
        <button
            v-if="!isShowingAnswer"
            @click="showAnswer"
            class="btn btn-primary me-3 px-3 fs-4"
        >
            Show answer
        </button>
        <div class="mt-5" v-if="isShowingAnswer">
            <button @click="againAnswer" class="btn btn-primary me-3 px-3 fs-4">
                Again
            </button>
            <button @click="hardAnswer" class="btn btn-primary me-3 px-3 fs-4">
                Hard
            </button>
            <button @click="goodAnswer" class="btn btn-primary me-3 px-3 fs-4">
                Good
            </button>
            <button @click="easyAnswer" class="btn btn-primary me-3 px-3 fs-4">
                Easy
            </button>
        </div>
        <div class="mt-3">
            Remaining questions: {{ studyDeck.cards.length }}
        </div>
    </div>
</template>

<script>
import Swal from "sweetalert2";
import JSONFetchClient from "../modules/JSONFetchClient.js";

import("../../plugins/prism/prism.js");

export default {
    name: "DeckStudy",
    props: ["deck"],

    data() {
        return {
            studyDeck: JSON.parse(atob(this.deck)),
            formMethod: "POST",
            csrfToken: document.querySelector('meta[name="csrf-token"]')
                .content,
            isShowingAnswer: false,
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

        againAnswer() {
            const currentCard = this.studyDeck.cards.shift();
            this.studyDeck.cards.push(currentCard);
            this.isShowingAnswer = false;
        },

        easyAnswer() {
            this.submitAnswerInterval("easy");
        },

        goodAnswer() {
            this.submitAnswerInterval("good");
        },

        hardAnswer() {
            this.submitAnswerInterval("hard");
        },

        submitAnswerInterval(interval) {
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
                    "",
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
    },
};
</script>
