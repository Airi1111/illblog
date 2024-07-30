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
            <div class="posts">
                <strong><h3>投稿内容</h3></strong>
                <div class="post">
                    <div class="image-container">
                        <div class="file-input-wrapper">
                            <input type="file" name="post[images][]" multiple accept="image/*" id="file-input" />
                            <button type="button" class="add-image-button">
                                <i class="fa-solid fa-plus" style="color: #ffffff;"></i>
                            </button>
                            <div class="file-input-preview" id="preview"></div>
                             <button type="button" class="delete-button">
                                <i class="fa-solid fa-delete-left"style="color: #ffffff;"></i>
                            </button>
                        </div>
                    </div>
                    <div class="title">
                        <input type="text" name="post[title]" placeholder="タイトル" required />
                    </div>
                    <br>
                    <div class="comment">
                        <textarea name="post[comment]" placeholder="コメント本文"></textarea>
                    </div>
                    <div class="tags">
                        <label for="tags-input">タグ</label>
                        <input type="text" id="tags-input" placeholder="タグを入力" />
                        <div class="tags-container" id="tags-container"></div>
                        <input type="hidden" name="post[tags]" id="tags-hidden-input" />
                    </div>
                </div>
                <input type="submit" value="投稿する" />
            </div>
        </form>
    </div>

    <script>
        // 画像プレビュー機能
        document.querySelector('.add-image-button').addEventListener('click', function() {
            const fileInputWrapper = document.querySelector('.file-input-wrapper');
            const newFileInput = document.createElement('input');
            newFileInput.type = 'file';
            newFileInput.name = 'post[images][]';
            newFileInput.accept = 'image/*';
            newFileInput.style.display = 'none'; // 隠す

            // 新しい画像プレビュー用の要素を作成
            const newPreview = document.createElement('div');
            newPreview.className = 'file-input-preview';
            fileInputWrapper.appendChild(newFileInput);
            fileInputWrapper.appendChild(newPreview);

            newFileInput.addEventListener('change', function(event) {
                const preview = newPreview;
                preview.innerHTML = ''; // プレビューをクリア

                Array.from(event.target.files).forEach(file => {
                    if (file.type.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.file = file;

                        const reader = new FileReader();
                        reader.onload = function(e) {
                            img.src = e.target.result;
                        };
                        reader.readAsDataURL(file);

                        preview.appendChild(img);
                        
                        const removeButton = document.createElement('button');
                        removeButton.type = 'button';
                        removeButton.textContent = 'x';
                        removeButton.className = 'remove-image-button';
                        removeButton.addEventListener('click', function() {
                            removeImage(fileInputWrapper, newFileInput, preview);
                        });

                        preview.appendChild(removeButton);
                    }
                });
            });

            newFileInput.click(); // 新しいファイル選択ダイアログを開く
        });
        
        
        

        // タグ入力機能
        const tagsInput = document.getElementById('tags-input');
        const tagsContainer = document.getElementById('tags-container');
        const tagsHiddenInput = document.getElementById('tags-hidden-input');
        let tags = [];

        tagsInput.addEventListener('keypress', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
                const tagText = tagsInput.value.trim();
                if (tagText && !tags.includes(tagText)) {
                    tags.push(tagText);
                    addTag(tagText);
                    tagsInput.value = '';
                    updateTagsHiddenInput();
                }
            }
        });

        function addTag(tagText) {
            const tagElement = document.createElement('span');
            tagElement.className = 'tag';
            tagElement.textContent = tagText;

            const removeButton = document.createElement('button');
            removeButton.type = 'button';
            removeButton.textContent = 'x';
            removeButton.addEventListener('click', function() {
                removeTag(tagText);
            });

            tagElement.appendChild(removeButton);
            tagsContainer.appendChild(tagElement);
        }

        function removeTag(tagText) {
            tags = tags.filter(tag => tag !== tagText);
            updateTagsHiddenInput();
            renderTags();
        }

        function renderTags() {
            tagsContainer.innerHTML = '';
            tags.forEach(tag => {
                addTag(tag);
            });
        }

        function updateTagsHiddenInput() {
            tagsHiddenInput.value = tags.join(',');
        }
    </script>
</x-app-layout>
</body>
</html>
