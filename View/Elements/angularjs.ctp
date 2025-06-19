<!-- angularjs library -->
<script type="text/javascript" src="<?php echo $serverUrl ?>assets/plugins/angular/angular.min.js"></script>
<script type="text/javascript" src="<?php echo $serverUrl ?>assets/plugins/angular/angular-route.min.js"></script>
<script type="text/javascript" src="<?php echo $serverUrl ?>assets/plugins/angular/angular-resource.min.js"></script>
<script type="text/javascript" src="<?php echo $serverUrl ?>assets/plugins/angular-loading/loading-bar.js"></script>
<script type="text/javascript" src="<?php echo $serverUrl ?>assets/plugins/angular/angular-selectize.js"></script>

<!-- angularjs plugins -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/danialfarid-angular-file-upload/12.2.13/ng-file-upload.min.js"></script>

<!-- angularjs app -->
<script type="text/javascript" src="<?php echo $serverUrl ?>app/app.js?version=<?php echo time() ?>"></script>
<script type="text/javascript" src="<?php echo $serverUrl ?>app/directives.js?version=<?php echo time() ?>"></script>
<script type="text/javascript" src="<?php echo $serverUrl ?>app/filters.js?version=<?php echo time() ?>"></script>
<script type="text/javascript" src="<?php echo $serverUrl ?>app/services.js?version=<?php echo time() ?>"></script>
<script type="text/javascript" src="<?php echo $serverUrl ?>app/controllers.js?version=<?php echo time() ?>"></script>

<!-- angularjs scripts -->
<?php
  $scripts = array(
    'users',
    'cruds',
    'status'
  );
?>

<?php foreach ($scripts as $script): ?>
  <!-- <?php echo $script ?> -->
  <script type="text/javascript" src="<?php echo $serverUrl ?>app/<?php echo $script ?>/service.js<?php echo '?version=' . time() ?>"></script>
  <script type="text/javascript" src="<?php echo $serverUrl ?>app/<?php echo $script ?>/route.js<?php echo '?version=' . time() ?>"></script>
  <script type="text/javascript" src="<?php echo $serverUrl ?>app/<?php echo $script ?>/controller.js<?php echo '?version=' . time() ?>"></script>
  <!-- .<?php echo $script ?> -->
<?php endforeach ?>


