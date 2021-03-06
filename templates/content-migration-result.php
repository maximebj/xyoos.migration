<div class="wrap">
  <h1>Migration des contenus Xyoos</h1>
  
  <?php if( $offset + $this->nb_to_convert > $total ): ?>
  <h3>La migration est terminée ! </h3>
  <p><?php echo $total; ?> publications ont été traitées. <a href="/wp-admin/admin.php?page=xyoos-migration">Retour aux outils de migration</a></p>

  <?php else: ?>
  <table class="form-table">
    <tr valign="top">
      <th scope="row">Prochain lot : 
        <td>
          <form method="get" action="admin.php" >
            <input type="hidden" name="page" value="xyoos-migration">
            <input type="hidden" name="action" value="launch-content-migration">
            <input type="hidden" name="post_type" value="<?php echo $post_type; ?>">
            <input type="number" name="offset" min="0" step="<?php echo $this->nb_to_convert; ?>" value="<?php echo $offset + $this->nb_to_convert; ?>" class="small-text">
            <button class="button button-primary">Continuer la migration</button>
          </form>
        </td>
      </th>
    </tr>
  </table>
  <?php endif; ?>

  <hr>

  <h3>Résultats de la migration</h3>

  <ol start="<?php echo $offset + 1; ?>">
    <?php echo $results; ?>
  </ol>

</div>