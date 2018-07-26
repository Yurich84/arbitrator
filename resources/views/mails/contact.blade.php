<p>
    Сообщение с формы контактов
</p>
<p>
    Детали
</p>
<ul>
    <li>Name: <strong>{{ $name }}</strong></li>
    <li>Email: <strong>{{ $email }}</strong></li>
</ul>
<hr>
<p>
    @foreach ($messageLines as $messageLine)
        {{ $messageLine }}<br>
    @endforeach
</p>
<hr>