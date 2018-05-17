<?php
/**
 * Created by PhpStorm.
 * User: alex
 * Date: 18-3-20
 * Time: 下午3:45
 */
namespace library;
class Disk_management{
    public function __construct($user_name){
        $this->user_name = $user_name;
    }

    public function create_space(){
        if(is_dir(DISK_BASE.$this->user_name)){
            printf("Space Created For %s\n", $this->user_name);
            return 1;
        }else{
            $res = @mkdir(DISK_BASE.$this->user_name);
            if(!$res){
                throw new Exception("Unauthorized for creating space, please contact admin\n");
            }else{
                printf("Space created For %s\n", $this->user_name);
                return 1;
            }
        }
    }
    
    public function get_used_size(){
        
        $path = DISK_BASE.$this->user_name;
        if(!is_dir($path)){
            throw new Exception("Space not created\n");
        }
        $res = list_files($path);
        $size = 0;
        $size = shell_exec(sprintf("cd %s && du -d 1 -h | grep '\.$'", $path));
        $size = intval(trim($size));
        printf("%sK\n", $size);
        return $size;
    }
}


