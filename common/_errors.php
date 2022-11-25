<?php if (!empty($errors)) : ?>
    <ul>
        <div class="alert alert-danger" role="alert">
            <?php foreach ($errors as $error) : ?>
                <li><?= $error ?></li>
            <?php endforeach; ?>
        </div>
    </ul>
<?php endif; ?>
