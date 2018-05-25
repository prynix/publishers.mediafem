<?php include_once 'header.php'; ?>

<?php
if (isset($_GET['a']) && $_GET['a'] == "A") {
    $mensaje = "<h2>You are ready!</h2>You can log in now.";
}

if (isset($_GET['a']) && $_GET['a'] == "I") {
    $mensaje = "<h2>you haven't successfully</h2>The link to activate your account is invalid or has expired.";
}

if (isset($_GET['a']) && $_GET['a'] == "R") {
    $mensaje = "<h2>Thanks!</h2>Confirm the email that we sent to activate the account.";
}

if (isset($_GET['a']) && $_GET['a'] == "P") {
    $mensaje = "<h2>You've successfully</h2>Your password was changed successfully.";
}

if (isset($_GET['a']) && $_GET['a'] == "new_password_failed") {
    $mensaje = "<h2>you haven't successfully</h2>Your activation code is incorrect or has expired. Check your email and follow the instructions.";
}

if (isset($_GET['a']) && $_GET['a'] == "ban") {
    $mensaje = "<h2>you haven't successfully</h2>The account was deactivated for failing to comply with program policies.";
}
?>

<!-- SECCION: PUBLISHERS -->
<section id="publishers" class="greenBackground">
    <div class="container">
        <h2><?php echo $lang['publishers'] ?></h2>

        <div class="row">
            <div class="col-7">
                <h3><?php echo $lang['welcome_adtomatik_sites'] ?></h3>

                <?php echo $lang['info_adtomatik_sites'] ?>                

                <br /><br />

                <div style="text-align: center"><img src="images/publisher_img.jpg" alt="lista de publishers" style="border-radius: 4px;" /></div>

                <br />
               <?php if ($_GET['amb']=='test'){ ?>
<form action="http://testpublishers.adtomatik.com/register" method="post">
<?php }else{ ?>
                <form action="http://publishers.adtomatik.com/register" method="post">
<?php } ?>                    
<input type="submit" name="enviar_comentario" value="<?php echo $lang['publisher_register'] ?>" />
                </form>
            </div>

            <div class="col-offset-1"></div>

            <div class="col-4 textCenter">
                <h3><?php echo $lang['publisher_login'] ?></h3>
                <form id="form_acceso" action="http://publishers.adtomatik.com/login" method="post">
                    <input type="text" required="required" name="email" id="login" placeholder="<?php echo $lang['email'] ?>" />
                    <input type="password" required="required" name="password" id="password" placeholder="<?php echo $lang['password'] ?>" />
                    <div class="textLeft">
                        <input type="checkbox" value="1" name="remember"> <?php echo $lang['remember'] ?>
                    </div>
                    <input type="submit" name="enviar_comentario" value="ENTER" />
                    <div><a href="#" data-reveal-id="forgot_password" style="color: #fff !important;"><?php echo $lang['forgot_password'] ?></a></div>
                </form>
            </div>
        </div>
    </div>

    <div class="dataCount whiteBackground">
        <div class="row container">
            <h3><?php echo $lang['publisher_reasons'] ?></h3>

            <div class="col-offset-1"></div>

            <div class="col-2 textCenter">
                <span id="count1" class="gigantFont">90</span><br />
                <?php echo $lang['ad_networks'] ?>
            </div>

            <div class="col-offset-2"></div>

            <div class="col-2 textCenter">
                <span id="count2"  class="gigantFont">100</span><br />
                <?php echo $lang['dsp_worldwide'] ?>
            </div>

            <div class="col-offset-2"></div>

            <div class="col-2 textCenter">
                <span id="count3" class="gigantFont">230</span><br />
                <?php echo $lang['countries_served'] ?>
            </div>

            <div class="col-offset-1"></div>
        </div>
    </div>
</section>
<!-- /SECCION: PUBLISHERS -->

<!-- MODALS -->
<div id="get_Ok" class="reveal-modal">
    <div class="mensaje"><?php echo $mensaje; ?></div>

    <a class="close-reveal-modal">&#215;</a>
</div>
<a href="#" data-reveal-id="get_Ok" style="display: none;">GET OK</a>

<div id="get_Error" class="reveal-modal">
    <div class="mensaje"><?php echo $mensaje; ?></div>

    <a class="close-reveal-modal">&#215;</a>
</div>
<a href="#" data-reveal-id="get_Error" style="display: none;">GET ERROR</a>

<div id="forgot_password" class="reveal-modal">
    <div class="mensaje">
        <h2 class="textCenter"><?php echo $lang['not_access_account'] ?></h2>

        <form action="http://publishers.adtomatik.com/forgot_password" method="post">
            <table class="textCenter" style="width:100%">
                <tbody>
                    <tr>
                        <td><strong><?php echo $lang['enter_email'] ?>:</strong></td>
                    </tr>
                    <tr>
                        <td><input id="email" type="text" name="email" value="" size="30" maxlength="80" /></td>
                    </tr>
                    <tr style="height: 20px;">
                        <td></td>
                    </tr>
                    <tr>
                        <td style="text-align: center;"><input id="aceptar" class="btn_default" style="color: #333; font: normal bold 12px arial;" type="submit" value="Confirm" /></td>
                    </tr>
                </tbody>
            </table>
        </form>

        <div id="form_forgot_Ok" style="display: none;"><?php echo $lang['request_sent'] ?></div>
    </div>

    <a class="close-reveal-modal">&#215;</a>
</div>
<!-- /MODALS -->

<?php include_once 'footer.php'; ?>
