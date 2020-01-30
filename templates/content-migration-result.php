<div class="wrap">
  <h1>Migration des contenus Xyoos</h1>
  
  <ol>
    <?php echo $result; ?>
  </ol>

  <table class="form-table">
    <tr valign="top">
      <th scope="row">Refaire une migration : 
        <td>
          <form method="get" action="admin.php" >
            <input type="hidden" name="page" value="xyoos-migration">
            <input type="hidden" name="action" value="launch-content-migration">
            <input type="hidden" name="post_type" value="<?php echo $post_type; ?>">
            <input type="number" name="offset" min="0" step="50" value="<?php echo $offset + $nb_to_convert; ?>" class="small-text">
            <button class="button button-primary">Continuer la migration</button>
          </form>
        </td>
      </th>
    </tr>
  </table>
</div>