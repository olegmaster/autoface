convertAviIntoOgg(){
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

    	fi
    done
}

base_path=/home/pz257197/auto-face.meral.com.ua/www/storage/app/public/data

for dir in $base_path/*; do
	path_new=$dir/video;
	echo $path_new
	convertAviIntoOgg;
done