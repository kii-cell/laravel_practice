<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>翌日対応期限のタスク</title>
</head>

<body>
    <p>{{ $name }}様</p>

    <p>翌日対応期限のタスクが以下の通りです。</p>

    <ul>
        @foreach ($tasks as $task)
            <li>{{ $task->deadline->format('Y年m月d日 H時i分') }}:
                {{ $task->title }}（{{ $task->status === 1 ? '起票' : '対応中' }}）</li>
        @endforeach
    </ul>

    <p>※本メールは自動送信のため返信できません。</p>
</body>

</html>
