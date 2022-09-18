import { createApp } from "vue";
import ExamStatisticsRelatedButton from "../components/ExamStatisticsRelatedButton.vue";

createApp({
    data() {
        return {};
    },
    components: {
        ExamStatisticsRelatedButton,
    },
}).mount("#app");

window.onload = function loadDataTable() {
    if (document.getElementById("examStatisticsTable")) {
        $("#examStatisticsTable").DataTable({
            order: [[0, "desc"]],
        });
    }
};
