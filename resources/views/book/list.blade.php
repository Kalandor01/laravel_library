
@foreach ($books as $book)
    <form action="/api/books/{{$book->book_id}}" method="post">
        {{csrf_field()}}
        {{method_field('GET')}}
        <div class="form-group">
            <input type="submit" value="{{$book->title}}">
        </div>
    </form>
@endforeach