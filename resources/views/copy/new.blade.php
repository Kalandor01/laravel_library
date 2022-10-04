
<form action="/api/copies" method="post">
    {{csrf_field()}}
    <select name="book_id" placeholder="Book Id">
        @foreach ($books as $book)
            <option value="{{$book->book_id}}"
            >{{$book->title}}</option>
        @endforeach
    </select>
    <input type="submit" value="ok">
</form>