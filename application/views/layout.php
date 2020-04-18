<!DOCTYPE html>
<html lang="ja">
<head>
    <meta http-equiv="Content-Type" content="text/html"; charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php if (isset($title)): echo $this->escape($title) . '-' ; endif; ?>Mini Blog</title>
</head>
<body>
    <header class="header">
        <h1><a href="<?php echo $base_url; ?>">Mini Blog</a></h1>
    </header>
    <main class="main">
        <?php  echo $_content; ?>
    </main>

</body>
</html>
