"use strict";

// Access the form element...
var form = document.getElementById("filter-form"); // ...and take over its submit event.

form.addEventListener("submit", function (event) {
  var xhr = new XMLHttpRequest(); // Set up our request

  xhr.open("POST", "price.php"); // The data sent is what the user provided in the form

  fd = new FormData(form);
  fd.append("aaa", "bbb");
  xhr.send(fd);
  event.preventDefault();
}); // submitForm()
// .then(data => console.log(data))
// .catch(error => console.log(error));

function submitForm() {
  return new Promise(function (resolve, reject) {
    var xhr = new XMLHttpRequest(); // Define what happens on successful data submission

    xhr.onload = function (event) {
      //alert(this.response);
      if (xhr.status < 400) {
        return resolve(xhr.response);
      } else {
        return reject(new Error(xhr.statusText));
      }
    }; // Define what happens in case of error


    xhr.onerror = function (event) {
      return reject(new Error('Oops! Something went wrong.'));
    }; // Bind the FormData object and the form element


    var FD = new FormData(form);
    var object = {};
    FD.forEach(function (value, key) {
      return object[key] = value;
    });
    var json = JSON.stringify(object);
    console.log(json); // Set up our request

    xhr.open("POST", "/price.php", true); //xhr.setRequestHeader("Content-type", "multipart/form-data");
    // The data sent is what the user provided in the form

    xhr.send(FD);
  });
} // XHR.onprogress = function (event)
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