
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="Adtomatik-tag" content="NzQ2"/>
        <title>AdTomatik</title>
		
        <link rel="icon" type="image/x-icon" href="<?php echo asset('images/webpage_images/favicon.ico');?>">

        <link href="http://fonts.googleapis.com/css?family=Varela+Round" rel="stylesheet" type="text/css" />

        <link href="<?php echo asset('css/webpage_css/stylesheet.css'); ?>" rel="stylesheet" type="text/css" media="screen" />

        <!--[if IE]>
          <script src="<?php echo asset('js/webpage_js/html5shiv.min.js'); ?>"></script>
          <script src="<?php echo asset('js/webpage_js/respond.min.js'); ?>"></script>
        <![endif]-->

        <!--[if (gte IE 6)&(lte IE 8)]>
          <script src="<?php echo asset('js/webpage_js/selectivizr.min.js'); ?>"></script>
        <![endif]-->
    </head>
    <body>

        <!-- HEADER -->
        <header>
            <div class="row container">
                <h1 class="col-4"><a id="logo" href="http://www.adtomatik.com" title="">AdTomatik</a></h1>

                <nav class="col-8">
                    <div id="languages">
                       	<a href="?lang=en"><img src="<?php echo asset('images/webpage_images/united-kingdom2.png')?>"></img></a>
                        <a href="?lang=es"><img src="<?php echo asset('images/webpage_images/spain2.png')?>"></img></a> 
                        <a href="?lang=ru"><img src="<?php echo asset('images/webpage_images/russia2.png')?>"></img></a> 
			<a href="?lang=pt"><img src="<?php echo asset('images/webpage_images/portugal2.png')?>"></img></a>
			<a href="?lang=zh"><img src="<?php echo asset('images/webpage_images/china2.png')?>"></img></a>	
		    </div>
                    <ul>
                        <li><a href="marketers"><?php echo Lang::get('webpage.marketers') ?></a></li>
                        <li><a href="publishers"><?php echo Lang::get('webpage.publishers') ?></a></li>
                        <li><a href="http://group.mediafem.com/es/index.php#slide_empresa"><?php echo Lang::get('webpage.company') ?></a></li>
                        <li><a href="contact"><?php echo Lang::get('webpage.contact') ?></a></li>
                        <li style="margin: 19px 10px 0;"><a href="login" class="roundedLink"><?php echo Lang::get('webpage.publishers_login') ?></a></li>
                        <li><a href="register" class="roundedLink"><?php echo Lang::get('webpage.publishers_signup') ?></a></li>
                    </ul>
                </nav>
            </div>
        </header>
        <!-- /HEADER -->


