<?php

header("Content-Type: application/xml");

$root_url = rtrim("http://".$_SERVER['HTTP_HOST'].preg_replace("|(.*/).*|", '$1', $_SERVER['REQUEST_URI']), "/");

echo '<'.'?xml version="1.0"?'.'>';
?>
<Module>

 <ModulePrefs title="OpenSocial makeRequest example" author="Phillip Pearson">
  <Require feature="opensocial-0.7" />
  <Require feature="dynamic-height"/>
  <Require feature="views" />
 </ModulePrefs>
 
 <Content type="html" view="canvas">
  <![CDATA[
  <script>
    var ROOT_URL = <?php echo json_encode($root_url) ?>;
  </script>
  <ul id="status"></ul>
  <script src="<?php echo htmlspecialchars($root_url) ?>/jquery.js?<?= filemtime("jquery.js") ?>"></script>
  <script src="<?php echo htmlspecialchars($root_url) ?>/app.js?<?= filemtime("app.js") ?>"></script>
  ]]>
 </Content>
 
 <Content type="html" view="profile">
  <![CDATA[
  <p>Nothing to see here, move along...</p>
  ]]>
 </Content>
</Module>
