
<form action="/api/users" method="post">
    {{csrf_field()}}
    <input type="text" name="name" placeholder="Name">
    <input type="text" name="email" placeholder="E-mail">
    <input type="submit" value="ok">
</form>