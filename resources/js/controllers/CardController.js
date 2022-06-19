import { createApp } from "vue";
import Swal from "sweetalert2";
import CardForm from "../components/CardForm.vue";
import JSONFetchClient from "../modules/JSONFetchClient.js";

createApp({
    data() {
        return {
            card: typeof window.card !== "undefined" ? window.card : "",
            decks: typeof window.decks !== "undefined" ? window.decks : "",
            tags: typeof window.tags !== "undefined" ? window.tags : "",
        };
    },
    components: {
        CardForm,
    },
}).mount("#app");

window.onload = function loadDataTable() {
    if (document.getElementById("cardsTable")) {
        $("#cardsTable").DataTable({
            order: [
                [5, "desc"],
                [0, "desc"],
            ],
        });
    }
};

function submitForm(href) {
    const formData = new FormData();
    formData.append("_method", "DELETE");

    JSONFetchClient(href, formData, "POST")
        .then((result) => {
            //eslint-disable-line
            Swal.fire("Deleted!", result.message, "success").then(() => {
                window.location.reload();
            });
        })
        .catch((error) => {
            error.json().then(() => {
                Swal.fire(
                    "Warning",
                    "An error has been encountered. Try deleting the card again.",
                    "warning"
                ).then(() => {
                    window.location.reload();
                });
            });
        });
}

const deleteButtons = document.querySelectorAll(".card__delete");
if (deleteButtons.length > 0) {
    deleteButtons.forEach((button) => {
        button.addEventListener("click", function promptDelete(event) {
            event.preventDefault();
            Swal.fire({
                title: "Are you sure you want to delete this card?",
                text: "You won't be able to revert this!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!",
            }).then((result) => {
                if (result.isConfirmed) {
                    submitForm(this.href);
                }
            });
        });
    });
}

$(document).ready(() => {
    $(".card-tags").select2({
        placeholder: "Select an option",
    });
});
