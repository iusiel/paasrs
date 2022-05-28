<script>
import Swal from "sweetalert2";
import JSONFetchClient from "../modules/JSONFetchClient.js";

export default {
    name: "ImportCards",

    props: ["formAction"],

    data() {
        return {
            formMethod: "POST",
        };
    },

    methods: {
        openFileBrowser() {
            this.$refs.file.click();
        },

        processFileUpload(event) {
            const [filetoUpload] = event.target.files;
            const formData = new FormData();
            formData.append("csv", filetoUpload);

            JSONFetchClient(this.formAction, formData, this.formMethod)
                .then((result) => {
                    Swal.fire(result.message, "", "success").then(() => {
                        window.location.reload();
                    });
                })
                .catch(() => {
                    Swal.fire(
                        "Error",
                        "Something went wrong with the import. Please make sure that you csv file has exactly four columns.",
                        "error"
                    );
                });
        },
    },
};
</script>

<template>
    <div class="d-inline me-4">
        <button @click="openFileBrowser" class="btn btn-primary">
            Import Cards
        </button>
        <input
            @change="processFileUpload"
            ref="file"
            type="file"
            class="d-none"
            accept=".csv"
        />
    </div>
</template>
