<?php
# ------Structures---------
class Farm
{
    public $farm_id;
    public $barn_list = array();

    public function __construct()
    {
        $this->farm_id = uniqid();

    }
    public function add_barn($barn_)
    {
        $barn_->my_farm = $this;
        array_push($this->barn_list, $barn_);
    }

    public function get_animal_cnt_sep()
    {
        $items = array();
        foreach ($this->barn_list as $barn)
        {
            $barn_items = $barn->get_animal_cnt();
            $items[$barn->barn_id] = $barn_items;
        }
        return $items;

    }

    public function collect_from_barns_sep()
    {
        $items = array();
        foreach ($this->barn_list as $barn)
        {
            $barn_items = $barn->collect();
            $items[$barn->barn_id] = $barn_items;
        }
        return $items;
    }

    static public function SumUp_($mySepCollect)
    {
        $sumArray = array();
        foreach ($mySepCollect as $k => $subArray)
        {
            foreach ($subArray as $id => $value)
            {
                $sumArray[$id] += $value;
            }
        }
        return $sumArray;

    }

}

class Barn
{
    public $barn_id;
    public $my_farm;
    public $animal_list = array();
    public function __construct()
    {
        $this->barn_id = uniqid();
    }
    public function add_animal($animal)
    {
        $animal->my_barn = $this;
        array_push($this->animal_list, $animal);
    }

    public function get_animal_cnt()
    {
        $animals = array();
        foreach ($this->animal_list as $animal)
        {
            $animals[get_class($animal) ] += 1;
        }
        return $animals;

    }

    public function collect()
    {
        $items = array();
        foreach ($this->animal_list as $animal)
        {
            $animal_items = $animal->get_product();
            foreach ($animal_items as $item => $count)
            {
                if (array_key_exists($item, $items))
                {
                    $items[$item] += $count;
                }
                else
                {
                    $items[$item] = $count;
                }
            }
        }
        return $items;
    }
}

abstract class Animal
{
    public $animal_id;
    public $my_barn;

    public function __construct()
    {
        $this->animal_id = uniqid();
    }

    abstract public function get_product();
}

class Chicken extends Animal
{
    public function get_product()
    {
        return array(
            "Chicken-egg" => rand(0, 1)
        );
    }
}

class Cow extends Animal
{
    public function get_product()
    {
        return array(
            "Cow-milk" => rand(8, 12)
        );
    }
}

# ------TOOLS---------
function Print_info_sep($info_sep)
{
    foreach ($info_sep as $barn_id => $items)
    {
        echo "Barn #" . $barn_id . ":<br>";
        foreach ($items as $item => $cnt)
        {
            echo $item . ":" . $cnt . "<br>";
        }
    }
}
function Print_info_overall($info_overall)
{
    foreach ($info_overall as $item => $cnt)
    {
        echo $item . ":" . $cnt . "<br>";
    }
}

# ---------Script----------
$Barn_ = new Barn();

for ($i = 0;$i < 10;$i++)
{
    $cow = new Cow();
    $Barn_->add_animal($cow);
}

for ($i = 0;$i < 20;$i++)
{
    $chick = new Chicken();
    $Barn_->add_animal($chick);
}

$Farm_ = new Farm();
$Farm_->add_barn($Barn_);

$info = $Farm_->get_animal_cnt_sep();

echo "<b>Animals in each barn:</b><br>";
Print_info_sep($info);
echo "<b>Overall Animals:</b><br>";
Print_info_overall($Farm_->SumUp_($info));
echo "<br>";

echo "<b>Collecting Goods for 7 days</b><br>";

$week_collect = array();
for ($i = 0;$i < 7;$i++)
{
    array_push($week_collect, $Farm_->SumUp_($Farm_->collect_from_barns_sep()));
}

Print_info_overall($Farm_->SumUp_($week_collect));

echo "<br><b>Adding 5 Chicks and 1 cow</b><br><br>";

for ($i = 0;$i < 1;$i++)
{
    $cow = new Cow();
    $Barn_->add_animal($cow);
}

for ($i = 0;$i < 5;$i++)
{
    $chick = new Chicken();
    $Barn_->add_animal($chick);
}

$info = $Farm_->get_animal_cnt_sep();

echo "<b>Animals in each barn:</b><br>";
Print_info_sep($info);
echo "<b>Overall Animals:</b><br>";
Print_info_overall($Farm_->SumUp_($info));
echo "<br>";

echo "<b>Collecting Goods for 7 days</b><br>";

$week_collect = array();
for ($i = 0;$i < 7;$i++)
{
    array_push($week_collect, $Farm_->SumUp_($Farm_->collect_from_barns_sep()));
}

Print_info_overall($Farm_->SumUp_($week_collect));

?>
