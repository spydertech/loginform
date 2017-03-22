<?php

    $page_title    = 'Reset Password';
    $page_class    = 'login';

    $message = ''; //Success/Failure message to user 

    // Get the token and user ID from database.
    if (isset($_REQUEST['id'], $_REQUEST['token']) && is_numeric($_REQUEST['id']) && $_REQUEST['id'] > 0 && $_REQUEST['token'] <> '') {
        $id      = (int) $_REQUEST['id'];
        $result  = mysqli_query($db, 'SELECT * FROM users where user_id="'.$id.'"');
        $Results = mysqli_fetch_array($result);

        if ($result && $result -> num_rows == 1 ) {
            
            // If the token and user ID were found, show the reset form. 
            if ($Results['user_token'] == $_REQUEST['token']) {
                $showForm = '1';
            }
            
            // Otherwise, show an error.
            else {
                $message = '<div class="alert alert-danger" role="alert"><i class="pe-7s-close"></i>Invalid token. Either this token has already been used or you have followed an incorrect link.</div>';
            }
            
        } else {
            
            // If the token and user ID were not found, show an error.
            $message = '<div class="alert alert-danger" role="alert"><i class="pe-7s-close"></i>User does not exist.</div>';
        }
        
    } else {
        
        // Catch all error
        $message = '<div class="alert alert-danger" role="alert"><i class="pe-7s-close"></i>You have followed an incorrect link.</div>';
        
    }

    // Submit the reset form to change the password.
    if ( $_SERVER['REQUEST_METHOD'] == 'POST' && $message == '' ) {

        $id       = mysqli_real_escape_string($db,$_POST['id']);
        $password = mysqli_real_escape_string($db,$_POST['password']);
        $result   = mysqli_query($db, 'SELECT * FROM users where user_id="'.$id.'"');
        $Results  = mysqli_fetch_array($result);
        if (count($Results)>=1) {
            
            // Password changed successfully.
            $query = "UPDATE users SET user_password='".md5($password)."', user_token='' where user_id='".$Results['user_id']."'";
            mysqli_query($db,$query);

            $message = '<div class="alert alert-success" role="alert"><i class="pe-7s-close"></i><span class="icon icon-arrows-check"></span> Your password changed sucessfully.<br/>
            <a href="/payments/login.php">click here to login</a></span></div>';
        }
        else
        {
            // Catch all error
            $message = '<div class="alert alert-danger" role="alert"><i class="pe-7s-close"></i>Invalid key please try again. '. $encrypt .'</div>';
        }
    }

    // Include the theme files.
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
            <?php
                echo $message;
                if ($showForm == '1') { 
            ?>

                <!-- Password Reset Form -->
                <form id="login-form" method="post" action="">
                    <label for="user_password">Enter New Password</label>
                    <div class="input-group">
                        <span id="basic-addon1" class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                        <input type="password" id="password" name="password" class="form-control" aria-describedby="basic-addon1" />
                    </div>
                    <label for="user_password">Re-type New Password</label>
                    <div class="input-group">
                        <span id="basic-addon1" class="input-group-addon"><i class="fa fa-fw fa-user"></i></span>
                        <input type="password" id="password2" name="paswword2" class="form-control" aria-describedby="basic-addon1" />
                    </div>

                    <input name="action" type="hidden" value="reset" />
                    <input name="id" type="hidden" value="<?php echo $_REQUEST['id'] ?>" />
                    <input name="token" type="hidden" value="<?php echo $_REQUEST['token'] ?>" />
                    <div class="form-group text-center">
                        <input class="btn btn-lg btn-primary" type="submit" name="submit" value="Submit" onclick="mypasswordmatch();" />
                    </div>
                </form>

                <script>
                    function mypasswordmatch() {
                        var pass1 = $("#password").val();
                        var pass2 = $("#password2").val();
                        if (pass1 != pass2){
                            alert("Passwords do not match");
                            return false;
                        }else{
                            $( "#reset" ).submit();
                        }
                    }
                </script>

            <?php } ?>
            <p class="form-footer">
                <a href="<?php echo $domain; ?>/login.php">Back to Login</a>
            </p>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $('#login-form').validate({
            rules: { 
                "user_email": { required:true, email: true },
            }
        });
    });
</script>
