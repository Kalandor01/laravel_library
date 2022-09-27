
<form action="/api/books" method="post">
    {{csrf_field()}}
    <input type="text" name="author" placeholder="Author">
    <input type="text" name="title" placeholder="Title">
    <input type="submit" value="ok">
</form>