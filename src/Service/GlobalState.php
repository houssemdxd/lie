<?php
namespace App\Service;

class GlobalState
{
    private  static  $playerId=0;
    private static $roomId =0;

    public static  function setPlayerId($playerIdr)
    {
       $playerId = $playerIdr;
    }

    public static function getPlayerId()
    {
        return self::$playerId;
    }

    public function setRoomId($roomId)
    {
        $this->roomId = $roomId;
    }

    public function getRoomId()
    {
        return $this->roomId;
    }
}
