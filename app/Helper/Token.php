<?php
namespace App\Helper;
class Token{
    private function tokenType(){
        return auth('api')->user() ?? auth('host')->user();
    }
    public function getTokenType(){
        return $this->tokenType();
    }
}
