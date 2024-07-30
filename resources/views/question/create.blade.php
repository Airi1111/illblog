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
        <form action="{{ route('question.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <br>
            <div class="posts">
                <strong><h3>質問投稿</h3></strong>
                <div class="post">
                    <div class="image-container">
                        <div class="file-input-wrapper">
                            <input type="file" name="question[images][]" multiple accept="image/*" id="file-input" />
                            <button type="button" class="add-image-button">
                                <i class="fa-solid fa-plus" style="color: #ffffff;"></i>
                            </button>
                            <div class="file-input-preview" id="preview">
                                <input type="hidden" id="deleted-images" name="question[deleted_images]" />
                            </div>
                        </div>
                    </div>
                    <div class="title">
                        <input type="text" name="question[title]" placeholder="見出し" required />
                    </div>
                    <br>
                    <div class="comment">
                        <textarea name="question[comment]" placeholder="質問本文"></textarea>
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

            // 画像追加ボタンのイベントリスナー
            document.querySelector('.add-image-button').addEventListener('click', function() {
                fileInput.click();
            });

            fileInput.addEventListener('change', function(event) {
                Array.from(event.target.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const preview = document.createElement('div');
                        preview.className = 'file-input-preview';
                        preview.style.position = 'relative'; // スタイルを相対的に設定

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
                            preview.remove(); // 画像プレビューを削除
                            // 削除された画像のファイル名を追加
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
