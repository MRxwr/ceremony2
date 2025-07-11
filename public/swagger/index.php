
<?php
$allowed_ip = '2a00:1851:14:807f:603b:86b8:f596:c644'; // CHANGE THIS TO YOUR IP
if ($_SERVER['REMOTE_ADDR'] !== $allowed_ip) {
    echo "<!DOCTYPE html><html><head><title>{$_SERVER['REMOTE_ADDR']}Access Denied</title></head><body style='font-family:sans-serif;background:#f7f7f7;text-align:center;padding-top:100px;'><h2>Access Denied</h2><p>Your IP is not allowed to view this page.</p></body></html>";
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Swagger UI</title>
  <link rel="stylesheet" href="https://unpkg.com/swagger-ui-dist@5.12.0/swagger-ui.css">
  <style>
    body { margin:0; background:#f7f7f7; }
  </style>
</head>
<body>
  <div id="swagger-ui"></div>
  <script src="https://unpkg.com/swagger-ui-dist@5.12.0/swagger-ui-bundle.js"></script>
  <script src="https://unpkg.com/swagger-ui-dist@5.12.0/swagger-ui-standalone-preset.js"></script>
  <script>
    window.onload = function() {
      SwaggerUIBundle({
        url: '../openapi.yaml',
        dom_id: '#swagger-ui',
        deepLinking: true,
        presets: [
          SwaggerUIBundle.presets.apis,
          SwaggerUIStandalonePreset
        ],
        layout: "StandaloneLayout"
      });
    };
  </script>
</body>
</html>
