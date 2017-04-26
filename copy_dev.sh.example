admin_l5="./../admin-l5";
content_service="./../content-service";
payment_service="./../payment-service";
www_l5="./../www-l5";

# Define your function here
copy_src_to_vendor () {
   if [ -d $1 ]; then
		rm -rf $1"/vendor/publy/publy-service-client"
		mkdir $1"/vendor/publy/publy-service-client"
		cp -r * $1"/vendor/publy/publy-service-client"

		echo "copy "$1
	fi
}

copy_src_to_vendor $admin_l5
copy_src_to_vendor $content_service
copy_src_to_vendor $payment_service
copy_src_to_vendor $www_l5

echo 'finish'