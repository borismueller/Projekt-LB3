<form action="/user/doEdit" method="post">
  <input type="text" name="username" value="<?= $user->name;?>" />
  <input type="password" name="password" value="" placeholder="new password" />
  <input type="password" name="repeat" value="" placeholder="repeat"/>
  <input type="submit" />
</form>
