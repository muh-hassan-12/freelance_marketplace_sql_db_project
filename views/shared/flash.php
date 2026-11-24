<?php
$flash = getFlash();
if ($flash):
    $icon = $flash['type'] === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-triangle-fill';
?>
<div class="alert alert-<?= $flash['type'] ?>">
    <i class="bi <?= $icon ?>"></i>
    <?= htmlspecialchars($flash['message']) ?>
</div>
<?php endif; ?>
