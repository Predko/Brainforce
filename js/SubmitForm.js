"use strict";
// Access the form element...
var form = document.getElementById("filter-form");
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
// XHR.onprogress = function (event)
// {
//     if (event.lengthComputable)
//     {
//         alert(`Получено ${event.loaded} из ${event.total} байт`);
//     } else
//     {
//         alert(`Получено ${event.loaded} байт`); // если в ответе нет заголовка Content-Length
//     }
// }
//     // 1. Создаём новый XMLHttpRequest-объект
// let xhr = new XMLHttpRequest();
// // 2. Настраиваем его: POST-запрос по URL /article/.../load
// xhr.open('POST', '/customers/edit/submit');
// // 3. Отсылаем запрос
// xhr.send();
// // 4. Этот код сработает после того, как мы получим ответ сервера
// xhr.onload = function() {
//   if (xhr.status != 200) { // анализируем HTTP-статус ответа, если статус не 200, то произошла ошибка
//     alert(`Ошибка ${xhr.status}: ${xhr.statusText}`); // Например, 404: Not Found
//   } else { // если всё прошло гладко, выводим результат
//     alert(`Готово, получили ${xhr.response.length} байт`); // response -- это ответ сервера
//   }
// };
// xhr.onprogress = function(event) {
//   if (event.lengthComputable) {
//     alert(`Получено ${event.loaded} из ${event.total} байт`);
//   } else {
//     alert(`Получено ${event.loaded} байт`); // если в ответе нет заголовка Content-Length
//   }
// };
// xhr.onerror = function() {
//   alert("Запрос не удался");
// };
