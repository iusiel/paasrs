<template>
    <form id="cardForm" @submit="submitForm">
        <div class="mb-3">
            <label for="question" class="form-label form-label__required"
                >Question</label
            >
            <textarea
                v-model="formFields.question.value"
                id="question"
                rows="5"
                class="form-control"
                name="question"
            ></textarea>
            <div class="form__error-message">
                {{ formFields.question.errorMessage }}
            </div>
        </div>
        <div class="mb-3">
            <label for="answer" class="form-label form-label__required"
                >Answer</label
            >
            <textarea
                v-model="formFields.answer.value"
                id="answer"
                rows="5"
                class="form-control"
                name="answer"
            ></textarea>
            <div class="form__error-message">
                {{ formFields.answer.errorMessage }}
            </div>
        </div>
        <div class="mb-3">
            <label for="extra-info" class="form-label">Extra Information</label>
            <textarea
                v-model="formFields.extraInformation.value"
                id="extra-info"
                rows="5"
                class="form-control"
                name="extra_information"
            ></textarea>
            <div class="form__error-message">
                {{ formFields.extraInformation.errorMessage }}
            </div>
        </div>
        <div class="mb-3">
            <label for="tags" class="form-label">Tags</label>
            <input
                v-model="formFields.tags.value"
                type="text"
                class="form-control"
                id="tags"
                name="tags"
                placeholder="Tag 1, Tag 2"
            />
            <div class="form-text">
                Comma separated string (e.g. tag 1,tag 2)
            </div>
        </div>
        <div v-if="editmode" class="mb-3">
            <label for="deck" class="form-label form-label__required"
                >Deck</label
            >
            <select v-model="formFields.deckId" class="form-select">
                <option
                    v-for="deck in formFields.decks"
                    v-bind:key="deck.id"
                    v-bind:value="deck.id"
                >
                    {{ deck.name }}
                </option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Submit</button>
        <button
            type="button"
            class="btn btn-primary ms-3"
            @click="goToPreviousPage"
        >
            Cancel
        </button>
    </form>
</template>

<script>
import Swal from "sweetalert2";
import JSONFetchClient from "../modules/JSONFetchClient.js";

export default {
    name: "CardForm",

    props: ["deck", "card", "formAction", "editmode", "decks"],

    data() {
        return {
            formMethod: "POST",
            csrfToken: document.querySelector('meta[name="csrf-token"]')
                .content,
            formFields: {
                decks: [],
                deckId: this.deck,
                question: {
                    value: "",
                    errorMessage: "",
                },
                answer: {
                    value: "",
                    errorMessage: "",
                },
                extraInformation: {
                    value: "",
                    errorMessage: "",
                },
                tags: {
                    value: "",
                    errorMessage: "",
                },
                appearOn: {
                    value: "",
                    errorMessage: "",
                },
            },
        };
    },

    mounted() {
        if (typeof this.card !== "undefined") {
            const card = JSON.parse(atob(this.card));
            this.formFields.question.value = card.question;
            this.formFields.answer.value = card.answer;
            this.formFields.extraInformation.value = card.extra_information;
            this.formFields.tags.value = card.tags;
        }

        if (typeof this.decks !== "undefined") {
            this.formFields.decks = JSON.parse(atob(this.decks));
        }
    },

    methods: {
        submitForm(event) {
            event.preventDefault();
            this.clearErrorMessages();

            const myForm = document.getElementById("cardForm");
            const formData = new FormData(myForm);
            formData.append("deck_id", this.formFields.deckId);
            if (typeof this.editmode !== "undefined") {
                formData.append("_method", "PUT");
            }

            JSONFetchClient(this.formAction, formData, this.formMethod)
                .then((result) => {
                    if (result.message) {
                        Swal.fire(result.message, "", "success").then(
                            (value) => {
                                if (value.isConfirmed) {
                                    window.location.reload();
                                }
                            }
                        );
                    }
                })
                .catch((error) => {
                    error.json().then((result) => {
                        const errors = Object.entries(result.errors);
                        errors.forEach((fieldError) => {
                            const [field, message] = fieldError;
                            const [errorMessage] = message;
                            if (field === "extra_information") {
                                this.formFields.extraInformation.errorMessage =
                                    errorMessage;
                            } else if (this.formFields[field]) {
                                this.formFields[field].errorMessage =
                                    errorMessage;
                            }
                        });
                    });
                });
        },

        goToPreviousPage() {
            window.history.back();
        },

        clearErrorMessages() {
            this.formFields.question.errorMessage = "";
            this.formFields.answer.errorMessage = "";
            this.formFields.extraInformation.errorMessage = "";
            this.formFields.tags.errorMessage = "";
        },
    },
};
</script>
