<template>
    <form id="cardForm" @submit="submitForm">
        <div v-if="markedMessage">
            <div class="alert alert-warning" role="alert">
                You marked this card because of the following reason:
                <br /><br />
                <pre class="fs-6">{{ markedMessage }}</pre>
            </div>
            <div class="alert alert-danger" role="alert">
                The marked message will be deleted after saving your changes to
                the card. Make sure that you have applied the changes that you
                want to apply.
            </div>
        </div>
        <div class="form-check mb-3">
            <input
                v-model="formFields.createReverseCard"
                class="form-check-input"
                type="checkbox"
                id="createReverseCard"
                name="create_reverse_card"
            />
            <label class="form-check-label" for="createReverseCard">
                Create Reverse Card
            </label>
        </div>
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
            <select
                multiple
                v-model="formFields.tags.value"
                class="form-select card-tags"
                id="tags"
                name="tags"
                ref="cardtags"
            >
                <option v-for="tag in formFields.tagOptions" v-bind:key="tag">
                    {{ tag }}
                </option>
            </select>
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
        <button type="button" class="btn btn-primary ms-3" @click="previewCard">
            Preview
        </button>
        <button
            type="button"
            class="btn btn-primary ms-3"
            @click="goToPreviousPage"
        >
            Cancel
        </button>
        <div v-if="isShowingPreview" class="card-form__preview-container">
            <div class="container mt-5">
                <card-form-preview
                    v-bind:tags="formFields.tags.value"
                    v-bind:question="formFields.question.value"
                    v-bind:answer="formFields.answer.value"
                    v-bind:extraInformation="formFields.extraInformation.value"
                />

                <button type="submit" class="btn btn-primary">Submit</button>
                <button
                    type="button"
                    class="btn btn-primary ms-3"
                    @click="closePreviewCard"
                >
                    Close Preview
                </button>
                <button
                    type="button"
                    class="btn btn-primary ms-3"
                    @click="goToPreviousPage"
                >
                    Cancel
                </button>
            </div>
        </div>
    </form>
</template>

<script>
import Swal from "sweetalert2";
import JSONFetchClient from "../modules/JSONFetchClient.js";
import CardFormPreview from "./CardFormPreview.vue";
export default {
    name: "CardForm",

    components: {
        CardFormPreview,
    },

    props: ["deck", "card", "formAction", "editmode", "decks", "tags"],

    data() {
        return {
            formMethod: "POST",
            csrfToken: document.querySelector('meta[name="csrf-token"]')
                .content,
            markedMessage: "",
            formFields: {
                decks: [],
                deckId: this.deck,
                createReverseCard: false,
                tagOptions: [],
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
            isShowingPreview: false,
        };
    },

    mounted() {
        if (typeof this.card !== "undefined") {
            const card = JSON.parse(atob(this.card));
            this.formFields.question.value = card.question;
            this.formFields.answer.value = card.answer;
            this.formFields.extraInformation.value = card.extra_information;
            this.formFields.tags.value = card.tags ? card.tags.split(",") : [];
            this.markedMessage = card.marked_message;
        }

        if (typeof this.decks !== "undefined") {
            this.formFields.decks = JSON.parse(atob(this.decks));
        }

        if (typeof this.tags !== "undefined") {
            this.formFields.tagOptions = JSON.parse(atob(this.tags));
        }

        // hack so that MDE initialization is a bit late
        setTimeout(() => {
            const questionMDE = new window.SimpleMDE({
                element: document.getElementById("question"),
            });
            questionMDE.codemirror.on("change", () => {
                this.formFields.question.value = questionMDE.value();
            });

            const answerMDE = new window.SimpleMDE({
                element: document.getElementById("answer"),
            });
            answerMDE.codemirror.on("change", () => {
                this.formFields.answer.value = answerMDE.value();
            });

            const extraInfoMDE = new window.SimpleMDE({
                element: document.getElementById("extra-info"),
            });
            extraInfoMDE.codemirror.on("change", () => {
                this.formFields.extraInformation.value = extraInfoMDE.value();
            });
        }, 100);
    },

    methods: {
        submitForm(event) {
            event.preventDefault();
            this.clearErrorMessages();

            const myForm = document.getElementById("cardForm");
            const formData = new FormData(myForm);
            formData.append("deck_id", this.formFields.deckId);
            formData.set("tags", $(".card-tags").val().join(","));

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

        previewCard() {
            this.isShowingPreview = true;
            document.body.style.overflow = "hidden";
        },

        closePreviewCard() {
            this.isShowingPreview = false;
            document.body.style.overflow = null;
        },
    },
};
</script>
