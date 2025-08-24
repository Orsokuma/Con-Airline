<?php 
include "../pages/dbconnect.php";
ob_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
$_GET['u'] = abs((int) $_GET['u']);
$userid = $_GET['u'];
$dbbd = $db->query("SELECT * FROM users WHERE userid=$userid");
$ir = $db->fetch_row($dbbd);

?>
    <h2>Mailbox</h2>
        <table width=100% class='table' cellspacing='1'>
            <tr>
                <td colspan='6'><hr /></td>
            </tr>
            <tr>
                <td align=center><a href='mailbox.php?a=inbox&u=<?php echo $ir['userid'];?>'>INBOX</a></td>
                <td align=center><a href='mailbox.php?a=outbox&u=<?php echo $ir['userid'];?>'>OUTBOX</a></td>
                <td align=center><a href='mailbox.php?a=compose&u=<?php echo $ir['userid'];?>'>COMPOSE</a></td>
            </tr> 
            <tr>
                <td colspan='6'><hr /></td>
            </tr>
        </table>
<?php
$_GET['a'] = array_key_exists('a', $_GET) ? $_GET['a'] : null;
switch ($_GET['a']) {
    case 'inbox': mail_inbox(); break;
    case 'outbox': mail_outbox(); break; 
    case 'compose': mail_compose(); break;
    case 'delete': mail_delete(); break;
    case 'send': mail_send(); break;
    case 'delall': mail_delall(); break;
    case 'delall2': mail_delall2(); break;
    case 'archive': mail_archive(); break;
    case 'del': mail_massdel(); break;
    case 'read': mail_view(); break;
    default: mail_inbox(); break;
}








function mail_inbox()
{
    global $db, $userid;

    echo <<<JS
<script type="text/javascript">
function setCheckboxes(formName, doCheck) {
    const form = document.forms[formName];
    if (!form) return false;

    const checkboxes = form.querySelectorAll("input[type='checkbox'][name^='del']");
    checkboxes.forEach(cb => cb.checked = doCheck);

    return true;
}
</script>
JS;

    echo <<<HTML
<p>Only the last 25 messages sent to you are visible.</p>

<form name="massdelete" method="post" action="mailbox.php?a=del">
<table width="100%" class="table" cellspacing="1" border="0">
    <tr>
        <th align="center">From</th>
        <th align="center">Sent</th>
        <th align="center">Status</th>
        <th align="center">Read</th>
        <th align="center">Delete</th>
    </tr>
HTML;

    $q = $db->query("
        SELECT 
            m.mail_id   AS mail_id,
            m.mail_time AS mail_time,
            m.mail_read AS mail_read,
            u.userid    AS userid,
            u.username  AS username,
            u.premiumdays AS premiumdays,
            u.profileimage AS profileimage
        FROM mail m
        LEFT JOIN users u ON m.mail_from = u.userid
        WHERE m.mail_to = {$userid}
        ORDER BY m.mail_time DESC
        LIMIT 25
    ");

    $i = 0;
    while ($r = $db->fetch_row($q)) {
        // Directly use associative keys
        $mail_id     = $r['mail_id'];
        $mail_time   = $r['mail_time'];
        $mail_read   = $r['mail_read'];
        $userid_from = $r['userid'];
        $username    = $r['username'];
        $premiumdays = $r['premiumdays'];
        $profileimg  = $r['profileimage'];

        $rowClass = "d" . ($i % 2);
        $i++;

        $profileImg = $profileimg 
            ? "<img src='{$profileimg}' width='30' height='30' style='vertical-align:middle;' />" 
            : "";

        $nameColor = ($userid_from == 1 || $premiumdays > 0) ? "green" : "white";

        $from = $userid_from
            ? "$profileImg <span style='color:{$nameColor};'>{$username}</span> ({$userid_from})"
            : "<span style='color:yellow; font-weight:bold;'>SYSTEM</span>";

        $sentDate = date("F j, Y, g:i:s a", $mail_time);

        $status = $mail_read == 0
            ? "<span style='color:red;'>Unread</span>"
            : "<span style='color:green;'>Read</span>";

        echo <<<ROW
    <tr class="{$rowClass}">
        <td align="center">{$from}</td>
        <td align="center">{$sentDate}</td>
        <td align="center">{$status}</td>
        <td align="center"><a href="mailbox.php?a=read&ID={$mail_id}&u={$_GET['u']}">Open</a></td>
        <td align="center">
            <a href="mailbox.php?a=delete&ID={$mail_id}&u={$_GET['u']}"><img src='../images/trash1.gif' height='20' width='20'></a>
            <input type="checkbox" name="del{$i}" value="yes" />
            <input type="hidden" name="id{$i}" value="{$mail_id}" />
        </td>
    </tr>
    <tr><td colspan="5"><hr /></td></tr>
ROW;
    }

    echo <<<HTML
    <tr>
        <td colspan="5" align="center">
            <input type="submit" name="sellmass" id="sellmass" value="Delete Selected" class="button" />
        </td>
    </tr>
</table>
</form>
HTML;
}






function mail_outbox()
{
    global $db,$ir,$c,$userid,$h,$set;
    echo "Only the last 10 messages you have sent are visible.<br />
<table width=550 cellspacing=1 class='table'><tr style='background:gray'><th>To</th><th width=60%>Message</th></tr>";
    $q = $db->query("SELECT m.*,u.* FROM mail m LEFT JOIN users u ON m.mail_to=u.userid WHERE m.mail_from=$userid ORDER BY mail_time DESC LIMIT 10");
    while ($r = $db->fetch_row($q)) {
        $sent = date('F j, Y, g:i:s a', $r['mail_time']);
        echo "<tr><td>{$r['username']} [{$r['userid']}]</td><td>{$r['mail_subject']}</td></tr><tr><td>Sent at: $sent<br /></td><td>{$r['mail_text']}</td></tr>";
    }

    echo '</table>';
}






function mail_compose()
{
    global $db, $ir, $userid;

    $smilies = [
        [":)", "smilies1/smiley1.gif", "Smile"],
        [";)", "smilies1/smiley2.gif", "Wink"],
        [":o", "smilies1/smiley3.gif", "Surprised"],
        [":D", "smilies1/smiley4.gif", "Cheesy Grin"],
        [":s", "smilies1/smiley5.gif", "Confused"],
        [":(", "smilies1/smiley6.gif", "Sad"],
        [":red", "smilies1/smiley7.gif", "Angry"],
        [":clown", "smilies1/smiley8.gif", "Clown"],
        [":bashful", "smilies1/smiley9.gif", "Embarrassed"],
        [":x", "smilies1/smiley10.gif", "Star"],
        [":green", "smilies1/smiley11.gif", "Sick"],
        [":|", "smilies1/smiley12.gif", "Bored"],
        [";(", "smilies1/smiley13.gif", "Begging"],
        [":]", "smilies1/smiley14.gif", "Smug"],
        [":horny", "smilies1/smiley15.gif", "Horny"],
        [":cool", "smilies1/smiley16.gif", "Cool"],
        [":p", "smilies1/tongue.png", "Tongue", "20", "20"],
        [":hb", "smilies1/heartbreak.gif", "Heartbreak"],
        [":3", "smilies1/love.gif", "Love"],
        [":haha", "smilies1/haha.gif", "Haha"],
    ];

    ?>
    <script type="text/javascript">
        function insert(el, ins) {
            if (el.setSelectionRange) {
                el.value = el.value.substring(0, el.selectionStart) +
                    ins +
                    el.value.substring(el.selectionEnd, el.value.length);
            } else if (document.selection && document.selection.createRange) {
                el.focus();
                var range = document.selection.createRange();
                range.text = ins + range.text;
            }
        }
    </script>

    <form action="mailbox.php?a=send&u=<?php echo $ir['userid']; ?>" method="post">
        <table width="85%" border="0">
            <tr>
                <td>Send to:</td>
                <td>
                    <?php
                    $selected = -1; // default: none selected
                    $ret = "<select name='userid'>";
                    $q = $db->query("SELECT userid, username FROM users ORDER BY username ASC");

                    $first = ($selected == -1) ? 0 : 1;

                    while ($r = $db->fetch_row($q)) {
                        $ret .= "\n<option value='{$r['userid']}'";
                        if ($selected == $r['userid'] || $first == 0) {
                            $ret .= " selected='selected'";
                            $first = 1;
                        }
                        $ret .= ">{$r['username']}</option>";
                    }
                    $db->free_result($q);
                    $ret .= "\n</select>";
                    echo $ret;
                    ?>
                </td>
            </tr>
            <tr>
                <td>Subject:</td>
                <td><input type="text" name="subject" size="60"></td>
            </tr>
            <tr>
                <td>Message:</td>
                <td>
                    <center>
                        <textarea name="message" rows="10" cols="70"></textarea><br/>
                        <?php foreach ($smilies as $smiley): ?>
                            <input type="image"
                                   src="<?= $smiley[1] ?>"
                                   alt="<?= $smiley[2] ?>"
                                   title="<?= $smiley[2] ?>"
                                   <?php if (isset($smiley[3])): ?>
                                       width="<?= $smiley[3] ?>" height="<?= $smiley[4] ?>"
                                   <?php endif; ?>
                                   onclick="insert(this.form.message,'<?= $smiley[0] ?>'); return false;" />
                        <?php endforeach; ?>
                    </center>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Send" class="button">
                </td>
            </tr>
        </table>
    </form>
    <?php
}




function mail_send()
{
    global $db, $ir, $userid;

    $msg   = $_POST['message'];
    $subj  = isset($_POST['subject']) ? $db->escape($_POST['subject']) : '';

    $codes = [
        ":)", ";)", ":o", ":D", ":s", ":(", ":red", ":clown", ":bashful",
        ":x", ":green", ":|", ";(", ":]", ":horny", ":cool", ":p", ":hb", ":3", ":haha"
    ];
    $images = [
        "<img src='smilies1/smiley1.gif' />",
        "<img src='smilies1/smiley2.gif' />",
        "<img src='smilies1/smiley3.gif' />",
        "<img src='smilies1/smiley4.gif' />",
        "<img src='smilies1/smiley5.gif' />",
        "<img src='smilies1/smiley6.gif' />",
        "<img src='smilies1/smiley7.gif' />",
        "<img src='smilies1/smiley8.gif' />",
        "<img src='smilies1/smiley9.gif' />",
        "<img src='smilies1/smiley10.gif' />",
        "<img src='smilies1/smiley11.gif' />",
        "<img src='smilies1/smiley12.gif' />",
        "<img src='smilies1/smiley13.gif' />",
        "<img src='smilies1/smiley14.gif' />",
        "<img src='smilies1/smiley15.gif' />",
        "<img src='smilies1/smiley16.gif' />",
        "<img src='smilies1/tongue.png' width='20' height='20' />",
        "<img src='smilies1/heartbreak.gif' />",
        "<img src='smilies1/love.gif' />",
        "<img src='smilies1/haha.gif' />"
    ];

    $newmsg = $db->escape(str_replace($codes, $images, $msg));
    $to     = (int) $_POST['userid'];

    // double-check columns exist in your table
    $sql = "INSERT INTO mail 
            (mail_from, mail_to, mail_time, mail_subject, mail_text, mail_read) 
            VALUES 
            ($userid, $to, UNIX_TIMESTAMP(), '$subj', '$newmsg', 0)";

    if (!$db->query($sql)) {
        echo "Query failed: " . $db->error . "<br />SQL: " . $sql;
        return;
    }

    $db->query("UPDATE users SET new_mail=new_mail+1 WHERE userid={$to}");

    echo "Message sent.<br /><a href='mailbox.php?u=" . $ir['userid'] . "'>&gt; Back</a>";
}






function mail_delete()
{
    global $db,$ir,$c,$userid,$h;
    $db->query("DELETE FROM mail WHERE mail_id={$_GET['ID']} AND mail_to=$userid");
    echo "Message deleted.<br />";
}









function mail_view()
{
    global $db, $userid;

    $id = abs((int) ($_GET['ID'] ?? 0));
    if (!$id) {
        echo "<div style='color:red;'>Invalid mail ID.</div>";
        return;
    }

    $q = $db->query("
        SELECT m.*, u.userid, u.username, u.premiumdays, u.profileimage
        FROM mail m 
        LEFT JOIN users u ON m.mail_from = u.userid 
        WHERE m.mail_id = {$id}
        LIMIT 1
    ");
    if ($db->num_rows($q) === 0) {
        echo "<div style='color:red;'>Message not found.</div>";
        return;
    }

    $r     = $db->fetch_row($q);
    $sent  = date('F j, Y, g:i:s a', $r['mail_time']);
    $from  = 'SYSTEM';
    $color = 'white';

    // Profile avatar
    $avatar = '';
    if (!empty($r['profileimage'])) {
        $avatar = "<img src='" . htmlspecialchars($r['profileimage']) . "' width='30' height='30' class='avatar' alt='avatar'> ";
    }

    // Determine username + color
    if (!empty($r['userid'])) {
        if ($r['premiumdays'] > 0 || $r['userid'] == 1) {
            $color = 'green';
        }
        $from = $avatar . "<span style='color:{$color};'>{$r['username']}</span> ({$r['userid']})";
    }

    // Mark as read if not already
    if ((int)$r['mail_read'] === 0) {
        $db->query("UPDATE mail SET mail_read=1 WHERE mail_to={$userid} AND mail_id={$id} LIMIT 1");
        $db->query("UPDATE users SET new_mail=new_mail-1 WHERE userid={$userid}");
    }

    // Output HTML
    echo <<<HTML
    <table width="100%" class="table" border="0" cellspacing="1">
        <tr>
            <td width="20%"></td>
            <td width="80%"></td>
        </tr>
        <tr>
            <td><strong>From:</strong> {$from}</td>
            <td><strong>Subject:</strong> {$r['mail_subject']}</td>
        </tr>
        <tr>
            <td><strong>Sent:</strong> {$sent}<br /><br/>
            <a href="mailbox.php?a=delete&ID={$r['mail_id']}&u={$_GET['u']}"><img src="../images/trash1.gif" height="20" width="20" alt="Delete"> Delete</a>
            </td>
            <td><strong>Message:</strong> {$r['mail_text']}
            </td>
        </tr>
    </table>
HTML;
}










function mail_delall()
{
    global $ir,$c,$userid,$h;
    echo "This will delete all the messages in your inbox.<br />
There is <b>NO</b> undo, so be sure.<br />
<a href='mailbox.php?a=delall2&u={$_GET['u']}'>&gt; Yes, delete all messages</a><br />
<a href='mailbox.php&u={$_GET['u']}'>&gt; No, go back</a><br /><br /><br />";
}
function mail_delall2()
{
    global $db,$ir,$c,$userid,$h;
    $db->query("DELETE FROM mail WHERE mail_to=$userid");
    echo "All mails in your inbox were deleted.<br />
    <a href='mailbox.php&u={$_GET['u']}'>&gt; Back</a>";
}

function mail_massdel()
{
    global $db,$ir,$c,$userid,$h;
    $counter = 1;
    $deleted = 0;
    while ($counter < 25) {
        $dodel = 'del'.$counter;
        if ('yes' == $_POST[$dodel]) {
            $delid = 'id'.$counter;
            $db->query("DELETE FROM mail WHERE mail_to=$userid AND mail_id={$_POST[$delid]}");
            ++$deleted;
        }
        ++$counter;
    }
    echo "<center>

$deleted messages deleted.
<a href=mailbox.php&u={$_GET['u']}>> Back</a>";
}

?>