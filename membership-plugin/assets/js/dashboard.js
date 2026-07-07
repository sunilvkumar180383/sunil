// Dashboard JavaScript

document.addEventListener('DOMContentLoaded', function() {
    initializeDashboard();
});

function initializeDashboard() {
    setupFileUpload();
    setupNavigation();
    loadFiles();
}

// File Upload Setup
function setupFileUpload() {
    const dropzone = document.getElementById('dropzone');
    const fileInput = document.getElementById('fileInput');
    
    if (!dropzone) return;

    dropzone.addEventListener('click', () => fileInput.click());

    dropzone.addEventListener('dragover', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = 'var(--primary-color)';
        dropzone.style.backgroundColor = 'var(--light-gray)';
    });

    dropzone.addEventListener('dragleave', () => {
        dropzone.style.borderColor = 'var(--border-color)';
        dropzone.style.backgroundColor = 'white';
    });

    dropzone.addEventListener('drop', (e) => {
        e.preventDefault();
        dropzone.style.borderColor = 'var(--border-color)';
        dropzone.style.backgroundColor = 'white';
        handleFiles(e.dataTransfer.files);
    });

    fileInput.addEventListener('change', (e) => {
        handleFiles(e.target.files);
    });
}

function handleFiles(files) {
    const allowedExtensions = ['pes', 'dst', 'jef', 'exp', 'vip', 'vp3', 'xxx'];
    const fileList = [];
    
    for (let file of files) {
        const fileExtension = file.name.split('.').pop().toLowerCase();
        
        if (!allowedExtensions.includes(fileExtension)) {
            alert(`File ${file.name} is not a supported format.`);
            continue;
        }
        fileList.push(file);
    }
    
    if (fileList.length > 0) {
        uploadFiles(fileList);
    }
}

function uploadFiles(files) {
    const formData = new FormData();
    for (let file of files) {
        formData.append('files[]', file);
    }

    fetch('/api/upload.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Files uploaded successfully!');
            loadFiles();
        } else {
            alert('Upload failed: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function formatBytes(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return Math.round(bytes / Math.pow(k, i) * 100) / 100 + ' ' + sizes[i];
}

function loadFiles() {
    fetch('/api/get-files.php')
        .then(response => response.json())
        .then(files => {
            const filesList = document.getElementById('filesList');
            if (!filesList) return;

            filesList.innerHTML = '';

            if (!files || files.length === 0) {
                filesList.innerHTML = '<p>No files uploaded yet. Upload your first file!</p>';
                return;
            }

            files.forEach(file => {
                const fileCard = document.createElement('div');
                fileCard.className = 'file-card';
                fileCard.innerHTML = `
                    <h4>${file.original_name}</h4>
                    <p>${formatBytes(file.file_size)}</p>
                    <p>${new Date(file.upload_date).toLocaleDateString()}</p>
                    <div class="file-actions">
                        <button class="btn btn-primary" onclick="downloadFile(${file.id})">Download</button>
                        <button class="btn btn-outline" onclick="deleteFile(${file.id})">Delete</button>
                    </div>
                `;
                filesList.appendChild(fileCard);
            });
        })
        .catch(error => console.error('Error loading files:', error));
}

function downloadFile(fileId) {
    window.location.href = `/api/download.php?file_id=${fileId}`;
}

function deleteFile(fileId) {
    if (!confirm('Are you sure you want to delete this file?')) return;

    fetch('/api/delete-file.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ file_id: fileId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('File deleted successfully!');
            loadFiles();
        } else {
            alert('Delete failed: ' + data.message);
        }
    })
    .catch(error => console.error('Error:', error));
}

function setupNavigation() {
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            navLinks.forEach(l => l.classList.remove('active'));
            link.classList.add('active');
        });
    });
}
