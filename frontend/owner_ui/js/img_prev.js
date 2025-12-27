// ===== IMAGE UPLOAD PREVIEW =====
const input = document.getElementById('images');
const previewContainer = document.getElementById('previewContainer');
const uploadedImages = []; // store selected File objects

if (input) {
    input.addEventListener('change', () => {
        const file = input.files[0]; // single file at a time

        if (!file) return;

        if (!file.type.startsWith("image/")) {
            alert("Only image files are allowed.");
            return;
        }

        if (uploadedImages.length >= 4) {
            alert("Maximum 4 images allowed.");
            return;
        }

        uploadedImages.push(file);
        updatePreview();
        input.value = ""; // allow re-selecting same file
    });
}

function updatePreview() {
    previewContainer.innerHTML = "";

    uploadedImages.forEach((file, index) => {
        const reader = new FileReader();
        reader.onload = e => {
            const div = document.createElement('div');
            div.className = 'image-preview';
            div.style.backgroundImage = `url(${e.target.result})`;

            // Remove button
            const btn = document.createElement('button');
            btn.innerText = "×";
            btn.className = "remove-btn";
            btn.onclick = () => {
                uploadedImages.splice(index, 1);
                updatePreview();
            };

            div.appendChild(btn);
            previewContainer.appendChild(div);
        };
        reader.readAsDataURL(file);
    });
}

// ===== FORM SUBMISSION =====
const form = document.querySelector('form.listing-form');
if (form) {
    form.addEventListener('submit', (e) => {
        if (uploadedImages.length < 1) {
            e.preventDefault();
            alert("Please upload at least 1 image.");
            return;
        }

        // Create hidden file inputs for actual submission
        uploadedImages.forEach((file, i) => {
            const fileInput = document.createElement('input');
            fileInput.type = 'hidden';
            fileInput.name = 'images[]';
            fileInput.value = file.name; // backend still receives via $_FILES
            form.appendChild(fileInput);
        });
    });
}

// ===== TOAST NOTIFICATION & REDIRECT =====
if (typeof listingSuccess !== 'undefined' && listingSuccess) {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.classList.add('show');

        setTimeout(() => {
            toast.classList.remove('show');
            window.location.href = 'owner_home.php';
        }, 2000);
    }
}
