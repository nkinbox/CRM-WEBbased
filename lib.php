<?php
class dbconnect {
private $servername = "localhost";
private $username = "root";
//private $password = "jJtEVgxt4qrV7,y";
private $password = "";
private $conn;
private $error;

public function __construct() {
try {
    $this->conn = new PDO("mysql:host=" .$this->servername. ";dbname=umh", $this->username, $this->password);
    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $this->error = false;
    }
catch(PDOException $e)
    {
    $this->error = true;
    }
}
public function lastid() {
return $this->conn->lastInsertId();
}
public function query($sql) {
$stmt = $this->conn->prepare($sql);
$stmt->setFetchMode(PDO::FETCH_ASSOC);
return $stmt;
}
public function err() {
return $this->error;
}

public function __destruct() {
$this->conn = null;
}
}


function login($username,$password) {
$encrypt = _encrypt($password);
$con = new dbconnect();
$stmt = $con->query("select employeeid from employees where deleted = 'n' and phone = :phone and password = :password limit 1;");
$stmt->bindParam(':phone', $username);
$stmt->bindParam(':password', $encrypt);
$stmt->execute();
unset($con);
$result = $stmt->fetch();
if(empty($result))
return true;
$employeeid = $result['employeeid'];
$sessionid = sha1(time() . "jgffjfgffyuugkhgui8");
if(!$encrypt)
return true;
$con = new dbconnect();
$stmt = $con->query("select sessionid, breaks, TIMESTAMPDIFF(SECOND, logout, now()) as totalbreak from logins where resume = 'y' and employeeid = :employeeid limit 1;");
$stmt->bindParam(':employeeid', $employeeid);
$stmt->execute();
unset($con);
$result = $stmt->fetch();

if(empty($result)) {
$con = new dbconnect();
$stmt = $con->query("INSERT INTO `umh`.`logins` (`sessionid`, `employeeid`) VALUES (:sessionid, :employeeid);");
$stmt->bindParam(':sessionid', $sessionid);
$stmt->bindParam(':employeeid', $employeeid);
$stmt->execute();
unset($con);
} else {
$sessionid = $result['sessionid'];
$breaks = intval($result['breaks']) + 1;
$con = new dbconnect();
$stmt = $con->query("UPDATE `umh`.`logins` SET `breaks` = :breaks, `breaktime` = :breaktime WHERE `sessionid` = :sessionid limit 1;");
$stmt->bindParam(':breaks', $breaks);
$stmt->bindParam(':breaktime', $result['totalbreak']);
$stmt->bindParam(':sessionid', $result['sessionid']);
$stmt->execute();
unset($con);
}
setcookie("sessionID",$sessionid,0,"","",false,true);
return false;
}

function pause() {
if(empty($_COOKIE['sessionID']))
return;
$con = new dbconnect();
$stmt = $con->query("UPDATE `umh`.`logins` SET `breaktime` = `breaktime` + 1 WHERE `sessionid` = :sessionid limit 1;");
$stmt->bindParam(':sessionid', $_COOKIE['sessionID']);
$stmt->execute();
unset($con);
setcookie("sessionID","",time()-3600,"","",false,true);
}

function logout() {
if(empty($_COOKIE['sessionID']))
return;
$con = new dbconnect();
$stmt = $con->query("UPDATE `umh`.`logins` SET `resume`='n' WHERE `sessionid`=:sessionid limit 1;");
$stmt->bindParam(':sessionid', $_COOKIE['sessionID']);
$stmt->execute();
unset($con);
setcookie("sessionID","",time()-3600,"","",false,true);
}

function sessioncheck() {
if(empty($_COOKIE['sessionID']))
return false;
$con = new dbconnect();
$stmt = $con->query("select employees.employeeid, employees.name, employees.groupname, employees.handler from employees inner join logins on employees.employeeid = logins.employeeid where employees.deleted = 'n' and logins.sessionid = :sessionid and logins.resume = 'y' limit 1;");
$stmt->bindParam(':sessionid', $_COOKIE['sessionID']);
$stmt->execute();
unset($con);
$result = $stmt->fetch();
if(empty($result))
return false;
$GLOBALS['employeeid']=$result['employeeid'];
$GLOBALS['name']=$result['name'];
$GLOBALS['handler']=$result['handler'];
$GLOBALS['groupname']=$result['groupname'];

$con = new dbconnect();
$stmt = $con->query("select count(chats.sno) as num from chats inner join employees on chats.toid = employees.employeeid where employees.deleted = 'n' and toid = :id and unread = 'y';");
$stmt->bindParam(':id', $GLOBALS['employeeid']);
$stmt->execute();
unset($con);
$result = $stmt->fetch();
$GLOBALS['chatnum']=$result['num'];
return true;
}


function search($query) {
if(substr($query,0,1) == "#") {
$GLOBALS['hash'] = true;
$query = substr($query,1);
$query = '%' .$query. '%';
$con = new dbconnect();
$stmt = $con->query("select * from customers where fname like :query or lname like :query or phone like :query or altphone like :query;");
$stmt->bindParam(':query', $query);
$stmt->execute();
unset($con);
$result = $stmt->fetchAll();
$GLOBALS['customers'] = $result;
return;
} else {
$GLOBALS['hash'] = false;
}
$con = new dbconnect();
$stmt = $con->query("select customers.* , employees.name from customers inner join employees on customers.addedby = employees.employeeid where customers.customerid = :query or customers.email = :query or customers.phone = :query or customers.altphone = :query limit 1;");
$stmt->bindParam(':query', $query);
$stmt->execute();
unset($con);
$result = $stmt->fetch();
$GLOBALS['customers'] = $result;
if(!empty($result)) {
$con = new dbconnect();
$stmt = $con->query("select sales.* , employees.name from sales inner join employees on sales.saleagent = employees.employeeid where sales.customerid = :customerid ;");
$stmt->bindParam(':customerid', $result['customerid']);
$stmt->execute();
unset($con);
$result = $stmt->fetchAll();
$GLOBALS['sales'] = $result;
$con = new dbconnect();
$stmt = $con->query("select comments.* , employees.name from comments inner join employees on comments.commentby = employees.employeeid where saleid = :saleid;");
$stmt->bindParam(':saleid', $saleid);
$GLOBALS['notes'] = array();
foreach($result as $s) {
$saleid = $s['saleid'];
$stmt->execute();
$GLOBALS['notes'][$saleid] = $stmt->fetchAll();
}
unset($con);
}
}


function customerdata($id) {
$con = new dbconnect();
$stmt = $con->query("select customers.* , employees.name from customers inner join employees on customers.addedby = employees.employeeid where customers.customerid = :id limit 1;");
$stmt->bindParam(':id', $id);
$stmt->execute();
unset($con);
$result = $stmt->fetch();
$GLOBALS['customers'] = $result;
}


function AEcustomer() {
if(!empty($_POST['customerid'])) {
if($GLOBALS['handler'] != "a")
return true;
$con = new dbconnect();
$stmt = $con->query("select * from customers where customerid = :id limit 1;");
$stmt->bindParam(':id', $_POST['customerid']);
$stmt->execute();
unset($con);
$result = $stmt->fetch();
$sql="";
foreach($_POST as $key=>$value) {
$value = trim($value);
if($value != $result[$key]) {
if($sql == "")
$sql = $sql. " `" .$key. "`='" .$value."'";
else
$sql = $sql. ", `" .$key. "`='" .$value."'";
}}
$con = new dbconnect();
try {
$sql = "UPDATE `umh`.`customers` SET" .$sql. " WHERE `customerid` = :id limit 1;";
$stmt = $con->query($sql);
$stmt->bindParam(':id', $_POST['customerid']);
$stmt->execute();
$GLOBALS['cid'] = $_POST['customerid'];
$error=false;
} catch(PDOException $e) {
$error = true;
}
unset($con);
return $error;
} else {
$con = new dbconnect();
try {
$password = substr($_POST['fname'],0,1).substr($_POST['lname'],0,1). "0906";
$stmt = $con->query("INSERT INTO `umh`.`customers` (`customerid`, `salutation`, `fname`, `lname`, `email`, `phone`, `altphone`, `address`, `country`, `addedby`, `password`) VALUES (:customerid, :salutation, :fname, :lname, :email, :phone, :altphone, :address, :country, :addedby, :password);");
$time = time();
$stmt->bindParam(':customerid', $time);
$stmt->bindParam(':salutation', $_POST['salutation']);
$stmt->bindParam(':fname', $_POST['fname']);
$stmt->bindParam(':lname', $_POST['lname']);
$stmt->bindParam(':email', $_POST['email']);
$stmt->bindParam(':phone', $_POST['phone']);
$stmt->bindParam(':altphone', $_POST['altphone']);
$stmt->bindParam(':address', $_POST['address']);
$stmt->bindParam(':country', $_POST['country']);
$stmt->bindParam(':addedby', $GLOBALS['employeeid']);
$stmt->bindParam(':password', $password);
$stmt->execute();
$id = $con->lastid();
$cid = "KTS" .$id.date("Y");
$stmt = $con->query("UPDATE `umh`.`customers` SET `customerid` = :cid WHERE `sno` = :id limit 1;");
$stmt->bindParam(':cid', $cid);
$stmt->bindParam(':id', $id);
$stmt->execute();
$GLOBALS['cid'] = $cid;
$error = false;
} catch(PDOException $e) {
$error = true;
}
unset($con);
return $error;
}
}

function addsale() {
if($GLOBALS['handler'] == "t")
return true;
if(empty($_POST['customerid']))
return true;
customerdata($_POST['customerid']);
if(empty($GLOBALS['customers']))
return true;
$id=0;
$con = new dbconnect();
try {
$stmt = $con->query("INSERT INTO `umh`.`sales` (`customerid`, `amount`, `chequedetails`, `chequetype`, `plan`, `saleagent`, `paymenttakenby`) VALUES (:customerid, :amount, :chequedetails, :chequetype, :plan, :saleagent, :paymenttakenby);");
$stmt->bindParam(':customerid', $_POST['customerid']);
$stmt->bindParam(':amount', $_POST['amount']);
$stmt->bindParam(':chequedetails', $_POST['chequedetails']);
$stmt->bindParam(':chequetype', $_POST['chequetype']);
$stmt->bindParam(':plan', $_POST['plan']);
$stmt->bindParam(':saleagent', $GLOBALS['employeeid']);
$stmt->bindParam(':paymenttakenby', $_POST['paymenttakenby']);
$stmt->execute();
$id = $con->lastid();
$error = false;
} catch(PDOException $e) {
$error = true;
}
unset($con);
if(!$error && !empty($_POST['comment'])) {
$comment = "Added On : " .date("Y-m-d"). "<br><br>" .$_POST['comment'];
$con = new dbconnect();
try {
$stmt = $con->query("INSERT INTO `umh`.`comments` (`saleid`, `commentby`, `comment`) VALUES (:saleid, :commentby, :comment);");
$stmt->bindParam(':saleid', $id);
$stmt->bindParam(':commentby', $GLOBALS['employeeid']);
$stmt->bindParam(':comment', $comment);
$stmt->execute();
$error = false;
} catch(PDOException $e) {
$error = true;
}
unset($con);
}
return $error;
}

function getsalesdata($sid) {
$con = new dbconnect();
$stmt = $con->query("select sales.* , employees.name, customers.salutation, customers.fname, customers.lname from sales inner join employees on sales.saleagent = employees.employeeid inner join customers on sales.customerid = customers.customerid where sales.saleid = :sid limit 1;");
$stmt->bindParam(':sid', $sid);
$stmt->execute();
unset($con);
$GLOBALS['saledata'] = $stmt->fetch();
}


function editsale() {
if($GLOBALS['handler'] != "a")
return true;
$con = new dbconnect();
$stmt = $con->query("select * from sales where saleid = :sid and customerid = :customerid limit 1;");
$stmt->bindParam(':sid', $_POST['saleid']);
$stmt->bindParam(':customerid', $_POST['customerid']);
$stmt->execute();
unset($con);
$result = $stmt->fetch();
if(empty($result))
return true;
$arr = array("amount","chequedetails","chequetype","status","plan","paymenttakenby");
$sql="";
foreach($arr as $value) {
if($_POST[$value] != $result[$value]) {
if($sql == "")
$sql = $sql. " `" .$value. "`='" .$_POST[$value]."'";
else
$sql = $sql. ", `" .$value. "`='" .$_POST[$value]."'";
}}
$con = new dbconnect();
try {
$sql = "UPDATE `umh`.`sales` SET" .$sql. " WHERE `saleid`= :sid limit 1;";
$stmt = $con->query($sql);
$stmt->bindParam(':sid', $result['saleid']);
$stmt->execute();
$error=false;
} catch(PDOException $e) {
$error = true;
}
unset($con);
return $error;
}

function addnotes() {
$comment = "Added On : " .date("Y-m-d"). "<br><br>" .$_POST['comment'];
$con = new dbconnect();
try {
$stmt = $con->query("INSERT INTO `umh`.`comments` (`saleid`, `commentby`, `comment`) VALUES (:saleid, :commentby, :comment);");
$stmt->bindParam(':saleid', $_POST['sid']);
$stmt->bindParam(':commentby', $GLOBALS['employeeid']);
$stmt->bindParam(':comment', $comment);
$stmt->execute();
} catch(PDOException $e) {
}
unset($con);
}


function employeedata($eid,$month,$year) {
$con = new dbconnect();
$stmt = $con->query("select * from employees where employeeid = :eid limit 1;");
$stmt->bindParam(':eid', $eid);
$stmt->execute();
unset($con);
$GLOBALS['employees'] = $stmt->fetch();
if(empty($GLOBALS['employees']))
return;
$con = new dbconnect();
$stmt = $con->query("select login, logout, TIMESTAMPDIFF(SECOND, login, logout) as workingtime, breaks, breaktime from logins where employeeid = :eid and year(login) = :year and month(login) = :month order by login desc;");
$stmt->bindParam(':eid', $eid);
$stmt->bindParam(':year', $year);
$stmt->bindParam(':month', $month);
$stmt->execute();
unset($con);
$tlogins = $stmt->fetchAll();
$con = new dbconnect();
$stmt = $con->query("select sales.*, customers.salutation, customers.fname, customers.lname, employees.name from sales inner join customers on sales.customerid = customers.customerid inner join employees on sales.saleagent = employees.employeeid where sales.saleagent = :eid and year(sales.saledate) = :year and month(sales.saledate) = :month order by sales.saledate desc;");
$stmt->bindParam(':eid', $eid);
$stmt->bindParam(':year', $year);
$stmt->bindParam(':month', $month);
$stmt->execute();
unset($con);
$tsales = $stmt->fetchAll();
$stats=array();
$stats['total'] = 0;
$stats['rejected'] = 0;
$stats['success'] = 0;
$stats['pending'] = 0;
$stats['totala'] = 0.00;
$stats['rejecteda'] = 0.00;
$stats['successa'] = 0.00;
$stats['pendinga'] = 0.00;
$sales=array();
$logins=array();
$i = 0;
foreach($tlogins as $key=>$val) {
$logins[$key]=array();
$logins[$key]['id'] = $key;
$logins[$key]['login'] = $val['login'];
$logins[$key]['logout'] = $val['logout'];
$logins[$key]['workingtime'] = gmdate("H", $val['workingtime']);
$logins[$key]['totalbreaks'] = $val['breaks'];
$logins[$key]['breaktime'] = gmdate("H:i", $val['breaktime']);
$logins[$key]['total'] = 0;
$logins[$key]['rejected'] = 0;
$logins[$key]['pending'] = 0;
$logins[$key]['success'] = 0;
$logins[$key]['totala'] = 0.00;
$logins[$key]['rejecteda'] = 0.00;
$logins[$key]['pendinga'] = 0.00;
$logins[$key]['successa'] = 0.00;
$start = date_create_from_format('Y-m-d H:i:s', $val['login']);
$stop = date_create_from_format('Y-m-d H:i:s', $val['logout']);
foreach($tsales as $k=>$v) {
$d = date_create_from_format('Y-m-d H:i:s', $v['saledate']);
if($start <= $d && $stop >= $d) {
$logins[$key]['total']++;
$stats['total']++;
$logins[$key]['totala'] = $logins[$key]['totala'] + floatval($v['amount']);
$stats['totala'] = $stats['totala'] + floatval($v['amount']);
switch($v['status']) {
case "p":
$logins[$key]['pending']++;
$stats['pending']++;
$logins[$key]['pendinga'] = $logins[$key]['pendinga'] + floatval($v['amount']);
$stats['pendinga'] = $stats['pendinga'] + floatval($v['amount']);
break;
case "r":
$logins[$key]['rejected']++;
$stats['rejected']++;
$logins[$key]['rejecteda'] = $logins[$key]['rejecteda'] + floatval($v['amount']);
$stats['rejecteda'] = $stats['rejecteda'] + floatval($v['amount']);
break;
case "s":
$logins[$key]['success']++;
$stats['success']++;
$logins[$key]['successa'] = $logins[$key]['successa'] + floatval($v['amount']);
$stats['successa'] = $stats['successa'] + floatval($v['amount']);
}
$sales[$i] = array();
$sales[$i]['id'] = $key;
$sales[$i]['saleid'] = $v['saleid'];
$sales[$i]['customerid'] = $v['customerid'];
$sales[$i]['amount'] = $v['amount'];
$sales[$i]['chequedetails'] = $v['chequedetails'];
$sales[$i]['chequetype'] = $v['chequetype'];
$sales[$i]['status'] = $v['status'];
$sales[$i]['plan'] = $v['plan'];
$sales[$i]['saledate'] = $v['saledate'];
$sales[$i]['saleagent'] = $v['saleagent'];
$sales[$i]['name'] = $v['name'];
$sales[$i]['paymenttakenby'] = $v['paymenttakenby'];
$sales[$i]['salutation'] = $v['salutation'];
$sales[$i]['fname'] = $v['fname'];
$sales[$i]['lname'] = $v['lname'];
unset($tsales[$k]);
$i++;
}
}
}
foreach($tsales as $k=>$v) {
$stats['total']++;
$stats['totala'] = $stats['totala'] + floatval($v['amount']);
switch($v['status']) {
case "p":
$stats['pending']++;
$stats['pendinga'] = $stats['pendinga'] + floatval($v['amount']);
break;
case "r":
$stats['rejected']++;
$stats['rejecteda'] = $stats['rejecteda'] + floatval($v['amount']);
break;
case "s":
$stats['success']++;
$stats['successa'] = $stats['successa'] + floatval($v['amount']);
}
$sales[$i] = array();
$sales[$i]['id'] = $key;
$sales[$i]['saleid'] = $v['saleid'];
$sales[$i]['customerid'] = $v['customerid'];
$sales[$i]['amount'] = $v['amount'];
$sales[$i]['chequedetails'] = $v['chequedetails'];
$sales[$i]['chequetype'] = $v['chequetype'];
$sales[$i]['status'] = $v['status'];
$sales[$i]['plan'] = $v['plan'];
$sales[$i]['saledate'] = $v['saledate'];
$sales[$i]['saleagent'] = $v['saleagent'];
$sales[$i]['name'] = $v['name'];
$sales[$i]['paymenttakenby'] = $v['paymenttakenby'];
$sales[$i]['salutation'] = $v['salutation'];
$sales[$i]['fname'] = $v['fname'];
$sales[$i]['lname'] = $v['lname'];
unset($tsales[$k]);
$i++;
}
$con = new dbconnect();
$stmt = $con->query("select comments.* , employees.name from comments inner join employees on comments.commentby = employees.employeeid where saleid = :saleid;");
$stmt->bindParam(':saleid', $saleid);
$GLOBALS['notes'] = array();
foreach($sales as $s) {
$saleid = $s['saleid'];
$stmt->execute();
$GLOBALS['notes'][$saleid] = $stmt->fetchAll();
}
unset($con);
$GLOBALS['stats'] = $stats;
$GLOBALS['logins'] = $logins;
$GLOBALS['sales'] = $sales;
}

function administrator($month,$year) {
$group = "DEVELOPER";
$con = new dbconnect();
$stmt = $con->query("select * from employees where groupname <> :group ;");
$stmt->bindParam(':group', $group);
$stmt->execute();
unset($con);
$result = $stmt->fetchAll();
$group = array();
foreach($result as $val) {
if(array_key_exists($val['groupname'],$group)) {
$group[$val['groupname']][] = $val;
} else {
$group[$val['groupname']] = array();
$group[$val['groupname']][] = $val;
}
}
$GLOBALS['group'] = $group;

$con = new dbconnect();
$stmt = $con->query("select sales.*, customers.salutation, customers.fname, customers.lname, employees.name from sales inner join customers on sales.customerid = customers.customerid inner join employees on sales.saleagent = employees.employeeid where year(sales.saledate) = :year and month(sales.saledate) = :month order by sales.saledate desc;");
$stmt->bindParam(':year', $year);
$stmt->bindParam(':month', $month);
$stmt->execute();
unset($con);
$GLOBALS['sales'] = $stmt->fetchAll();
$stats = array();
$stats['total'] = 0;
$stats['success'] = 0;
$stats['pending'] = 0;
$stats['rejected'] = 0;
$stats['totala'] = 0.00;
$stats['successa'] = 0.00;
$stats['pendinga'] = 0.00;
$stats['rejecteda'] = 0.00;

$con = new dbconnect();
$stmt = $con->query("select comments.* , employees.name from comments inner join employees on comments.commentby = employees.employeeid where saleid = :saleid;");
$stmt->bindParam(':saleid', $saleid);
$GLOBALS['notes'] = array();
foreach($GLOBALS['sales'] as $sale) {
$saleid = $sale['saleid'];
$stmt->execute();
$GLOBALS['notes'][$saleid] = $stmt->fetchAll();
$stats['total']++;
$stats['totala'] = $stats['totala'] + floatval($sale['amount']);
switch($sale['status']) {
case "s":
$stats['success']++;
$stats['successa'] = $stats['successa'] + floatval($sale['amount']);
break;
case "p":
$stats['pending']++;
$stats['pendinga'] = $stats['pendinga'] + floatval($sale['amount']);
break;
case "r":
$stats['rejected']++;
$stats['rejecteda'] = $stats['rejecteda'] + floatval($sale['amount']);
break;
}
}
unset($con);
$GLOBALS['stats'] = $stats;
}

function getemployeedata($eid) {
$group = "DEVELOPER";
$con = new dbconnect();
$stmt = $con->query("select * from employees where groupname <> :group and employeeid = :eid limit 1;");
$stmt->bindParam(':group', $group);
$stmt->bindParam(':eid', $eid);
$stmt->execute();
unset($con);
$GLOBALS['employee'] = $stmt->fetch();
}

function AEemployee() {
if($GLOBALS['handler'] != "a")
return;
if(!empty($_POST['eid'])) {
getemployeedata($_POST['eid']);
if(empty($GLOBALS['employee']))
return;
$sql="";
foreach($GLOBALS['employee'] as $key=>$value) {
if($key == "employeeid") {}
elseif($key == "password") {
if(!empty($_POST['password'])) {
if($sql == "")
$sql = $sql. " `" .$key. "`='" .sha1($_POST['password'])."'";
else
$sql = $sql. ", `" .$key. "`='" .sha1($_POST['password'])."'";
}
} elseif($value != $_POST[$key]) {
if($sql == "")
$sql = $sql. " `" .$key. "`='" .$_POST[$key]."'";
else
$sql = $sql. ", `" .$key. "`='" .$_POST[$key]."'";
}}
$con = new dbconnect();
try {
$sql = "UPDATE `umh`.`employees` SET" .$sql. " WHERE `employeeid` = :id limit 1;";
$stmt = $con->query($sql);
$stmt->bindParam(':id', $_POST['eid']);
$stmt->execute();
} catch(PDOException $e) {}
unset($con);
} else {
$password=sha1($_POST['password']);
$con = new dbconnect();
try {
$stmt = $con->query("INSERT INTO `umh`.`employees` (`name`, `email`, `phone`, `password`, `handler`, `groupname`) VALUES (:name, :email, :phone, :password, :handler, :groupname);");
$stmt->bindParam(':name', $_POST['name']);
$stmt->bindParam(':email', $_POST['email']);
$stmt->bindParam(':phone', $_POST['phone']);
$stmt->bindParam(':password', $password);
$stmt->bindParam(':handler', $_POST['handler']);
$stmt->bindParam(':groupname', $_POST['groupname']);
$stmt->execute();
} catch(PDOException $e) {
var_dump($e);
}
unset($con);
}
}


function getchatgroup() {
$con = new dbconnect();
if(strtolower($GLOBALS['groupname']) != "admin") {
$stmt = $con->query("select employeeid, name, groupname from employees where (groupname = :gname or groupname = 'admin') and deleted = 'n';");
$stmt->bindParam(':gname', $GLOBALS['groupname']);
} else {
$stmt = $con->query("select employeeid, name, groupname from employees where deleted = 'n' and groupname <> 'DEVELOPER';");
}
$stmt->execute();
unset($con);
$result = $stmt->fetchAll();
$chatgroup=array();
$con = new dbconnect();
$stmt = $con->query("select count(sno) as num from chats where unread = 'y' and toid = :toid and fromid = :fromid ;");
$stmt->bindParam(':toid', $GLOBALS['employeeid']);
$stmt->bindParam(':fromid', $fromid);
foreach($result as $val) {
$fromid=$val['employeeid'];
$stmt->execute();
$result = $stmt->fetch();
$val['num']=$result['num'];
if(array_key_exists($val['groupname'],$chatgroup)) {
$chatgroup[$val['groupname']][] = $val;
} else {
$chatgroup[$val['groupname']] = array();
$chatgroup[$val['groupname']][] = $val;
}
}
unset($con);
$GLOBALS['chatgroup']=$chatgroup;
}

function getchat() {
$con = new dbconnect();
$stmt = $con->query("select * from chats where (toid = :toid and fromid = :fromid) or (toid = :fromid and fromid = :toid);");
$stmt->bindParam(':toid', $GLOBALS['employeeid']);
$stmt->bindParam(':fromid', $_GET['id']);
$stmt->execute();
$GLOBALS['chat'] = $stmt->fetchAll();
$stmt = $con->query("UPDATE `umh`.`chats` SET `unread`='n' WHERE toid = :toid and fromid = :fromid ;");
$stmt->bindParam(':toid', $GLOBALS['employeeid']);
$stmt->bindParam(':fromid', $_GET['id']);
$stmt->execute();
unset($con);
if(empty($GLOBALS['chat']))
return true;
else
return false;
}

function _encrypt($var) {
  return sha1($var);
}

function sendmessage() {
if($GLOBALS['employeeid'] == $_POST['id'])
return true;
$con = new dbconnect();
if($GLOBALS['handler'] == "a") {
$stmt = $con->query("select employeeid from employees where employeeid = :toid limit 1;");
$stmt->bindParam(':toid', $_POST['id']);
} else {
$stmt = $con->query("select employeeid from employees where employeeid = :toid and (groupname = 'admin' or groupname = (select groupname from employees where employeeid = :fromid limit 1));");
$stmt->bindParam(':toid', $_POST['id']);
$stmt->bindParam(':fromid', $GLOBALS['employeeid']);
}
$stmt->execute();
unset($con);
$result = $stmt->fetch();
if(empty($result))
return true;
if($_FILES['attachment']['error'] == 0) {
$filename = $_FILES["attachment"]["name"];
$filedata = sha1_file($_FILES["attachment"]["tmp_name"]);
if(move_uploaded_file($_FILES["attachment"]["tmp_name"], "jvjjvyykjbcvomooybcdjki/" .$filedata)) {
$con = new dbconnect();
$stmt = $con->query("INSERT INTO `umh`.`chats` (`toid`, `fromid`, `message`, `filename`, `filedata`) VALUES (:toid, :fromid, :message, :filename, :filedata);");
$stmt->bindParam(':toid', $_POST['id']);
$stmt->bindParam(':fromid', $GLOBALS['employeeid']);
$stmt->bindParam(':message', $_POST['message']);
$stmt->bindParam(':filename', $filename);
$stmt->bindParam(':filedata', $filedata);
$stmt->execute();
unset($con);
} else {return true;}
} else {
$con = new dbconnect();
$stmt = $con->query("INSERT INTO `umh`.`chats` (`toid`, `fromid`, `message`) VALUES (:toid, :fromid, :message);");
$stmt->bindParam(':toid', $_POST['id']);
$stmt->bindParam(':fromid', $GLOBALS['employeeid']);
$stmt->bindParam(':message', $_POST['message']);
$stmt->execute();
unset($con);
}
return false;
}


function downloadfile() {
if($GLOBALS['handler'] == "a") {
$sql = "select filename from chats where filedata = :filedata limit 1;";
$con = new dbconnect();
$stmt = $con->query($sql);
$stmt->bindParam(':filedata', $_GET['id']);
$stmt->execute();
unset($con);
$result=$stmt->fetch();
if(empty($result))
return true;
$GLOBALS['filename']=$result['filename'];
return false;
} else {
$sql = "select filename from chats where (toid = :toid or fromid = :toid) and filedata = :filedata limit 1;";
$con = new dbconnect();
$stmt = $con->query($sql);
$stmt->bindParam(':filedata', $_GET['id']);
$stmt->bindParam(':toid', $GLOBALS['employeeid']);
$stmt->execute();
unset($con);
$result=$stmt->fetch();
if(empty($result))
return true;
$GLOBALS['filename']=$result['filename'];
return false;
}
}

function switchinbox($eid) {
if($GLOBALS['handler'] != "a")
return;
$con = new dbconnect();
$stmt = $con->query("select name, groupname from employees where employeeid = :eid limit 1;");
$stmt->bindParam(':eid', $eid);
$stmt->execute();
unset($con);
$result=$stmt->fetch();
if(empty($result))
return;
$GLOBALS['employeeid'] = $eid;
$GLOBALS['groupname'] = $result['groupname'];
$GLOBALS['inboxname'] = $result['name'];
$val = $eid. ":" .$GLOBALS['inboxname']. ":" .$GLOBALS['groupname'];
setcookie("inboxID",$val,0,"","",false,true);
}

function backtoinbox() {
setcookie("inboxID","",time()-3600,"","",false,true);
}
?>
