<script>
import Swal from "sweetalert2";
import JSONFetchClient from "../modules/JSONFetchClient.js";

export default {
    data() {
        return {
            deckName: "",
            deckNameError: "",
            formMethod: "POST",
            formAction: `${
                document.querySelector('meta[name="base_url"]').content
            }/decks`,
            csrfToken: document.querySelector('meta[name="csrf-token"]')
                .content,
        };
    },

    methods: {
        submitForm(event) {
            event.preventDefault();
            this.deckNameError = "";

            const myForm = document.getElementById("createDeckForm");
            const formData = new FormData(myForm);

            JSONFetchClient(this.formAction, formData, this.formMethod)
                .then((result) => {
                    //eslint-disable-line
                    if (result.message === "Deck created successfully.") {
                        Swal.fire("Deck has been created.", "", "success").then(
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
                        if (result.errors.name[0] !== undefined) {
                            this.deckNameError = result.errors.name[0]; //eslint-disable-line
                        }
                    });
                });
        },
    },
};
</script>

<template>
    <div>
        <form
            @submit="submitForm"
            v-bind:action="formAction"
            v-bind:method="formMethod"
            id="createDeckForm"
        >
            <input name="_token" type="hidden" v-bind:value="csrfToken" />
            <div class="row">
                <div class="col col-12 col-lg-8">
                    <label class="create-deck__label">
                        New Deck Name:
                        <div>
                            <input
                                v-model="deckName"
                                type="text"
                                class="form-control"
                                name="name"
                                placeholder="Deck name"
                            />
                            <div class="form__error-message">
                                {{ deckNameError }}
                            </div>
                        </div>
                    </label>
                </div>

                <div class="col col-12 col-lg-4 mt-3 mt-lg-0">
                    <button type="submit" class="btn btn-primary">
                        Create new deck
                    </button>
                </div>
            </div>
        </form>
    </div>
</template>
