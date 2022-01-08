<?php
/**
 * @file
 * Stacks user report tpl.
 */
?>

<?php if (!empty($logentries_link)): ?>
    <h3>Builded feed</h3>
    <p><strong>Go to see how feed was builded: </strong><?php print $logentries_link; ?></p>
<?php endif; ?>

<h3>User Business Logic</h3>
<?php if (!empty($business_logic)): ?>

  <ul>

    <?php foreach($business_logic as $data): ?>

      <li>

        <span class="label">
          <strong><?php print $data['label']; ?></strong>
        </span>

        <?php print drupal_render($data['value']); ?>

      </li>

    <?php endforeach; ?>

  </ul>

<?php endif; ?>

<h3>Filtered By</h3>
<?php if (!empty($filtered_by)): ?>

    <ul>

      <?php foreach($filtered_by as $data): ?>

          <li>

        <span class="label">
          <strong><?php print $data['label']; ?></strong>
        </span>

            <?php print drupal_render($data['value']); ?>

          </li>

      <?php endforeach; ?>

    </ul>

<?php endif; ?>
