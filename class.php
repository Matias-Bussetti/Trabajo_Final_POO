<?php

interface playable{
    public function play();
}

trait hasNameAttribute {
    public function setName(String $name)
    {
        $this->name = $name;
    }
    public function getName()
    {
        return $this->name;
    }
}

class ArrayItemRepitedException extends Exception
{
    public function __construct()
    {
        $this->message = "Item repited on Array";
    }
}

class Person
{
    protected String $name;
    protected String $surName;
    protected int $age;

    use hasNameAttribute;

    // << Constructor >>
    
    public function __construct(String $name, String $surName, int $age) {
        $this->name = $name;
        $this->surName = $surName;
        $this->age = $age;
    }

    // << Setters >>

    public function setSurName(String $surName)
    {
        $this->surName = $surName;
    }

    public function setAge(String $age)
    {
        $this->age = $age;
    }

    // << Getters >>
    
    public function getSurName()
    {
        return $this->surName;
    }
    
    public function getAge()
    {
        return $this->age;
    }

    // << Methods >>

    public function toString()
    {
        return $this->name." ".$this->surName.", Edad:".$this->age;
    }

}

class Player extends Person
{
    private int $position;
    private array $performance;

    // << Constructor >>

    public function __construct(String $name, String $surName, int $age,  int $position) {
        parent::__construct($name, $surName, $age);
        $this->position = $position;
        $this->performance = [];
    }

    // << Setters >>

    public function setPosition(int $position)
    {
        $this->position = $position;
    }

    public function addPerformance(Performance $performance)
    {
        if (ArrayValidator::checkNotRepited($this->performance, $performance)) {
            $this->performance[] = $performance;
        }
    }

    // << Getters >>

    public function getPosition()
    {
        return $this->position;
    }

    public function getPerformance()
    {
        return $this->performance;
    }

    // << Methods >>

    public function toString()
    {
        $string = parent::toString() . ", Posición: ". $this->position;

        if (!empty($this->performance)) {
            
            $string .= "<details>";
            $string .= "<summary>Rendimiento del Jugador: </summary>";
            
            $string .= "<ul>";
            foreach ($this->performance as $performance) {
                $string .= "<li>" . $performance->toString() . "</li>";
            }
            $string .= "</ul>";

            $string .= "</details>";
            
        }

        return $string;
    }

}

class DT extends Person
{
    private array $tournamentWon;
 
    // << Constructor >>
    
    public function __construct(String $name, String $surName, int $age) {
        parent::__construct($name, $surName, $age);
        $this->tournamentWon = [];
    }

    // << Setters >>

    public function addTournamentWon(String $tournamentName)
    {
        if (ArrayValidator::checkNotRepited($this->tournamentWon, $tournamentName)) {
            $this->tournamentWon[] = $tournamentName;
        }
    }

    // << Getters >>

    public function getTournamentWon()
    {
        return $this->tournamentWon;
    }

    // Methods

    public function toString()
    {
        $string = "<br>DT:<ul>";
        $string .= "<li> " . $this->name . " " . $this->surName . ", Edad: ". $this->age . "</li>";
        if (!empty($this->tournamentWon)) {

            $string .= "<li> Torneos Ganados: <ul>";
            foreach ($this->tournamentWon as $tournamentName) {
                $string .= "<li>". $tournamentName ."</li>";
            }
            $string .= "</ul></li>";
            
        } else {
            
            $string .= "<li> No Gano Ningun Torneo</li>";
            
        }
        $string .= "</ul>";

        return $string;
    }

}

class Team
{

    private String $name;
    private String $primaryColor;
    private String $secondaryColor;
    private Array $players; // Array of Players
    private DT $dt;
    
    use hasNameAttribute;    

    // << Constructor >>

    public function __construct(String $name, String $primaryColor, String $secondaryColor) {
        $this->name = $name;
        $this->primaryColor = $primaryColor;
        $this->secondaryColor = $secondaryColor;
        $this->games = [];
        $this->players = [];
    }

    // << Setters >>

    public function setPrimaryColor(String $color)
    {
        $this->primaryColor = $color;
    }

    public function setSecondaryColor(String $color)
    {
        $this->secondaryColor = $color;
    }

    public function setDT(DT $dt)
    {
        $this->dt = $dt;
    }

    public function addPlayer(Player $player)
    {
        if (ArrayValidator::checkNotRepited($this->players, $player)) {
            $this->players[] = $player;
        }
    }

    // << Getters >>

    public function getPrimaryColor()
    {
        return $this->primaryColor;
    }

    public function getSecondaryColor()
    {
        return $this->secondaryColor;
    }

    public function getDT()
    {
        return $this->dt;
    }


    public function __call($method, $arguments)
    {
        if ($method = 'getPlayers') {
            switch (count($arguments)) {
                case 0:
                    return $this->players;

                    break;
                case 1:
                    return $this->players[array_key_first(array_filter($this->players, function($player) use ($arguments) {return $player->getPosition() == $arguments[0];}))];
                    break;
            }
        }
    }

    //Methods

    public function toString()
    {
        $string = "Nombre: " . $this->name;
        $string .= $this->dt->toString();
        $string .= "<details>";
        $string .= "<summary>Lista de Jugadores:</summary>";
        $string .= "<ol>";
        foreach ($this->players as $player) {
            $string .= "<li> " . $player->toString() . " </li>";
        }
        $string .= "</ol>";
        $string .= "</details>";
        return $string;
    }

}

class Game implements playable
{
    private Team $host;
    private Team $visitor;
    private Team $winner;

    // << Constructor >>

    public function __construct(Team $host, Team $visitor) {
        $this->host = $host;
        $this->visitor = $visitor;
    }

    // << Setters >>

    public function setHost(Team $team)
    {
        $this->host = $team;
    }  

    public function setVisitor(Team $team)
    {
        $this->visitor = $team;
    }  

    public function setWinner(Team $team)
    {
        // ! comprobar si es host o visitante
        $this->winner = $team;
    } 

    // << Getters >>

    public function getHost()
    {
        return $this->host;
    }  

    public function getVisitor()
    {
        return $this->visitor;
    }  

    public function getWinner()
    {
        return $this->winner;
    }  

    
    // << Methods >>
    
    public function play()
    {
        $winnerTeam = Rand(0,1);
        
        $this->winner = $winnerTeam ? $this->host : $this->visitor;

        if ($winnerTeam) {
            
            foreach ($this->host->getPlayers() as $player) {
                $player->addPerformance(new Performance($this, Rand(70,100)));
            }
            foreach ($this->visitor->getPlayers() as $player) {
                $player->addPerformance(new Performance($this, Rand(30,60)));
            }
            
        } else {

            foreach ($this->visitor->getPlayers() as $player) {
                $player->addPerformance(new Performance($this, Rand(70,100)));
            }
            foreach ($this->host->getPlayers() as $player) {
                $player->addPerformance(new Performance($this, Rand(30,60)));
            }

        }

    }

    public function toString()
    {
        return "Equipo Local: " . $this->host->getName() . ", Equipo Visitante: "  . $this->visitor->getName()  . ", Equipo Ganador: " . $this->winner->getName(); 
    }

}

class Competition implements playable
{
    private String $name;
    private array $games;
    private array $teams;
    private $winningTeam;
    private static $maxTeams = 6;

    use hasNameAttribute;

    // << Constructor >>

    public function __construct(String $name) {
        $this->name = $name;
        $this->games = [];
        $this->teams = [];
        $this->winningTeam == null;
    }

    // << Setters >>
    
    public function addTeam(Team $team)
    {
        if (ArrayValidator::checkNotRepited($this->teams, $team)) {
            if (sizeOf($this->teams) <= self::$maxTeams) {
                $this->teams[] = $team;
            }
        }
    }
    
    public function addGame(Game $game)
    {
        if (ArrayValidator::checkNotRepited($this->games, $game)) {
            $this->games[] = $game;
        }
    }

    public function setWinningTeam(Team $team)
    {
        $this->winningTeam = $team;
    }

    // ! completar setter de atributos para clase mas completa
    
    // << Getters >>

    public function getTeams()
    {
        return $this->teams;
    }

    public function getGames()
    {
        return $this->games;
    }

    public function getWinningTeam()
    {
        return $this->winningTeam;
    }

    // << Methods >>

    public function generateGames()
    {
        // Jornada 1
        $this->games[] = new Game($this->teams[2], $this->teams[5]);
        $this->games[] = new Game($this->teams[3], $this->teams[0]);
        $this->games[] = new Game($this->teams[4], $this->teams[1]);
        
        // Jornada 2
        $this->games[] = new Game($this->teams[1], $this->teams[3]);
        $this->games[] = new Game($this->teams[5], $this->teams[4]);
        $this->games[] = new Game($this->teams[0], $this->teams[2]);
        
        // Jornada 3
        $this->games[] = new Game($this->teams[2], $this->teams[1]);
        $this->games[] = new Game($this->teams[3], $this->teams[4]);
        $this->games[] = new Game($this->teams[0], $this->teams[5]);
        
        // Jornada 4
        $this->games[] = new Game($this->teams[1], $this->teams[0]);
        $this->games[] = new Game($this->teams[3], $this->teams[5]);
        $this->games[] = new Game($this->teams[4], $this->teams[2]);
        
        // Jornada 5
        $this->games[] = new Game($this->teams[2], $this->teams[3]);
        $this->games[] = new Game($this->teams[5], $this->teams[1]);
        $this->games[] = new Game($this->teams[0], $this->teams[4]);
    }
    
    public function play()
    {

        $points = [];

        foreach ($this->teams as $team) {
            $points[$team->getName()] = 0;
        }

        foreach ($this->games as $game) {
            $game->play();
            $points[$game->getWinner()->getName()]++;
        }

        
        foreach ($points as $team => $matchesWon) {

            
            if (!$this->winningTeam) {
                $this->winningTeam = $team;
            } else {
                $this->winningTeam = $points[$this->winningTeam] >= $matchesWon ? $this->winningTeam : $team;
            }
            
        }

        $this->teams[(array_key_first(array_filter($this->teams, function ($team) {return $team->getname() == $this->winningTeam;})))]->getDT()->addTournamentWon($this->name);

    }
}

class Performance
{
    private Game $game;
    // ! $_performance
    private int $performance;

    // << Constructor >>

    public function __construct(Game $game, int $performance) {
        $this->game = $game;
        $this->performance = $performance;
    }

    // << Getters >>

    public function getGame()
    {
        return $this->game;
    }

    public function getPerformance()
    {
        return $this->performance;
    }

    // << Methods >>

    public function toString()
    {
        // ! ul li 
        return " Rendimiento: " . $this->performance . "%, Partido: <ul><li>" . $this->game->toString() . "</li></ul>"; 
    }
}

// Clases de Ayuda
class NameSelector
{
    private static $names = ["Miguel","Ángel","Juan","Carlos","Alberto","José","Luis","Manuel","Pablo","Nicolás","Ignacio","Diego","Tomás"];
    private static $surNames = ["Gonzalez","Rodriguez","Gomez","Fernandez","Lopez","Diaz","Martinez","Pérez","Romero","Sánchez","García","Torres","Alvarez"];

    public static function pickName()
    {
        return self::$names[Rand(0,sizeof(self::$names)-1)];
    } 
    
    public static function pickSurName()
    {
        return self::$surNames[Rand(0,sizeof(self::$surNames)-1)];
    } 

}

class  ArrayValidator
{
    public static function checkNotRepited($array, $newItem)
    {
        try {
            foreach ($array as $item) {
                if ($item === $newItem) {
                    throw new ArrayItemRepitedException();
                }
            }
            return true;
        } catch (\ArrayItemRepitedException $ex) {
            
            echo '<br>';
            echo '<details class="error">';
            echo '<summary>';
            echo 'An error occurred';
            echo '</summary>';
            echo '<ul>';
            echo '<li>File: '. $ex->getFile() . '</li>';
            echo '<li>Line: '. $ex->getLine() . '</li>';
            echo '<li>Msg: '.  $ex->getMessage() . '</li>';
            echo '<li>Trace:';
            echo '<ul>';
            echo '<li>File: '. $ex->getTrace()[0]["file"] . '</li>';
            echo '<li>Line: '. $ex->getTrace()[0]["line"] . '</li>';
            echo '<li>Function: '. $ex->getTrace()[0]["function"] . '</li>';
            echo '<li>Class: '. $ex->getTrace()[0]["class"] . '</li>';
            echo '</ul>';
            echo '</details>';
            echo '<br>';
            
            return false;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabajo Final</title>
</head>
<body>
    <style>
        body{
            background-color: black;
            color: white;
            font-family: 'Arial';
        }
        summary{
            cursor: pointer;
        }
        details.error{
            color: #f00;
        }
    </style>

<?php

// * Se crean los equipos ...

$teams[] = new Team("Tecnicatura Universitaria en Periodismo y Emprendimientos de la Comunicación", "0c08ff", "ffef08");
$teams[] = new Team("Tecnicatura Universitaria en Seguridad Vial", "0c08ff", "ffef08");
$teams[] = new Team("Tecnicatura Universitaria en Producción Agropecuaria Sostenible", "0c08ff", "ffef08");
$teams[] = new Team("Tecnicatura Universitaria en Gestión de Emprendimientos Deportivos", "0c08ff", "ffef08");
$teams[] = new Team("Tecnicatura Universitaria en Emprendimientos del Diseño", "0c08ff", "ffef08");
$teams[] = new Team("Tecnicatura Universitaria en Desarrollo de Aplicaciones Web", "0c08ff", "ffef08");
$teams[] = new Team("Diplomatura Universitaria en Desarrollo Local", "0c08ff", "ffef08");

// ? Se crean los DT y los jugadores y se asigan en los equipos

for ($i=0; $i < 7 ; $i++) { 
    $dt[] = new DT(NameSelector::pickName() , NameSelector::pickSurName() , Rand(45,60));
}

foreach ($teams as $index => $team) {
    $team->setDT($dt[$index]);
    
    for ($position=1; $position < 12; $position++) { 
        $player = new Player(NameSelector::pickName() , NameSelector::pickSurName() , Rand(19, 35), $position);
        $team->addPlayer($player);
    }
}

// $ Se crean los torneos

$competitions[] = new Competition("Torneo de Handball");
$competitions[] = new Competition("Torneo de Football");
$competitions[] = new Competition("Torneo de Softball");
$competitions[] = new Competition("Torneo de Jokey");

echo "<h1>Torneos</h1>";

// + Se asignan los equipos a los torneos

foreach ($competitions as $competition) {

    $competition->addTeam($teams[0]);
    $competition->addTeam($teams[1]);
    $competition->addTeam($teams[2]);
    $competition->addTeam($teams[3]);
    $competition->addTeam($teams[4]);
    $competition->addTeam($teams[5]);
    
    $competition->generateGames();
    
    $competition->play();

    echo "Equipo gandor de '". $competition->getName() ."': " . $competition->getWinningTeam() . "<br>";

}

// * Excepciones

$player1 = new Player(NameSelector::pickName() , NameSelector::pickSurName() , Rand(19, 24), 12);
$player2 = new Player(NameSelector::pickName() , NameSelector::pickSurName() , Rand(19, 24), 13);

$teams[0]->addPlayer($player1);

$teams[0]->addPlayer($player1);

$teams[0]->addPlayer($player2);

echo "<h1>Equipos</h1>";
foreach ($teams as $team) {
    echo "<hr>".$team->toString();
}

?>

</body>
</html>













