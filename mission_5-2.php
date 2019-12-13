<?php
//データベース接続
$dsn = 'データベース名';
$user = 'ユーザー名';
$password = 'パスワード';
$pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
//データベース接続終了
//テーブル作成tbtest4
//$sql = "CREATE TABLE IF NOT EXISTS tbtest4"
//	." ("
//	. "id INT AUTO_INCREMENT PRIMARY KEY,"
//	. "name char(32),"
//	. "comment TEXT,"
//	. "pass TEXT,"
//	. "date TEXT"
//	.");";
//$stmt = $pdo->query($sql);
//テーブル作成終了
//変数の指定
$name_input = null;//最初の名前欄
$comment_input = null;//最初のコメント欄
$edit_input = null;//最初の編集番号指定
if(isset($_POST['button1'])){
//ボタン1を押したとき
	if(!empty($_POST['name'])&&!empty($_POST['comment'])){
	//name,commentデータがある時
		if(empty($_POST['edit'])){
		//editが何もない時そのままデータを入れる
		//insertを使ってデータ入力
			$sql = $pdo -> prepare("INSERT INTO tbtest4 (name, comment, pass, date) VALUES (:name, :comment, :pass, :date)");
			$sql -> bindParam(':name', $name, PDO::PARAM_STR);
			$sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
			$sql -> bindParam(':pass', $pass, PDO::PARAM_STR);
			$sql -> bindParam(':date', $date, PDO::PARAM_STR);
			$name = $_POST['name'];
			$comment = $_POST['comment'];
			$pass = $_POST['password1'];
			$date = date("Y/m/d H:i:s");
			$sql -> execute();
		}else{
		//editに番号がある時そのデータを編集する
			$name1 = $_POST['name'];
			$comment1 = $_POST['comment'];
			$pass1 = $_POST['password1'];
			$edit = $_POST['edit'];
			$sql = 'SELECT * FROM tbtest4';
			$stmt = $pdo->query($sql);
			$results = $stmt->fetchAll();
			foreach($results as $value){
				if($edit == $value['id']){
				//編集番号とデータ番号が同じ時編集
					$id = $edit;
					$name = $name1;
					$comment = $comment1;
					$pass = $pass1;
					$date = date("Y/m/d H:i:s");
					//書き込む
					$sql = 'update tbtest4 set name=:name,comment=:comment,pass=:pass,date=:date where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':name', $name, PDO::PARAM_STR);
					$stmt->bindParam(':comment', $comment, PDO::PARAM_STR);	
					$stmt->bindParam(':pass', $pass, PDO::PARAM_STR);
					$stmt->bindParam(':date', $date, PDO::PARAM_STR);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
				}else{
				//それ以外はそのまま
				}
			}
		}
	}else{
	//name,commentデータがない時データ表示
	}
//ボタン1を押したとき終了
}elseif(isset($_POST['button2'])){
//ボタン2を押したとき
	if(!empty($_POST['delete'])){
	//deleteデータがある時
		$pass=$_POST['password2'];
		$delete = $_POST['delete'];
		//データ読み込み
		$sql = 'SELECT * FROM tbtest4';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach($results as $value){
			if($delete == $value['id']){ 
			//削除番号とidが等しいとき
			$get_pass = $value['pass'];
			//パスワードを取得
				if($get_pass == $pass){
				//削除パスがパスワードと等しいとき削除
					$id = $value['id'];
					$sql = 'delete from tbtest4 where id=:id';
					$stmt = $pdo->prepare($sql);
					$stmt->bindParam(':id', $id, PDO::PARAM_INT);
					$stmt->execute();
					$get_pass=null;
				}else{
				//パスワードが違うとき
					echo "パスワードが違います".'<br/>';
					$get_pass=null;
				}
			}else{
			}
		}
	}
//ボタン2を押したとき終了
}elseif(isset($_POST['button3'])){
//ボタン3を押したとき
	if(!empty($_POST['custom'])){
	//customデータがある時
		$pass=$_POST['password3'];
		$custom=$_POST['custom'];
		//データ読み込み
		$sql = 'SELECT * FROM tbtest4';
		$stmt = $pdo->query($sql);
		$results = $stmt->fetchAll();
		foreach($results as $value){
			if($custom == $value['id']){
			//編集番号とidが等しいとき
			$get_pass = $value['pass'];
			//パスワードを取得
				if($get_pass == $pass){
				//削除パスがパスワードと等しいときデータをフォームに入れる
					$name_input = $value['name'];
					$comment_input = $value['comment'];
					$edit_input = $value['id'];
					$get_pass=null;
				}else{
				//パスワードが違うとき
					echo "パスワードが違います".'<br/>';
					$get_pass=null;
				}
			}
		}
	}else{
	//customデータがない時
	}
//ボタン3を押したとき終了
}else{
//その他
}
//selectによって表示
$sql = 'SELECT * FROM tbtest4';
$stmt = $pdo->query($sql);
$results = $stmt->fetchAll();
foreach($results as $row){
	echo $row['id'].',';
	echo $row['name'].',';
	echo $row['comment'].',';
	echo $row['date'].'<br>';
	echo "<hr>";
}
?>
<html>
<!-htmlを記述->
 <head>
  <title>
  </title>
  <meta charset="utf-8">
 </head>
<body>
<!-フォームデータの送信->
<form action="" method="post">
<!-フォーム作成->
	<p>名前：<br>
	<input type="text" name="name"value="<?php echo $name_input ?>"></p>
	<p>コメント：<br>
	<input type="text" name="comment"value="<?php echo $comment_input ?>"></p>
	<p>パスワード：<br>
	<input type="text" name="password1"value=""></p>
	<p><br>
	<input type="hidden" name="edit"value="<?php echo $edit_input ?>"></p>
	<p><input type="submit" value="送信" name="button1"></p>
	<p>削除対象番号：<br>
	<input type="text" name="delete"></p>
	<p>パスワード：<br>
	<input type="text" name="password2"value=""></p>
	<p><input type="submit" value="削除" name="button2"></p>
	<p>編集対象番号：<br>
	<input type="text" name="custom"></p>
	<p>パスワード：<br>
	<input type="text" name="password3"value=""></p>
	<p><input type="submit" value="編集" name="button3"></p>
</form>
</body>
</html>