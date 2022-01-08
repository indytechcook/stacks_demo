<?php
/**
 * @file
 * Stacks user report tpl.
 */
?>

<?php if (!empty($phrases)): ?>

  <ul>
    <?php foreach($phrases as $tid => $values): ?>
      <li>
        <a href="/taxonomy/term/<?php print $tid; ?>/edit" target="_blank">
          <?php print $tid; ?>
        </a>

        <?php if (!empty($values)): ?>
          <ul>
            <?php foreach($values as $value): ?>
              <li><?php print $value; ?></li>
            <?php endforeach; ?>
          </ul>
        <?php endif; ?>

      </li>
    <?php endforeach; ?>
  </ul>

<?php endif; ?>
