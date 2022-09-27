
<form action="/api/copies/{{$copy->copy_id}}" method="post">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <!-- return redirect('/copy/list'); -->
    <select name="user_id" placeholder="User Id">
        @foreach ($users as $user)
            <option value="{{$user->user_id}}"
            {{$user->user_id == $copy->user_id ? 'selected': ''}}
            >{{$user->name}}</option>
        @endforeach
    </select>
    <select name="book_id" placeholder="Book Id">
        @foreach ($books as $book)
            <option value="{{$book->book_id}}"
            {{$book->book_id == $copy->book_id ? 'selected': ''}}
            >{{$book->title}}</option>
        @endforeach
    </select>
    <select name="status" placeholder="Status">
        <option value=2
        <?php echo $copy->status == 2 ? 'selected' : ''?>
        >Selejtezendő</option>
        <option value=1
        <?php echo $copy->status == 1 ? 'selected' : ''?>
        >Kikölcsönzött</option>
        <option value=0
        <?php echo $copy->status == 0 ? 'selected' : ''?>
        >Kikölcsönözhető</option>
    </select>
    <input type="submit" value="ok">
</form>