# FileUpload module

1. Requirements
- nodejs
- gulp
- php

2. built-in .htaccess settings
```
php_value upload_max_filesize 1G
php_value post_max_size 1G
php_value memory_limit 1G
php_value max_input_time 3600
php_value max_execution_time 3600
```

3. folder settings
```
chmod 755 ./public/uploads
```