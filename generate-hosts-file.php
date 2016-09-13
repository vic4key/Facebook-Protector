<?php
file_put_contents('HOSTS', '127.0.0.1  example.com', FILE_APPEND | LOCK_EX);
echo 'Done!';
