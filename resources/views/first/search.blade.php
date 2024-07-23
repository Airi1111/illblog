<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <title>postsearch</title>
    <link rel="stylesheet" href="{{ asset('/css/search.css') }}">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">
</head>
<x-app-layout>
    <div class="container">
        <h3>検索ワードを入力してください（例：タグ、投稿内容、タイトル）</h3>
        <form action="{{ route('result') }}" method="GET" onsubmit="return pickKeywords()">
            <textarea id="keywordInput" name="keyword" rows="4" cols="50">{{ request('keyword', '') }}</textarea>
            <input type="submit" value="検索">
        </form>
    </div>
    
    <div class="footer">
        <a href="/">戻る</a>
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
    </script>
</x-app-layout>
</html>
