<?php include_once 'header.php'; ?>

<!-- SECCION: CONTACT -->
<section id="contact" class="greenBackground">
    <div class="container">
        <h2 class="row"><?php echo $lang['contact'] ?></h2>

        <p class="row"><?php echo $lang['info_contact'] ?></p>

        <div class="row">
            <div class="col-5">
                <form action="php/send_mail.php" method="post">
                    <?php
                    if (isset($_GET['e'])) {
                        switch ($_GET['e']) {
                            case 1:
                                echo '<div class="alerta error">All fields are required for submission.</div>';
                                break;
                            case 2:
                                echo '<div class="alerta error">Failed to send to email.</div>';
                                break;
                            case 3:
                                echo '<div class="alerta error">Please provide a directional valid email address.</div>';
                                break;
                            default:
                                echo '<div class="alerta error">Failed to send to email.</div>';
                                break;
                        }
                    } else if (isset($_GET['s'])) {
                        if ($_GET['s'] == 1)
                            echo '<div class="alerta ok">We received your contact, in 24hs our
specialists will contact you.</div>';
                    }
                    ?>

                    <input type="text" required="required" name="correo_electronico" placeholder="<?php echo $lang['email'] ?>" value="" />
                    <input type="text" required="required" name="asunto" placeholder="<?php echo $lang['subject'] ?>" value="" />
                    <select name="contact_for">
                        <option value="publisher"><?php echo $lang['iam_publisher'] ?></option>
                        <option value="advertiser"><?php echo $lang['iam_marketer'] ?></option>
                    </select>
                    <textarea required="required" name="comentario" placeholder="<?php echo $lang['message'] ?>"></textarea>
                    <input type="submit" name="enviar_comentario" value="<?php echo $lang['submit_message'] ?>" />
                </form>
            </div>

            <div class="col-offset-1"></div>

            <div class="col-6">
                <img src="images/oficinas.jpg" alt="Oficina AdTomatik" style="border-radius: 4px;" />

                <p class="icon icon_address">11380, Prosperity Farms Road, 33410-Palm Beach, Florida, USA</p>

                <p>
                    <span class="icon icon_linkedin_white"><a href="http://www.linkedin.com/company/3583007?trk=tyah&trkInfo=tas%3Aadtomatik%2Cidx%3A1-1-1" target="_BLANK">www.linkedin.com/company/3583007</a></span>
                </p>

                <p class="icon icon_phone">+1 786-315-9918</p>
            </div>
        </div>
    </div>
</section>
<!-- /SECCION: CONTACT -->

<?php include_once 'footer.php'; ?>