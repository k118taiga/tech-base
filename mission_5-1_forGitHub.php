<?php
if(isset($_POST["name"]) && isset($_POST["comment"])){
	$name = $_POST["name"];
	$comment = $_POST["comment"];
}
if(isset($_POST["edita"])){
	$edita = $_POST["edita"];
}
if(isset($_POST["password"])){
	$password = $_POST["password"];
}
if(isset($_POST["delete"])){
	$delete = $_POST["delete"];
}
if(isset($_POST["passworda"])){
	$passworda = $_POST["passworda"];
}
if(isset($_POST["edit"])){
	$edit = $_POST["edit"];
}
if(isset($_POST["passwordb"])){
	$passwordb = $_POST["passwordb"];
}

#データベースへ接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$passwordq = 'パスワード';
$pdo = new PDO($dsn, $user, $passwordq, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));

#テーブルを作る
$sql = "CREATE TABLE IF NOT EXISTS mission_5_1"
." ("
. "id INT AUTO_INCREMENT PRIMARY KEY,"
. "name char(32),"
. "comment TEXT"
.");";
$stmt = $pdo->query($sql);

#書き込み
//bindParamの引数（:nameなど）は4-2でどんな名前のカラムを設定したかで変える必要がある。
if(!empty($name) && !empty($comment)){
	if($password  == "pass"){
		if(!empty($edita)){#隠しているところに番号があったら
			$id = $edita;
			$sql = 'update mission_5_1 set name=:name,comment=:comment where id=:id';
			$stmt = $pdo->prepare($sql);
			$stmt->bindParam(':name', $name, PDO::PARAM_STR);
			$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
			$stmt->bindParam(':id', $id, PDO::PARAM_INT);
			$stmt->execute();
		}else{
			$sql = $pdo -> prepare("INSERT INTO mission_5_1 (name, comment) VALUES (:name, :comment)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> execute();
		}
	}
}

#削除
if(!empty($delete)){
	if($passworda == "pass"){
		$id = $delete;
		$sql = 'delete from mission_5_1 where id=:id';
		$stmt = $pdo->prepare($sql);
		$stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();
	}
}

#編集対象の取得
if(!empty($edit)){
	if($passwordb == "pass"){
		$id = $edit;
		$sql = 'SELECT * FROM mission_5_1';
		$stmt = $pdo->query($sql);
		foreach ($stmt as $row){
			if($row['id'] == $id){
				$editname = $row['name'];
				$editcomment = $row['comment'];
			}
		}
	}
}

?>

<html>
<head>
  <meta name="viewport" content="width=320, height=480, initial-scale=1.0, minimum-scale=1.0, maximum-scale=2.0, user-scalable=yes"><!-- for smartphone. ここは一旦、いじらなくてOKです。 -->
	<meta charset="utf-8"><!-- 文字コード指定。ここはこのままで。 -->
	<title>掲示板の練習</title>
</head>

<body>
好きなアーティスト

<form action="mission_5-1.php" method="post">
	<新規投稿フォーム><br>
	名前：<input type="text" name="name" value = "<?php if (!empty ($editname)){ echo $editname;} ?>"><br>
	コメント：<input type="text" name="comment" value = "<?php if (!empty ($editcomment)){ echo $editcomment;} ?>"><br>
	<input type="hidden" name="edita" value = "<?php if (!empty ($edit)){ echo $edit;} ?>"><br>
	パスワード：<input type="text" name="password"><br>
	<input type="submit" value="送信"><br>
	<削除フォーム><br>
	削除対象番号：<input type="text" name="delete"><br>
	パスワード：<input type="text" name="passworda"><br>
	<input type="submit" value="削除"><br>
	<編集フォーム><br>
	編集対象番号：<input type="text" name="edit"><br>
	パスワード：<input type="text" name="passwordb"><br>
	<input type="submit" value="編集">

</form>

</body>
</html>

<?php
#表示
//$rowの添字（[ ]内）は4-2でどんな名前のカラムを設定したかで変える必要がある。
$sql = 'SELECT * FROM mission_5_1';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach ($results as $row){
	//$rowの中にはテーブルのカラム名が入る
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].'<br>';
echo "<hr>";
}
?>
