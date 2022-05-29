<?php

?>
<div class="div-login">
    <!-- submit이 눌리면 l_mode가 login인 상태로 넘어간다. (get방식)-->
  <form method="post" action="login.php?l_mode=login">
    <fieldset>
      <legend>login</legend>
      <div class="form-group row change-input-text">
        <label for="loginId" class="col-sm-2 col-form-label">id</label>
        <div class="col-sm-10">
          <input type="text"class="form-control-plaintext" id="loginId"  name="loginId" placeholder='id를 입력하세요.'>
        </div>
      </div>
      <div class="form-group row change-input-text">
        <label for="loginPwd" class="col-sm-2 col-form-label">password</label>
        <div class="col-sm-10">
          <input type="text"  class="form-control-plaintext" id="loginPwd" name="loginPwd" placeholder='비밀번호를 입력하세요.'>
        </div>
      </div>
      <button type="submit" class="btn btn-primary">login</button>
      </fieldset>
  </form>
</div>