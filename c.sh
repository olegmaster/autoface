path_new=/var/www/autoface/storage/app/public/data/video


for file in $path_new/*; do
	if [[ ${file##*/} = *".avi"* ]]; then
		filename=${file##*/}
		echo $path_new/$filename
		echo $path_new/${filename%.avi}"xn990154torr0"		
		
		mv $path_new/$filename $path_new/${filename%.avi}"xn990154torr0"
		
		#echo $filename
		#echo ${filename%.avi}.mp4		
	fi  
done

for file in $path_new/*; do
	if [[ ${file##*/} = *"xn990154torr0"* ]]; then
		filename=${file##*/}
		ffmpeg -i $path_new/$filename -acodec libfaac -b:a 128k -vcodec mpeg4 -b:v 1200k -flags +aic+mv4 $path_new/${filename%xn990154torr0}.mp4
		rm -rf $path_new/$filename
		echo $path_new/$filename
		echo $path_new/${filename%.avi}"xn990154torr0"		
		
		#mv $path_new/$filename $path_new/${filename%.avi}"xn990154torr0"
		
		#echo $filename
		#echo ${filename%.avi}.mp4		
	fi  
done


#ffmpeg -i cam1_18_05_2018_14_41_00.avi -acodec libfaac -b:a 128k -vcodec mpeg4 -b:v 1200k -flags +aic+mv4 output.mp4

#ffmpeg -i ${file##*/} libfaac -b:a 128k -vcodec mpeg4 -b:v 1200k -flags +aic+mv ${file##*/}.mp4
