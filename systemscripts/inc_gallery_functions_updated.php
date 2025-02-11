<?php


function RelocateUploadedDemoPhotos($BaseDirectory, $PhotoMoveCount = -1)
{
$BaseDir =  $BaseDirectory . 'Gallery/'; //'D:/Shared Data/SERVER PHOTOS/Demo Photos/';
$UploadDir = $BaseDirectory . 'upload';

$Files = ListFiles($UploadDir, '.jpg');
//print_r($Files);//exit;
$FilesMoved['Count'] = 0;
$FilesMoved['Dirs'] = array();
foreach ($Files as $F)
{

 	

	$MovedInfo = MoveGalleryFile($F, $BaseDir);
	//$MovedInfo['FileMoved'] = false;
	//echo 'dont move';
	//print_R($MovedInfo);
  	$FilesMoved['Count']++;
  if ($MovedInfo['FileMoved'])
  {
  	$FilesMoved['Dirs'][$MovedInfo['FileDir']] = $MovedInfo['FileDir'];
  }
  
  if ($FilesMoved['Count'] == $PhotoMoveCount)
  {
  	break;
  }
}

	
		foreach($FilesMoved['Dirs']  as $Dir){
			//After moving files to specific location, call autorotate on them.
	  		//ExecuteAutoRotate(rtrim($Dir,'\\' ));
		
		}
	

	return $FilesMoved['Count']; 

}
function MoveGalleryFile($File, $NewBaseDir)
{
	//echo $File . "<P>\n <PRE>";
  $Filename = basename($File);
  $fInfo = stat($File);
 // print_R($fInfo); 
  
  $PhotoTaken = DatePhotoTaken($File);
 // echo $PhotoTaken;
  $DateInfo = getdate(strtotime($PhotoTaken));
  //print_R($DateInfo);
  $Courses =  API_CurrentRunningCourses(date('Ymd',strtotime($PhotoTaken)), array('CourseTypes' => '0,1'));
  //print_r($Courses);
  //exit;
	//Sort 12 Week First.
  uasort($Courses, 'orderbycourseWeight');
  $UseCourse = $Courses[0];
  
  //print_r($Courses);
  if ($UseCourse['CourseType'] == 1){
    $CourseDesc = '12 Week '.date('M',strtotime($UseCourse['FromDate'])); 	
  }
  else{
  	$CourseDesc = $UseCourse['CourseName']; //sanitize(Trim($UseCourse['CourseName']));
  }
  
  $FileMoved = false;
  //	echo "UseCourse : $UseCourse[CourseName]<P>\n";
 

  

       
       $FolderName = date('D jS M Y',strtotime($PhotoTaken));
       $DateInfo['DayDate'] = $FolderName;
      	if ($UseCourse['CourseType'] == 1)
      	{
  			$DirLink = $NewBaseDir . "$DateInfo[year]/$CourseDesc/Week$UseCourse[TodayIsWeek]/$FolderName/";
  			$DateInfo['Week'] = $UseCourse['TodayIsWeek'];
  		}
  		else
  		{
  		
		  		if ($UseCourse['CourseDuration'] < 1.5)
		  		{
		  			$DirLink = $NewBaseDir . "$DateInfo[year]/$CourseDesc/";
		  		}
		  		else
		  		{
		  			$DirLink = $NewBaseDir . "$DateInfo[year]/$CourseDesc/$FolderName/";
		  		}
  		}
			

  
  			
  			if (!file_exists($DirLink))
  			{
  				$CreateSuccess = mkdir( $DirLink, 0777, true);  
  				if (!$CreateSuccess){
  					file_put_contents('filelog2.txt', date('Ymd H:i:s') . ' Unable to MKDIR - <P>BaseFile:'.  $File . "\n<BR> ------------- " . $DirLink . $Filename . "\n", FILE_APPEND);   
  				}
  			}
  			
  			//Make sure file doesnt already exist.
  			if (!file_exists($DirLink . $Filename)){
  			
	  			//Move File
	  			if (rename ($File, $DirLink . $Filename))
	  			{ 
	  				//echo "rename ($File, $DirLink . $Filename); ";
	  				$FileMoved  = true;
	  				file_put_contents('movefile.txt', $DirLink . $Filename, FILE_APPEND);
					}
					
	
					WriteDirectoryFile($DirLink, $Courses, $DateInfo);
				}
				
	$Info['FileMoved'] =  $FileMoved;				
	$Info['FileDir'] =  $DirLink;
				
		return $Info;

}
function orderbycourseWeight($item1, $item2)
{
	//Order by 12 Week, then short course then anything else.
	if ($item1['CourseType'] == 1) {return -1;};
	if ($item2['CourseType'] == 1) {return $item1['CourseType'] - $item2['CourseType'];};

	if ($item1['CourseType'] == 0 and $item2['CourseType'] > 1) {return -1;};	
	if ($item2['CourseType'] == 0 and $item1['CourseType'] > 1) {return 1;}	
}

function DatePhotoTaken($filename){

	$exif_date = '';

	$path = pathinfo($filename);//
	//print_R($path);
	if (strtolower($path['extension'] ) == 'jpg'){


		$exif_data = exif_read_data ($filename);
		if (!empty($exif_data['DateTimeOriginal'])) {
	    	$exif_date = $exif_data['DateTimeOriginal'];
	    	return $exif_date;
		}
	}
	//print_r($exif_data);
	//echo $filename;
	if ($exif_date == ''){
	  	$fInfo = stat($filename);
		  $D = getdate($fInfo['mtime']);
		  return "$D[year]-$D[month]-$D[mday] $D[hours]:$D[minutes]:$D[seconds]";
	}


}


function ListFiles($dir, $FileMatch = '*') {

    if($dh = opendir($dir)) {

        $files = Array();
        $inner_files = Array();

        while($file = readdir($dh)) {
            if($file != "." && $file != ".." && $file[0] != '.') 
            {
            		//echo $file . $FileMatch;
	
                if(is_dir($dir . "/" . $file)) 
                {
                		
                    $inner_files = ListFiles($dir . "/" . $file,$FileMatch);
                    if(is_array($inner_files)) 
                    {
                      $files = array_merge($files, $inner_files);
                    } 
                } 
                else 
                {       
   									if ($FileMatch == '*' or stripos($file, $FileMatch) != false ){             
                    array_push($files, $dir . "/" . $file);
                    }
                }
              
            }
        }

        closedir($dh);
        return $files;
    }
}

/* This function uses a autorotate app http://pilpi.net/software/JPEG-EXIF_autorotate.php*/
function ExecuteAutoRotate($Dir)
{

	$cmd = " Call \"C:\\Program Files\\JPEG-EXIF_autorotate\\autooperatedir_recursive.bat\" \"C:\\Program Files\\JPEG-EXIF_autorotate\\jhead\" \"$Dir\" \"C:\\Program Files\\JPEG-EXIF_autorotate\" -autorot";
	echo '<PRE>' . $cmd;
	exec($cmd, $out);
	print_R($out);
}



function WriteDirectoryFile($DirPath, $Courses, $DateDetails)
{
	$FullFN = $DirPath  . 'dirdetails.txt';
		$filedetails = array();
		/*if (file_exists($FullFN)){
			$filedetails  = parse_ini_file($FullFN);
		}*/

		$filedetails['Week'] = @$DateDetails['Week'];
		$filedetails['DemoDate'] = $DateDetails['DayDate'];		
		
		
		foreach($Courses as $C){
			
			$filedetails['ID_'.$C['CourseID']] = $C['CourseID'];
			$filedetails['NAME_'.$C['CourseID']] = $C['CourseName'];
			
		
		}
		
		//print_r($filedetails);
		
		$filetext = '';
		foreach($filedetails as $key => $line){
		
			$filetext .= "$key=$line\n";
		
		}
		
		file_put_contents($FullFN,$filetext);
		
		
		
		//SetSetting('DEMOPHOTOS_' . date('Ymd',$DateDetails[0]), array('dir' => $DirPath) );
		
}

function API_CurrentRunningCourses($Dte, $Options){

	$Running_JSON =	file_get_contents('http://api.cookingisfun.ie/courses/running/' . $Dte . '/' . $Options['CourseTypes']);
	$Running = json_decode($Running_JSON,true);
	return $Running['courses'];
}

?>