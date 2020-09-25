<?php 
class openapi_test
{
    public function pageIndex()
    {
        phpinfo();
    }

    public function pageUpload()
    {
        $f = '/home/vagrant/work/choujiang/www/upload/1.png';
        $r = utility_file::upload($f);

        echo "<pre>";
        print_r($r);
    }
}