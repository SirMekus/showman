import { triggerFileChanger, uploadImage, removeImage } from "./ImageUpload.js";
import { insertAfter, removeElement } from "./Dom.js";
import { togglePasswordVisibility, getKey, displayErrorInCanvass, capitalLetters, showSpinner } from "./helper.js";

/**
 * Adds a istener for specific tags for elements that may not yet
 * exist.
 * @param selector the selector in form [tag].[class] (i.e. a.someBtn)
 * @param event and event (i.e. click)
 * @param funct a function reference to execute on an event
 */
function addLiveListener(selector, event, funct) {
    /**
     * Set up interval to check for new items that do not
     * have listeners yet. This will execute every 1/10 second and
     * apply listeners to
     */
    setInterval(function () {
        var selectors = selector.split(" ");

        for (var i = 0; i < selectors.length; i++) {
            document
                .querySelectorAll(selectors[i])
                .forEach(function (currentValue, currentIndex, listObj) {
                    listObj[currentIndex].addEventListener(event, funct);
                });
        }
    }, 1000);
}

function registerEventListeners() {
    addLiveListener(".select-photo", "click", triggerFileChanger);
    addLiveListener(".image", "change", uploadImage);
    addLiveListener(".remove-image", "click", removeImage);
    addLiveListener(".password-visibility", "click", togglePasswordVisibility);

    addLiveListener("a.pre-run", "click", function(event){ 
        event.preventDefault(); 
        
        var clickedLink = event.currentTarget;
        
        var href = clickedLink.getAttribute("href");
        
        var classToUse = clickedLink.dataset.classname || "remove";
        
        var textWord = clickedLink.innerHTML || "Continue";
        
        var caption = clickedLink.dataset.caption || "Shall we?";
    
        if(textWord.toLowerCase() == 'log out' && document.querySelector("div.main-menu-for-dashboard") != null  && document.documentElement.clientWidth < 1300 )
        {
            $(document).find("div.main-menu-for-dashboard").toggle();
        }

        displayErrorInCanvass(`
        <div class='card card-body'>
            <h5 class='card-title d-flex justify-content-center'>${caption}</h5>
            <div class='card-footer'> 
                <div class='btn-group d-flex justify-content-center' data-toggle='buttons'> 
                    <button type='button' class='close btn text-dark btn-lg' data-bs-dismiss="offcanvas" aria-label="Close" aria-hidden='true'>Cancel</button>
                    <a href='${href}' class='${classToUse} btn btn-danger btn-lg'>${capitalLetters(textWord)}</a>
                </div>
            </div>
        </div>`)
    });

    addLiveListener("a.logout", "click", function(event){ 
        event.preventDefault();
    
        showSpinner()
    
        document.getElementById('logout-form').submit();
    })

    //General for all pages that use a POST submit method especially.
    addLiveListener("#form .form", "submit", function (event) {
        event.preventDefault();

        var this_form = event.currentTarget;

        //In case there are more than 2 submit buttons in a form.
        var submit_button = this_form.querySelector("input[type='submit']");

        if (this_form.querySelector("div.success") == null) {
            let div = document.createElement("div");
            div.className = "success";

            this_form.insertBefore(div, submit_button.parentElement);
        }

        var responseArea = this_form.querySelector(".success");

        if (this_form.querySelector("#hidden_content") != null) {
            this_form.querySelector("#hidden_content").value =
                frames["richedit"].document.body.innerHTML;
        }

        var notFilled = false;

        //We make sure those fields that are required are filled incase the user mistakenly skips any.
        this_form
            .querySelectorAll("input")
            .forEach(function (currentValue, currentIndex, listObj) {
                var currentNode = listObj[currentIndex];

                if (
                    currentNode.dataset.name != undefined ||
                    currentNode.getAttribute("required") != undefined
                ) {
                    if (currentNode.value == "") {
                        notFilled = true;

                        var name =
                            currentNode.dataset.name ||
                            currentNode.getAttribute("name");
                        currentNode.classList.remove("is-valid");
                        currentNode.classList.add("is-invalid");

                        //$(this).removeClass("is-valid").addClass("is-invalid");
                        responseArea.innerHTML =
                            "<span class='text-danger'>You should fill in the " +
                            capitalLetters(name) +
                            " field before you proceed</span>";

                        //responseArea.html("<span class='text-danger'>You should fill in the "+capitalLetters(name)+" field before you proceed</span>");

                        return false;
                    }
                    currentNode.classList.remove("is-invalid");
                    currentNode.classList.add("is-valid");

                    //$(this).removeClass("is-invalid").addClass("is-valid");
                }
            });

        if (notFilled == true) {
            return false;
        }

        var sub_value = submit_button.value;

        var action = this_form.getAttribute("action");

        //var method = this_form.getAttribute('method')//$(this)[0]["method"];

        var data_to_send = new FormData(this_form);

        if (this_form.querySelector("div.upload-progress-div") == null) {
            var ajaxIndicator = document.createElement("div");
            ajaxIndicator.className =
                "d-flex justify-content-center spinner-div";
            ajaxIndicator.innerHTML =
                "<div class='spinner-grow position-fixed' role='status' style='left: 50%; top: 50%; height:60px; width:60px; margin:0px auto; position: absolute; z-index:1000; color:var(--color-theme)'><span class='sr-only'>Loading...</span>";

            document.body.appendChild(ajaxIndicator);
        }

        submit_button.value = "...in progress";
        submit_button.setAttribute("disabled", "disabled");

        axios
            .post(action, data_to_send)
            .then((response) => {
                console.log(response.data)
                if(response.data.status && response.data.redirect_url){
                    if(document.querySelector(".navbar") != null){
                        const nav = document.querySelector(".navbar");
                        
                        var progressMenu = document.createElement("div");
                        
                        progressMenu.className = "progress fixed-top";
                        progressMenu.innerHTML = "<div class='progress-bar progress-bar-striped bg-info' role='progressbar' aria-valuenow='75' aria-valuemin='0' aria-valuemax='100' style='width: 75%'></div>"
                        
                        insertAfter(progressMenu, nav);

                    }
                    
                    var replace = response.data.replace || false;
                    //console.log(response)
                    
                    if(replace == false)
                    {
                        window.location.href = response.data.redirect_url;
                    }
                    else
                    {
                        window.location.replace(response.data.redirect_url);
                    }
                }
                else{
                    responseArea.innerHTML = `<span class='text-success'>${response.data}</span>`;
                }

                //responseArea.innerHTML = `<span class='text-success'>${response.data}</span>`;
                removeElement(this_form, ".server-response");
            })
            .catch((error) => {
                if (!error || !error.response) {
                    return;
                }
                //console.log(error.response.data.message ?? error.response.data);
                removeElement(this_form, ".server-response");
                switch (error.response.status) {
                    case 422:
                        //data = error.response.data;
                        //console.log(error.response.data.message ?? error.response.data);
                        var items = error.response.data.errors;
                        if (items != undefined) {
                            for (var item in items) {
                                //This may be an element that is dynamically added to the form field, thus may not be always present in the DOM
                                if (this_form.querySelector(`[name='${item}']`) == null) {
                                    continue;
                                }

                                var sibling = this_form.querySelector(`[name='${item}']`).nextElementSibling;
                                if (sibling == null) {
                                    //Then we need to create it
                                    var element = document.createElement("div");
                                    element.id = item;
                                    element.className =
                                        "server-response text-danger";
                                    insertAfter(
                                        element,
                                        this_form.querySelector(
                                            `[name='${item}']`
                                        )
                                    );
                                } else {
                                    if (sibling.id != item) {
                                        var element =
                                            document.createElement("div");
                                        element.id = item;
                                        element.className =
                                            "server-response text-danger";
                                        insertAfter(element, sibling);
                                    }
                                }

                                var responseForElement =
                                    this_form.querySelector(`#${item}`);
                                responseForElement.innerHTML = items[item][0];
                            }

                            if(items.length > 1){
                                responseArea.innerHTML =
                                "<span class='server-response text-danger'>Please make sure you fill required fields in the form and try again.</span>";
                            }
                            else{
                                responseArea.innerHTML =
                                `<span class='server-response text-danger'>${error.response.data.message}</span>`;
                            }
                        } 
                        else {
                            console.log(error.response.data.message ?? error.response.data)
                            const msg = error.response.data.message ?? error.response.data
                            responseArea.innerHTML = "<span class='server-response text-danger'>" +msg +"</span>"
                        }

                        break;

                    case 401:
                        responseArea.innerHTML =
                            "<span class='server-response text-danger'>" +
                            error.response.data.message +
                            "</span>";

                        break;

                    case 404:
                        responseArea.innerHTML =
                            "<span class='server-response text-danger'>" +
                            error.response.data.message +
                            "</span>";

                        break;

                    default:
                        responseArea.innerHTML =
                            "<span class='server-response text-danger'>There was a problem in submission. Please try again</span>";
                }
            })
            .then(() => {
                submit_button.value = sub_value;

                submit_button.removeAttribute("disabled");
                document.querySelector(".spinner-div").remove();
            });
    });
}

export { registerEventListeners, addLiveListener };
