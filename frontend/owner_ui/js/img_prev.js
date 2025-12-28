// ===== SINGLE IMAGE UPLOAD PREVIEW =====
const input = document.getElementById('images');
const previewContainer = document.getElementById('previewContainer');
const form = document.querySelector('form.listing-form');

if (input) {
    input.addEventListener('change', () => {
        const file = input.files[0];

        if (!file) return;

        // Validation
        if (!file.type.startsWith("image/")) {
            alert("Only image files are allowed.");
            input.value = "";
            return;
        }

        // Show Preview
        const reader = new FileReader();
        reader.onload = e => {
            previewContainer.innerHTML = `
                <div class="image-preview" style="background-image: url(${e.target.result})">
                    <button type="button" class="remove-btn" id="clearImage">&times;</button>
                </div>
            `;

            // Handle removal
            document.getElementById('clearImage').onclick = () => {
                input.value = "";
                previewContainer.innerHTML = "";
            };
        };
        reader.readAsDataURL(file);
    });
}

// ===== FORM SUBMISSION =====
if (form) {
    form.addEventListener('submit', (e) => {
        if (!input.files || input.files.length === 0) {
            e.preventDefault();
            alert("Please upload an image.");
        }
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

function previewImage(event) {
    const previewContainer = document.getElementById('previewContainer');
    previewContainer.innerHTML = ""; // Clear previous preview
    
    const file = event.target.files[0];
    
    if (file) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            const img = document.createElement("img");
            img.src = e.target.result;
            img.style.width = "200px";
            img.style.height = "150px";
            img.style.objectFit = "cover";
            img.style.borderRadius = "8px";
            img.style.border = "1px solid #ddd";
            
            previewContainer.appendChild(img);
        }
        
        reader.readAsDataURL(file);
    }
}