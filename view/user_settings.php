<form class="center-form" action="/user/doCreate" method="post">
  <div>
  <!-- TODO LOGO-->
  <div><?php echo $_SESSION['username']; ?></div>
  <div>
    <label class="label-help">?</label><br>
    <input id="" class="input-form" name="password" type="password" placeholder="Password">
  </div>
  <div>
    <input id="" class="input-form" name="password" type="password" placeholder="Repeat password">
  </div>
  <div class="submit-form-div">
    <input id="" class="submit-form" name="change" type="submit" value="change">
  </div>
  </div>
  <a href="/user" class="form-switch">Cancel</a>
</form>
