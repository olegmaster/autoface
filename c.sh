path_new=/home/pz257197/auto-face.meral.com.ua/www/storage/app/public/data/video


for number in {1..60}
do
    for file in $path_new/*; do
    	if [[ ${file##*/} = *".avi"* ]]; then
    		filename=${file##*/}
    		echo $path_new/$filename
    		echo $path_new/${filename%.avi}"xn990154torr0"

    		mv $path_new/$filename $path_new/${filename%.avi}"xn990154torr0"

    	fi
    done

    for file in $path_new/*; do
    	if [[ ${file##*/} = *"xn990154torr0"* ]]; then
    		filename=${file##*/}

    		ffmpeg -i $path_new/$filename $path_new/${filename}.ogg
    		echo ${filename}
    		mv $path_new/$filename".ogg" $path_new/${filename%xn990154torr0.ogg}".ogg"
    		rm -rf $path_new/$filename

    		#mv $path_new/$filename $path_new/${filename%.avi}"xn990154torr0"

    		#echo $filename
    		#echo ${filename%.avi}.mp4
    	fi
    done
    sleep 1
done




#ffmpeg -i cam1_18_05_2018_14_41_00.avi -acodec libfaac -b:a 128k -vcodec mpeg4 -b:v 1200k -flags +aic+mv4 output.mp4

#ffmpeg -i ${file##*/} libfaac -b:a 128k -vcodec mpeg4 -b:v 1200k -flags +aic+mv ${file##*/}.mp4
