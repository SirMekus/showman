var CookieUtil = {
    get: function (name) {
        var cookieName = encodeURIComponent(name) + "=",
            cookieStart = document.cookie.indexOf(cookieName),
            cookieValue = null;
        if (cookieStart > -1) {
            var cookieEnd = document.cookie.indexOf(";", cookieStart);
            if (cookieEnd == -1) {
                cookieEnd = document.cookie.length;
            }
            cookieValue = decodeURIComponent(
                document.cookie.substring(
                    cookieStart + cookieName.length,
                    cookieEnd
                )
            );
        }
        return cookieValue;
    },

    set: function (name, value, expires, path, domain, secure) {
        var cookieText =
            encodeURIComponent(name) + "=" + encodeURIComponent(value);
        if (expires instanceof Date) {
            cookieText += "; expires=" + expires.toGMTString();
        }
        if (path) {
            cookieText += "; path=" + path;
        }
        if (domain) {
            cookieText += "; domain=" + domain;
        }
        if (secure) {
            cookieText += "; secure";
        }
        document.cookie = cookieText;
    },

    unset: function (name, path, domain, secure) {
        this.set(name, "", new Date(0), path, domain, secure);
    },
};

function calculate_time_zone() {
    var rightNow = new Date();
    var jan1 = new Date(rightNow.getFullYear(), 0, 1, 0, 0, 0, 0); // jan 1st
    var june1 = new Date(rightNow.getFullYear(), 6, 1, 0, 0, 0, 0); // june 1st
    var temp = jan1.toGMTString();
    var jan2 = new Date(temp.substring(0, temp.lastIndexOf(" ") - 1));
    temp = june1.toGMTString();
    var june2 = new Date(temp.substring(0, temp.lastIndexOf(" ") - 1));
    var std_time_offset = (jan1 - jan2) / (1000 * 60 * 60);
    var daylight_time_offset = (june1 - june2) / (1000 * 60 * 60);
    var dst;
    if (std_time_offset == daylight_time_offset) {
        dst = "0"; // daylight savings time is NOT observed
    } else {
        // positive is southern, negative is northern hemisphere
        var hemisphere = std_time_offset - daylight_time_offset;
        if (hemisphere >= 0) std_time_offset = daylight_time_offset;
        dst = "1"; // daylight savings time is observed
    }
    var i;

    return std_time_offset;

    //document.cookie = "timezone = "+encodeURIComponent(convert(std_time_offset));
}

//After fetching the timezone(that has been calculated on the fly) the script that made the operation will then convert it.
function convert(value) {
    var hours = parseInt(value);
    value -= parseInt(value);
    value *= 60;
    var mins = parseInt(value);
    value -= parseInt(value);
    value *= 60;
    var secs = parseInt(value);
    var display_hours = hours;
    // handle GMT case (00:00)
    if (hours == 0) {
        display_hours = "00";
    } else if (hours > 0) {
        // add a plus sign and perhaps an extra 0
        display_hours = hours < 10 ? "+0" + hours : "+" + hours;
    } else {
        // add an extra 0 if needed
        display_hours = hours > -10 ? "-0" + Math.abs(hours) : hours;
    }

    mins = mins < 10 ? "0" + mins : mins;
    return display_hours + ":" + mins;
}

function queryString(name, url = window.location.href) {
    name = name.replace(/[\[\]]/g, "\\$&");
    var regex = new RegExp("[?&]" + name + "(=([^&#]*)|&|#|$)"),
        results = regex.exec(url);
    if (!results) return null;
    if (!results[2]) return "";
    return decodeURIComponent(results[2].replace(/\+/g, " "));
}

function getQueryStringsFromUrl(url){
    if(url.split("?").length > 1){
        var query = url.split("?")[1]
        var urlSearchParams = new URLSearchParams(query)
        var params = Object.fromEntries(urlSearchParams.entries());
        return params
    }
    else{
        return null
    }

}

function getFullDate() {
    var fullDate = new Date();

    var month = fullDate.getMonth() + 1;
    if (month < 10) {
        month = "0" + month;
    }

    var date = fullDate.getDate();

    if (date < 10) {
        date = "0" + date;
    }

    //The final and well-formatted today's date.
    var today_date = fullDate.getFullYear() + "-" + month + "-" + date;
    return today_date;
}


function empty(val) {
    if (val == undefined || val == "") {
        return true;
    }
}



function dragstart_handler(ev) {
    // Add the target element's id to the data transfer object
    ev.dataTransfer.setData("text/plain", ev.target.id);
}

function dragover_handler(ev) {
    ev.preventDefault();

    ev.dataTransfer.dropEffect = "move";
}

function drop_handler(ev) {
    ev.preventDefault();

    const drop_target_id = ev.target.parentElement.id;

    const drop_target_order = $(document)
        .find("#" + drop_target_id)
        .find(".order")
        .val();

    // Get the id of the origin/src
    const srcID = ev.dataTransfer.getData("text/plain");

    const srcOrder = $(document)
        .find("#" + srcID)
        .find(".order")
        .val();

    if (srcOrder > drop_target_order) {
        $("#" + srcID).insertBefore("#" + drop_target_id);
    } else {
        $("#" + srcID).insertAfter("#" + drop_target_id);
    }

    var i = 0;

    $(document)
        .find("tbody tr")
        .each(function () {
            i += 1;
            $(this).find(".order").val(i);
        });

    $(document).find(".save").removeClass("d-none");
}

function money_format(amount, currency = "") {
    if (empty(currency) == true) {
        if (CookieUtil.get("currency") != null) {
            currency = CookieUtil.get("currency");
        } else {
            currency = "NGN";
        }
    }

    //else the currency must have been set.
    if (typeof currency != "string") {
        currency = "NGN";
    }

    var formatter = new Intl.NumberFormat("en-US", {
        style: "currency",
        currency: currency,

        // These options are needed to round to whole numbers if that's what you want.
        //minimumFractionDigits: 0,
        //maximumFractionDigits: 0,
    });

    return formatter.format(amount);
}

function capitalLetters(s) {
    return s
        .trim()
        .split(" ")
        .map((i) => i[0].toUpperCase() + i.substr(1))
        .reduce((ac, i) => `${ac} ${i}`);
}

function lazyLoadImages() {
    // create config object: rootMargin and threshold
    // are two properties exposed by the interface
    const config = {
        rootMargin: "0px 0px 50px 0px",
        threshold: 0,
    };

    // register the config object with an instance
    // of intersectionObserver
    let observer = new IntersectionObserver(function (entries, self) {
        // iterate over each entry
        entries.forEach((entry) => {
            // process just the images that are intersecting.
            // isIntersecting is a property exposed by the interface
            if (entry.isIntersecting) {
                var image = entry.target;
                image.src = image.dataset.src;
                image.classList.remove("lazy-load");
                // custom function that copies the path to the img
                // from data-src to src
                console.log(entry.target);
                // the image is now in place, stop watching
                self.unobserve(entry.target);
            }
        });
    }, config);

    const imgs = document.querySelectorAll("[data-src]");
    imgs.forEach((img) => {
        observer.observe(img);
    });
}

var acceptedDocs = ["image/jpeg", "image/png", "image/gif", "image/webp"];

let acceptedSize = 3228267;

//******************* BASICALLY FOR GENERATING STRONG KEYS/PASSWORDS
const lowerCase = "abcdefghijklmnopqrstuvwxyz";
const upperCase = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
const numbers = "1234567890";
const special = "`~!@#$%^&*()-=_+[]{}|;':\",./<>?";
const hex = "123456789ABCDEF";

function random() {
    const { crypto, Uint32Array } = window;
    // if (typeof crypto?.getRandomValues === 'function' && typeof Uint32Array === 'function') {
    if (typeof crypto.getRandomValues === "function" && typeof Uint32Array === "function") {
        // Divide a random UInt32 by the maximum value (2^32 -1) to get a result between 0 and 1
        return (window.crypto.getRandomValues(new Uint32Array(1))[0] / 4294967295);
    }

    return Math.random();
}

function keyGen(length,useLowerCase = true,useUpperCase = true,useNumbers = true,useSpecial = true,useHex = false) {
    let chars = "";
    let key = "";

    if (useLowerCase) chars += lowerCase;
    if (useUpperCase) chars += upperCase;
    if (useNumbers) chars += numbers;
    if (useSpecial) chars += special;
    if (useHex) chars += hex;

    for (let i = 0; i < length; i++) {
        key += chars[Math.floor(random() * chars.length)];
    }

    return key;
}

function getKey(strength = null) {
    switch (strength) {
        case "only_letters":
            return keyGen(10, true, true, false, false, false);
        case "decent_pw":
            return keyGen(10, true, true, true, false, false);
        case "strong_pw":
            return keyGen(15, true, true, true, true, false);
        case "ft_knox_pw":
            return keyGen(30, true, true, true, true, false);
        case "ci_key":
            return keyGen(32, true, true, true, false, false);
        case "160_wpa":
            return keyGen(20, true, true, true, true, false);
        case "504_wpa":
            return keyGen(63, true, true, true, true, false);
        case "64_wep":
            return keyGen(5, false, false, false, false, true);
        case "128_wep":
            return keyGen(13, false, false, false, false, true);
        case "152_wep":
            return keyGen(16, false, false, false, false, true);
        case "256_wep":
            return keyGen(29, false, false, false, false, true);
        default:
            return keyGen(8, true, true, true, false, false);
        //throw Error(`No such strength "${strength}"`);
    }
}

function displayErrorInCanvass($msg) {
    //Just in case one have been created already, we remove it
    if(document.querySelector("#offcanvasBottom") != null){
    document.querySelector("#offcanvasBottom").remove();
    }

    let div = document.createElement('div');
        div.className = 'offcanvas offcanvas-bottom';
        div.id = 'offcanvasBottom'
        div.setAttribute('tabindex', -1)
        div.setAttribute('aria-labelledby', 'offcanvasBottomLabel')
        div.innerHTML = `<div class='offcanvas-header d-flex justify-content-center'>
        <h5 class='offcanvas-title text-center' id='offcanvasBottomLabel'>${$msg}</h5>
      </div>`;
      document.body.appendChild(div);
      
    new bootstrap.Offcanvas(document.getElementById("offcanvasBottom")).show();
}

function showSpinner($msg) {
    //Just in case one have been created already, we remove it
    if(document.querySelector(".spinner-div") != null){
    document.querySelector(".spinner-div").remove();
    }

    //$("<div class='d-flex justify-content-center spinner-div'><div class='spinner-grow position-fixed' role='status' style='left: 50%; top: 50%; height:60px; width:60px; margin:0px auto; position: absolute; z-index:1000; color:var(--color-theme)'><span class='sr-only'>Loading...</span></div").prependTo(div);

    let div = document.createElement('div');
        div.className = 'd-flex justify-content-center spinner-div';
        div.innerHTML = `<div class='spinner-grow position-fixed' role='status' style='left: 50%; top: 50%; height:60px; width:60px; margin:0px auto; position: absolute; z-index:1000; color:var(--color-theme)'><span class='sr-only'>Loading...</span></div`;
      
    document.body.appendChild(div);
      
}

function togglePasswordVisibility(event){
    event.preventDefault()
    
    var clicked_buton = event.currentTarget

	let icon = clicked_buton.children[0]
	
	var passwordField = !empty(clicked_buton.dataset.id) ? document.querySelector(`#${clicked_buton.dataset.id}`) : clicked_buton.parentElement.parentElement.parentElement.querySelector("input");
	
	if(passwordField.getAttribute("type") == "password")
	{
		passwordField.setAttribute("type", "text")
        icon.classList.remove('fa-eye-slash')
        icon.classList.add('fa-eye')
	}
	else
	{
		if(passwordField.getAttribute("type") == "text")
		{
            passwordField.setAttribute("type", "password")
            icon.classList.remove('fa-eye')
            icon.classList.add('fa-eye-slash')
	    }
	}
}

  //This is the default storage location on the internet where files are saved
function storage(name, sub_folder='school'){
    return `${document.querySelector('meta[name="base_url"]').content}storage/uploads/${sub_folder}/${name}`
}

function* range(start, end) {
    for (let i = start; i <= end; i++) {
        yield i;
    }
}
  
function numberToAlphabet(index=0){
    if(index < 0){
        index = 0
    }

    if(index > 25){
        index = 25
    }

    return upperCase.split("")[index];
}

export {getFullDate, displayErrorInCanvass, togglePasswordVisibility, queryString, capitalLetters, getQueryStringsFromUrl, storage, range, getKey, numberToAlphabet, showSpinner}