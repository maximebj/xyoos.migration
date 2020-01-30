<div class="wrap">
  <h1>Options de Migration Xyoos</h1>
    
  <table class="form-table">
    <tr valign="top">
      <th scope="row">Migrer les contenus : 
        <td>
          <form method="get" action="admin.php" >
            <input type="hidden" name="page" value="xyoos-migration">
            <input type="hidden" name="action" value="launch-content-migration">
            <input type="number" name="offset" min="0" step="50" value="0" class="small-text">
            <select name="post_type">
              <option value="cours" selected>Cours</option>
              <option value="post">Articles</option>
              <option value="tutoriels">Tutoriels</option>
            </select>
            <button class="button button-primary">Démarrer la migration</button>
          </form>
        </td>
      </th>
    </tr>

    <tr valign="top">
      <th scope="row">Migrer les champs ACF : 
        <td>
          <form method="get" action="admin.php" >
            <input type="hidden" name="page" value="xyoos-migration">
            <input type="hidden" name="action" value="launch-acf-migration">
            <button class="button button-primary">Générer les JSON</button>
          </form>
        </td>
      </th>
    </tr>
        
  </table>
</div>

