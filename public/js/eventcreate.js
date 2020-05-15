import sanitize from "./sanitize.js";

const createButton = document.getElementById('create-button');

createButton.addEventListener("click", createEvent);

function createEvent() {
    let eventForm = document.getElementById('creation-form');
    let result = validateUserInput(eventForm);

    let errorDiv = document.getElementById('js-errors');
    let newLabel = document.createElement('label');
    newLabel.setAttribute('id', 'error-string');

    if(errorDiv.hasChildNodes()){
        errorDiv.removeChild(document.getElementById('error-string'));
    }

    if(result !== false) {
        fetch('http://localhost/api/createnewevent', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: result,
        })
            .then((response) => response.text())
            .then((data) => {
                if(data === "true") {
                    newLabel.textContent = "Your event has been created successfully!";

                    eventForm.reset();
                }

                else {
                    newLabel.textContent = "There was an error with the creation of your event. Please check that your details are correct!";
                }

                errorDiv.appendChild(newLabel);

            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
    else {
        newLabel.textContent = "An input value appears to be empty.";
        errorDiv.appendChild(newLabel);
    }
}

function validateUserInput(userInput) {
    let formValues = [userInput.elements["event-title"].value, userInput.elements["event-description"].value, userInput.elements["types"].value,
                      userInput.elements["event-time-start"].value, userInput.elements["event-time-end"].value];

    let checkNulls = [];
    for (let value of formValues) {
        if (value === "" || value === " ") {
            checkNulls.push(true);
        } else {
            checkNulls.push(false);
        }
    }

    if(checkNulls.includes(true)){
        return false;
    }

    else { //Only sanitise values if all values are valid and return formdata array, which will then be posted to backend
        const formData = [];

        for(let key in formValues) {
            let sanitized_value = sanitize(formValues[key]);
            formData.push(sanitized_value);
        }

        return JSON.stringify(formData);
    }
}