<!-- FOOTER -->
<footer>
    <div class="row container">
        <div class="col-3">
            <h3><?php echo Lang::get('webpage.about_us') ?></h3>
            
            <p>
                <?php echo Lang::get('webpage.footer_message') ?>
                
            </p>
        </div>

        <div class="col-offset-1"></div>

        <div class="col-3">
            <h3><?php echo Lang::get('webpage.our_office') ?></h3>

            <p>11380 Prosperity Farms Road<br />
                33410 - Palm Beach<br />
                Florida<br />
                USA<br />
                Tel.: +1 786-315-9918
            </p>
        </div>

        <div class="col-5">
            <h3><?php echo Lang::get('webpage.subscribe') ?></h3>

            <form action="http://lomasdezamora.us4.list-manage.com/subscribe/post?u=8593d71c7e0a64342c417bc65&id=9ff6f61cab" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                <input type="text" value="" name="EMAIL" class="required email" id="mce-EMAIL" placeholder="<?php echo Lang::get('webpage.enter_email_here') ?>" />
                <div id="mce-responses" class="clear">
                    <div class="response" id="mce-error-response" style="display:none"></div>
                    <div class="response" id="mce-success-response" style="display:none"></div>
                </div>
                <div style="position: absolute; left: -5000px;"><input type="text" name="b_8593d71c7e0a64342c417bc65_9ff6f61cab" tabindex="-1" value=""></div>
                <input type="submit" value="<?php echo Lang::get('webpage.send') ?>" name="subscribe" id="mc-embedded-subscribe" class="button">
            </form>

            <p><?php echo Lang::get('webpage.dont_worry') ?> :)</p>
        </div>
    </div>

    <div class="row copyright">
        <div class="container">
            <p class="col-6">Copyright © 2009 - <?php echo date('Y'); ?> AdTomatik by MediaFem LLC.</p>&nbsp;&nbsp;&nbsp;&nbsp;
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<p class="col-6">Made with &nbsp;<span class="icon_heart"></span>&nbsp; in Florida, USA.</p>&nbsp;&nbsp;&nbsp;
        </div>
    </div>
</footer>
<!-- /FOOTER -->

<script src="<?php echo asset('js/webpage_js/jquery.min.js')?>"></script>
<script src="<?php echo asset('js/webpage_js/jquery.validate.js')?>"></script>
<script src="<?php echo asset('js/webpage_js/jquery.reveal.js')?>"></script>
<script src="<?php echo asset('js/webpage_js/placeholdem.min.js')?>"></script>
<script src="<?php echo asset('js/webpage_js/countUp.min.js')?>"></script>
<script src="<?php echo asset('js/webpage_js/scripts.js')?>"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');
 
  ga('create', 'UA-54020812-1', 'auto');
  ga('send', 'pageview');
 
</script>
</body>
</html>



