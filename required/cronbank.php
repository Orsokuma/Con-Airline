<?php
session_start();
require "../lib/basic_error_handler.php";
//set_error_handler('../error_php');
include "../required/config.php";
define("MONO_ON", 1);
require "../class/class_db_{$_CONFIG['driver']}.php";
require_once('../required/global_func.php');
$db = new database;
$db->configure($_CONFIG['hostname'], $_CONFIG['username'],
        $_CONFIG['password'], $_CONFIG['database'], $_CONFIG['persistent']);
$db->connect();
$c = $db->connection_id;
$set = array();
$settq = $db->query("SELECT * FROM `settings`");
while ($r = $db->fetch_row($settq)) {
    $set[$r['conf_name']] = $r['conf_value'];
}
// START CRON


// Bank Loans Table Layout: SELECT `id`, `userid`, `amount`, `perday`, `bank` FROM `bankloans` WHERE 1
$loans = $db->query('SELECT * FROM bankloans');
while($bs=$db->fetch_row($loans)) {
    $loan = $bs['userid'];
    $loanid = $bs['id'];
    $amount = $bs['amount'];
    $payback = $bs['perday'];
    $paybackdis = money_formatter($bs['perday']);
    $bank = $bs['bank'];
    $fine = '500000';
    $finedis = money_formatter($fine);
    $uquery = $db->query("SELECT * FROM users WHERE userid=$loan");
    $ir = $db->fetch_row($uquery);
    
    if($amount < $payback) {
        // not enough to take loan so we'll delete it.
        $db->query("UPDATE users SET loan=0 WHERE userid=$loan"); $db->query("DELETE FROM bankloans WHERE id=".$loanid);
    } else {
        // Is the loan repaid?
        if($amount == '0') { 
            $db->query("UPDATE users SET loan=0 WHERE userid=$loan"); $db->query("DELETE FROM bankloans WHERE id=".$loanid); 
            echo 'Loan Repaid<br />';
        } else {
            if($payback > $ir['bucks']) { 
                // Fine for not enough bucks
                echo 'Not Enough Bucks to Deduct. Add Fine of '.money_formatter($fine).'<br />';
                $db->query("UPDATE bankloans SET amount=amount+$fine WHERE id=$loanid"); 
                $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$loan','out','$fine','Your bank: $bank have Fined you: $finedis. Added to your Loan.',unix_timestamp(),'bucks')");
            } else {
                // if all good we'll go ahead and deduct the loan
                echo 'Deduct Loan from '.$ir['username'].' for the amount of '.$payback.'<br />';
                $db->query("UPDATE users SET bucks=bucks-$payback WHERE userid=$loan");
                $db->query("UPDATE bankloans SET amount=amount-$payback WHERE id=$loanid");
                $db->query("INSERT INTO `money`(`userid`, `outin`, `amount`,`item`,`date`,`type`) VALUES ('$loan','out','$payback','Loan Repayment of: $paybackdis',unix_timestamp(),'bucks')");
            }
        }
    }
}
echo 'Success';





?>