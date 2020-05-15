import sanitize from "./sanitize.js";

(function () {

    const amendBtn = document.getElementById("amend-btn");
    const deleteBtn = document.getElementById("delete-btn");
    const registerBtn = document.getElementById("register-btn");

    const registeredBtn = document.getElementById("registered-btn");

    document.addEventListener("DOMContentLoaded", onLoad);

    function onLoad() {

        if (typeof (amendBtn) != 'undefined' && amendBtn != null) {
            amendBtn.addEventListener("click", function () {
                const cancelBtn = document.getElementById("cancel-button");
                const saveBtn = document.getElementById("save-button");

                const eventDetails = document.getElementById("event-content");
                const eventDetailsAmend = document.getElementById("event-content-edit-hidden");

                eventDetails.classList.add("hidden");
                eventDetailsAmend.classList.remove("hidden");

                cancelBtn.addEventListener("click", function () {

                    /* location.reload is used instead of the below two lines of code to prevent
                    *  the unnecessary toggling of node classes, essentially "resetting" the page. */

                    // eventDetails.classList.remove("hidden");
                    // eventDetailsAmend.classList.add("hidden");

                    location.reload();
                });

                saveBtn.addEventListener("click", function () {

                    let origEventTitle = document.getElementById("event-title").textContent;
                    let origEventDescription = document.getElementById("event-description").textContent;
                    let origEventType = document.getElementById("event-type").textContent;
                    let origEventStartTime = document.getElementById("event-start-time").textContent;
                    let origEventEndTime = document.getElementById("event-end-time").textContent;

                    let amendEventTitle = sanitize(document.getElementById("amend-event-title").value);
                    let amendEventDescription = sanitize(document.getElementById("amend-event-description").value);
                    let amendEventType = sanitize(document.getElementById("types").value);
                    let amendEventStartTime = sanitize(document.getElementById("amend-event-start-time").value);
                    let amendEventEndTime = sanitize(document.getElementById("amend-event-end-time").value);


                    let oldValues = [origEventTitle, origEventDescription, origEventType,
                                     origEventStartTime, origEventEndTime];

                    let newValues = [amendEventTitle, amendEventDescription, amendEventType,
                                     amendEventStartTime, amendEventEndTime];

                    let payload = [oldValues, newValues];

                    fetch("http://localhost/api/updateevent", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    })
                        .then( (response) => response.json() ).then( (data) => {
                        if(data === true) {
                            location.reload();
                        }
                        else {
                            alert("There was an error updating your event.");
                            location.reload();
                        }
                        console.log(data)
                    } )
                        .catch((error) => {
                            console.log(`Error: ${error}`);
                        });

                });
            });
        }

        if (typeof (deleteBtn) != 'undefined' && deleteBtn != null) {
            deleteBtn.addEventListener("click", function () {

                let userAnswer = confirm("Are you sure? This will delete this event from our database. Press OK to continue.");

                if(userAnswer === true) {

                    let eventTitle = document.getElementById("event-title").textContent;
                    let eventDescription = document.getElementById("event-description").textContent;
                    let eventType = document.getElementById("event-type").textContent;
                    let eventStartTime = document.getElementById("event-start-time").textContent;
                    let eventEndTime = document.getElementById("event-end-time").textContent;

                    let payload = [eventTitle, eventDescription, eventType, eventStartTime, eventEndTime];

                    fetch("http://localhost/api/deleteevent", {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                        },
                        body: JSON.stringify(payload),
                    })
                        .then( (response) => response.json() ).then( (data) => {
                        if(data === true) {
                            // Provide confirmation to user

                            // Wait for however long

                            location.href = "../view";
                        }

                        else {
                            // error handle
                        }
                    } )
                        .catch((error) => {
                            console.log(`Error: ${error}`);
                        });

                }

            });

        }

        if (typeof (registerBtn) != 'undefined' && registerBtn != null) {
            registerBtn.addEventListener("click", function () {
                let eventTitle = document.getElementById("event-title").textContent;
                let eventDescription = document.getElementById("event-description").textContent;
                let eventType = document.getElementById("event-type").textContent;
                let eventStartTime = document.getElementById("event-start-time").textContent;
                let eventEndTime = document.getElementById("event-end-time").textContent;

                let payload = [eventTitle, eventDescription, eventType, eventStartTime, eventEndTime];

                fetch("http://localhost/api/registerevent", {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(payload),
                })
                    .then( (response) => response.json() ).then( (data) => {
                    if(data === true) {
                        document.getElementById("register-btn").value = "Registered!";
                    }

                    else {
                        // error handle
                    }

                    location.reload();
                } )
                    .catch((error) => {
                        console.log(`Error: ${error}`);
                    });
            });
        }

        if (typeof (registeredBtn) != 'undefined' && registeredBtn != null) {
            registeredBtn.addEventListener("click", function () {
                let errorDiv = document.getElementById('js-errors');
                let newLabel = document.createElement('label');
                newLabel.setAttribute('id', 'error-string');
                if(errorDiv.hasChildNodes()){
                    errorDiv.removeChild(document.getElementById('error-string'));
                }

                newLabel.textContent = "Oops! You're already registered!";
                errorDiv.appendChild(newLabel);
            });
        }

    }

})();
