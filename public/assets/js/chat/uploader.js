window.ChatUploader = (function () {

    let input;
    let button;
    let preview;
    let filename;
    let removeButton;

    function init() {

        input = document.getElementById('attachment');

        button = document.getElementById('attachment-button');

        preview = document.getElementById('attachment-preview');

        filename = document.getElementById('attachment-name');

        removeButton = document.getElementById('remove-attachment');

        if (!input) {
            return;
        }

        registerEvents();

    }

    function registerEvents() {

        button.addEventListener(
            'click',
            openFileDialog
        );

        input.addEventListener(
            'change',
            previewFile
        );

        removeButton.addEventListener(
            'click',
            removeFile
        );

    }

    function openFileDialog() {

        input.click();

    }

    function previewFile() {

        const file = input.files[0];

        if (!file) {

            hidePreview();

            return;

        }

        if (!validate(file)) {

            input.value = '';

            hidePreview();

            return;

        }

        filename.innerHTML =
            file.name + " (" + formatSize(file.size) + ")";

        preview.classList.remove('d-none');

    }

    function removeFile() {

        input.value = '';

        hidePreview();

    }

    function hidePreview() {

        preview.classList.add('d-none');

        filename.innerHTML = '';

    }

    function validate(file) {

        const maxSize = 10 * 1024 * 1024;

        if (file.size > maxSize) {

            alert("Maximum file size is 10 MB.");

            return false;

        }

        const allowed = [

            "image/jpeg",

            "image/png",

            "image/webp",

            "application/pdf",

            "application/msword",

            "application/vnd.openxmlformats-officedocument.wordprocessingml.document",

            "application/vnd.ms-excel",

            "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",

            "application/zip",

            "application/x-zip-compressed"

        ];

        if (!allowed.includes(file.type)) {

            alert("Unsupported file type.");

            return false;

        }

        return true;

    }

    function formatSize(bytes) {

        if (bytes < 1024)
            return bytes + " B";

        if (bytes < 1024 * 1024)
            return (bytes / 1024).toFixed(1) + " KB";

        return (bytes / 1024 / 1024).toFixed(2) + " MB";

    }

    return {

        init

    };

})();