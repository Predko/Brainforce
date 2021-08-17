"use strict";
// Access the form element...
var form = document.getElementById("filter-form");
form.addEventListener("blur", function (event) { return LostFocus(event); }, true);
// ...and take over its submit event.
form.addEventListener("submit", function (event) {
    submitForm()
        .then(function (data) { return ChangeTable(data); })
        .catch(function (error) { return console.log(error); });
    event.preventDefault();
});
function submitForm() {
    return new Promise(function (resolve, reject) {
        var xhr = new XMLHttpRequest();
        // Define what happens on successful data submission
        xhr.onload = function () {
            if (xhr.readyState === xhr.DONE) {
                if (xhr.status < 400) {
                    return resolve(xhr.responseText);
                }
                else {
                    return reject(new Error(xhr.statusText));
                }
            }
        };
        // Define what happens in case of error
        xhr.onerror = function () {
            return reject(new Error('Oops! Something went wrong.'));
        };
        // xhr.onprogress = function(event) {
        //   if (event.lengthComputable) {
        //     alert(`Получено ${event.loaded} из ${event.total} байт`);
        //   } else {
        //     alert(`Получено ${event.loaded} байт`); // если в ответе нет заголовка Content-Length
        //   }
        // xhr.onreadystatechange = function() {
        //     if( xhr.readyState==4 && xhr.status==200 ){
        //         console.log( xhr.responseText );
        //     }
        // };
        xhr.open('POST', '/price.php');
        // // Bind the FormData object and the form element
        var FD = new FormData(form);
        FD.append("JSrequest", "yes");
        // Set up our request
        // The data sent is what the user provided in the form
        xhr.send(FD);
    });
}
function ChangeTable(text) {
    if (text.trim() == "NoChanged") {
        // Изменения не требуются.
        return;
    }
    var tableContainer = document.getElementById("table-container");
    if (tableContainer != null) {
        tableContainer.innerHTML = text;
    }
    else {
        console.log("Нет контейнера для таблицы.");
    }
}
function LostFocus(event) {
    var field = event.target;
    if (field.tagName.toUpperCase() === 'INPUT') {
        var input = field;
        // Ограничиваем длину введённой строки 10-ю числами.
        var value = input.value.trim().substr(0, 10);
        if (input.name === 'min' || input.name === 'max' || input.name === 'amount') {
            var min = parseFloat(input.min);
            var max = parseFloat(input.max);
            if (!value || value === null || value.trim().length == 0) {
                switch (input.name) {
                    case 'min':
                        input.value = input.min;
                        break;
                    case 'max':
                        input.value = input.max;
                        break;
                    case 'amount':
                        input.value = "20";
                        break;
                }
            }
            else if (parseFloat(value) < min) {
                input.value = input.min;
            }
            else if (parseFloat(value) > max) {
                input.value = input.max;
            }
        }
    }
}
