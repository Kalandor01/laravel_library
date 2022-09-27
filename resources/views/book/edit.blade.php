
<form action="/api/books/{{$book->book_id}}" method="post">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <input type="text" name="author" placeholder="Author">
    <input type="text" name="title" placeholder="Title">
    <input type="submit" value="ok">
</form>