<?php
require_once __DIR__ . '/../autoloader.php';

$repo = new CampingSiteRepository();
$sites = [];
try{
	$sites = $repo->findAll();
}catch(Exception $e){
	$error = $e->getMessage();
}
?><!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Catalogue</title>
	<link rel="stylesheet" href="/css/main.css">
</head>
<body>
<h1>Camping sites</h1>
<?php if (!empty($error)): ?>
	<div class="error"><?php echo htmlspecialchars($error); ?></div>
<?php endif; ?>
<?php if (empty($sites)): ?>
	<p>No sites found.</p>
<?php else: ?>
	<ul class="catalogue-list">
	<?php foreach($sites as $site): ?>
		<li class="catalogue-item">
			<h2><?php echo htmlspecialchars($site->name ?? 'Untitled'); ?></h2>
			<p><strong>City:</strong> <?php echo htmlspecialchars($site->city ?? '-'); ?></p>
			<p><strong>Capacity:</strong> <?php echo htmlspecialchars($site->capacity ?? '-'); ?></p>
			<?php if ($site->image): ?>
				<img src="<?php echo htmlspecialchars($site->image); ?>" alt="<?php echo htmlspecialchars($site->name); ?>" style="max-width:200px;display:block;">
			<?php endif; ?>
			<p><?php echo nl2br(htmlspecialchars($site->description ?? '')); ?></p>
		</li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>
</body>
</html>