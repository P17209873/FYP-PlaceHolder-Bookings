import sanitize from "./sanitize.js";

const loginButton = document.getElementById('login-button');

const checkboxShowHidePasswd = document.getElementById("chk-showhide-passwd");
checkboxShowHidePasswd.addEventListener("click", function () {
    let x = document.getElementById("password");
    if(x.type==="password")
    {
        x.type = "text";
    }

    else
    {
        x.type = "password";
    }
});

loginButton.addEventListener("click", loginUser);

/**
 *
 */
function loginUser() {
    let loginForm = document.getElementById('login-form');
    let result = validateUserInput(loginForm);

    let errorDiv = document.getElementById('js-errors');
    let newLabel = document.createElement('label');
    newLabel.setAttribute('id', 'error-string');

    if(errorDiv.hasChildNodes()){
        errorDiv.removeChild(document.getElementById('error-string'));
    }

    if (result !== false) {

        fetch('http://localhost/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: result,
        })
            .then((response) => response.json())
            .then((data) => {
                if(data === "Login attempt successful") {
                    location.replace(`/home`); // take to logged in homepage
                }

                else {
                    newLabel.textContent = "There is an issue with your credentials.";
                    errorDiv.appendChild(newLabel);
                }
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

function validateUserInput(loginForm) {
    let formValues = [loginForm.elements["username"], loginForm.elements["password"]];

    let checkNulls = [];
    for (let value of formValues) {
        if (value.value === "" || value.value === " ") {
            checkNulls.push(true);
        } else {
            checkNulls.push(false);
        }
    }

        if(checkNulls.includes(true)){
            return false;
        }

        else { //Only sanitise values if all values are valid and return formdata array, which will then be posted to backend
            const formData = {};

            formData["username"] = sanitize(formValues[0].value);
            formData["password"] = sanitize(formValues[1].value);

            return JSON.stringify(formData);
        }

}
