<?php

    $page_title     = 'Log In';
    $page_class     = 'login';
    $page_keywords  = '';
    $page_desc      = '';

    // Start a session and log into the system.
    include_once ('./assets/include/db-user-login.php');

    // Include the template files.
    include_once ('./assets/template/website/main.php');

?>

<!-- Begin the page content. -->

<div class="container">
    <div class="row">
        <div class="col-md-12 text-center">
            <a href="./">
                <img src="./assets/img/logo-primary.png" alt="<?php echo $co_name; ?>" />
            </a>
        </div>
    </div>
    <div class="row">
        <div class="login_wrap col-md-5">
            <h1><?php echo $page_title; ?></h1>
            <?php echo $message; ?>
            <form id="login-form" method="post" action="">
                <div class="form-group">
                    <label for="user_email">Email Address <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                        <input type="email" name="user_email" class="form-control" />
                    </div>
                </div>
                <div class="form-group">
                    <label for="user_password">Password <span class="required">*</span></label>
                    <div class="input-group">
                        <span class="input-group-addon"><i class="fa fa-fw fa-asterisk"></i></span>
                        <input type="password" name="user_password" class="form-control" />
                    </div>
                </div>
                <div class="form-group text-center">
                    <input class="btn btn-lg btn-primary" type="submit" name="submit" value="Login" /> 
                </div>
            </form>
            <p class="form-footer">
                <a href="login.php?mode=forgot">I forgot my password!</a>
            </p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#login-form').validate({
            rules: { 
                "user_email":       { required:true, email: true },
                "user_password":    { required:true }
            }
        });
    });
</script>