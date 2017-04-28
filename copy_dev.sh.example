BASEDIR=$(dirname "$0")

admin_l5=$BASEDIR"/../admin-l5";
batch_service=$BASEDIR"/../batch-service";
content_service=$BASEDIR"/../content-service";
payment_service=$BASEDIR"/../payment-service";
www_l5=$BASEDIR"/../www-l5";

# Define your function here
copy_src_to_vendor () {
   if [ -d $1 ]; then
		rm -rf $1"/vendor/publy/publy-service-client"
		mkdir $1"/vendor/publy/publy-service-client"
		cp -r $BASEDIR"/"* $1"/vendor/publy/publy-service-client"

		echo "cp "$BASEDIR"/* "$1"/vendor/publy/publy-service-client"
	fi
}

copy_src_to_vendor $admin_l5
copy_src_to_vendor $batch_service
copy_src_to_vendor $content_service
copy_src_to_vendor $payment_service
copy_src_to_vendor $www_l5

echo 'finish'