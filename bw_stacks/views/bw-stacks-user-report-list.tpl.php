<?php
/**
 * @file
 * Stacks user report tpl.
 */
?>

<?php if (!empty($list)): ?>
  <ul class="stacks-debug-list<?php if ($list_class) { print " " . $list_class; } ?>">
    <?php foreach($list as $tid): ?>
      <li><a href="/taxonomy/term/<?php print $tid; ?>/edit" target="_blank"><?php print $tid; ?></a></li>
    <?php endforeach; ?>
  </ul>

<?php endif; ?>
