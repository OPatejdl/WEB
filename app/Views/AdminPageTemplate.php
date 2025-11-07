<?php
global $tplData;
require_once("TemplateBasics.class.php");
$tmplHeaders = new TemplateBasics();
?>



<?php
$tmplHeaders->getHTMLHeader($tplData["title"]);
?>

<?php
    $view = "
<div class='row align-items-center py-5 mt-5'>
    <div>
        <h2 class='mb-5'> Správa uživatelů</h2>
    </div>";

    if (empty($tplData["users"])) {
        $view .= "<p>Zatím žádní uživatelé</p>";
    } else {
        $view .= "
    <div class='table-responsive'>
        <table class='table table-dark table-hover align-middle'>
            <thead class='table-warning text-center'>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Počet reviews</th>
                    <th>Datum vytvoření</th>
                    <th>Role</th>
                    <th>Akce</th>
                </tr>
            </thead>
            
            <tbody>
                
                <!-- Each User -->";
       // User's rows
        foreach ($tplData["users"] as $user) {
            $view .= "
                <tr class='text-center'>
                    <td>".$user["id_user"]."</td>
                    <td>".$user["username"]."</td>
                    <td>".$tplData[$user["username"]]["reviews_count"]."</td>
                    <td>".$user["created_at"]."</td>
                    <td class='text-center'>
                        <form method='post' class='d-flex align-items-center gap-2 mb-0'>
                            <input type='hidden' name='update_user_id' value='{$user['id_user']}'>
                            <select 
                                name='new_role_id'
                                class='form-select form-select-sm bg-dark text-light border-secondary'
                                style='width: 50%'>";
            foreach ($tplData["roles"] as $role) {
                if ($role["name"] == "Super Admin") {
                    continue;
                }

                if ($role["id_role"] == $user["fk_id_role"]) {
                    $view .= "<option value='{$role["id_role"]}' selected>" . $role["name"] . "</option>>";
                } else {
                    $view .= "<option value='{$role["id_role"]}'>" . $role["name"] . "</option>>";
                }
            }

            $view .= "
                            </select>
                            <button type='submit' class='btn btn-sm btn-outline-primary'>Update</button>
                        </form>
                    <td/>
                </tr>
                ";
        }

        $view .= "
            </tbody>
        </table>
    </div>";
    }

    $view .= "
</div>";

    echo $view;
?>

<?php
$tmplHeaders->getHTMLFooter();
?>
