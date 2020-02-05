<html>
    <form action=" mission_5-2.php " method="post">
 
 <h3>【理想の休日の過ごし方を教えてくださいませ。】</h3>
 


 <input type="text" name="delete" placeholder="削除したい番号" ><br>
 <input type="text" name="passd" placeholder="パスワード" >
 <input type="submit" value="削除する"><br><br>
 
 <input type="text" name="edit" placeholder="編集したい番号" ><br>
 <input type="text" name="passe" placeholder="パスワード" >
 <input type="submit" value="編集する"><br><br>
    


<?php
//mysql接続----------------------------------------------------------------------------------------------
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//---------------------------------------------------------------------------------------------------------

//編集受け取り--------------------------------------------------------------------------------------
if(!empty($_POST["edit"])and !empty ($_POST["passe"])){
	
	$edit=$_POST["edit"];
	$passe=$_POST["passe"];
	
	
	$sql = 'SELECT * FROM tba';
	$stmt = $pdo->query($sql);
	$results = $stmt->fetchAll();
	foreach ($results as $row){
		if($row['id']==$edit and $row['pass']==$passe){
			$editnum=$row['id'];
			$editname=$row['name'];
			$editcomment=$row['comment'];							
			$editpass=$row['pass'];
		}else{
			$editnum="";
			$editname="";
			$editcomment="";							
			$editpass="";
	
		}
	}
}else{
	echo "";
}
//----------------------------------------------------------------------------------------------



?>
 <input type="text" name="name" placeholder="名前" value = "<?php if(!empty($edit) and !empty ($passe)){ echo $editname;}else{echo "";}?>">
 <input type="text" name="comment" placeholder="コメント" value = "<?php if(!empty($edit) and !empty ($passe)){echo $editcomment;}else{echo "";}?>" >
 <input type="hidden" name="textbox" value = "<?php if(!empty($edit)and !empty ($passe)){ echo $editnum;}else{echo "";}?>" ><br>
 <input type="text" name="pass" placeholder="パスワード" /*value = "<?php if(!empty($edit)and !empty ($passe)){echo $editpass;}else{echo "";}?>"*/ >
 <input type="submit" value="送信する"><br><br>


<?php
//編集or投稿------------------------------------------------------------------------------------------------------
//編集-----------------------------------------------------------------------------------------------------------
if(!empty($_POST["textbox"])){
	$id = $_POST["textbox"]; //変更する投稿番号
	$name = $_POST["name"];
	$comment =$_POST["comment"] ; 
	$pass =$_POST["pass"] ;
	$sql = 'update tba set name=:name,comment=:comment,pass=:pass where id=:id';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':name', $name, PDO::PARAM_STR);
	$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
	$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->execute();

//-------------------------------------------------------------------------------------------------------------------
}else{
//投稿----------------------------------------------------------------------------------------------------------------
	//ファイルの存在分岐（通常投稿or新規投稿）--------------------------------------------------------------------
	if(!empty($_POST["name"])and!empty($_POST["comment"])and !empty ($_POST["pass"])){

		//デ―タ入力(投稿(新規＆通常))--------------------------------------------------------------------------------------------------
		$insert = $pdo -> prepare("INSERT INTO tba (name, comment,pass,date) VALUES (:name, :comment, :pass,:date)");
		$insert -> bindParam(':name', $name, PDO::PARAM_STR);
		$insert -> bindParam(':comment', $comment, PDO::PARAM_STR);
		$insert -> bindParam(':pass', $pass, PDO::PARAM_STR);
		$insert -> bindParam(':date', $date, PDO::PARAM_STR);

		$name = $_POST["name"];
		$comment = $_POST["comment"];
		$pass = $_POST["pass"];
		$date =date("Y/m/d H:i:s");
		$insert -> execute();
		//-------------------------------------------------------------------------------------------------------------

	}else{
		echo"";
	}
	//-----------------------------------------------------------------------------------------------------------------
}
//----------------------------------------------------------------------------------------------------------------------------


//削除--------------------------------------------------------------------------------------------------------
if(!empty($_POST["delete"])and !empty ($_POST["passd"])){
	
	$id = $_POST["delete"];
	$passd=$_POST["passd"];
	$sql = 'delete from tba where id=:id and pass=:pass';
	$stmt = $pdo->prepare($sql);
	$stmt->bindParam(':id', $id, PDO::PARAM_INT);
	$stmt->bindParam(':pass', $passd, PDO::PARAM_STR);
	
	$stmt->execute();
}else{   
	echo "";
}
//--------------------------------------------------------------------------------------------------------



//デ―タ表示----------------------------------------------------------------------------------------------------
$display = 'SELECT * FROM tba';
$stmt = $pdo->query($display);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].'<br>';
	echo "<hr>";
	}
//-------------------------------------------------------------------------------------------------------------
?>
</form>
</html>