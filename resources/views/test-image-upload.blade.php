<!DOCTYPE html>
<html>
<head>
    <title>Test Image Upload</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Test Upload Ảnh - Product ID: {{ $productId }}</h2>
        
        <form id="testForm" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label>Chọn ảnh:</label>
                <input type="file" name="images[]" id="images" class="form-control" multiple accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
        
        <div id="result" class="mt-4"></div>
        <div id="console" class="mt-4 p-3 bg-light" style="max-height: 400px; overflow-y: auto;"></div>
    </div>

    <script>
        function log(message, type = 'info') {
            const console = document.getElementById('console');
            const time = new Date().toLocaleTimeString();
            console.innerHTML += `<div class="text-${type}">[${time}] ${message}</div>`;
            console.scrollTop = console.scrollHeight;
        }

        document.getElementById('testForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const files = document.getElementById('images').files;
            
            log(`Số file được chọn: ${files.length}`, 'primary');
            
            for (let i = 0; i < files.length; i++) {
                log(`File ${i+1}: ${files[i].name} (${(files[i].size / 1024).toFixed(2)} KB)`, 'secondary');
            }
            
            log('Đang gửi request...', 'warning');
            
            try {
                const response = await fetch('/admin/products/{{ $productId }}/images', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    }
                });
                
                log(`Response status: ${response.status}`, response.ok ? 'success' : 'danger');
                
                const data = await response.json();
                log(`Response data: ${JSON.stringify(data, null, 2)}`, response.ok ? 'success' : 'danger');
                
                document.getElementById('result').innerHTML = `
                    <div class="alert alert-${data.success ? 'success' : 'danger'}">
                        <strong>${data.success ? 'Thành công!' : 'Lỗi!'}</strong><br>
                        ${data.message}<br>
                        <pre>${JSON.stringify(data, null, 2)}</pre>
                    </div>
                `;
                
            } catch (error) {
                log(`Error: ${error.message}`, 'danger');
                document.getElementById('result').innerHTML = `
                    <div class="alert alert-danger">
                        <strong>Lỗi!</strong><br>
                        ${error.message}
                    </div>
                `;
            }
        });
        
        log('Test page loaded', 'success');
    </script>
</body>
</html>
