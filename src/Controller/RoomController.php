<?php
namespace App\Controller;
use App\Entity\Table;
use App\Repository\HandRepository;
use App\Repository\PlayerRepository;
use App\Repository\RoomRepository;
use App\Repository\RoundRepository;
use App\Repository\TableRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse; // Import JsonResponse
use Symfony\Component\HttpFoundation\RedirectResponse;
use App\Service\GlobalState;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CardsRepository;
use App\Repository\TimeRepository;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use App\Entity\Cards;
use App\Entity\Player;
use App\Entity\Room;
use App\Entity\Round;
use App\Entity\Hand;
use App\Entity\Time;



class RoomController extends AbstractController
{
    private $globalState;

    public function __construct(GlobalState $globalState)
    {
        $this->globalState = $globalState;
    }


    #[Route ('/roomspesification',name:"app_romm_spesification")]
    public function spesification(Request $request)
    {






        return $this->render('room/roomspec.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }

    #[Route('/verify', name: 'app_verify')]
    public function verifyPlayer(Request $request, PlayerRepository $playerRepository): RedirectResponse
    {
        // Get roomId and playerId from the request (assuming they are passed as query parameters)
        $roomId = $request->query->get('roomId');
        $playerId = $request->query->get('playerId');
        
        // Check if the player exists in the database
        $player = $playerRepository->findOneBy(['id' => $playerId, 'room' => $roomId]);

        if ($player) {
            // If the player exists, redirect to the game function
            return $this->redirectToRoute('app_game', [
                'id' => $roomId,
                'player_token' => $playerId,
            ]);
        } else {
            // If the player does not exist, redirect to the join function
            return $this->redirectToRoute('app_join', [
                'roomId' => $roomId,
            ]);
        }
    }












    #[Route('/lies', name: 'app_home')]
    public function index(): Response
    {
       
        return $this->render('room/home.html.twig', [
            'controller_name' => 'RoomController',
        ]);
    }




#[Route('/join', name: 'app_join')]
public function join(Request $request,HubInterface $hub,RoomRepository $roomRepository,EntityManagerInterface $em ,PlayerRepository $playerRepository,GlobalState $globalState)
{
    $playerId = $request->query->get('playerId');
     
    $roomId = $request->query->get('roomId'); 

    // Check for null or empty values
if (empty($roomId)) {
    return new JsonResponse(["message" => "Player ID or Room ID is null or undefined"], Response::HTTP_BAD_REQUEST);
}

    



    


    $room = $roomRepository->find($roomId);
   
   

    $player=$playerRepository->findOneBy(["id"=>$playerId,"room"=>$room]);
    if ($player)
    {
        return new JsonResponse([
            'playerId' => "999x",
            
        ]);
    }
 #verify th room if full or not 
 if($room)
 {

 if($room->isReady()==true)
 {
     return new JsonResponse(["message"=>"this room is full",Response::HTTP_UNAUTHORIZED]);

 }
 }
 else
 {
    return new JsonResponse(["message"=>"this is invalid room ",Response::HTTP_NOT_FOUND]);


 }

        $p = new Player();
       $room=$roomRepository->findOneBy(["id"=>$roomId]);
       $p->setRoom($room);
       $p->setWinner(0);
       $p->setCode("kkkkk");
        $p->setState(0);
       $em->persist($p);
       $em->flush();
       $playerId=$p->getId();
       $players=$playerRepository->findBy(["room"=>$room]);
        $numberOfActiveParticipant = count($players);
        if($numberOfActiveParticipant==$room->getNumberParticipant())
        {
            $room->setReady(true);
            $em->persist($room);
            $em->flush();

            $update = new Update(
                "/".$room->getId()."/ready",
                json_encode(['ready' =>true])
            );
        
            $hub->publish($update);
        


        }

        if($room && $p)
        {
            return new JsonResponse([
                'playerId' => $playerId,
                'roomId' => $roomId,
                'state Room'=>$room->isReady(),
                'sucess' => 100,
            ]);

        }
else {


    return new JsonResponse([
        'roomId'=>'1x1x' ,
    ]);
}
    
}



    #[Route('/createRoom/{nb}', name: 'app_createroom')]
    public function createRoom( $nb,RoomRepository $roomRepository,EntityManagerInterface $em): Response
    {   
         #step 1
        $room = new Room();
        $room->setNumberParticipant($nb);
        $room->setCode("555");
        $room->setName("room1");
        
        $em->persist($room);
        $em->flush();

        #step 2 :
        #extract the new inserted room id 

        $roomId=$room->getId();
        
        $room=$roomRepository->findOneBy(["id"=>$roomId]);


        $player = new Player();
        $player->setCode("181818");
        $player->setRoom($room);
        $player->setState(1);
        $player->setWinner(0);
        $em->persist($player);
        $em->flush();
        $playerId = $player->getId();

        for ($i=1;$i<11;$i++)
        {
         $card=new Cards();
         $card->setCard($i);
         $card->setNumber(4);
         $card->setRoom($room);
         $em->persist($card);
         $em->flush();
        }

       
        return new JsonResponse([  
            'id' => $roomId,
            'player_token' => $playerId,
        ]);
    }

 
 
    #[Route('/game/{id}/{player_token}', name: 'app_game')]
    public function game($id,$player_token ,RoomRepository $roomRepository, CardsRepository $cardsRepository, PlayerRepository $playerRepository,EntityManagerInterface $em): Response
    {  
        // Retrieve the room by ID
        $room = $roomRepository->findOneBy(['id' => $id]);
        if (!$room)
        {
            return new JsonResponse(["message"=>"this room is invalid",Response::HTTP_NOT_FOUND]);

        }
       
        // Retrieve the list of cards for the specific room
        $cards = $cardsRepository->findBy(['room' => $room]);
    
        // Ensure there are cards available
        $sum=0;
        foreach ($cards as $card) {
           $sum=$card->getNumber()+$sum;
        }


        if ($sum < 10) {
            throw new \Exception('Not enough cards available in the room');
        }
    
        $selectedCards = [];
        $numberCard=0;
        if($room->getNumberParticipant()==2)
        {
         
            $numberCard=20;
        }
        elseif ($room->getNumberParticipant()==3)
        {
            $numberCard=13;
        }
        else
        {
            $numberCard=10;


        }

        // Continue selecting until we have 10 valid cards
        while (count($selectedCards) < $numberCard) {
            // Get a random index from the cards array
            $randomKey = array_rand($cards);
            $selectedCard = $cards[$randomKey];
            // Check if the selected card has a quantity greater than 0
            if ($selectedCard->getNumber() > 0) {
                // Add the card to the selected list
                $selectedCards[] = $selectedCard;
                // Reduce the quantity of the selected card by 1
                $selectedCard->setNumber($selectedCard->getNumber() - 1);
                // Persist the change in quantity
                $em->persist($selectedCard);
    
                // If the quantity of this card reaches 0, remove it from the list to prevent re-selection
                if ($selectedCard->getNumber() == 0) {
                    unset($cards[$randomKey]);
                }
            }
        }
    
        // Save the changes to the database
        $em->flush();



        //delete redandant cards 
        
        for($i=0;$i<count($selectedCards);$i++)
        {   $nb=0;
        for($j=0;$j<count($selectedCards);$j++)
        { 
                if($selectedCards[$i]== $selectedCards[$j])
                {
                    $nb++;

                }

        }
        if ($nb==4)
        {  $removedCard=$selectedCards[$i]->getCard();  

            for ($k = 0; $k < count($selectedCards); $k++) {
                if ($selectedCards[$k]->getCard() == $removedCard) {
                   $selectedCards[$k]->setCard(-1);
                }
            }

        }


    }

        //end of verifcation 


        for ($i = 0; $i < count($selectedCards); $i++) {
            $hand = new Hand();
        
            if($selectedCards[$i]->getCard() != -1)
            {
            // Assuming $selectedCards is an array of Cards objects
            $hand->setCard($selectedCards[$i]->getCard());
        
            // Assuming $player_token is a Player object

            $player=$playerRepository->findOneBy(["id"=>$player_token]);
            $hand->setPlayer($player);
        
            $em->persist($hand);
        }
        }
        
        // Flush after the loop to optimize database transactions
        $em->flush();
        

    
        return $this->redirectToRoute('real', [
            'player_token' => $player_token,
            'id' =>$id
        ]);    
    
    }
    
    #[Route('/realgame/{id}/{player_token}', name: 'real')]
    public function Realgame($id,$player_token,TableRepository $tableRepository,TimeRepository $timeres,RoomRepository $roomRepository, CardsRepository $cardsRepository, PlayerRepository $playerRepository,HandRepository $handRepository,RoundRepository  $roundRepository,EntityManagerInterface $em): Response
    {  

        $room=$roomRepository->find($id);
        $goalroom=$room->getGoal();
        $player=$playerRepository->find($player_token);
        $players=$playerRepository->findBy(["room"=>$room]);
        $cards=$handRepository->findBy(["player"=>$player]);
        //to get the last cards putted 
        $round=$roundRepository->findoneBy(["room"=>$room ] , ['id' => 'DESC']);
        $roundBlocked=false;
        if($round)
        {
        if ($round->getBlock()==1)
        {
            $roundBlocked=true;
        }
        }
        else{
            $roundBlocked=true;
   
        }
        $rounds=$roundRepository->findBy(["room"=>$room ]);
        $nb=0;
        foreach ($rounds as $round) {
            // Fetch tables associated with each round
            $tables =$tableRepository->findBy(['round' => $round]);
            $nb=$nb+count($tables);
        }

        $turnearned=false;
        if(count($rounds)==0)
        {
            $turnearned=true;
        }






        // Get the last inserted time for the room
        $ok=false;

    $lastTimeEntry = $timeres->findOneBy(['room' => $room], ['id' => 'DESC']);
        if($lastTimeEntry)
        {
    $lastInsertedTime = $lastTimeEntry->getTimeround();
    $currentTime = new \DateTime();

    // Compare current time with the last inserted time
    if ($currentTime >= $lastInsertedTime) {
        $ok=true;
    }
    if($lastTimeEntry->isLie()==true)
    {
    $ok=true;
    }


        }
        else
        {
            $ok=true;
        }


    $state=$player->getState();

    $lie=false;
    // In your controller or service where you need the last round
    $lastRound = $roundRepository->findOneBy([], ['id' => 'DESC']);
    
    if ($lastRound) {
        $lastPlayer = $lastRound->getPlayer();  // Get the player from the last round
        if($lastPlayer->getId()!=$player_token)
        {
            $lie=true;
        }


    }
        $ready=false;
        if($room->isReady())
        {
            $ready=true;
        }

      $times = $timeres->findBy(["room"=>$room]);
      $first=false;
      if(count($times)==0)
      {$first=true;}


        return $this->render('room/game.html.twig', [
            'players' => $players,
            'rommid'=>$id,
            'cards'=>$cards,
            'player'=>$player,
            'state'=> $state,
            'ok'=>$ok,
            'lie'=>$lie,
            'nb'=>$room->getNumberParticipant(),
            'ready'=> $ready,
            'table'=> $nb,
            'first'=>$first,
            'earn'=>$turnearned,
            'roundBlocked'=>$roundBlocked,
            'playerIdt'=>$player->getId(),
            'goal'=>$goalroom
        ]);
    }







    #[Route('/put/{id}/{goal}', name: 'app_put')]
    public function put(
        $id, 
        $goal,
        TableRepository $tableRepository,
        HubInterface $hubInterface,
        Request $request,
        TimeRepository $times,
        HandRepository $handRepository,
        RoomRepository $roomRepository,
        PlayerRepository $playerRepository,
        RoundRepository $roundRepository,
        EntityManagerInterface $em
    ) {
        $em->getConnection()->beginTransaction(); // Start transaction
        try {
            // Extract the player
            $player = $playerRepository->find($id);
            if (!$player) {
                return new JsonResponse(['status' => 'Player not found'], Response::HTTP_NOT_FOUND);
            }
    
            $room = $player->getRoom();
            if ($room->isReady() == false) {
                return new JsonResponse(['status' => 'Room is not ready'], Response::HTTP_NOT_ACCEPTABLE);
            }
    
            if ($player->getWinner() == 1) {
                return new JsonResponse(["message" => "You are already a winner"], Response::HTTP_UNAUTHORIZED);
            }
    
            if ($player->getState() == 0) {
                return new JsonResponse(["message" => "It's not your turn"], Response::HTTP_UNAUTHORIZED);
            }
    
            // Get cards from request
            $data = json_decode($request->getContent(), true);
            if (!isset($data['cards']) || !is_array($data['cards'])) {
                return new JsonResponse(['status' => 'Invalid cards data'], Response::HTTP_BAD_REQUEST);
            }
            $cards = $data['cards'];
    
            // Winner declaration logic
            $rounds = $roundRepository->findBy(["room" => $room]);
            if (count($rounds) > 0) {
                $previousRound = $rounds[count($rounds) - 1];
                $winnerPlayer = $previousRound->getPlayer();
                $handwinner = $handRepository->findBy(["player" => $winnerPlayer]);
    
                if (count($handwinner) == 0) {
                    $nb = 0;
                    foreach ($playerRepository->findBy(["room" => $room]) as $p) {
                        if ($p->getWinner() == 1) {
                            $nb++;
                        }
                    }
    
                    $winnerPlayer->setWinner(1);
                    $winnerPlayer->setRank($nb + 1);
                    $em->persist($winnerPlayer);
                    $em->flush();
    
                    // Publish the win update
                    $winningPlayers = array_map(fn($p) => $p->getId(), $playerRepository->findBy(["room" => $room, "winner" => 1]));
                    $hubInterface->publish(new Update(
                        '/' . $room->getId() . '/win',
                        json_encode(['winningPlayerIds' => $winningPlayers])
                    ));
                }
            }
    
            // Update player states and determine the next round player
            $players = $playerRepository->findBy(["room" => $room]);
            $currentPlayerIndex = array_search($player, $players);
            $nextPlayerIndex = ($currentPlayerIndex + 1) % count($players);
            $foundNextPlayer = false;
    
            while (!$foundNextPlayer) {
                $nextPlayer = $players[$nextPlayerIndex];
                if ($nextPlayer->getWinner() != 1 && $nextPlayer->getState() != 1) {
                    $nextPlayer->setState(1);
                    $em->persist($nextPlayer);
                    $foundNextPlayer = true;
                }
                $nextPlayerIndex = ($nextPlayerIndex + 1) % count($players);
            }
    
            foreach ($players as $p) {
                if ($p !== $nextPlayer) {
                    $p->setState(0);
                    $em->persist($p);
                }
            }
    
            $em->flush(); // Ensure all state updates are committed
    
            // Create a new round and process the cards
            $round = new Round();
            $round->setPlayer($player);
            $round->setRoom($room);
            $round->setBlock(0);
            $em->persist($round);
    
            foreach ($cards as $card) {
                $selectedCard = $handRepository->findOneBy(["player" => $player, "card" => $card]);
                if ($selectedCard) {
                    $em->remove($selectedCard);
                    $table = new Table();
                    $table->setCards($card);
                    $table->setRound($round);
                    $em->persist($table);
                } else {
                    throw $this->createNotFoundException('Card not found in player\'s hand');
                }
            }
    
            // Timer logic
            $timesInRoom = $times->findBy(["room" => $room]);
            $turnEarned = (count($rounds) == 0 && count($timesInRoom) > 0);
    
            if ($turnEarned) {
                $room->setGoal($goal);
                $hubInterface->publish(new Update(
                    "/".$room->getId()."/goalUpdate",
                    json_encode(['goalUpdate' => $goal])
                ));
            }
    
            $time = new Time();
            $time->setLie(false);
            $time->setRoom($room);
            $time->setTimeround((new \DateTime())->modify('+10 seconds'));
            $em->persist($time);
            $em->flush();
     // Count the number of cards on the table
     $cardCount = 0;
     foreach ($rounds as $round) {
         $cardCount += count($tableRepository->findBy(['round' => $round]));
     }
            // Send the put done update
            $hubInterface->publish(new Update(
                '/' . $room->getId() . '/putdone',
                json_encode([
                    'update' => 'put done ' . date("h:i:sa"),
                    'currentroundplayer' => $player->getId(),
                    'nextplayer' => $nextPlayer->getId(),
                    'cardnumber' => $cardCount
                ])
            ));
    
          
    
            // Send the table update
            $hubInterface->publish(new Update(
                '/' . $room->getId() . '/table',
                json_encode(['cardnumber' => $cardCount])
            ));

            $em->getConnection()->commit(); // Commit transaction
            return new JsonResponse(['status' => 'success'], Response::HTTP_OK);
    
        } catch (\Exception $e) {
            $em->getConnection()->rollBack(); // Rollback transaction on error
            throw $e;
        }
    }
    
    

    #[Route('/lie/{id}', name: 'app_lie')]
    public function lie($id, Request $request,HubInterface $hubInterface, TableRepository $tableRepository,HandRepository $handRepository, RoomRepository $roomRepository, PlayerRepository $playerRepository, RoundRepository $roundRepository,EntityManagerInterface $em)
    {  
       $p = $playerRepository->findOneBy(["id"=>$id]);
       if($p->getWinner()==1)
       {
        return new JsonResponse(["message" =>"you are no longer able to play you are a winner !!!!!!",Response::HTTP_UNAUTHORIZED]);
       }
        $rounds=$roundRepository->findBy(["room"=>$p->getRoom()]);

        if(count($rounds)==0)
        {
            return new JsonResponse(["message" =>"there is no rounds has been taken by ".$id,Response::HTTP_UNAUTHORIZED]);
        }
        $currentRound=$rounds[count($rounds)-1];
        if($currentRound->getBlock()==1)
        {
            return new JsonResponse(["message" =>"round has been taken by ".$id,Response::HTTP_UNAUTHORIZED]);
        }

       
        $roundplayer=$currentRound->getPlayer();
         $player_id = $roundplayer->getId();
        if($player_id==$id)
        {
            return new JsonResponse(["message"=>"you cannot do this action !!",Response::HTTP_UNAUTHORIZED]);
        }
        $update = new Update(
            '/'.$p->getRoom()->getId().'/'.'prevent',
            json_encode(['update' => 'prevent Update published '])
        );

        $hubInterface->publish($update);


        
        $currentRound->setBlock(1);
        $em->persist($currentRound);
        $em->flush();

        #extract all the table cards 
        $cardsTable=$tableRepository->findAll();
       





        $goal=$p->getRoom()->getGoal();
  
        
        $cards=$tableRepository->findBy(["round"=>$currentRound]);
        $desicion=0;
        $players=$playerRepository->findBy(["room"=>$p->getRoom()]);
        $roomMercure= $p->getRoom();

        foreach ($cards as $card) {
      
         if ($card->getCards()!=$goal)
         {
            $desicion=1;

         }

        }
        if($desicion==1)
        {
            #in case he is right update state 
            foreach ($players as  $pl) {
                if ($pl != $p)
                {$pl->setState(0);}
            else
            {
                $pl->setState(1);
            }
                $em->persist($pl);
            }
            $em->flush();
            #mercure upadate he will be playing
            #give the enemy all the cards 
            foreach ($cardsTable as $card) {
                
                $hand = new hand();
                $hand->setCard($card->getCards());
                $hand->setPlayer($roundplayer);
                $em->persist($hand);
                
            }
            $em->flush();


 $selectedCards=$handRepository->findBy(["player"=>$roundplayer]);
 //delete redandant cards 
        
 for($i=0;$i<count($selectedCards);$i++)
 {   $nb=0;
 for($j=0;$j<count($selectedCards);$j++)
         if($selectedCards[$i]->getCard() ==$selectedCards[$j]->getCard())
         {
             $nb++;

         }


 
 if ($nb>=4)
 {

for($c=0;$c<4;$c++)
{
    $em->remove($selectedCards[$i]);
    $em->flush();

}

 }
 }
 


//end of verifcation 


$cards = $handRepository->findBy(["player" => $roundplayer]);

#send updateq to the loser player
  // Prepare an array to hold the card details
  $cardDetails = [];

  // Iterate through each card and extract necessary details
  foreach ($cards as $card) {
      $cardDetails[] = $card->getCard(); // Assuming `getCard()` returns the card value
  }
$update = new Update(
    '/'.$roundplayer->getId().'/'.'loserplayer',
    json_encode(['update' => $cardDetails])
);

$hubInterface->publish($update);



#in this lien i will handel the case that player can win by losing 






        }
        # in case he was wrong he will take all the cards and the enemy status updated

        
        else
        {
            
                #check if he is a winner 
                $ok=0;
                $hands=$handRepository->findBy(["player"=>$roundplayer]);
                if(count($hands)==0)
                {       $ok=1;
                        $roundplayer->setWinner(1);
                        $room=$roundplayer->getRoom();
                        $roomMercure= $room;
                        $nb=0;
                        for ($i=0;$i<count($players);$i++)
                        {
                                if($players[$i]->getWinner()==1)
                                {
                                    $nb++;
                                }
                        }
                        $roundplayer->setRank($nb+1);



                
            #try to update statate in lie method 

                $players= $playerRepository->findBy(["room"=>$room]);
                $roundplayer->setState(0);
                #try to persist the winner player 
                $em->persist($roundplayer);
                $em->flush();
                 // Send updates with Mercure about winning players
         $allPlayers = $playerRepository->findBy(["room" => $room]);

        // Initialize an empty array to hold winning players
         $winningPlayers = [];

        // Loop through the players and filter those with winner == 1
         foreach ($allPlayers as $player) { // Start foreach
            if ($player->getWinner() == 1) {
                $winningPlayers[] = $player->getId();
            }
             } // End foreach

             $update = new Update(
            '/' . $room->getId() . '/win',
            json_encode([
                'update' => 'Winning player IDs',
                'winningPlayerIds' => $winningPlayers
            ])
         );

                     $hubInterface->publish($update);
                #try to change state to the closet stillplaying player from the winner 

                $maxplayernumber=count($players)-1;
                    $nb=-1;
                foreach ($players as $p) 
                {$nb++;
                        if($p->getId() == $roundplayer->getId())
                        {$p->setWinner(1);
                           break;     
                        }
                }
                while ($players[$nb]->getWinner()==1)
                {

                    if($nb== $maxplayernumber){$nb=0;}
                    else {$nb=$nb+1;}
                    $nextPlayer= $players[$nb];

                    $nextPlayer->setState(1) ;

                    #persist next player 
                    $em->persist($nextPlayer);
                    $em->flush();

                }                
                
                      
              
                      #mercure upadet here !!!!!!!
                    


            }
        





                #update state if he is a winner 

            if($ok==0)
            {
            foreach ($players as  $pl) {
                if ($pl->getId() != $roundplayer->getId())
                {$pl->setState(0);}
            else
            {
                $pl->setState(1);
            }
                $em->persist($pl);
            }
            $em->flush();


            }
                                    

            $p=$playerRepository->findOneBy(["id"=>$id]);
            foreach ($cardsTable as $card) {
                
                $hand = new hand();
                $hand->setCard($card->getCards());
                $hand->setPlayer($p);
                $em->persist($hand);


            }
            $em->flush();


            

        $selectedCards=$handRepository->findBy(["player"=>$p]);
        //delete redandant cards 
                
        for($i=0;$i<count($selectedCards);$i++)
        {   $nb=0;
        for($j=0;$j<count($selectedCards);$j++)
                if($selectedCards[$i]->getCard() ==$selectedCards[$j]->getCard())
                {
                    $nb++;

                }


        
        if ($nb>=4)
        {

        for($c=0;$c<4;$c++)
        {
            $em->remove($selectedCards[$i]);
            $em->flush();

        }

        }
     }
 

        


# if he is an angel send update to it 
     $angel=$roundplayer->getId();
     $update = new Update(
        "/".$angel,
        json_encode(['update' => 'New update received at angel '.date("h:i:sa")])
    );

        $hubInterface->publish($update);





        }




                #delelte all the table 
                foreach ($cardsTable as $card) {
                    $em->remove($card);
                    $em->flush();
            }
            #delete all the rounds 
            foreach ($rounds as $round) {
                $em->remove($round);
                $em->flush();
            }


            $rounds=$roundRepository->findBy(["room"=>$roomMercure ]);
            $nb=0;
            foreach ($rounds as $round) {
                // Fetch tables associated with each round
                $tables =$tableRepository->findBy(['round' => $round]);
                $nb=$nb+count($tables);
            }
        

         $update = new Update(
            '/'.$roomMercure->getId().'/'.'table',
            json_encode(['cardnumber' => $nb])
        );
        
        $hubInterface->publish($update);
        
        



        return new JsonResponse([
                "round"=>$currentRound->getId(),
                "goal"=>$goal,
                "player"=>$roundplayer->getId(),
                "desicon"=>$desicion


        ]);
    
    }
    #[Route('/verify-time/{roomId}', name: 'verify_time')]
public function verifyTime($roomId, TimeRepository $timeRepository): JsonResponse
{
    // Get the last inserted time for the room
    $lastTimeEntry = $timeRepository->findOneBy(['room' => $roomId], ['id' => 'DESC']);

    if (!$lastTimeEntry) {
        return new JsonResponse(['message' => 'No time entry found'], Response::HTTP_NOT_FOUND);
    }
    if($lastTimeEntry->isLie==true)
    {
        return new JsonResponse(['message' => 'lier round']);
    }

    $lastInsertedTime = $lastTimeEntry->getTimeround();
    $currentTime = new \DateTime();

    // Compare current time with the last inserted time
    if ($currentTime >= $lastInsertedTime) {
        return new JsonResponse(['canPlay' => true], Response::HTTP_OK);
    } else {
        return new JsonResponse(['canPlay' => false], Response::HTTP_OK);
    }
}

#[Route('/publish', name: 'publish')]
public function publish(HubInterface $hub) : JsonResponse 
{
    $update = new Update(
        '/test',
        json_encode(['update' => 'New update received at '.date("h:i:sa")])
    );

    $hub->publish($update);

    return $this->json(['message' => 'Update published']);
}










#[Route('/handupdate/{id}', name: 'publish')]
public function handUpdate($id, HandRepository $handRepository, PlayerRepository $playerRepository) : JsonResponse 
{
    // Find the player by ID
    $player = $playerRepository->find($id);
    
    // Find all cards associated with the player
    $cards = $handRepository->findBy(["player" => $player]);

    // Prepare an array to hold the card details
    $cardDetails = [];

    // Iterate through each card and extract necessary details
    foreach ($cards as $card) {
        $cardDetails[] = $card->getCard(); // Assuming `getCard()` returns the card value
    }

    // Return the card details as a JSON response
    return $this->json(['updatedhand' => $cardDetails]);
}




}

