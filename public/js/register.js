import sanitize from "./sanitize.js";

const registerButton = document.getElementById("register-button");
registerButton.addEventListener("click", registerUser);

/**
 * This function takes the values of the registration form, ensuring that none of the form values are empty, and then
 * uses the fetch method to post the data to the backend.
 */
function registerUser() {

    let registrationForm = document.getElementById('registration-form');

    let result = validateUserInput(registrationForm);

    let errorDiv = document.getElementById('js-errors');
    let newLabel = document.createElement('label');
    newLabel.setAttribute('id', 'error-string');

    if(errorDiv.hasChildNodes()){
        errorDiv.removeChild(document.getElementById('error-string'));
    }

    if(result !== false) {

        fetch('http://localhost/api/createnewuser', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: result,
        })
            .then((response) => response.json())
            .then((data) => {
                if(data === "Username already exists in database" || data === "Email address already exists in database"){
                    newLabel.textContent += data.toString();
                    errorDiv.appendChild(newLabel);
                }

                else if(data === "Invalid account credentials" || data === "User account was not created"){
                    newLabel.textContent += data.toString();
                    errorDiv.appendChild(newLabel);
                }

                else {
                    newLabel.textContent += data.toString();
                    errorDiv.appendChild(newLabel);

                    registrationForm.reset();
                }

            })
            .catch((error) => {
                console.error('Error:', error);
            });
    }
}
/**
 * This function validates user input based on the checkNulls for loop, only allowing the user to proceed if
 * none of the values are "true". More validation takes place on the backend.
 *
 * @param registrationForm
 * @returns {object | boolean}
 */
function validateUserInput(registrationForm) {

    let formValues = [registrationForm.elements["username"], registrationForm.elements["email"],
                  registrationForm.elements["first-name"], registrationForm.elements["surname"],
                  registrationForm.elements["psw"], registrationForm.elements["psw-repeat"]];

    let checkNulls = [];
    for (let value of formValues) {
        if(value.value === "" || value.value === " "){
            checkNulls.push(true);
        }
        else {
            checkNulls.push(false);
        }
    }

    if(checkNulls.includes(true)){ //There are values that are not valid
        let errorDiv = document.getElementById('js-errors');
        let newLabel = document.createElement('label');
        newLabel.setAttribute('id', 'error-string');

        if(errorDiv.hasChildNodes()){
            errorDiv.removeChild(document.getElementById('error-string'));
        }

        for(let key in formValues){
            if(checkNulls[key] === true){
                switch(key){
                    case '0':
                        newLabel.textContent += "There is an issue with your username.<br/>";
                        break;
                    case '1':
                        newLabel.textContent += "There is an issue with your email address.<br/>";
                        break;
                    case '2':
                        newLabel.textContent += "There is an issue with your first name.<br/>";
                        break;
                    case '3':
                        newLabel.textContent += "There is an issue with your surname.<br/>";
                        break;
                    case '4':
                        newLabel.textContent += "There is an issue with your password.<br/>";
                        break;
                    case '5':
                        newLabel.textContent += "There is an issue with your repeated password.<br/>";
                        break;
                    default:
                        break;
                }
            }
        }
        newLabel.textContent += "(It's probably empty!)";

        errorDiv.appendChild(newLabel);
        return false;
    }

    else { //Only sanitise values if all values are valid and return formdata array, which will then be posted to backend
        const formData = [];
        for(let key in formValues) {
            let sanitized_value = sanitize(formValues[key].value);
            formData.push(sanitized_value);
        }

        return JSON.stringify(formData);
    }
}

