window.onload = function loadDataTable() {
    if (document.getElementById("examStatisticsTable")) {
        $("#examStatisticsTable").DataTable({
            order: [[0, "desc"]],
        });
    }
};
