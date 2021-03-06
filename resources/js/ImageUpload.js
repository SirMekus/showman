import { displayErrorInCanvass } from "./helper.js";

function triggerFileChanger(e) {
    e.preventDefault();

    var target_class = e.currentTarget.dataset.targetclass
    
    var target = document.querySelector(`.${target_class}`)
    
    if (target.files.length >= 5) {
        displayErrorInCanvass(`You can (and should) only select a maximum of 5 images for upload`);

        return;
    }

    //The goal is to grab any previously uploaded file and then attach it to this new result created below
    if (target.files.length > 0) {
        if (document.getElementById("previously_uploaded") != null) {
            document.getElementById("previously_uploaded").remove();
        }

        let clonedNode = target.cloneNode(true);
        clonedNode.id = "previously_uploaded";
        clonedNode.removeAttribute("name");
        clonedNode.classList.remove(target_class);
        document.body.appendChild(clonedNode);
    }

    var event = new MouseEvent("click");

    target.dispatchEvent(event);
}

function uploadImage(e) {
    var selectedFiles = e.currentTarget.files

    let index = document.querySelectorAll(".remove-image").length;

    var preview_box_locator = e.currentTarget.getAttribute("data-preview");

    var preview_box = document.querySelector(`.${preview_box_locator}`);

    //var images = {};

    for (let i = 0; i < selectedFiles.length; i++) {
        var size = selectedFiles[i].size;
        var type = selectedFiles[i].type;

        if (!window.acceptedDocs.includes(type)) {
            displayErrorInCanvass(`${file[i].name} is unknown. Please upload an image file in JPG, PNG, GIF or WEBP format to continue`);
            removeImage(i);
            break;
        }

        if (size > window.acceptedSize) {
            displayErrorInCanvass(`File size for ${selectedFiles[i].name} too large. File must not be greater than ${(window.acceptedSize /1024/1024).toFixed("0")}MB`
            );
            removeImage(i);
            break;
        }

        let img = {
            src: URL.createObjectURL(selectedFiles[i]),
            file: selectedFiles[i],
            index:index
        };

        let div = document.createElement('div');
        div.className = 'div-for-this-photo me-2';
        div.innerHTML = `<a style='float:clear;' class='btn btn-lg remove-image' data-entry='${index}'  href='#'><span>&times;</span></a><a href='#' data-fancybox='gallery' data-caption='how it will be displayed ${i}' class='card'><img class='card-img-top' src='${img.src}' /> </a>`;

        preview_box.appendChild(div);

        index++
    }

    if (document.getElementById("previously_uploaded") != null) {
        const dt = new DataTransfer();
        const { files } = document.getElementById("previously_uploaded");
        console.log(files)
        for (var i = 0; i < files.length; i++) {
            const merged_file = files[i];
            dt.items.add(merged_file);
        }

        const current_file = e.currentTarget.files;

        for (var p = 0; p < current_file.length; p++) {
            const merged_file = current_file[p];
            dt.items.add(merged_file);
        }

        e.currentTarget.files = dt.files;

        document.getElementById("previously_uploaded").remove();

        var found = document.querySelectorAll(".remove-image").length - 1
        
        document.querySelectorAll(".remove-image").forEach(function(currentValue, currentIndex, listObj) {
            listObj[currentIndex].setAttribute('data-entry', currentIndex)
        })
    }
}


function removeImage(event) {
    event.preventDefault();
    var currentButton = event.currentTarget

    var index = currentButton.dataset.entry
console.log(`index is ${index}`)
	const dt = new DataTransfer()
    
	const input = document.querySelector('.image')
	//const { files } = input

	for (var i = 0; i < input.files.length; i++) {
		const file = input.files[i]
        
		if (index != i){
            //console.log("found it")
			dt.items.add(file) // here you exclude the file. thus removing it.
        }
	}
    
	input.files = dt.files // Assign the updates list

    currentButton.parentElement.remove();

    document.querySelectorAll(".remove-image").forEach(function(currentValue, currentIndex, listObj) {
        listObj[currentIndex].setAttribute('data-entry', currentIndex)
        console.log(currentValue + ', ' + currentIndex + ', ' + this);
        console.log(listObj)
    })
}

function removePhoto(index)
{
	const dt = new DataTransfer()
	const input = document.getElementById('image')
	const { files } = input

	for (let i = 0; i < files.length; i++) {
		const file = files[i]
		if (index !== i)
			dt.items.add(file) // here you exclude the file. thus removing it.
	}
	//console.log(dt.files);
	input.files = dt.files // Assign the updates list

	//$(this).parent().remove()

    document.querySelectorAll(".remove-image").forEach(function(currentValue, currentIndex, listObj) {
        listObj[currentIndex].setAttribute('data-entry', currentIndex)
        console.log(currentValue + ', ' + currentIndex + ', ' + this);
        console.log(listObj)
    })

	var found = $(document).find('.remove-image').length - 1;

	$(document).find('.remove-image').each(function (k) {
			    console.log("k is: "+ (found - k))
			    $(this).attr('data-entry', found - k)
		    })
	}

export { triggerFileChanger, removeImage, uploadImage, removePhoto };
