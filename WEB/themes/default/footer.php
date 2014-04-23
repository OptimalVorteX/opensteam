<?php if (!isset( $cfg["website"] ) ) {header('HTTP/1.1 404 Not Found'); die; } ?>
</div>
<footer>
   <div class="row">
      <div class="col-lg-12">
         <ul class="list-unstyled">
            <li class="pull-right"><a href="#top">Back to top</a></li>
         </ul>
         <div>
            Powered by <a href="http://ohsystem.net">OHSystem</a>
         </div>
         <p>Template based on <a href="http://bootswatch.com/default/" rel="nofollow">Bootswatch</a>. </p>
      </div>
   </div>
</footer>
<script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
<script src="<?=OSS_THEME_LINK?>js/bootstrap.js"></script>
<script src="<?=OSS_THEME_LINK?>js/scripts.js"></script>
<?=OPENSTEAM_AFTER_FOOTER()?>
</body>
</html>