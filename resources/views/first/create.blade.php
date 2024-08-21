<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>Create Post</title>
    <link rel="stylesheet" href="{{ asset('/css/create.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<x-app-layout>
    <div class="createview">
        <br>
        <form action="{{ route('store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <br>
            <div class="button-container">
                <a href="{{ route('question.create') }}" class="question-button">質問投稿へ</a>
            </div>
            <div class="posts">
                <strong><h3>投稿内容</h3></strong>
                <div class="post">
                    <div class="image-container">
                        <div class="file-input-wrapper">
                            <input type="file" name="post[images][]" multiple accept="image/*" id="file-input" />
                            <button type="button" class="add-image-button">
                                <i class="fa-solid fa-plus" style="color: #ffffff;"></i>
                            </button>
                            <div class="file-input-preview" id="preview">
                                <input type="hidden" id="deleted-images" name="post[deleted_images]" />
                            </div>
                        </div>
                    </div>
                    <div class="title">
                        <input type="text" name="post[title]" placeholder="タイトル" required />
                    </div>
                    <br>
                    <div class="comment">
                        <textarea name="post[comment]" placeholder="コメント本文"></textarea>
                    </div>
                </div>
                <input type="submit" value="投稿する" />
            </div>
        </form>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fileInput = document.querySelector('#file-input');
    const previewContainer = document.querySelector('#preview');
    const deletedImagesInput = document.querySelector('#deleted-images');
    let deletedImages = [];

    document.querySelector('.add-image-button').addEventListener('click', function() {
        fileInput.click();
    });

    fileInput.addEventListener('change', function(event) {
        Array.from(event.target.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const preview = document.createElement('div');
                preview.className = 'file-input-preview';
                preview.style.position = 'relative';

                const img = document.createElement('img');
                img.file = file;

                const reader = new FileReader();
                reader.onload = function(e) {
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);

                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'remove-image-button';
                removeButton.innerHTML = '<i class="fa-solid fa-delete-left" style="color: #ffffff;"></i>';
                removeButton.addEventListener('click', function() {
                    preview.remove();
                    if (!deletedImages.includes(file.name)) {
                        deletedImages.push(file.name);
                        updateDeletedImagesInput();
                    }
                });

                preview.appendChild(img);
                preview.appendChild(removeButton);
                previewContainer.appendChild(preview);
            }
        });
    });

    function updateDeletedImagesInput() {
        deletedImagesInput.value = deletedImages.join(',');
    }
});
</script>

</x-app-layout>
</body>
</html>
