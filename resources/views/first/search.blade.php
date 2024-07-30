<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>検索ページ</title>
    <link rel="stylesheet" href="{{ asset('/css/search.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<body>
    <x-app-layout>
        <div class="container">
            <strong><h3>検索ワードを入力してください（例：タグ、タイトル）</h3></strong>
            <div class="cp_iptxt">
                <form action="{{ route('result') }}" method="GET" onsubmit="return pickKeywords()">
                    <label for="keywordInput" class="ef">Search</label>
                    <textarea id="keywordInput" name="keyword"  rows="1" cols="20" placeholder="キーワードを入力してください">
                        {{ request('keyword', '') }}
                    </textarea>
                    <input type="submit" value="検索">
                </form>
            </div>
        </div>

        <script>
            const stopWords = ['the', 'is', 'in', 'and', 'of', 'to', 'with', 'a', 'for', 'on'];

            function removeStopWords(words) {
                return words.filter(word => !stopWords.includes(word));
            }

            function extractKeywords(text) {
                let words = text.toLowerCase().split(/\W+/); // 非単語文字で分割
                return removeStopWords(words);
            }

            function pickKeywords() {
                let input = document.getElementById('keywordInput').value;
                if (input) {
                    let keywords = extractKeywords(input);
                    document.getElementById('keywordInput').value = keywords.join(' ');
                    return true;
                } else {
                    alert('検索ワードを入力してください');
                    return false;
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                let input = document.getElementById('keywordInput');
                let text = input.value.trim();
                
                // もし内容が全て空白だった場合、テキストエリアを空にする
                if (text === '') {
                    input.value = '';
                }
            });
        </script>
    </x-app-layout>
</body>
</html>
