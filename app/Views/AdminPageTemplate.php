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
        <table class='table  table-hover align-middle border-opacity-100 '>
            <thead class='table-warning text-center'>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Email</th>
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
            // do not show Admins if user is not Super Admin
            if ($tplData["user"]["priority"] != $tplData["rolesPrior"]["Super Admin"]
                && $user["priority"] == $tplData["rolesPrior"]["Admin"]) {
                continue;
            }

            $view .= "
                <tr class='text-center'>
                    <td>".$user["id_user"]."</td>
                    <td>".$user["username"]."</td>
                    <td>".$user["email"]."</td>
                    <td>".$tplData[$user["username"]]["reviews_count"]."</td>
                    <td>".$user["created_at"]."</td>
                    <td>
                        <form method='POST' action='' class='d-flex align-items-center justify-content-center gap-2 mb-0'>
                            <input type='hidden' name='action' value='editUserRole'>
                            <input type='hidden' name='update_user_id' value='{$user['id_user']}'>
                            <select 
                                name='new_role_id'
                                class='form-select form-select-sm border-secondary'
                                style='width: 50%'>";
            foreach ($tplData["roles"] as $role) {
                if ($role["priority"] < $tplData["user"]["priority"]) {
                    if ($role["id_role"] == $user["fk_id_role"]) {
                        $view .= "<option value='{$role["id_role"]}' selected>" . $role["name"] . "</option>";
                    } else {
                        $view .= "<option value='{$role["id_role"]}'>" . $role["name"] . "</option>";
                    }
                }
            }

            $view .= "
                            </select>
                            <button type='submit' class='btn btn-sm btn-outline-primary'>Update</button>
                        </form>
                    </td>
                    <td>
                        <!-- DELETE USER FORM -->
                        <form method='POST' action=''>
                            <input type='hidden' name='action' value='deleteUser'>
                            <input type='hidden' name='delete_user_id' value='{$user['id_user']}'>
                                <button type='submit' class='btn btn-outline-danger btn-sm'>
                                    Delete
                                </button>
                        </form>
                    </td>
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
