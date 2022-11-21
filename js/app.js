const uploadFile = document.getElementById('file_upload');

uploadFile.addEventListener('change', (event) => {
    const file = event.target.files[0];

    // ファイルが選択されなかった場合は終了
    if (file == null) return;

    const reader = new FileReader();
    const preview = document.getElementById("preview");
    const previewImage = document.getElementById("preview_image");
    const plusIcon = document.getElementById("plus_icon");
    const uploadText = document.getElementById("upload_text"); 

    if (plusIcon != null) {
        preview.removeChild(plusIcon);
    } 

    if (uploadText != null) {
        preview.removeChild(uploadText);
    } 

    if (previewImage != null) {
        preview.removeChild(previewImage);
    } 

    reader.onload = function() {
        const img = document.createElement("img");
        img.setAttribute("src", reader.result);
        img.setAttribute("id", "preview_image");
        preview.appendChild(img);
    } 

    reader.readAsDataURL(file);
});
