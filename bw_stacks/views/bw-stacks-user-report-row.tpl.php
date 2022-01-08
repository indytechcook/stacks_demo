<tr>

    <td><?php print $id; ?></td>

    <td><a href="/node/<?php print $content->getId(); ?>/edit" target="_blank"><?php print $content->getId(); ?></a></td>

    <td><?php print $content->getTitle(); ?></td>

    <td><?php print $result->getType() ?></td>

    <td><?php print \PrettyDateTime\PrettyDateTime::parse(new DateTime('@' . $content->getCreated())); ?></td>

    <td>
      <?php
      if ($result->getType() === 'job') {
          $links = array_map(function ($value) {
              if (isset($value->tid)) {
                return l($value->tid, "/taxonomy/term/{$value->tid}/edit");
              }
          }, $content->getONETtids());
          print implode(", ", $links);
        }
      ?>
    </td>

    <td>
      <?php
      if ($result->getType() === 'job') {
        if ($content->getLocationRef()) {
          print l($content->getLocationRef()->getId(), "/taxonomy/term/{$content->getLocationRef()->getId()}/edit");
        }
      }
      ?>
    </td>

    <td>
      <?php
      if ($result->getType() === 'job') {
        if ($content->getCompanyRef()) {
          print l($content->getCompanyRef()->getId(), "/taxonomy/term/{$content->getCompanyRef()->getId()}/edit");
        }
      }
      else if ($result->getType() === 'article') {
        if ($content->getCompanyTid()) {
          print l($content->getCompanyTid(), "/taxonomy/term/{$content->getCompanyTid()}/edit");
        }
      }
      ?>
    </td>

    <td>
      <?php
      if ($result->getType() === 'job') {
        print $content->getMinExperience();
      }
      ?>
    </td>

    <td><?php print $result->getSource(); ?></td>

    <td><?php print implode(', ', $stats); ?></td>

    <td><?php $result->getAlteredBy(); ?></td>

</tr>
