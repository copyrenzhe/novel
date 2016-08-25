<center>
    @if(count($errors) > 0)
    <ul>
        @foreach($errors->all() as $error)
        <li><font color="red">{{ $error }}</font></li>
        @endforeach
    </ul>
    @endif
</center>