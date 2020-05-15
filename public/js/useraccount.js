import sanitize from "./sanitize.js";

const btnShowHide = document.getElementById("show-hide-fieldset");
const btnCancel = document.getElementById("cancel-button");
const btnSave = document.getElementById("save-button");

btnShowHide.addEventListener("click", function () {
    btnShowHide.classList.add("hidden");
    btnShowHide.classList.remove("btn");
    btnShowHide.classList.remove("btn-primary");

    document.getElementById("fieldset-update-details").classList.toggle("hidden");
});

btnCancel.addEventListener("click", function () {
    btnShowHide.classList.remove("hidden");
    btnShowHide.classList.add("btn");
    btnShowHide.classList.add("btn-primary");

    document.getElementById("fieldset-update-details").classList.add("hidden");
});

btnSave.addEventListener("click", function () {

    let errorDiv = document.getElementById('js-errors');
    let newLabel = document.createElement('label');
    newLabel.setAttribute('id', 'error-string');

    if(errorDiv.hasChildNodes()){
        errorDiv.removeChild(document.getElementById('error-string'));
    }

    let currentPasswd = document.getElementById("current-password").value;
    let newPasswd = document.getElementById("new-password").value;
    let newPasswdRepeat = document.getElementById("new-password-repeat").value;

    let validPasswd = checkValidPassword(newPasswd, newPasswdRepeat);

    let payload = [];

    payload.push(sanitize(currentPasswd));
    payload.push(sanitize(newPasswd));
    payload.push(sanitize(newPasswdRepeat));

    let checkNulls = [];
    for (let value of payload) {
        if (value === "" || value === " ") {
            checkNulls.push(true);
        } else {
            checkNulls.push(false);
        }
    }

    if(!checkNulls.includes(true) && validPasswd){

        fetch('http://localhost/api/updatepassword', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload),
        })
            .then((response) => response.json())
            .then((data) => {
                if(data === true) {
                    newLabel.textContent = "Your password has been updated.";
                    errorDiv.appendChild(newLabel);
                }

                else {
                    newLabel.textContent = "There was an error updating your password.";
                    errorDiv.appendChild(newLabel);
                }
            })
            .catch((error) => {
                console.error('Error:', error);
            });

    }

    else {
        newLabel.textContent = "There was an error updating your password.";
        errorDiv.appendChild(newLabel);
    }
});

function checkValidPassword(password, repeatPassword) {
    if(password === repeatPassword){
        return true;
    }

    return false;
}
