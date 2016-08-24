<center>
    @if(count($errors) > 0)
    <ul>
        @foreach($messages->all() as $error)
        <li><font color="red">{{ $error }}</font></li>
        @endforeach
    </ul>
    @endif
</center>