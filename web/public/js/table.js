/**
 * @return {void}
 */
function centerTableColumns() {
    var tableLine = document.querySelector("div.table-block > table > tbody > tr");
    var tableColumns = tableLine.querySelectorAll("td");
    var titleLine = document.querySelector("div.title-columns");
    var titleColumns = titleLine.querySelectorAll("span");

    titleLine.style.width = tableLine.offsetWidth + "px";

    var amountTableColumns = tableColumns.length;
    var amountTitleColumns = titleColumns.length;

    if (amountTableColumns === 1) {
        var width = tableLine.offsetWidth / amountTitleColumns;

        for (var i = 0; i < amountTitleColumns; i++) {
            titleColumns[i].style.width = width + "px";
        }
    } else {
        for (var i = 0; i < amountTitleColumns; i++) {
            titleColumns[i].style.width = tableColumns[i].offsetWidth + "px";
        }
    }
}

window.addEventListener("resize", centerTableColumns);

centerTableColumns();
