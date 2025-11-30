// script.js: Client-side Validation (Optional, for better UX)
document.addEventListener('DOMContentLoaded', function() {
    const loginForm = document.querySelector('form[action="login.php"]');
    if (loginForm) {
        loginForm.addEventListener('submit', function(e) {
            const username = document.getElementById('username').value.trim();
            const password = document.getElementById('password').value.trim();
            if (!username || !password) {
                alert('Please enter both username and password.');
                e.preventDefault();
            }
        });
    }

    const uploadForm = document.querySelector('form[action="upload.php"]');
    if (uploadForm) {
        uploadForm.addEventListener('submit', function(e) {
            const title = document.getElementById('title').value.trim();
            const description = document.getElementById('description').value.trim();
            const demoLink = document.getElementById('demo_link').value.trim();
            const image = document.getElementById('image').files[0];
            
            if (!title || !description || !demoLink || !image) {
                alert('Please fill all fields and select an image.');
                e.preventDefault();
            }
        });
    }
});