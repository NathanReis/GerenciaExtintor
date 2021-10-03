/**
 * @param {Date} dateTime
 *
 * @return {Date}
 */
function addTimeZoneFromDateObject(dateTime) {
    dateTime.setHours(dateTime.getHours() - 3);

    return dateTime;
}

/**
 * @param {string} dateTime
 *
 * @return {Date}
 */
function addTimeZoneFromString(dateTime) {
    return addTimeZoneFromDateObject(new Date(dateTime));
}

/**
 * @param {Date} dateTime
 *
 * @return {string}
 */
function formatDateToShowFromDateObject(dateTime) {
    var year = dateTime.getFullYear().toString();
    var month = (dateTime.getMonth() + 1).toString();
    month = month.padStart(2, "0");
    var date = dateTime.getDate().toString();
    date = date.padStart(2, "0");

    return date + "/" + month + "/" + year;
}

/**
 * @param {string} date
 *
 * @return {string}
 */
function formatDateToShowFromString(date) {
    return date.replace(/(\d{4})-(\d{2})-(\d{2}).*/, "$3/$2/$1");
}
