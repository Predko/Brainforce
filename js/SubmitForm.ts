
// Access the form element...
const form = <HTMLFormElement>document.getElementById("filter-form");

form.addEventListener("blur", (event) => LostFocus(event), true);

// ...and take over its submit event.
form.addEventListener("submit", function (event)
{
    submitForm()
    .then(data => ChangeTable(<string>data))
    .catch(error => console.log(error));
    
    event.preventDefault();
});

function submitForm()
{
    return new Promise((resolve, reject) =>
    {

        const xhr = new XMLHttpRequest();
        
        // Define what happens on successful data submission
        xhr.onload = () =>
        {
            if (xhr.readyState === xhr.DONE) 
            {
                if (xhr.status < 400) 
                {
                    return resolve(xhr.responseText);
                }
                else
                {
                    return reject(new Error(xhr.statusText));
                }
            } 
        }

        // Define what happens in case of error
        xhr.onerror = () =>
        {
            return reject(new Error('Oops! Something went wrong.'));
        }

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
        let FD = new FormData(form);

        FD.append("JSrequest", "yes");
        
        // Set up our request

        // The data sent is what the user provided in the form
        xhr.send(FD);
    });
}

function ChangeTable(text: string)
{
    if (text.trim() == "NoChanged")
    {   
        // Изменения не требуются.
        return;
    }

    let tableContainer = <HTMLTableElement>document.getElementById("table-container");

    if (tableContainer != null)
    {
        tableContainer.innerHTML = text;
    }
    else
    {
        console.log("Нет контейнера для таблицы.");
    }
}

function LostFocus(event:FocusEvent) 
{
    const field = <HTMLElement>event.target;

    if (field.tagName.toUpperCase() === 'INPUT')
    {
        const input = <HTMLInputElement>field;

        // Ограничиваем длину введённой строки 10-ю числами.
        const value = input.value.trim().substr(0, 10);

        if(input.name === 'min' || input.name === 'max' || input.name === 'amount')
        {
            let min = parseFloat(input.min);
            let max = parseFloat(input.max);

            if (!value || value === null || value.trim().length == 0)
            {
                switch (input.name) 
                {
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
            else
            if (parseFloat(value) < min)
            {
                input.value = input.min;
            }
            else
            if (parseFloat(value) > max)
            {
                input.value = input.max;
            }
        }
    }
}
