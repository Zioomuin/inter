<?php
/*
2. Веб
Создать PHP-страницу upload.php с формой загрузки CSV-файла
В CSV-файле должны быть 2 столбца: название файла, содержимое
Рядом с файлом upload.php требуется создать папку /upload/ и создать в ней файлы, прочитав CSV-файл.
Какие дыры это может создать? Как бороться?
Ограничений на функции и возможности PHP нет.
Пример файла CSV:
1.txt,Привет
2.log,Тест
3.html,<h1>Заголовок</h1>
*/
?>


<h1>Выберите CSV файл для загрузки</h1>
<form enctype="multipart/form-data" action="upload.php" method="post">
Отправить этот файл: <input name="inputFile" type="file" /><input type="submit" value="Отправить">
</form> 

<?php
function printErrorLoadFile($errorLoading)
{
	switch ($errorLoading) {
            case UPLOAD_ERR_INI_SIZE:
                $message = "The uploaded file exceeds the upload_max_filesize directive in php.ini";
                break;
            case UPLOAD_ERR_FORM_SIZE:
                $message = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form";
                break;
            case UPLOAD_ERR_PARTIAL:
                $message = "The uploaded file was only partially uploaded";
                break;
            case UPLOAD_ERR_NO_FILE:
                $message = "No file was uploaded";
                break;
            case UPLOAD_ERR_NO_TMP_DIR:
                $message = "Missing a temporary folder";
                break;
            case UPLOAD_ERR_CANT_WRITE:
                $message = "Failed to write file to disk";
                break;
            case UPLOAD_ERR_EXTENSION:
                $message = "File upload stopped by extension";
                break;

            default:
                $message = "Unknown upload error";
                break;
        }
	echo $message;
}

//START

if($_SERVER["REQUEST_METHOD"]!="POST"){
;//to do not see "no file" when the page loaded
//if user refresh page after message "no file" or other "no file" will be :(
} else if(!isset($_FILES['inputFile'])){
	echo "no file";
}else if($_FILES['inputFile']['error'] != 0){
	printErrorLoadFile($_FILES['inputFile']['error']);
}else if(!is_uploaded_file($_FILES['inputFile']['tmp_name'])){
	echo "Something wrong";
}else if(pathinfo($_FILES['inputFile']['name'], PATHINFO_EXTENSION)!="csv"){
	echo "Extencion of uploaded file isn't CSV. You should load CSV file";
}else {
	$handle = fopen($_FILES['inputFile']['tmp_name'], "r");
	$uploadsDirectory="upload";
	mkdir($uploadsDirectory, 0777, true);

	while($dataCsv=fgetcsv($handle)){
		//We have to check users permissions for upload file with *.php, *.html or any other extancions that we need validate
		$extencion=pathinfo($dataCsv[0], PATHINFO_EXTENSION);
		//code to test permissions. Lock php scripts in this example
		if(strncasecmp($extencion,"php",3)==0){
			echo $dataCsv[0]." was not uploaded. You don't have enough permission.";
		}else if(!($newFile = fopen($uploadsDirectory."/".$dataCsv[0], "w"))){
			echo "Can't create new file";
		}else{
			
			if (flock($newFile, LOCK_EX)){
    				ftruncate($newFile, 0); //If other thread has already filled file
   				if(!fwrite($newFile, $dataCsv[1]))
					echo "Can't write intp file!";
    				flock($newFile, LOCK_UN);
			}
			fclose($newFile);
			echo $dataCsv[0]." uploaded sucsesfully";
		}
		echo "<br>";
	}
	close($handle);
}

?>