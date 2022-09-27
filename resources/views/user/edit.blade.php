
<form action="/api/users/{{$user->user_id}}" method="post">
    {{csrf_field()}}
    {{method_field('PUT')}}
    <input type="text" name="name" placeholder="Name">
    <input type="text" name="email" placeholder="E-mail">
    <input type="submit" value="ok">
</form>