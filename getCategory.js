
function getBookId() {
    const aKeyValue = window.location.search.substring(1).split("&");
    const id = aKeyValue[0].split("=")[1];
    return id;
};

function showSelectedBook(data) {
    const selectedBookId = getBookId();
    let category;

    for (const key in data.products) {
        let bookObj = data.products[key];

        if (bookObj.category == selectedBookId) {
            bookName = bookObj.name;
        }
    }
    document.querySelector("h1").innerHTML = category;
}



fetch("categories.json")
    .then(response => response.json())
    .then(data => showSelectedBook(data));

