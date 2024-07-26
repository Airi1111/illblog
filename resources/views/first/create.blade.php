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
                    }
                });
            });

            newFileInput.click(); // 新しいファイル選択ダイアログを開く
        });
    </script>
</x-app-layout>
</body>
</html>
