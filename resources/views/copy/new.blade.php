
<form action="/api/copies" method="post">
    {{csrf_field()}}
    <select name="user_id" placeholder="User Id" style="display:none">
        <option value="1">Library</option>
    </select>
    <select name="book_id" placeholder="Book Id">
        @foreach ($books as $book)
            <option value="{{$book->book_id}}"
            >{{$book->title}}</option>
        @endforeach
    </select>
    <select name="status" placeholder="Status" style="display:none">
        <option value="0">Kikölcsönözhető</option>
    </select>
    <input type="submit" value="ok">
</form>